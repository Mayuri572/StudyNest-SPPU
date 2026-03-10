<?php
session_start();
require_once '../config/db.php';

// Handle Add to Cart POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 0;
    }
    $_SESSION['cart'][$product_id]++;
    header("Location: store.php" . (isset($_GET['search']) ? "?search=" . urlencode($_GET['search']) : ""));
    exit();
}

// Search & Filter
$search   = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');
$params   = [];
$types    = '';
$where    = ["status = 'published'"];

if ($search !== '') {
    $where[]  = "(title LIKE ? OR author LIKE ?)";
    $keyword  = "%$search%";
    $params[] = $keyword;
    $params[] = $keyword;
    $types   .= 'ss';
}
if ($category !== '') {
    $where[]  = "category = ?";
    $params[] = $category;
    $types   .= 's';
}

$sql  = "SELECT * FROM products WHERE " . implode(" AND ", $where) . " ORDER BY id DESC";
$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$products = $stmt->get_result();

include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="container listing-section">
    <h2 class="section-title">Academic Book Store</h2>

    <div class="toolbar">
        <form method="GET" action="store.php" style="display:flex; gap:15px; flex-wrap:wrap; width:100%; align-items:center;">
            <select name="category" class="form-control" style="width:200px;" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <option value="Textbook" <?php echo $category === 'Textbook' ? 'selected' : ''; ?>>Textbooks</option>
                <option value="Guide"    <?php echo $category === 'Guide'    ? 'selected' : ''; ?>>Decodes / Guides</option>
            </select>
            <div class="search-bar" style="background:var(--white); flex:1; max-width:350px;">
                <input type="text" name="search" placeholder="Search books or author..."
                       value="<?php echo htmlspecialchars($search); ?>" style="width:100%;">
                <button type="submit" style="border:none; background:none; cursor:pointer;">
                    <i class="fa-solid fa-search"></i>
                </button>
            </div>
            <?php if ($search || $category): ?>
                <a href="store.php" class="btn btn-outline">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if ($products->num_rows === 0): ?>
        <div style="text-align:center; padding:60px 20px; color:var(--text-light);">
            <i class="fa-solid fa-magnifying-glass" style="font-size:3rem; margin-bottom:15px;"></i>
            <p style="font-size:1.1rem;">No products found<?php echo $search ? ' for "' . htmlspecialchars($search) . '"' : ''; ?>.</p>
            <a href="store.php" class="btn btn-primary" style="margin-top:15px;">View All Products</a>
        </div>
    <?php else: ?>
    <div class="product-grid">
        <?php while ($p = $products->fetch_assoc()): ?>
        <div class="card">
            <a href="product-detail.php?id=<?php echo $p['id']; ?>">
                <?php
                $img = (!empty($p['main_image']) && $p['main_image'] !== 'placeholder.png')
                       ? $p['main_image']
                       : ($p['image'] ?? '');

                if (empty($img)) {
                    $normalized_title = strtolower(preg_replace('/[^a-z0-9]+/', '', (string)($p['title'] ?? '')));
                    $title_image_map = [
                        'datastructuresusingc' => 'Data_Structures_Using_C.jpg',
                        'datastructuresusingcincludingc' => 'Data_Structures_Using_C.jpg',
                        'softwareengineeringdecode' => 'Software_Engineering_Decode.jpg',
                        'softwareengineeringdecode2019' => 'Software_Engineering_Decode.jpg',
                        'engineeringmathematicsiii' => 'Engineering_Mathematics-III.webp',
                    ];
                    if (isset($title_image_map[$normalized_title])) {
                        $img = $title_image_map[$normalized_title];
                    } elseif (strpos($normalized_title, 'datastructuresusingc') !== false) {
                        $img = 'Data_Structures_Using_C.jpg';
                    } elseif (strpos($normalized_title, 'softwareengineeringdecode') !== false) {
                        $img = 'Software_Engineering_Decode.jpg';
                    }
                }
                ?>
                <?php if (!empty($img)): ?>
                    <img src="/studynest/assets/images/<?php echo htmlspecialchars($img); ?>"
                         alt="<?php echo htmlspecialchars($p['title']); ?>"
                         style="width:100%; height:250px; object-fit:contain; padding:20px; background:#fff;">
                <?php else: ?>
                    <img src="https://via.placeholder.com/300x250?text=Book+Cover"
                         alt="<?php echo htmlspecialchars($p['title']); ?>"
                         style="width:100%; height:250px; object-fit:contain; padding:20px; background:#fff;">
                <?php endif; ?>
            </a>

            <div class="card-content">
                <span class="card-tag"><?php echo htmlspecialchars($p['category'] ?? 'Book'); ?></span>
                <a href="product-detail.php?id=<?php echo $p['id']; ?>">
                    <h3 class="card-title"><?php echo htmlspecialchars($p['title']); ?></h3>
                </a>
                <?php if (!empty($p['author'])): ?>
                    <p class="card-desc" style="margin-bottom:8px; font-size:0.9rem;">
                        By <?php echo htmlspecialchars($p['author']); ?>
                    </p>
                <?php endif; ?>

                <?php $preview = !empty($p['short_description']) ? $p['short_description'] : ($p['description'] ?? ''); ?>
                <p class="card-desc"><?php echo htmlspecialchars(substr($preview, 0, 90)) . (strlen($preview) > 90 ? '...' : ''); ?></p>

                <div style="display:flex; justify-content:space-between; align-items:center; margin-top:auto;">
                    <?php if (!empty($p['sale_price']) && $p['sale_price'] < $p['price']): ?>
                        <div>
                            <span class="card-price" style="margin:0;">₹<?php echo number_format($p['sale_price'], 2); ?></span>
                            <span style="font-size:0.85rem; color:var(--text-light); text-decoration:line-through; margin-left:5px;">
                                ₹<?php echo number_format($p['price'], 2); ?>
                            </span>
                        </div>
                    <?php else: ?>
                        <div class="card-price" style="margin:0;">₹<?php echo number_format($p['price'], 2); ?></div>
                    <?php endif; ?>

                    <?php if ((int)$p['stock_quantity'] > 0): ?>
                        <form method="POST" action="store.php<?php echo $search ? '?search=' . urlencode($search) : ''; ?>">
                            <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                            <button type="submit" name="add_to_cart" class="btn btn-primary">
                                <i class="fa-solid fa-cart-plus"></i> Add
                            </button>
                        </form>
                    <?php else: ?>
                        <span style="color:#ef4444; font-size:0.85rem; font-weight:600;">Out of Stock</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>
