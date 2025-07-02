<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$test_id = isset($_GET['test_id']) ? intval($_GET['test_id']) : 0;

require_once '../../controllers/QuestionmanController.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>📘 Danh sách câu hỏi - <?= htmlspecialchars($test['title']) ?></title>
    <link rel="stylesheet" href="../../src/css/layout.css">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background: #f0f0f0;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #336699;
        }
        .back-link:hover {
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

        <!-- Content -->
        <div class="line"></div>
        <div class="main2">
            <h2>📘 Danh sách câu hỏi cho đề thi: <?= htmlspecialchars($test['title']) ?></h2>

            <?php if ($questions->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Nội dung</th>
                            <th>A</th>
                            <th>B</th>
                            <th>C</th>
                            <th>D</th>
                            <th>Đáp án đúng</th>
                            <th>Điểm</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; while ($q = $questions->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($q['content']) ?></td>
                                <td><?= htmlspecialchars($q['option_a']) ?></td>
                                <td><?= htmlspecialchars($q['option_b']) ?></td>
                                <td><?= htmlspecialchars($q['option_c']) ?></td>
                                <td><?= htmlspecialchars($q['option_d']) ?></td>
                                <td style="color: green; font-weight: bold;"><?= htmlspecialchars($q['correct']) ?></td>
                                <td><?= htmlspecialchars($q['score']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>❗ Đề thi này chưa có câu hỏi nào.</p>
            <?php endif; ?>

            <a class="back-link" href="detail_test.php?id=<?= $test_id ?>">← Quay lại chi tiết đề thi</a>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        ©2025 Quản lý thi trắc nghiệm
    </div>
</div>
</body>
</html>
