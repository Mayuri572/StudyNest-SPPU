<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<main class="container listing-section">
    <h2 class="section-title">Your Shopping Cart</h2>

    <div class="cart-grid">
        <div class="cart-table-wrapper">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="display:flex; align-items:center; gap:15px;">
                            <img src="https://via.placeholder.com/60" style="border-radius:6px;">
                            <strong>Theory of Computation</strong>
                        </td>
                        <td>₹450</td>
                        <td><input type="number" value="1" class="form-control" style="width:70px; padding:8px;"></td>
                        <td style="font-weight:600; color:var(--primary);">₹450</td>
                        <td><button class="btn remove-item-btn" style="background:#fef2f2; color:#ef4444; padding:8px 12px;"><i class="fa-solid fa-trash"></i></button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="summary-card">
            <h3 style="margin-bottom:20px; color:var(--primary);">Order Summary</h3>
            <div class="summary-row"><span>Subtotal</span><span>₹450</span></div>
            <div class="summary-row"><span>Shipping</span><span>₹50</span></div>
            <div class="summary-row summary-total"><span>Total</span><span>₹500</span></div>
            <a href="checkout.php" class="btn btn-accent" style="width:100%; margin-top:20px; font-size:1.1rem;">Proceed to Checkout</a>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>