<?php
require_once 'controllers/AuthController.php';
require_once './config/database.php';
$error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
$success = '';
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="src/css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Đăng nhập</h2>

        <?php include "views/login_form.php"; ?>

        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>

        <div class="links">
            <a href="register.php">Chưa có tài khoản? Đăng ký</a>
            <a href="views/forgot_password.php">Quên mật khẩu?</a>
            <a href="index.php">Quay lại trang chủ</a>
        </div>
    </div>

    <script src="src/js/login.js"></script>
</body>
</html>
