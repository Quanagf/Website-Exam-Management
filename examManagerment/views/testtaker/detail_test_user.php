<?php
session_start();
require_once(__DIR__ . '/../../config/database.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'taker') {
    header("Location: ../../index.php");
    exit();
}

if (!isset($_SESSION['selected_test']) || !isset($_SESSION['time_check'])) {
    echo "<p style='color:red;'>Không có đề thi được chọn.</p>";
    echo "<a href='dashboard_taker.php'>Quay lại Dashboard</a>";
    exit();
}

$test = $_SESSION['selected_test'];
$time = $_SESSION['time_check'];
$user_id = $_SESSION['user']['id'];

// Kiểm tra đã nộp chưa
$stmt = $conn->prepare("SELECT status FROM test_responses WHERE test_id = ? AND test_taker_id = ?");
$stmt->bind_param("ii", $test['id'], $user_id);
$stmt->execute();
$result = $stmt->get_result();
$response = $result->fetch_assoc();

$has_submitted = false;
$can_retry = false;

if ($response) {
    if ($response['status'] === 'pending') {
        $can_retry = true;
    } else {
        $has_submitted = true;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>📄 Chi tiết đề thi</title>
    <link rel="stylesheet" href="../../src/css/layout.css">
</head>
<body>
<div class="container">
    <div class="header">
        <h1>📄 Chi tiết đề thi</h1>
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
            <div class="menu-items1"><a href="dashboard_taker.php"><span class="icon">🏠</span> Trang chính</a></div>
            <div class="menu-items1"><a href="../account/profile.php"><span class="icon">👤</span> Tài khoản</a></div>
            <div class="menu-items1"><a href="../../index.php"><span class="icon">🔙</span> Quay lại </a></div>
            <div class="menu-items1"><a href="../../logout.php"><span class="icon">🚪</span> Đăng xuất</a></div>
        </div>
        <div class="line"></div>

        <div class="main2">
            <h2><?= htmlspecialchars($test['title']) ?></h2>
            <p><strong>Mô tả:</strong><br><?= nl2br(htmlspecialchars($test['description'])) ?></p>
            <p><strong>⏱ Thời lượng:</strong> <?= $test['duration'] ?> phút</p>
            <p><strong>🟢 Thời gian mở:</strong> <?= $test['open_time'] ?></p>
            <p><strong>🔴 Thời gian đóng:</strong> <?= $test['close_time'] ?></p>

            <hr>

            <?php if ($has_submitted): ?>
                <p style="color: red;">⚠️ Bạn đã tham gia bài thi này rồi. Không thể làm lại.</p>
            <?php elseif ($time['now'] < $time['start']): ?>
                <p style="color: orange;">⏳ Đề thi chưa mở. Vui lòng quay lại sau.</p>
            <?php elseif ($time['now'] > $time['end']): ?>
                <p style="color: red;">❌ Đề thi đã kết thúc.</p>
            <?php else: ?>
                <form method="GET" action="../../controllers/DotestController.php">
                    <input type="hidden" name="id" value="<?= $test['id'] ?>">
                    <button type="submit">
                        🚀 <?= $can_retry ? 'Làm lại bài thi' : 'Vào thi' ?>
                    </button>
                </form>
            <?php endif; ?>

           
        </div>
    </div>

    <div class="footer">
        ©2025 Hệ thống thi trắc nghiệm
    </div>
</div>
</body>
</html>
