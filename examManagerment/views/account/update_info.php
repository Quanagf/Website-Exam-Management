<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit();
}

$user = $_SESSION['user'];
$fullname = $user['fullname'] ?? '';
$email = $user['email'] ?? '';

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
    <title>Cập nhật thông tin</title>
    <link rel="stylesheet" href="../../src/css/layout.css">
    <link rel="stylesheet" href="../../src/css/profile/update_info.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>👤 Cập nhật thông tin</h1>
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
                <div class="menu-items1"><a href="javascript:history.back()"><span class="icon">🔙</span>Quay lại</a></div>
            </div>
            <div class="line"></div>

            <div class="main2">
                <h2>📝 Cập nhật thông tin</h2>

                <?php if ($success): ?>
                    <p class="message success"><?= htmlspecialchars($success) ?></p>
                <?php endif; ?>
                <?php if ($error): ?>
                    <p class="message error"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>

                <form method="post" action="../../controllers/ProfileController.php" class="update-form">
                    <label>Họ tên:</label><br>
                    <input type="text" name="fullname" value="<?= htmlspecialchars($fullname) ?>" required><br><br>

                    <label>Email:</label><br>
                    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required><br><br>

                    <button type="submit" name="update_info">💾 Cập nhật</button>
                </form>
            </div>
        </div>

        <div class="footer">
            ©2025 Quản lý thi trắc nghiệm
        </div>
    </div>
</body>

</html>