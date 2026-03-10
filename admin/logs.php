<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php"); exit();
}

$log_file = '../logs/login_logs.txt';
$logs     = [];
if (file_exists($log_file)) {
    $lines = array_reverse(array_filter(explode("\n", file_get_contents($log_file))));
    foreach ($lines as $line) {
        if (trim($line)) $logs[] = trim($line);
    }
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="container" style="padding:40px 20px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; flex-wrap:wrap; gap:15px;">
        <div style="display:flex; align-items:center; gap:15px;">
            <a href="dashboard.php" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i></a>
            <h2 style="color:var(--primary);">Login Logs</h2>
        </div>
        <span style="color:var(--text-light); font-size:0.9rem;"><?php echo count($logs); ?> entries</span>
    </div>

    <div class="card" style="padding:20px; overflow-x:auto;">
        <?php if (empty($logs)): ?>
            <p style="color:var(--text-light); text-align:center; padding:40px;">No login logs yet.</p>
        <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr><th>Timestamp</th><th>Email</th><th>Status</th><th>IP Address</th></tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log):
                    $parts = array_map('trim', explode('|', $log));
                    if (count($parts) < 4) continue;
                    $is_failed = str_contains($parts[2], 'FAILED');
                ?>
                <tr>
                    <td style="font-size:0.9rem; color:var(--text-light);"><?php echo htmlspecialchars($parts[0]); ?></td>
                    <td><?php echo htmlspecialchars($parts[1]); ?></td>
                    <td>
                        <span style="padding:4px 12px; border-radius:20px; font-size:0.8rem; font-weight:600;
                            background:<?php echo $is_failed ? '#fef2f2' : '#f0fdf4'; ?>;
                            color:<?php echo $is_failed ? '#ef4444' : '#22c55e'; ?>;">
                            <?php echo htmlspecialchars($parts[2]); ?>
                        </span>
                    </td>
                    <td style="font-size:0.9rem; color:var(--text-light);"><?php echo htmlspecialchars($parts[3]); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>