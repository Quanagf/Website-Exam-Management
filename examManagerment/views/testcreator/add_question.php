<?php
session_start();
require_once '../../controllers/TestController.php';
require_once __DIR__ . '/../../config/database.php';
$test_id = isset($_GET['test_id']) ? intval($_GET['test_id']) : 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm câu hỏi mới</title>
    <link rel="stylesheet" href="../../css/style.css"> <!-- tuỳ bạn -->
</head>
<body>
    <h2>➕ Thêm câu hỏi</h2>

    <form action="../../controllers/QuestionController.php" method="POST">
    <input type="hidden" name="test_id" value="<?= $test_id ?>">
    <input type="hidden" name="save_question" value="1">

    <label>Câu hỏi:</label>
    <textarea name="content" required></textarea>

    <label>Đáp án A:</label>
    <input type="text" name="option_a" required>

    <label>Đáp án B:</label>
    <input type="text" name="option_b" required>

    <label>Đáp án C:</label>
    <input type="text" name="option_c" required>

    <label>Đáp án D:</label>
    <input type="text" name="option_d" required>

    <label>Đáp án đúng:</label>
    <select name="correct" required>
        <option value="">-- Chọn --</option>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <option value="D">D</option>
    </select>

    <label>Điểm số:</label>
    <input type="number" name="score" value="1" min="1" required>

    <button type="submit">Lưu câu hỏi</button>
</form>
</body>
</html>