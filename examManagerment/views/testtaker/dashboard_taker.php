<?php
session_start();
require_once(__DIR__ . '/../../config/database.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'taker') {
    header("Location: ../../index.php");
    exit();
}

$user = $_SESSION['user'];
$user_id = $user['id'];

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>🎯 Dashboard Thí Sinh</title>
    <link rel="stylesheet" href="../../src/css/layout.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🎯 Xin chào, <?= htmlspecialchars($user['fullname'] ?? $user['username']) ?>!</h1>
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
                <div class="menu-items1"><a href="dashboard_taker.php"><span class="icon">🏠</span> Trang chính</a>
                </div>
                <div class="menu-items1"><a href="../account/profile.php"><span class="icon">👤</span> Tài khoản</a>
                </div>
                <div class="menu-items1"><a href="../../logout.php"><span class="icon">🚪</span> Đăng xuất</a></div>
            </div>
            <div class="line"></div>

            <div class="main2">
                <!-- Nhập mã đề thi -->
                <form method="POST" action="../../controllers/JointestController.php">
                    <label>🔑 Nhập mã đề thi:</label><br>
                    <input type="text" name="share_code" required>
                    <button type="submit" name="join_test">✅ Vào thi</button>
                </form>

                <!-- Hiển thị lỗi -->
                <?php if (!empty($error)): ?>
                    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>

                <hr>

                <!-- Xem các bài đã thi -->
                <form method="POST" action="../../controllers/JointestController.php">
                    <input type="hidden" name="view_submitted_tests" value="1">
                    <button type="submit">📊 Xem các bài đã thi</button>
                </form>
            </div>
        </div>

        <div class="footer">
            ©2025 Hệ thống thi trắc nghiệm
        </div>
    </div>
</body>

</html>