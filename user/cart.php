<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Handle actions: update qty, remove
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action     = $_POST['action'] ?? '';
    $product_id = (int)($_POST['product_id'] ?? 0);

    if ($action === 'remove' && isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    } elseif ($action === 'update' && $product_id > 0) {
        $qty = (int)($_POST['quantity'] ?? 1);
        if ($qty <= 0) {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $_SESSION['cart'][$product_id] = $qty;
        }
    }
    header("Location: cart.php");
    exit();
}

// Fetch product details for items in cart
$cart_items = [];
$subtotal   = 0;

if (!empty($_SESSION['cart'])) {
    $ids         = array_keys($_SESSION['cart']);
    $placeholder = implode(',', array_fill(0, count($ids), '?'));
    $types       = str_repeat('i', count($ids));
    $stmt        = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholder)");
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $qty          = $_SESSION['cart'][$row['id']];
        $row['qty']   = $qty;
        $row['total'] = $row['price'] * $qty;
        $subtotal    += $row['total'];
        $cart_items[] = $row;
    }
    $stmt->close();
}

$shipping = count($cart_items) > 0 ? 50 : 0;
$grand_total = $subtotal + $shipping;

include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="container listing-section">
    <h2 class="section-title">Your Shopping Cart</h2>

    <?php if (empty($cart_items)): ?>
        <div style="text-align:center; padding:60px 20px; color:var(--text-light);">
            <i class="fa-solid fa-cart-shopping" style="font-size:4rem; margin-bottom:20px; color:#e2e8f0;"></i>
            <h3 style="margin-bottom:10px;">Your cart is empty</h3>
            <p>Add some books to get started!</p>
            <a href="store.php" class="btn btn-primary" style="margin-top:20px;">Browse Store</a>
        </div>
    <?php else: ?>
    <div class="cart-grid">
        <div class="cart-table-wrapper">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td style="display:flex; align-items:center; gap:15px;">
                            <?php if (!empty($item['image'])): ?>
                                <img src="/studynest/assets/images/<?php echo htmlspecialchars($item['image']); ?>"
                                     alt="<?php echo htmlspecialchars($item['title']); ?>"
                                     style="width:60px; height:60px; object-fit:contain; border-radius:6px;">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/60?text=Book"
                                     alt="<?php echo htmlspecialchars($item['title']); ?>"
                                     style="border-radius:6px;">
                            <?php endif; ?>
                            <div>
                                <strong><?php echo htmlspecialchars($item['title']); ?></strong>
                                <?php if (!empty($item['author'])): ?>
                                    <br><small style="color:var(--text-light);"><?php echo htmlspecialchars($item['author']); ?></small>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>₹<?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <form method="POST" action="cart.php" style="display:inline;">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <input type="number" name="quantity" value="<?php echo $item['qty']; ?>"
                                       min="1" max="10" class="form-control"
                                       style="width:70px; padding:8px; display:inline-block;"
                                       onchange="this.form.submit()">
                            </form>
                        </td>
                        <td style="font-weight:600; color:var(--primary);">₹<?php echo number_format($item['total'], 2); ?></td>
                        <td>
                            <form method="POST" action="cart.php">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="btn" style="background:#fef2f2; color:#ef4444; padding:8px 12px;"
                                        onclick="return confirm('Remove this item?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="summary-card">
            <h3 style="margin-bottom:20px; color:var(--primary);">Order Summary</h3>
            <div class="summary-row"><span>Subtotal</span><span>₹<?php echo number_format($subtotal, 2); ?></span></div>
            <div class="summary-row"><span>Shipping</span><span>₹<?php echo number_format($shipping, 2); ?></span></div>
            <div class="summary-row summary-total"><span>Total</span><span>₹<?php echo number_format($grand_total, 2); ?></span></div>
            <a href="checkout.php" class="btn btn-accent" style="width:100%; margin-top:20px; font-size:1.1rem; text-align:center;">
                <i class="fa-solid fa-lock"></i> Proceed to Checkout
            </a>
            <a href="store.php" class="btn btn-outline" style="width:100%; margin-top:10px; text-align:center;">
                <i class="fa-solid fa-arrow-left"></i> Continue Shopping
            </a>
        </div>
    </div>
    <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>