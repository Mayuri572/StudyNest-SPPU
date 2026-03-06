<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<main>
    <section class="hero">
        <div class="container hero-container">
            <div class="hero-content">
                <h1>Your Smart Companion for SPPU Studies</h1>
                <p>Access high-quality, updated notes, previous year question papers, and buy academic books directly from our student-friendly store. Ace your exams easily!</p>
                <div style="display:flex; gap:15px; flex-wrap:wrap;">
                    <a href="user/notes.php" class="btn btn-primary">Explore Notes</a>
                    <a href="user/store.php" class="btn btn-accent">Visit Store</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="https://via.placeholder.com/600x450?text=Education+Illustration" alt="Hero Illustration" style="border-radius:20px; box-shadow:var(--shadow-lg); width:100%;">
            </div>
        </div>
    </section>

    <section class="listing-section container">
        <h2 class="section-title">Why Choose StudyNest?</h2>
        <div class="product-grid">
            <div class="card text-center" style="padding: 30px;">
                <i class="fa-solid fa-book-open" style="font-size:3rem; color:var(--accent); margin-bottom:20px;"></i>
                <h3 class="card-title">Updated Notes</h3>
                <p class="card-desc">Topper handwritten and curated digital notes matching the latest syllabus.</p>
            </div>
            <div class="card text-center" style="padding: 30px;">
                <i class="fa-solid fa-file-pdf" style="font-size:3rem; color:var(--accent); margin-bottom:20px;"></i>
                <h3 class="card-title">PYQ Bank</h3>
                <p class="card-desc">Topic-wise sorted previous year question papers to understand exam patterns.</p>
            </div>
            <div class="card text-center" style="padding: 30px;">
                <i class="fa-solid fa-cart-shopping" style="font-size:3rem; color:var(--accent); margin-bottom:20px;"></i>
                <h3 class="card-title">Academic Store</h3>
                <p class="card-desc">Buy recommended textbooks and reference books at discounted prices.</p>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>