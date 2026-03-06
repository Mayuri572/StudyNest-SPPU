<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<main class="container listing-section">
    <a href="store.php" class="btn btn-outline" style="margin-bottom: 20px;"><i class="fa-solid fa-arrow-left"></i> Back to Store</a>

    <div class="product-detail-grid">
        <img src="https://via.placeholder.com/500x600?text=Book+Cover" class="product-image-large">
        
        <div>
            <span class="card-tag">Textbook</span>
            <h1 style="color:var(--primary); font-size:2.5rem; margin-top:10px;">Theory of Computation</h1>
            <p style="font-size:1.1rem; color:var(--text-light); margin-top:10px;">Author: Vivek Kulkarni</p>
            
            <div class="detail-price">₹450 <span style="font-size:1.2rem; color:var(--text-light); text-decoration:line-through; font-weight:400;">₹550</span></div>
            
            <p style="margin-bottom: 25px;">Highly recommended textbook strictly aligned with the latest SPPU syllabus. It covers Automata Theory, Turing Machines, and Complexity classes with extensively solved examples and previous university questions.</p>
            
            <div class="qty-selector">
                <label style="font-weight:600;">Quantity:</label>
                <input type="number" class="form-control" value="1" min="1">
            </div>
            
            <button class="btn btn-primary add-to-cart-btn" style="width:100%; font-size:1.1rem; padding:15px;"><i class="fa-solid fa-cart-shopping"></i> Add to Cart</button>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>