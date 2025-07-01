<?php
session_start();
require_once 'controllers/AuthController.php';
$error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
</head>
<body>
    <h2>Đăng ký</h2>

    <?php include "views/register_form.php"; ?>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>    
    <?php endif; ?>

    <br>
    <div>
        <a href="login.php">Đã có tài khoản? Đăng nhập</a><br>
        <a href="index.php">Quay lại trang chủ</a>
    </div>
</body>
</html>