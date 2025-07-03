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

$dashboardLink = '';
switch ($user['role']) {
    case 'creator':
        $dashboardLink = '../testcreator/dashboard_creator.php';
        break;
    case 'taker':
        $dashboardLink = '../testtaker/dashboard_taker.php';
        break;
    case 'admin':
        $dashboardLink = '../admin/dashboard_admin.php';
        break;
    default:
        $dashboardLink = '../../index.php';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đổi mật khẩu</title>
    <link rel="stylesheet" href="../../src/css/layuot.css">
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🔑 Đổi mật khẩu</h1>
        <div class="menu-container">
            <div class="hamburger">&#9776;</div>
            <div class="menu-items">
                <a href="../../index.php">Trang chủ</a>
                <a href="../../logout.php">Đăng xuất</a>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="main1">
            <div class="menu-items1"><a href="profile.php"><span class="icon">🔙</span>Quay lại</a></div>
        </div>
        <div class="line"></div>

        <div class="main2">
            <h2>🔑 Đổi mật khẩu</h2>

            <?php if ($success): ?>
                <p class="message success"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>
            <?php if ($error): ?>
                <p class="message error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form method="post" action="../../controllers/ProfileController.php" class="update-form">
                <label>Mật khẩu hiện tại:</label><br>
                <input type="password" name="current_password" required><br><br>

                <label>Mật khẩu mới:</label><br>
                <input type="password" name="new_password" required><br><br>

                <label>Xác nhận mật khẩu mới:</label><br>
                <input type="password" name="confirm_password" required><br><br>

                <button type="submit" name="change_password">🔁 Đổi mật khẩu</button>
            </form>

           
        </div>
    </div>

    <div class="footer">
        ©2025 Quản lý thi trắc nghiệm
    </div>
</div>
</body>
</html>
