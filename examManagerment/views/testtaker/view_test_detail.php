<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . '/../../config/database.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'taker') {
    header("Location: ../../index.php");
    exit();
}

if (!isset($_SESSION['detail_questions'], $_SESSION['user_answers'], $_SESSION['test_info'], $_SESSION['test_score'])) {
    echo "<p style='color:red;'>❌ Không tìm thấy dữ liệu chi tiết bài làm.</p>";
    echo "<a href='dashboard_taker.php'>Quay lại Dashboard</a>";
    exit();
}

$questions = $_SESSION['detail_questions'];
$user_answers = $_SESSION['user_answers'];
$test = $_SESSION['test_info'];
$score = $_SESSION['test_score'];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chi tiết bài thi</title>
    <link rel="stylesheet" href="../../src/css/layout.css">
    <style>
        .question-box {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 20px;
            background: #f9f9f9;
        }

        .question-box li {
            margin: 4px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>📋 Chi tiết bài thi đã làm</h1>
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
                <p><strong>📝 Tên bài thi:</strong> <?= htmlspecialchars($test['title']) ?></p>
                <p><strong>📄 Mô tả:</strong> <?= nl2br(htmlspecialchars($test['description'])) ?></p>
                <p><strong>⏱ Thời lượng:</strong> <?= $test['duration'] ?> phút</p>
                <p><strong>🎯 Điểm đạt được:</strong> <span
                        style="color:green; font-weight:bold"><?= $score ?>/10</span></p>
                <hr>

                <?php if (empty($questions)): ?>
                    <p>⚠️ Không có dữ liệu để hiển thị.</p>
                <?php else: ?>
                    <?php foreach ($questions as $index => $q): ?>
                        <div class="question-box">
                            <p><strong>Câu <?= $index + 1 ?>:</strong> <?= htmlspecialchars($q['content']) ?></p>
                            <ul>
                                <?php foreach (['A', 'B', 'C', 'D'] as $opt):
                                    $text = $q['option_' . strtolower($opt)];
                                    $is_correct = ($q['correct'] === $opt);
                                    $is_selected = ($user_answers[$q['id']] ?? '') === $opt;
                                    ?>
                                    <li style="color: <?= $is_correct ? 'green' : ($is_selected ? 'red' : '#000') ?>;">
                                        <?= $opt ?>. <?= htmlspecialchars($text) ?>
                                        <?= $is_correct ? ' ✅ Đáp án đúng' : '' ?>
                                        <?= $is_selected ? ' (Bạn chọn)' : '' ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>


            </div>

            <div class="footer">
                ©2025 Hệ thống thi trắc nghiệm
            </div>
        </div>
</body>

</html>