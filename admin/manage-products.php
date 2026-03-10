<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php"); exit();
}
require_once '../config/db.php';

$success = '';
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    $stmt   = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $del_id);
    $stmt->execute();
    $stmt->close();
    $success = "Product deleted successfully.";
}

$products = $conn->query("SELECT * FROM products ORDER BY id DESC");

include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="container" style="padding:40px 20px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; flex-wrap:wrap; gap:15px;">
        <div style="display:flex; align-items:center; gap:15px;">
            <a href="dashboard.php" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i></a>
            <h2 style="color:var(--primary);">Manage Products</h2>
        </div>
        <a href="add-product.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add New</a>
    </div>

    <?php if ($success): ?>
        <div style="color:green; background:#f0fdf4; border:1px solid #bbf7d0; padding:12px; border-radius:8px; margin-bottom:20px;">
            <i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <div class="card" style="padding:20px; overflow-x:auto;">
        <?php if ($products->num_rows === 0): ?>
            <p style="color:var(--text-light); text-align:center; padding:40px;">No products found. <a href="add-product.php">Add one now</a>.</p>
        <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>ID</th><th>Image</th><th>Title</th><th>Author</th><th>Category</th><th>Price</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($p = $products->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td>
                        <?php if (!empty($p['image'])): ?>
                            <img src="/studynest/assets/images/<?php echo htmlspecialchars($p['image']); ?>"
                                 alt="<?php echo htmlspecialchars($p['title']); ?>"
                                 style="width:50px; height:50px; object-fit:contain; border-radius:6px;">
                        <?php else: ?>
                            <span style="color:var(--text-light); font-size:0.8rem;">No image</span>
                        <?php endif; ?>
                    </td>
                    <td><strong><?php echo htmlspecialchars($p['title']); ?></strong></td>
                    <td><?php echo htmlspecialchars($p['author'] ?? '-'); ?></td>
                    <td><span class="card-tag" style="display:inline-block;"><?php echo htmlspecialchars($p['category'] ?? '-'); ?></span></td>
                    <td style="font-weight:600; color:var(--primary);">₹<?php echo number_format($p['price'], 2); ?></td>
                    <td>
                        <a href="manage-products.php?delete=<?php echo $p['id']; ?>"
                           class="btn" style="background:#fef2f2; color:#ef4444; padding:8px 14px;"
                           onclick="return confirm('Delete this product permanently?')">
                            <i class="fa-solid fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>