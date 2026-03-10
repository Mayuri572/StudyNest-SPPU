<?php
session_start();
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: store.php");
    exit();
}

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $qty = max(1, (int)($_POST['quantity'] ?? 1));
    if (!isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = 0;
    }
    $_SESSION['cart'][$id] += $qty;
    header("Location: cart.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND status = 'published'");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: store.php");
    exit();
}
$p = $result->fetch_assoc();
$stmt->close();

// Determine which image to show
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

// Determine price to show
$display_price = (!empty($p['sale_price']) && $p['sale_price'] < $p['price'])
                 ? $p['sale_price']
                 : $p['price'];

// Use long_description if available, else short_description, else description
$full_desc = !empty($p['long_description'])
             ? $p['long_description']
             : (!empty($p['short_description']) ? $p['short_description'] : ($p['description'] ?? ''));

include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="container listing-section">
    <a href="store.php" class="btn btn-outline" style="margin-bottom:20px;">
        <i class="fa-solid fa-arrow-left"></i> Back to Store
    </a>

    <div class="product-detail-grid">
        <!-- Product Image -->
        <?php if (!empty($img)): ?>
            <img src="/studynest/assets/images/<?php echo htmlspecialchars($img); ?>"
                 alt="<?php echo htmlspecialchars($p['title']); ?>"
                 class="product-image-large">
        <?php else: ?>
            <img src="https://via.placeholder.com/500x600?text=Book+Cover"
                 alt="<?php echo htmlspecialchars($p['title']); ?>"
                 class="product-image-large">
        <?php endif; ?>

        <!-- Product Info -->
        <div>
            <span class="card-tag"><?php echo htmlspecialchars($p['category'] ?? 'Book'); ?></span>
            <h1 style="color:var(--primary); font-size:2.2rem; margin-top:10px; line-height:1.3;">
                <?php echo htmlspecialchars($p['title']); ?>
            </h1>

            <?php if (!empty($p['author'])): ?>
                <p style="font-size:1.05rem; color:var(--text-light); margin-top:8px;">
                    <i class="fa-solid fa-pen-nib"></i> <?php echo htmlspecialchars($p['author']); ?>
                </p>
            <?php endif; ?>

            <!-- Price -->
            <div class="detail-price" style="margin:20px 0;">
                ₹<?php echo number_format($display_price, 2); ?>
                <?php if (!empty($p['sale_price']) && $p['sale_price'] < $p['price']): ?>
                    <span style="font-size:1.1rem; color:var(--text-light); text-decoration:line-through; font-weight:400; margin-left:10px;">
                        ₹<?php echo number_format($p['price'], 2); ?>
                    </span>
                    <span style="font-size:0.9rem; background:#dcfce7; color:#16a34a; padding:4px 10px; border-radius:20px; margin-left:8px; font-weight:600;">
                        <?php echo round((($p['price'] - $p['sale_price']) / $p['price']) * 100); ?>% OFF
                    </span>
                <?php endif; ?>
            </div>

            <!-- Stock Status -->
            <p style="margin-bottom:15px;">
                <?php if ((int)$p['stock_quantity'] > 0): ?>
                    <span style="color:#16a34a; font-weight:600;">
                        <i class="fa-solid fa-circle-check"></i> In Stock (<?php echo $p['stock_quantity']; ?> available)
                    </span>
                <?php else: ?>
                    <span style="color:#ef4444; font-weight:600;">
                        <i class="fa-solid fa-circle-xmark"></i> Out of Stock
                    </span>
                <?php endif; ?>
            </p>

            <!-- Description -->
            <?php if (!empty($full_desc)): ?>
                <p style="margin-bottom:25px; color:var(--text-light); line-height:1.8; font-size:0.97rem;">
                    <?php echo nl2br(htmlspecialchars($full_desc)); ?>
                </p>
            <?php endif; ?>

            <!-- SKU -->
            <?php if (!empty($p['sku'])): ?>
                <p style="font-size:0.85rem; color:var(--text-light); margin-bottom:20px;">
                    SKU: <?php echo htmlspecialchars($p['sku']); ?>
                </p>
            <?php endif; ?>

            <!-- Add to Cart Form -->
            <?php if ((int)$p['stock_quantity'] > 0): ?>
            <form method="POST" action="product-detail.php?id=<?php echo $p['id']; ?>">
                <div class="qty-selector">
                    <label style="font-weight:600;">Quantity:</label>
                    <input type="number" name="quantity" class="form-control"
                           value="1" min="1" max="<?php echo min(10, $p['stock_quantity']); ?>"
                           style="width:80px; text-align:center;">
                </div>
                <button type="submit" name="add_to_cart" class="btn btn-primary"
                        style="width:100%; font-size:1.1rem; padding:15px; margin-bottom:12px;">
                    <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                </button>
            </form>
            <?php else: ?>
                <button class="btn btn-outline" style="width:100%; padding:15px; cursor:not-allowed; margin-bottom:12px;" disabled>
                    <i class="fa-solid fa-ban"></i> Out of Stock
                </button>
            <?php endif; ?>

            <a href="store.php" class="btn btn-outline" style="width:100%; text-align:center;">
                <i class="fa-solid fa-store"></i> Continue Shopping
            </a>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
