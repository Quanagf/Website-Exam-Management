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
    <link rel="stylesheet" href="../../src/css/layout.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>➕ Thêm câu hỏi</h1>
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
                <div class="menu-items1"><a href="javascript:history.back()"><span class="icon">🔙</span> Quay lại</a>
                </div>
            </div>
            <div class="line"></div>

            <div class="main2">
                <h2>✏️ Thêm câu hỏi vào đề #<?= htmlspecialchars($test_id) ?></h2>

                <form action="../../controllers/QuestionController.php" method="POST" class="update-form">
                    <input type="hidden" name="test_id" value="<?= $test_id ?>">
                    <input type="hidden" name="add_question" value="1">

                    <label>Câu hỏi:</label><br>
                    <textarea name="content" rows="3" required></textarea><br><br>

                    <label>Đáp án A:</label><br>
                    <input type="text" name="option_a" required><br><br>

                    <label>Đáp án B:</label><br>
                    <input type="text" name="option_b" required><br><br>

                    <label>Đáp án C:</label><br>
                    <input type="text" name="option_c" required><br><br>

                    <label>Đáp án D:</label><br>
                    <input type="text" name="option_d" required><br><br>

                    <label>Đáp án đúng:</label><br>
                    <select name="correct" required>
                        <option value="">-- Chọn --</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select><br><br>

                    <p style="color: gray; font-size: 14px;">💡 Điểm sẽ tự động tính: 10 chia đều cho số câu.</p>

                    <button type="submit">💾 Lưu câu hỏi</button>
                </form>
            </div>
        </div>

        <div class="footer">
            ©2025 Quản lý thi trắc nghiệm
        </div>
    </div>
</body>

</html>