<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}
$user = $_SESSION['user'];
?>

<h2>Thông tin tài khoản</h2>
<p>Tên đăng nhập: <?= htmlspecialchars($user['username']) ?></p>
<p>Email: <?= htmlspecialchars($user['email']) ?></p>
<p>Họ tên: <?= htmlspecialchars($user['fullname']) ?></p>
<p>Quyền: <?= htmlspecialchars($user['role']) ?></p>

<a href="update_info.php">Cập nhật thông tin</a> |
<a href="change_password.php">Đổi mật khẩu</a>
