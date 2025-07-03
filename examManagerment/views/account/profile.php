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
    <title>Thông tin tài khoản</title>
    <link rel="stylesheet" href="../../src/css/layout.css">
</head>
<body>
<div class="container">
    <div class="header">
        <h1>👤 Thông tin tài khoản</h1>
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

            <div class="menu-items1"><a href="../../index.php"><span class="icon">🔙</span>Quay lại</a></div>
        </div>
        <div class="line"></div>

        <div class="main2">
            <h2>👋 Xin chào, <?= htmlspecialchars($user['fullname'] ?? $user['username']) ?></h2>
            <?php if ($success): ?>
                <p class="message success"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>
            <?php if ($error): ?>
                <p class="message error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <p><strong>Tên đăng nhập:</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Họ tên:</strong> <?= htmlspecialchars($user['fullname']) ?></p>
            <p><strong>Quyền:</strong> <?= htmlspecialchars($user['role']) ?></p>

            <div class="action-links">
                <a href="update_info.php">✏️ Cập nhật thông tin</a> |
                <a href="change_password.php">🔑 Đổi mật khẩu</a> |
            </div>
        </div>
    </div>

    <div class="footer">
        ©2025 Quản lý thi trắc nghiệm
    </div>
</div>
</body>
</html>
