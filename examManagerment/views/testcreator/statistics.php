<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
// Kiểm tra quyền
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'creator') {
    header("Location: ../../index.php");
    exit();
}
if (!isset($_SESSION['statistics']) || !isset($_SESSION['test_title'])) {
    header("Location: dashboard_testcreator.php");
    exit();
}

$user = $_SESSION['user'];
$stats = $_SESSION['statistics'];
$test_title = $_SESSION['test_title'];
$users = $_SESSION['user_results'] ?? [];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>📊 Thống kê bài thi</title>
    <link rel="stylesheet" href="../../src/css/layout.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>📊 Thống kê kết quả: <?= htmlspecialchars($test_title) ?></h1>
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
                <h3>📈 Thống kê tổng quan</h3>
                <ul>
                    <li><strong>Tổng số thí sinh:</strong> <?= $stats['total'] ?? 0 ?></li>
                    <li><strong>Điểm trung bình:</strong> <?= number_format($stats['average_score'] ?? 0, 2) ?></li>
                    <li><strong>Điểm cao nhất:</strong> <?= $stats['max_score'] ?? 0 ?></li>
                    <li><strong>Điểm thấp nhất:</strong> <?= $stats['min_score'] ?? 0 ?></li>
                    <li><strong>Số người đạt (>= 5):</strong> <?= $stats['passed'] ?? 0 ?></li>
                    <li><strong>Số người trượt (< 5):</strong> <?= $stats['failed'] ?? 0 ?></li>
                </ul>

                <hr>
                <h3>📋 Danh sách thí sinh đã làm bài</h3>
                <?php if (!empty($users)): ?>
                    <table border="1" cellpadding="8" cellspacing="0">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Họ và tên</th>
                                <th>Điểm</th>
                                <th>Thời gian nộp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $index => $row): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($row['fullname']) ?></td>
                                    <td><?= htmlspecialchars($row['score']) ?></td>
                                    <td><?= htmlspecialchars($row['submitted_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>⚠️ Chưa có thí sinh nào làm bài thi này.</p>
                <?php endif; ?>

            </div>
        </div>
        <div class="footer">
            ©2025 Quản lý thi trắc nghiệm
        </div>
    </div>
</body>

</html>