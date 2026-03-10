<?php
session_start();
require_once '../config/db.php';
require_once '../config/paypal.php';

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Redirect if cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: store.php");
    exit();
}

// Fetch cart items
$cart_items  = [];
$subtotal    = 0;
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

$shipping    = 50;
$grand_total = $subtotal + $shipping;
$usd_total   = round($grand_total / 83, 2); // INR to USD

$error = '';

// Handle UPI / COD order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order_cod'])) {
    $full_name    = trim($_POST['full_name'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $address      = trim($_POST['address'] ?? '');
    $city         = trim($_POST['city'] ?? 'Pune');
    $pincode      = trim($_POST['pincode'] ?? '');
    $payment_type = trim($_POST['payment_type'] ?? 'cod');

    if (empty($full_name) || empty($email) || empty($address) || empty($pincode)) {
        $error = "Please fill all required fields.";
    } else {
        $order_number     = 'SN' . strtoupper(uniqid());
        $payment_method   = ($payment_type === 'upi') ? 'online' : 'cod';
        $shipping_address = "$address, $city - $pincode";

        $ins = $conn->prepare(
            "INSERT INTO orders
             (user_id, order_number, full_name, email, address, city, pincode,
              payment_method, payment_status, order_status, total_amount, shipping_address)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'pending', ?, ?)"
        );
        $ins->bind_param("isssssssds",
            $_SESSION['user_id'], $order_number, $full_name, $email,
            $address, $city, $pincode, $payment_method, $grand_total, $shipping_address
        );

        if ($ins->execute()) {
            $order_id = $conn->insert_id;
            $ins->close();

            // Insert order items with correct columns
            foreach ($cart_items as $item) {
                $unit_price = $item['price'];
                $item_sub   = $item['price'] * $item['qty'];
                $ins_item   = $conn->prepare(
                    "INSERT INTO order_items (order_id, product_id, quantity, unit_price, subtotal)
                     VALUES (?, ?, ?, ?, ?)"
                );
                $ins_item->bind_param("iiidd", $order_id, $item['id'], $item['qty'], $unit_price, $item_sub);
                $ins_item->execute();
                $ins_item->close();
            }

            $_SESSION['pending_order_id'] = $order_id;
            $_SESSION['order_total']      = $grand_total;
            unset($_SESSION['cart']);

            header("Location: payment.php?status=success&order_id=$order_id&method=" . urlencode($payment_type));
            exit();
        } else {
            $error = "Order could not be placed. Please try again.";
        }
    }
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="container listing-section">
    <h2 class="section-title">Secure Checkout</h2>

    <?php if ($error): ?>
        <div style="color:red; background:#fef2f2; border:1px solid #fecaca; padding:12px; border-radius:8px; margin-bottom:20px;">
            <i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <div class="cart-grid">
        <!-- Billing Details -->
        <div class="summary-card">
            <h3 style="margin-bottom:20px; color:var(--primary);">
                <i class="fa-solid fa-location-dot"></i> Billing & Shipping Details
            </h3>
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" id="full_name" class="form-control"
                       value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" id="email" class="form-control" placeholder="student@sppu.edu" required>
            </div>
            <div class="form-group">
                <label>Delivery Address *</label>
                <textarea id="address" class="form-control" rows="3"
                          placeholder="Room No, Hostel/Building, Area..." required></textarea>
            </div>
            <div style="display:flex; gap:15px;">
                <div class="form-group" style="flex:1;">
                    <label>City</label>
                    <input type="text" id="city" class="form-control" value="Pune">
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Pincode *</label>
                    <input type="text" id="pincode" class="form-control" placeholder="411001" required>
                </div>
            </div>
        </div>

        <!-- Order Summary + Payment -->
        <div class="summary-card">
            <h3 style="margin-bottom:20px; color:var(--primary);">
                <i class="fa-solid fa-receipt"></i> Order Summary
            </h3>
            <?php foreach ($cart_items as $item): ?>
            <div class="summary-row" style="border-bottom:1px solid var(--border); padding-bottom:10px; margin-bottom:10px;">
                <span><?php echo htmlspecialchars($item['title']); ?> × <?php echo $item['qty']; ?></span>
                <span>₹<?php echo number_format($item['total'], 2); ?></span>
            </div>
            <?php endforeach; ?>
            <div class="summary-row"><span>Subtotal</span><span>₹<?php echo number_format($subtotal, 2); ?></span></div>
            <div class="summary-row"><span>Shipping</span><span>₹<?php echo number_format($shipping, 2); ?></span></div>
            <div class="summary-row summary-total">
                <span>Total</span>
                <span>
                    ₹<?php echo number_format($grand_total, 2); ?>
                    <small style="color:var(--text-light); font-size:0.78rem;">(~$<?php echo $usd_total; ?> USD)</small>
                </span>
            </div>

            <hr style="margin:20px 0;">

            <!-- PayPal Button -->
            <p style="font-weight:600; margin-bottom:12px; color:var(--text-dark);">
                <i class="fa-brands fa-paypal" style="color:#003087;"></i> Pay with PayPal Sandbox
            </p>
            <div id="paypal-button-container"></div>

            <div style="display:flex; align-items:center; gap:10px; margin:15px 0; color:var(--text-light);">
                <hr style="flex:1;"> <span style="font-size:0.9rem;">OR</span> <hr style="flex:1;">
            </div>

            <!-- UPI Button -->
            <form method="POST" action="checkout.php" id="upi-form">
                <input type="hidden" name="payment_type" value="upi">
                <input type="hidden" name="full_name"    id="upi_full_name">
                <input type="hidden" name="email"        id="upi_email">
                <input type="hidden" name="address"      id="upi_address">
                <input type="hidden" name="city"         id="upi_city">
                <input type="hidden" name="pincode"      id="upi_pincode">
                <button type="submit" name="place_order_cod" onclick="return fillForm('upi')"
                        class="btn btn-primary" style="width:100%; padding:13px; margin-bottom:10px; cursor:pointer;">
                    <i class="fa-solid fa-mobile-screen-button"></i> Pay via UPI / GPay
                </button>
            </form>

            <!-- COD Button -->
            <form method="POST" action="checkout.php" id="cod-form">
                <input type="hidden" name="payment_type" value="cod">
                <input type="hidden" name="full_name"    id="cod_full_name">
                <input type="hidden" name="email"        id="cod_email">
                <input type="hidden" name="address"      id="cod_address">
                <input type="hidden" name="city"         id="cod_city">
                <input type="hidden" name="pincode"      id="cod_pincode">
                <button type="submit" name="place_order_cod" onclick="return fillForm('cod')"
                        class="btn btn-outline" style="width:100%; padding:13px; cursor:pointer;">
                    <i class="fa-solid fa-money-bill-wave"></i> Cash on Delivery (COD)
                </button>
            </form>
        </div>
    </div>
</main>

<!-- PayPal SDK with your Sandbox Client ID -->
<script src="https://www.paypal.com/sdk/js?client-id=<?php echo PAYPAL_CLIENT_ID; ?>&currency=USD"></script>
<script>
function validateForm() {
    const fields = ['full_name', 'email', 'address', 'pincode'];
    for (let f of fields) {
        if (!document.getElementById(f).value.trim()) {
            alert('Please fill: ' + f.replace('_', ' '));
            document.getElementById(f).focus();
            return false;
        }
    }
    return true;
}

function fillForm(type) {
    if (!validateForm()) return false;
    document.getElementById(type + '_full_name').value = document.getElementById('full_name').value;
    document.getElementById(type + '_email').value     = document.getElementById('email').value;
    document.getElementById(type + '_address').value   = document.getElementById('address').value;
    document.getElementById(type + '_city').value      = document.getElementById('city').value;
    document.getElementById(type + '_pincode').value   = document.getElementById('pincode').value;
    return true;
}

function saveBilling() {
    fetch('save_billing.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            full_name : document.getElementById('full_name').value,
            email     : document.getElementById('email').value,
            address   : document.getElementById('address').value,
            city      : document.getElementById('city').value,
            pincode   : document.getElementById('pincode').value,
        })
    });
}

paypal.Buttons({
    createOrder: function(data, actions) {
        if (!validateForm()) return;
        saveBilling();
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: '<?php echo $usd_total; ?>',
                    currency_code: 'USD'
                },
                description: 'StudyNest SPPU - Academic Books Order'
            }]
        });
    },
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            window.location.href = 'payment.php?status=success&paypal_order_id=' + data.orderID + '&method=paypal';
        });
    },
    onCancel: function(data) {
        window.location.href = 'payment.php?status=cancel';
    },
    onError: function(err) {
        alert('PayPal payment failed. Please use UPI or COD.');
        console.error(err);
    },
    style: {
        layout : 'vertical',
        color  : 'blue',
        shape  : 'rect',
        label  : 'pay'
    }
}).render('#paypal-button-container');
</script>

<?php include '../includes/footer.php'; ?>