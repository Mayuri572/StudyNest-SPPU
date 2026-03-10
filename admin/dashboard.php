<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../config/db.php';

// Stats
$total_users    = $conn->query("SELECT COUNT(*) AS c FROM users WHERE role='user'")->fetch_assoc()['c'];
$total_orders   = $conn->query("SELECT COUNT(*) AS c FROM orders")->fetch_assoc()['c'];
$total_products = $conn->query("SELECT COUNT(*) AS c FROM products")->fetch_assoc()['c'];
$revenue        = $conn->query("SELECT SUM(total_amount) AS r FROM orders WHERE status='paid'")->fetch_assoc()['r'] ?? 0;

// Recent orders
$recent_orders = $conn->query("SELECT o.id, o.full_name, o.total_amount, o.status, o.created_at FROM orders o ORDER BY o.created_at DESC LIMIT 5");

include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="container" style="padding:40px 20px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px; flex-wrap:wrap; gap:15px;">
        <h2 style="color:var(--primary); font-size:1.8rem;">
            <i class="fa-solid fa-gauge"></i> Admin Dashboard
        </h2>
        <span style="color:var(--text-light);">Welcome, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong></span>
    </div>

    <!-- Stats Cards -->
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(220px, 1fr)); gap:20px; margin-bottom:40px;">
        <div class="card" style="padding:25px; text-align:center; border-top:4px solid var(--primary);">
            <i class="fa-solid fa-users" style="font-size:2.5rem; color:var(--primary); margin-bottom:12px;"></i>
            <h3 style="font-size:2rem; color:var(--primary);"><?php echo $total_users; ?></h3>
            <p style="color:var(--text-light);">Total Students</p>
        </div>
        <div class="card" style="padding:25px; text-align:center; border-top:4px solid #22c55e;">
            <i class="fa-solid fa-bag-shopping" style="font-size:2.5rem; color:#22c55e; margin-bottom:12px;"></i>
            <h3 style="font-size:2rem; color:#22c55e;"><?php echo $total_orders; ?></h3>
            <p style="color:var(--text-light);">Total Orders</p>
        </div>
        <div class="card" style="padding:25px; text-align:center; border-top:4px solid var(--accent);">
            <i class="fa-solid fa-book" style="font-size:2.5rem; color:var(--accent); margin-bottom:12px;"></i>
            <h3 style="font-size:2rem; color:var(--accent);"><?php echo $total_products; ?></h3>
            <p style="color:var(--text-light);">Total Products</p>
        </div>
        <div class="card" style="padding:25px; text-align:center; border-top:4px solid #8b5cf6;">
            <i class="fa-solid fa-indian-rupee-sign" style="font-size:2.5rem; color:#8b5cf6; margin-bottom:12px;"></i>
            <h3 style="font-size:2rem; color:#8b5cf6;">₹<?php echo number_format($revenue, 0); ?></h3>
            <p style="color:var(--text-light);">Total Revenue</p>
        </div>
    </div>

    <!-- Quick Links -->
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr)); gap:15px; margin-bottom:40px;">
        <a href="add-product.php" class="btn btn-primary" style="text-align:center; padding:15px;">
            <i class="fa-solid fa-plus"></i> Add Product
        </a>
        <a href="manage-products.php" class="btn btn-outline" style="text-align:center; padding:15px;">
            <i class="fa-solid fa-boxes-stacked"></i> Manage Products
        </a>
        <a href="manage-orders.php" class="btn btn-outline" style="text-align:center; padding:15px;">
            <i class="fa-solid fa-clipboard-list"></i> Manage Orders
        </a>
        <a href="manage-users.php" class="btn btn-outline" style="text-align:center; padding:15px;">
            <i class="fa-solid fa-users-gear"></i> Manage Users
        </a>
        <a href="logs.php" class="btn btn-outline" style="text-align:center; padding:15px;">
            <i class="fa-solid fa-file-lines"></i> View Logs
        </a>
    </div>

    <!-- Recent Orders -->
    <div class="card" style="padding:25px;">
        <h3 style="color:var(--primary); margin-bottom:20px;"><i class="fa-solid fa-clock-rotate-left"></i> Recent Orders</h3>
        <?php if ($recent_orders->num_rows === 0): ?>
            <p style="color:var(--text-light);">No orders yet.</p>
        <?php else: ?>
        <div style="overflow-x:auto;">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Order ID</th><th>Customer</th><th>Amount</th><th>Status</th><th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($o = $recent_orders->fetch_assoc()): ?>
                <tr>
                    <td><strong>#<?php echo $o['id']; ?></strong></td>
                    <td><?php echo htmlspecialchars($o['full_name']); ?></td>
                    <td>₹<?php echo number_format($o['total_amount'], 2); ?></td>
                    <td>
                        <span style="padding:4px 12px; border-radius:20px; font-size:0.8rem; font-weight:600;
                            background:<?php echo $o['status']==='paid' ? '#f0fdf4' : '#fffbeb'; ?>;
                            color:<?php echo $o['status']==='paid' ? '#22c55e' : '#f59e0b'; ?>;">
                            <?php echo strtoupper(htmlspecialchars($o['status'])); ?>
                        </span>
                    </td>
                    <td style="color:var(--text-light); font-size:0.9rem;"><?php echo htmlspecialchars($o['created_at']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>