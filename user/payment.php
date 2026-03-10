<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$order_id = (int)($_GET['order_id'] ?? $_SESSION['pending_order_id'] ?? 0);
$method   = $_GET['method'] ?? 'upi';
$status   = $_GET['status'] ?? 'success';

// If cancelled
if ($status === 'cancel') {
    include '../includes/header.php';
    include '../includes/navbar.php';
    ?>
    <main class="container" style="padding:60px 20px; text-align:center;">
        <div style="max-width:520px; margin:0 auto; background:var(--white); padding:50px 40px; border-radius:16px; box-shadow:var(--shadow-lg);">
            <div style="width:80px; height:80px; background:#fef2f2; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 25px;">
                <i class="fa-solid fa-circle-xmark" style="font-size:3rem; color:#ef4444;"></i>
            </div>
            <h2 style="color:#ef4444; margin-bottom:10px;">Payment Cancelled</h2>
            <p style="color:var(--text-light); margin-bottom:30px;">
                Your payment was cancelled. No money was charged. You can try again.
            </p>
            <div style="display:flex; gap:15px; justify-content:center; flex-wrap:wrap;">
                <a href="checkout.php" class="btn btn-primary">Try Again</a>
                <a href="store.php" class="btn btn-outline">Back to Store</a>
            </div>
        </div>
    </main>
    <?php
    include '../includes/footer.php';
    exit();
}

// Mark order as paid in DB if order_id exists
if ($order_id > 0) {
    $stmt = $conn->prepare("UPDATE orders SET status = 'paid' WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
}

// Clear cart session
unset($_SESSION['cart']);
unset($_SESSION['pending_order_id']);
unset($_SESSION['order_total']);
unset($_SESSION['billing']);

// Payment method label
$method_label = match(strtolower($method)) {
    'cod'    => 'Cash on Delivery',
    'paypal' => 'PayPal Sandbox',
    'upi'    => 'UPI / GPay',
    default  => strtoupper($method)
};

include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="container" style="padding:60px 20px; text-align:center;">
    <div style="max-width:560px; margin:0 auto; background:var(--white); padding:50px 40px; border-radius:16px; box-shadow:var(--shadow-lg);">

        <!-- Success Icon -->
        <div style="width:90px; height:90px; background:#f0fdf4; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 25px;">
            <i class="fa-solid fa-circle-check" style="font-size:3.5rem; color:#22c55e;"></i>
        </div>

        <h2 style="color:var(--primary); margin-bottom:10px; font-size:1.8rem;">
            <?php echo strtolower($method) === 'cod' ? 'Order Placed!' : 'Payment Successful!'; ?>
        </h2>
        <p style="color:var(--text-light); margin-bottom:30px; font-size:1rem;">
            <?php if (strtolower($method) === 'cod'): ?>
                Your order <?php echo $order_id > 0 ? '<strong>#'.$order_id.'</strong>' : ''; ?> has been placed with <strong>Cash on Delivery</strong>.
            <?php else: ?>
                Your payment was completed successfully.
                <?php echo $order_id > 0 ? 'Order <strong>#'.$order_id.'</strong> is confirmed.' : ''; ?>
            <?php endif; ?>
        </p>

        <!-- Order Details Box -->
        <div style="background:#f8fafc; border-radius:12px; padding:22px 25px; text-align:left; margin-bottom:28px; border:1px solid var(--border);">
            <?php if ($order_id > 0): ?>
            <div style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid var(--border);">
                <span style="color:var(--text-light);">Order ID</span>
                <strong>#<?php echo $order_id; ?></strong>
            </div>
            <?php endif; ?>
            <div style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid var(--border);">
                <span style="color:var(--text-light);">Payment Method</span>
                <strong><?php echo htmlspecialchars($method_label); ?></strong>
            </div>
            <div style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid var(--border);">
                <span style="color:var(--text-light);">Status</span>
                <span style="color:#22c55e; font-weight:700;">
                    <i class="fa-solid fa-check-circle"></i>
                    <?php echo strtolower($method) === 'cod' ? 'CONFIRMED' : 'PAID'; ?>
                </span>
            </div>
            <div style="display:flex; justify-content:space-between; padding:8px 0;">
                <span style="color:var(--text-light);">Date & Time</span>
                <strong><?php echo date('d M Y, h:i A'); ?></strong>
            </div>
        </div>

        <p style="color:var(--text-light); font-size:0.9rem; margin-bottom:25px;">
            <i class="fa-solid fa-envelope"></i> A confirmation has been recorded in your account.
        </p>

        <!-- Action Buttons -->
        <div style="display:flex; gap:15px; justify-content:center; flex-wrap:wrap;">
            <a href="/studynest/user/store.php" class="btn btn-primary">
                <i class="fa-solid fa-store"></i> Continue Shopping
            </a>
            <a href="/studynest/index.php" class="btn btn-outline">
                <i class="fa-solid fa-house"></i> Go to Home
            </a>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>