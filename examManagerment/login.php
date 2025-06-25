<?php
session_start();
require_once 'controllers/AuthController.php';
require_once './config/database.php';
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
    <title>Đăng nhập</title>
</head>
<body>
    <h2>Đăng nhập</h2>
    <?php
    include "views/login_form.php";
    ?>
    <?php if (!empty($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
</body>
</html>