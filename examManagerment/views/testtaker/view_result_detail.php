<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . '/../../config/database.php');

// Kiểm tra quyền truy cập
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'taker') {
    header("Location: ../../index.php");
    exit();
}

// Kiểm tra dữ liệu
if (!isset($questions) || !isset($user_answers)) {
    echo "<p style='color:red;'>❌ Không có dữ liệu chi tiết kết quả.</p>";
    echo "<a href='dashboard_taker.php'>🔙 Quay lại Dashboard</a>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Kết quả chi tiết</title>
    <link rel="stylesheet" href="../src/css/layout.css">
    <style>
        
        .answer-correct {
            background: #d4edda;
        }

        .answer-wrong {
            background: #f8d7da;
        }

        .answer-right {
            background: #cce5ff;
        }

        .question-box {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>📘 Kết quả chi tiết bài thi</h1>
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
                <?php foreach ($questions as $index => $q):
                    $qid = $q['id'];
                    $correct = strtoupper($q['correct']);
                    $user_ans = strtoupper($user_answers[$qid] ?? '—');
                    $is_correct = ($user_ans === $correct);
                    $result_icon = $is_correct ? '✅' : '❌';
                    ?>
                    <div class="question-box">
                        <p><strong>Câu <?= $index + 1 ?>:</strong> <?= htmlspecialchars($q['content']) ?></p>
                        <?php foreach (['A', 'B', 'C', 'D'] as $opt):
                            $text = $q['option_' . strtolower($opt)];
                            $class = '';
                            if ($opt === $user_ans) {
                                $class = ($opt === $correct) ? 'answer-correct' : 'answer-wrong';
                            } elseif ($opt === $correct) {
                                $class = 'answer-right';
                            }
                            ?>
                            <div style="padding:4px;" class="<?= $class ?>">
                                <?= $opt ?>. <?= htmlspecialchars($text) ?>
                                <?= ($opt === $user_ans) ? ' <strong>(Bạn chọn)</strong>' : '' ?>
                                <?= ($opt === $correct) ? ' <strong>(Đáp án đúng)</strong>' : '' ?>
                            </div>
                        <?php endforeach; ?>
                        <p><strong>Kết quả:</strong> <?= $result_icon ?></p>
                    </div>
                <?php endforeach; ?>


            </div>
        </div>

        <div class="footer">
            ©2025 Hệ thống thi trắc nghiệm
        </div>
    </div>
</body>

</html>