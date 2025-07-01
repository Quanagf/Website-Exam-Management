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
    echo "<a href='dashboard_testtaker.php'>Quay lại Dashboard</a>";
    exit();
}

$questions = $_SESSION['detail_questions'];
$user_answers = $_SESSION['user_answers'];
$test = $_SESSION['test_info'];
$score = $_SESSION['test_score'];
?>

<h2>📋 Chi tiết bài thi đã làm</h2>

<!-- Hiển thị thông tin đề -->
<p><strong>📝 Tên bài thi:</strong> <?= htmlspecialchars($test['title']) ?></p>
<p><strong>📄 Mô tả:</strong> <?= nl2br(htmlspecialchars($test['description'])) ?></p>
<p><strong>⏱ Thời lượng:</strong> <?= $test['duration'] ?> phút</p>
<p><strong>🎯 Điểm đạt được:</strong> <span style="color:green; font-weight:bold"><?= $score ?>/10</span></p>
<hr>

<!-- Hiển thị từng câu -->
<?php if (empty($questions)): ?>
    <p>⚠️ Không có dữ liệu để hiển thị.</p>
<?php else: ?>
    <?php foreach ($questions as $index => $q): ?>
        <div>
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
            <hr>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<a href="../../views/testtaker/dashboard_taker.php">← Quay lại Dashboard</a>