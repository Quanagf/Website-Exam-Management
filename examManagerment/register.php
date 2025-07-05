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
    <link rel="stylesheet" href="src/css/register.css">
</head>

<body>
    <div class="register-container">
        <h2>Đăng ký</h2>

        <?php include "views/register_form.php"; ?>

        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <br>
        <div class="links">
            <a href="index.php">Đã có tài khoản? Đăng nhập</a><br>
        </div>
    </div>
    <script src="src/js/register.js"></script>
</body>

</html>