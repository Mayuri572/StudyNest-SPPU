<?php
// admin/dashboard.php
session_start();

// Protection: Check if user is logged in AND has 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Unauthorized access, redirect to login
    header("Location: ../auth/login.php");
    exit();
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h4>Admin Dashboard</h4>
        </div>
        <div class="card-body">
            <h5 class="card-title">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h5>
            <p class="card-text">You have administrative privileges to manage StudyNest.</p>
            
            <hr>
            
            <div class="d-grid gap-2 d-md-block">
                <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
                </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>