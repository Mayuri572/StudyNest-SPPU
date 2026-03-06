<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<main class="container listing-section">
    <h2 class="section-title">Academic Book Store</h2>
    
    <div class="toolbar">
        <select class="form-control" style="width: 200px;">
            <option>All Categories</option>
            <option>Textbooks</option>
            <option>Decodes / Guides</option>
        </select>
    </div>

    <div class="product-grid">
        <div class="card">
            <a href="product-detail.php">
                <img src="https://via.placeholder.com/300x400?text=Book+Cover" style="width:100%; height:250px; object-fit:contain; padding:20px; background:#fff;">
            </a>
            <div class="card-content">
                <span class="card-tag">Textbook</span>
                <a href="product-detail.php"><h3 class="card-title">Theory of Computation</h3></a>
                <p class="card-desc">By Vivek Kulkarni | Standard reference for SPPU.</p>
                <div style="color:var(--accent); margin-bottom:10px;">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <div class="card-price" style="margin:0;">₹450</div>
                    <button class="btn btn-primary add-to-cart-btn"><i class="fa-solid fa-cart-plus"></i> Add</button>
                </div>
            </div>
        </div>
        <div class="card">
            <a href="product-detail.php">
                <img src="https://via.placeholder.com/300x400?text=Decode+Cover" style="width:100%; height:250px; object-fit:contain; padding:20px; background:#fff;">
            </a>
            <div class="card-content">
                <span class="card-tag">Guide</span>
                <a href="product-detail.php"><h3 class="card-title">DBMS Decode (2019)</h3></a>
                <p class="card-desc">Techneo Publications | Quick exam revision guide.</p>
                <div style="color:var(--accent); margin-bottom:10px;">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <div class="card-price" style="margin:0;">₹210</div>
                    <button class="btn btn-primary add-to-cart-btn"><i class="fa-solid fa-cart-plus"></i> Add</button>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>