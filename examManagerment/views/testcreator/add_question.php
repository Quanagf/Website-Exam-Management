<?php
session_start();
require_once '../../controllers/TestController.php';
require_once __DIR__ . '/../../config/database.php';
// Kiểm tra quyền truy cập
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'creator') {
    header("Location: ../../index.php");
    exit();
}
$test_id = isset($_GET['test_id']) ? intval($_GET['test_id']) : 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm câu hỏi mới</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <h2>➕ Thêm câu hỏi</h2>

    <form action="../../controllers/QuestionController.php" method="POST">
        <input type="hidden" name="test_id" value="<?= $test_id ?>">
        <input type="hidden" name="add_question" value="1"> <!-- Đổi tên đúng với controller -->

        <label>Câu hỏi:</label><br>
        <textarea name="content" required></textarea><br>

        <label>Đáp án A:</label><br>
        <input type="text" name="option_a" required><br>

        <label>Đáp án B:</label><br>
        <input type="text" name="option_b" required><br>

        <label>Đáp án C:</label><br>
        <input type="text" name="option_c" required><br>

        <label>Đáp án D:</label><br>
        <input type="text" name="option_d" required><br>

        <label>Đáp án đúng:</label><br>
        <select name="correct" required>
            <option value="">-- Chọn --</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
        </select><br><br>

        <!-- ❌ Bỏ input điểm -->
        <!-- ✅ Thông báo -->
        <p style="color: gray; font-size: 14px;">💡 Điểm sẽ được tự động tính: 10 chia đều cho số câu.</p>

        <button type="submit">💾 Lưu câu hỏi</button>
    </form>
</body>
</html>
