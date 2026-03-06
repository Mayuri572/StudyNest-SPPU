<?php
session_start();
require_once '../config/db.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

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
            $error = "Email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, 'user')");
            $insert_stmt->bind_param("sss", $full_name, $email, $hashed_password);
            
            if ($insert_stmt->execute()) {
                $success = "Account created! <a href='login.php'>Sign In here</a>.";
            } else {
                $error = "Registration failed. Try again.";
            }
            $insert_stmt->close();
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
        <p class="subtitle">Join StudyNest SPPU today.</p>
        
        <?php if($error): ?>
            <div class="alert alert-danger" style="color:red; margin-bottom:15px;"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success" style="color:green; margin-bottom:15px;"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" class="form-control" placeholder="John Doe" required>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="student@sppu.edu" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Min. 6 characters" required minlength="6">
            </div>
            <button type="submit" class="btn btn-accent" style="width:100%; padding:12px; margin-top:10px; cursor:pointer;">Create Account</button>
        </form>
        
        <p style="margin-top:25px; color:var(--text-light); font-size:0.95rem;">
            Already have an account? <a href="login.php" style="color:var(--primary); font-weight:600;">Sign In</a>
        </p>
    </div>
</main>

<?php include '../includes/footer.php'; ?>