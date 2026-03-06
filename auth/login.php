<?php
session_start();
require_once '../config/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $captcha_input = trim($_POST['captcha']);

    if (empty($email) || empty($password) || empty($captcha_input)) {
        $error = "Please fill all fields.";
    } elseif (strtolower($captcha_input) !== strtolower($_SESSION['captcha'] ?? '')) {
        $error = "Invalid CAPTCHA code.";
    } else {
        $stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header("Location: ../admin/dashboard.php");
                } else {
                    header("Location: ../index.php");
                }
                exit();
            } else { $error = "Incorrect password."; }
        } else { $error = "Email not found."; }
        $stmt->close();
    }
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="auth-container">
    <div class="auth-card">
        <h2>Welcome Back!</h2>
        <p class="subtitle">Sign in to access your notes and orders.</p>

        <?php if($error): ?>
            <div class="alert alert-danger" style="color:red; margin-bottom:15px;"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="login.php" method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="student@sppu.edu" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="form-group" style="margin-top:15px;">
                <label>Security Check</label>
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                    <img src="captcha.php" id="cap_img" style="border:1px solid #ddd; border-radius:4px;">
                    <button type="button" onclick="document.getElementById('cap_img').src='captcha.php?'+Math.random();" style="background:none; border:none; color:var(--primary); cursor:pointer; font-size:0.8rem;">Refresh</button>
                </div>
                <input type="text" name="captcha" class="form-control" placeholder="Enter code" required>
            </div>

            <div style="text-align: right; margin-bottom: 20px; margin-top:10px;">
                <a href="#" style="font-size: 0.9rem; color: var(--primary);">Forgot Password?</a>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; padding:12px; cursor:pointer;">Sign In</button>
        </form>
        
        <p style="margin-top:25px; color:var(--text-light); font-size:0.95rem;">
            Don't have an account? <a href="register.php" style="color:var(--primary); font-weight:600;">Register Here</a>
        </p>
    </div>
</main>

<?php include '../includes/footer.php'; ?>