<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php"); exit();
}
require_once '../config/db.php';

$success = '';
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    if ($del_id !== $_SESSION['user_id']) { // Prevent self-deletion
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
        $stmt->bind_param("i", $del_id);
        $stmt->execute();
        $stmt->close();
        $success = "User deleted.";
    }
    header("Location: manage-users.php"); exit();
}

$users = $conn->query("SELECT id, full_name, email, role, created_at FROM users ORDER BY created_at DESC");

include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="container" style="padding:40px 20px;">
    <div style="display:flex; align-items:center; gap:15px; margin-bottom:25px;">
        <a href="dashboard.php" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i></a>
        <h2 style="color:var(--primary);">Manage Users</h2>
    </div>

    <?php if ($success): ?>
        <div style="color:green; background:#f0fdf4; border:1px solid #bbf7d0; padding:12px; border-radius:8px; margin-bottom:20px;">
            <i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <div class="card" style="padding:20px; overflow-x:auto;">
        <table class="cart-table">
            <thead>
                <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Joined</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php while ($u = $users->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $u['id']; ?></td>
                    <td><strong><?php echo htmlspecialchars($u['full_name']); ?></strong></td>
                    <td style="color:var(--text-light);"><?php echo htmlspecialchars($u['email']); ?></td>
                    <td>
                        <span style="padding:4px 12px; border-radius:20px; font-size:0.8rem; font-weight:600;
                            background:<?php echo $u['role']==='admin' ? '#eff6ff' : '#f8fafc'; ?>;
                            color:<?php echo $u['role']==='admin' ? '#3b82f6' : '#64748b'; ?>;">
                            <?php echo strtoupper($u['role']); ?>
                        </span>
                    </td>
                    <td style="font-size:0.85rem; color:var(--text-light);"><?php echo htmlspecialchars($u['created_at']); ?></td>
                    <td>
                        <?php if ($u['role'] !== 'admin'): ?>
                            <a href="manage-users.php?delete=<?php echo $u['id']; ?>"
                               class="btn" style="background:#fef2f2; color:#ef4444; padding:8px 12px; font-size:0.85rem;"
                               onclick="return confirm('Delete this user?')">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        <?php else: ?>
                            <span style="color:var(--text-light); font-size:0.8rem;">Protected</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../includes/footer.php'; ?>