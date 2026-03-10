<?php
session_start();
require_once 'config/db.php';

$newsletter_msg   = '';
$newsletter_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newsletter_email'])) {
    $email = trim($_POST['newsletter_email'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $newsletter_error = "Please enter a valid email address.";
    } else {
        // Check duplicate
        $chk = $conn->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
        $chk->bind_param("s", $email);
        $chk->execute();
        $chk->store_result();
        if ($chk->num_rows > 0) {
            $newsletter_msg = "You are already subscribed!";
        } else {
            $ins = $conn->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)");
            $ins->bind_param("s", $email);
            $ins->execute() 
                ? $newsletter_msg = "🎉 Subscribed successfully! You'll get the latest updates."
                : $newsletter_error = "Subscription failed. Please try again.";
            $ins->close();
        }
        $chk->close();
    }
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

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
                <img src="/studynest/assets/images/hero_section_img.png"
                     alt="hero_section_img.png"
                     style="border-radius:20px; box-shadow:var(--shadow-lg); width:100%;">
            </div>
        </div>
    </section>

    <section class="listing-section container">
        <h2 class="section-title">Why Choose StudyNest?</h2>
        <div class="product-grid">
            <div class="card text-center" style="padding:30px;">
                <i class="fa-solid fa-book-open" style="font-size:3rem; color:var(--accent); margin-bottom:20px;"></i>
                <h3 class="card-title">Updated Notes</h3>
                <p class="card-desc">Topper handwritten and curated digital notes matching the latest SPPU syllabus.</p>
            </div>
            <div class="card text-center" style="padding:30px;">
                <i class="fa-solid fa-file-pdf" style="font-size:3rem; color:var(--accent); margin-bottom:20px;"></i>
                <h3 class="card-title">PYQ Bank</h3>
                <p class="card-desc">Topic-wise sorted previous year question papers to understand exam patterns.</p>
            </div>
            <div class="card text-center" style="padding:30px;">
                <i class="fa-solid fa-cart-shopping" style="font-size:3rem; color:var(--accent); margin-bottom:20px;"></i>
                <h3 class="card-title">Academic Store</h3>
                <p class="card-desc">Buy recommended textbooks and reference books at discounted prices.</p>
            </div>
        </div>
    </section>
</main>

<!-- Newsletter Success/Error Messages via JS -->
<?php if ($newsletter_msg || $newsletter_error): ?>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        const msg = <?php echo json_encode($newsletter_msg ?: $newsletter_error); ?>;
        const isErr = <?php echo $newsletter_error ? 'true' : 'false'; ?>;
        const div = document.createElement('div');
        div.innerText = msg;
        div.style.cssText = `position:fixed; bottom:20px; right:20px; padding:15px 25px;
            background:${isErr ? '#fef2f2' : '#f0fdf4'}; color:${isErr ? '#ef4444' : '#22c55e'};
            border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.1); z-index:9999; font-weight:500;`;
        document.body.appendChild(div);
        setTimeout(() => div.remove(), 4000);
    });
</script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
