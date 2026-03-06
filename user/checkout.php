<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<main class="container listing-section">
    <h2 class="section-title">Secure Checkout</h2>

    <div class="cart-grid">
        <div class="summary-card">
            <h3 style="margin-bottom:20px; color:var(--primary);">Billing & Shipping Details</h3>
            <form>
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" class="form-control" placeholder="John Doe">
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" class="form-control" placeholder="student@sppu.edu">
                </div>
                <div class="form-group">
                    <label>Delivery Address</label>
                    <textarea class="form-control" rows="3" placeholder="Room No, Hostel/Building, Area..."></textarea>
                </div>
                <div style="display:flex; gap:15px;">
                    <div class="form-group" style="flex:1;">
                        <label>City</label>
                        <input type="text" class="form-control" value="Pune">
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label>Pincode</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
            </form>
        </div>

        <div class="summary-card">
            <h3 style="margin-bottom:20px; color:var(--primary);">Payment</h3>
            
            <label style="display:flex; align-items:center; gap:10px; padding:15px; border:1px solid var(--accent); border-radius:8px; margin-bottom:15px; background:#fffbeb; cursor:pointer;">
                <input type="radio" name="payment" checked>
                <strong>UPI / GPay (Recommended)</strong>
            </label>
            
            <label style="display:flex; align-items:center; gap:10px; padding:15px; border:1px solid var(--border); border-radius:8px; margin-bottom:20px; cursor:pointer;">
                <input type="radio" name="payment">
                <strong>Cash on Delivery (COD)</strong>
            </label>

            <div class="summary-row summary-total"><span>Amount to Pay:</span><span>₹500</span></div>
            <button class="btn btn-primary" style="width:100%; margin-top:15px; font-size:1.1rem;">Place Order Now</button>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>