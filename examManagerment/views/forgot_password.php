<?php
session_start();
require_once __DIR__ . '/../config/database.php';


$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu</title>
</head>
<body>
    <h2>Khôi phục mật khẩu</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <p style="color:green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST" action="../controllers/AuthController.php">
        <label>Tên đăng nhập:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Email đã đăng ký:</label><br>
        <input type="email" name="email" required><br><br>

        <button type="submit" name="forgot_password">Đặt lại mật khẩu</button>
    </form>

    <br>
    <div>
        <a href="login.php">Quay lại đăng nhập</a><br>
        <a href="index.php">Trang chủ</a>
    </div>
</body>
</html>
