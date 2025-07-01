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
<html>
<head>
    <meta charset="UTF-8">
    <title>Tạo đề thi</title>
</head>
<body>
    <h2>Tạo đề thi</h2>

    <form method="POST" action="../../controllers/TestController.php">
        <label>📝 Tiêu đề:</label>
        <input type="text" name="title" required><br><br>

        <label>📄 Mô tả:</label><br>
        <textarea name="description" rows="4" cols="50"></textarea><br><br>

        <label>⏱️ Thời gian làm bài (phút):</label>
        <input type="number" name="duration" min="1" required><br><br>

        <label>🕒 Ngày & giờ bắt đầu thi:</label>
        <input type="datetime-local" name="open_time" required><br><br>

        <label>🕓 Ngày & giờ kết thúc thi:</label>
        <input type="datetime-local" name="close_time" required><br><br>

        <button type="submit" name="create">✅ Tạo đề</button>
    </form>

    <?php if (!empty($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
</body>
</html>