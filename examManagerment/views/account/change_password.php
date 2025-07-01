<?php
session_start();
$user = $_SESSION['user'] ?? null;

if (!$user) {
    header("Location: ../../index.php");
    exit();
}

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>
<?php if ($success): ?><p style="color: green;"><?= $success ?></p><?php endif; ?>
<?php if ($error): ?><p style="color: red;"><?= $error ?></p><?php endif; ?>
<h2>🔑 Đổi mật khẩu</h2>

<form method="post" action="../../controllers/ProfileController.php">
    <label>Mật khẩu hiện tại:</label><br>
    <input type="password" name="current_password" required><br>

    <label>Mật khẩu mới:</label><br>
    <input type="password" name="new_password" required><br>

    <label>Xác nhận mật khẩu mới:</label><br>
    <input type="password" name="confirm_password" required><br><br>

    <button type="submit" name="change_password">🔁 Đổi mật khẩu</button>
</form>


<p><a href="profile.php">🔙 Quay lại trang quản lý tài khoản</a></p>
