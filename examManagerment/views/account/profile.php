<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit();
}
$user = $_SESSION['user'];
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>

<h2>Thông tin tài khoản</h2>
<p>Tên đăng nhập: <?= htmlspecialchars($user['username']) ?></p>
<p>Email: <?= htmlspecialchars($user['email']) ?></p>
<p>Họ tên: <?= htmlspecialchars($user['fullname']) ?></p>
<p>Quyền: <?= htmlspecialchars($user['role']) ?></p>

<a href="update_info.php">Cập nhật thông tin</a> |
<a href="change_password.php">Đổi mật khẩu</a><br>
<?php if ($success): ?>
    <p style="color: green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>
<?php if ($error): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<?php
$dashboardLink = '';
switch ($user['role']) {
    case 'creator':
        $dashboardLink = '../testcreator/dashboard_testcreator.php';
        break;
    case 'taker':
        $dashboardLink = '../testtaker/dashboard_taker.php';
        break;
    case 'admin':
        $dashboardLink = '../admin/dashboard_admin.php';
        break;
    default:
        $dashboardLink = '../index.php';
}
?>

<a href="<?= $dashboardLink ?>">◀--- Quay lại trang dashboard</a>
