<?php
session_start();
require_once '../../controllers/TestController.php';
require_once __DIR__ . '/../../config/database.php';
// Kiểm tra quyền truy cập
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'creator') {
    header("Location: ../../index.php");
    exit();
}

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
    <title>Tạo đề thi</title>
    <link rel="stylesheet" href="../../src/css/layout.css">
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🎓 Quản lý đề thi</h1>
        <div class="menu-container">
            <div class="hamburger">&#9776;</div>
            <div class="menu-items">
                <a href="../../index.php">Trang chủ</a>
                <a href="../account/profile.php">Tài khoản</a>
                <a href="../../logout.php">Đăng xuất</a>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="main1">
         
            <div class="menu-items1"><a href="javascript:history.back()"><span class="icon">🔙</span>Quay lại</a></div>
            <div class="menu-items1"><a href="../account/profile.php"><span class="icon">👤</span> Tài khoản</a></div>
            <div class="menu-items1"><a href="../../logout.php"><span class="icon">🚪</span> Đăng xuất</a></div>
        </div>
        <div class="line"></div>

        <div class="main2">
            <h2>📝 Tạo đề thi mới</h2>

            <?php if (!empty($error)): ?>
                <p class="message error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form method="POST" action="../../controllers/TestController.php" class="create-test-form">
                <label>📝 Tiêu đề:</label><br>
                <input type="text" name="title" required><br><br>

                <label>📄 Mô tả:</label><br>
                <textarea name="description" rows="4" cols="50"></textarea><br><br>

                <label>⏱️ Thời gian làm bài (phút):</label><br>
                <input type="number" name="duration" min="1" required><br><br>

                <label>🕒 Ngày & giờ bắt đầu thi:</label><br>
                <input type="datetime-local" name="open_time" required><br><br>

                <label>🕓 Ngày & giờ kết thúc thi:</label><br>
                <input type="datetime-local" name="close_time" required><br><br>

                <button type="submit" name="create">✅ Tạo đề</button>
            </form>
        </div>
    </div>

    <div class="footer">
        ©2025 Quản lý thi trắc nghiệm
    </div>
</div>
</body>
</html>
