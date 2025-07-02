<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

$test_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$test_result = $conn->query("SELECT t.*, u.username AS creator_name FROM tests t JOIN users u ON t.test_creator_id = u.id WHERE t.id = $test_id");
if ($test_result->num_rows === 0) {
    die("❌ Đề thi không tồn tại.");
}
$test = $test_result->fetch_assoc();

date_default_timezone_set('Asia/Ho_Chi_Minh');
$now = new DateTime();
$open_time = new DateTime($test['open_time']);
$close_time = new DateTime($test['close_time']);

function getStatus($now, $open_time, $close_time) {
    if ($now < $open_time) return "🕒 Chưa mở";
    if ($now >= $open_time && $now <= $close_time) return "✅ Đang mở";
    return "🔒 Đã đóng";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>📘 Chi tiết đề thi - <?= htmlspecialchars($test['title']) ?></title>
    <link rel="stylesheet" href="../../src/css/layout.css">
    <style>
        .detail-content p {
            margin: 8px 0;
        }
        .detail-content a {
            text-decoration: none;
            color: #336699;
        }
        .detail-content a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>👑 Quản lý đề thi</h1>
        <div class="menu-container">
            <div class="hamburger">&#9776;</div>
            <div class="menu-items">
                <a href="../../index.php">Trang chủ</a>
                <a href="../../logout.php">Đăng xuất</a>
            </div>
        </div>
    </div>

    <!-- Main -->
    <div class="main">
        <!-- Sidebar -->
        <div class="main1">
            <div class="menu-items1"><a href="user_management.php"><span class="icon">👥</span> Quản lý người dùng</a></div>
            <div class="menu-items1"><a href="test_management.php"><span class="icon">🧪</span> Quản lý đề thi</a></div>
            <div class="menu-items1"><a href="submission_management.php"><span class="icon">📋</span> Quản lý bài làm</a></div>
            <div class="menu-items1"><a href="statistics.php"><span class="icon">📊</span> Thống kê hệ thống</a></div>
        </div>

        <div class="line"></div>
        <div class="main2">
            <div class="detail-content">
                <h2>📘 Chi tiết đề thi: <?= htmlspecialchars($test['title']) ?></h2>
                <p><strong>Người tạo:</strong> <?= htmlspecialchars($test['creator_name']) ?></p>
                <p><strong>Mô tả:</strong> <?= htmlspecialchars($test['description']) ?></p>
                <p><strong>Ngày tạo:</strong> <?= htmlspecialchars($test['created_at']) ?></p>
                <p><strong>Mã chia sẻ:</strong> <?= $test['share_code'] ?: '<em>Không có</em>' ?></p>
                <p><strong>⏱ Trạng thái:</strong> <?= getStatus($now, $open_time, $close_time) ?></p>
                <p><strong>Mở lúc:</strong> <?= htmlspecialchars($test['open_time']) ?></p>
                <p><strong>Đóng lúc:</strong> <?= htmlspecialchars($test['close_time']) ?></p>
                <p><strong>Thời gian làm bài:</strong> <?= htmlspecialchars($test['duration']) ?> phút</p>

                <hr>
                <p>
                    <a href="detail_question.php?test_id=<?= $test['id'] ?>" style="margin-right: 10px;">📋 Xem danh sách câu hỏi</a>
                </p>
                <a href="dashboard_admin.php">← Quay lại trang quản lý</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        ©2025 Quản lý thi trắc nghiệm
    </div>
</div>
</body>
</html>
