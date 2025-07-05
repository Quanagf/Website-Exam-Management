<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/database.php';
// Kiểm tra quyền truy cập
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'taker') {
    header("Location: ../../index.php");
    exit();
}

// Kiểm tra biến
if (!isset($total_questions) || !isset($correct_count) || !isset($score) || !isset($test_id)) {
    echo "<p style='color:red;'>❌ Thiếu dữ liệu kết quả.</p>";
    echo "<a href='dashboard_taker.php'>🔙 Quay lại Dashboard</a>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Kết quả nộp bài</title>
    <link rel="stylesheet" href="../../src/css/layout.css">
    <style>
        .result-box {
            border: 1px solid #ccc;
            padding: 20px;
            margin: 0 auto;
            max-width: 500px;
            text-align: center;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .result-box p {
            font-size: 18px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Kết quả nộp bài</h1>
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
                <div class="menu-items1"><a href="../index.php"><span class="icon">🔙</span> Quay lại trang chính</a>
                </div>
            </div>
            <div class="line"></div>

            <div class="main2">
                <div class="result-box">
                    <h2>✅ Bạn đã nộp bài thành công!</h2>
                    <p>📋 Tổng số câu hỏi: <strong><?= $total_questions ?></strong></p>
                    <p>🎯 Số câu đúng: <strong><?= $correct_count ?></strong></p>
                    <p>🏆 Điểm số của bạn: <strong style="color: green; font-size: 22px;"><?= $score ?>/10</strong></p>
                    <br>
                    <a href="../controllers/DotestController.php?action=result_detail&test_id=<?= $test_id ?>">🔍 Xem
                        chi tiết từng câu</a>
                    <br><br>

                </div>
            </div>
        </div>

        <div class="footer">
            ©2025 Hệ thống thi trắc nghiệm
        </div>
    </div>
</body>

</html>