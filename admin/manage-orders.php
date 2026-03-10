<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php"); exit();
}
require_once '../config/db.php';

// Update order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $status   = $_POST['status'];
    $allowed  = ['pending', 'paid', 'shipped', 'delivered', 'cancelled'];
    if (in_array($status, $allowed)) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: manage-orders.php"); exit();
}

$orders = $conn->query("SELECT o.*, u.email FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");

include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="container" style="padding:40px 20px;">
    <div style="display:flex; align-items:center; gap:15px; margin-bottom:25px;">
        <a href="dashboard.php" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i></a>
        <h2 style="color:var(--primary);">Manage Orders</h2>
    </div>

    <div class="card" style="padding:20px; overflow-x:auto;">
        <?php if ($orders->num_rows === 0): ?>
            <p style="color:var(--text-light); text-align:center; padding:40px;">No orders yet.</p>
        <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr><th>ID</th><th>Customer</th><th>Email</th><th>Amount</th><th>Payment</th><th>Status</th><th>Date</th><th>Update</th></tr>
            </thead>
            <tbody>
                <?php while ($o = $orders->fetch_assoc()): ?>
                <tr>
                    <td><strong>#<?php echo $o['id']; ?></strong></td>
                    <td><?php echo htmlspecialchars($o['full_name']); ?></td>
                    <td style="font-size:0.85rem; color:var(--text-light);"><?php echo htmlspecialchars($o['email'] ?? ''); ?></td>
                    <td style="font-weight:600;">₹<?php echo number_format($o['total_amount'], 2); ?></td>
                    <td><?php echo htmlspecialchars(strtoupper($o['payment_method'] ?? '')); ?></td>
                    <td>
                        <span style="padding:4px 12px; border-radius:20px; font-size:0.8rem; font-weight:600;
                            background:<?php echo match($o['status']) {
                                'paid' => '#f0fdf4', 'shipped' => '#eff6ff', 'delivered' => '#f0fdf4',
                                'cancelled' => '#fef2f2', default => '#fffbeb'
                            }; ?>;
                            color:<?php echo match($o['status']) {
                                'paid','delivered' => '#22c55e', 'shipped' => '#3b82f6',
                                'cancelled' => '#ef4444', default => '#f59e0b'
                            }; ?>;">
                            <?php echo strtoupper(htmlspecialchars($o['status'])); ?>
                        </span>
                    </td>
                    <td style="font-size:0.85rem; color:var(--text-light);"><?php echo htmlspecialchars($o['created_at']); ?></td>
                    <td>
                        <form method="POST" style="display:flex; gap:5px; align-items:center;">
                            <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                            <select name="status" class="form-control" style="padding:7px; font-size:0.85rem; width:130px;">
                                <?php foreach (['pending','paid','shipped','delivered','cancelled'] as $s): ?>
                                    <option value="<?php echo $s; ?>" <?php echo $o['status']===$s ? 'selected' : ''; ?>>
                                        <?php echo ucfirst($s); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-primary" style="padding:8px 12px; font-size:0.8rem;">
                                <i class="fa-solid fa-check"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>