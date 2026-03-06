<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<main class="container listing-section">
    <h2 class="section-title">Premium SPPU Notes</h2>
    
    <div class="toolbar">
        <select class="form-control" style="width: 200px;">
            <option>All Branches</option>
            <option>Computer Engineering</option>
            <option>IT Engineering</option>
            <option>E&TC Engineering</option>
        </select>
        <div class="search-bar" style="background:var(--white); width: 100%; max-width: 300px;">
            <input type="text" placeholder="Search subjects..." style="width:100%;">
            <i class="fa-solid fa-search"></i>
        </div>
    </div>

    <div class="product-grid">
        <div class="card">
            <img src="https://via.placeholder.com/400x250?text=DSA+Notes" class="img-placeholder">
            <div class="card-content">
                <span class="card-tag">Computer Engg</span>
                <h3 class="card-title">Data Structures & Algorithms</h3>
                <p class="card-desc">Complete Unit 1 to 6 handwritten notes with C++ code examples and diagrams.</p>
                <a href="#" class="btn btn-outline" style="width:100%;"><i class="fa-solid fa-eye"></i> View Notes</a>
            </div>
        </div>
        <div class="card">
            <img src="https://via.placeholder.com/400x250?text=Maths+III" class="img-placeholder">
            <div class="card-content">
                <span class="card-tag">Second Year</span>
                <h3 class="card-title">Engineering Mathematics III</h3>
                <p class="card-desc">Step-by-step solutions for transform calculus and statistics.</p>
                <a href="#" class="btn btn-outline" style="width:100%;"><i class="fa-solid fa-eye"></i> View Notes</a>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>