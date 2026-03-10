<?php
session_start();
require_once '../config/db.php';

$error   = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';

    if (empty($full_name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "An account with this email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, 'user')");
            $insert->bind_param("sss", $full_name, $email, $hashed_password);

            if ($insert->execute()) {
                $success = "Account created successfully! <a href='login.php' style='color:var(--primary); font-weight:600;'>Sign In here</a>.";
            } else {
                $error = "Registration failed. Please try again.";
            }
            $insert->close();
        }
        $stmt->close();
    }
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="auth-container">
    <div class="auth-card">
        <h2>Create an Account</h2>
        <p class="subtitle">Join StudyNest SPPU today — it's free!</p>

        <?php if ($error): ?>
            <div style="color:red; background:#fef2f2; border:1px solid #fecaca; padding:12px; border-radius:8px; margin-bottom:15px; font-size:0.95rem;">
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div style="color:green; background:#f0fdf4; border:1px solid #bbf7d0; padding:12px; border-radius:8px; margin-bottom:15px; font-size:0.95rem;">
                <i class="fa-solid fa-circle-check"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" class="form-control" placeholder="John Doe" required
                       value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="student@sppu.edu" required
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Min. 6 characters" required minlength="6">
            </div>
            <button type="submit" class="btn btn-accent" style="width:100%; padding:12px; margin-top:10px; cursor:pointer;">
                <i class="fa-solid fa-user-plus"></i> Create Account
            </button>
        </form>

        <p style="margin-top:25px; color:var(--text-light); font-size:0.95rem;">
            Already have an account? <a href="login.php" style="color:var(--primary); font-weight:600;">Sign In</a>
        </p>
    </div>
</main>

<?php include '../includes/footer.php'; ?>