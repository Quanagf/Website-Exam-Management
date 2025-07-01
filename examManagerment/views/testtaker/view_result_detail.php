
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
?>

<h2>📘 Kết quả chi tiết</h2>

<?php foreach ($questions as $index => $q): 
    $qid = $q['id'];
    $correct = strtoupper($q['correct']);
    $user_ans = strtoupper($user_answers[$qid] ?? '—');

    $is_correct = ($user_ans === $correct);
    $result_icon = $is_correct ? '✅' : '❌';
?>
    <div style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">
        <p><strong>Câu <?= $index + 1 ?>:</strong> <?= htmlspecialchars($q['content']) ?></p>
        <?php foreach (['A', 'B', 'C', 'D'] as $opt): 
            $text = $q['option_' . strtolower($opt)];
            $highlight = '';

            if ($opt === $user_ans) {
                $highlight = ($opt === $correct) ? 'background: #d4edda;' : 'background: #f8d7da;';
            } elseif ($opt === $correct) {
                $highlight = 'background: #cce5ff;';
            }
        ?>
            <div style="padding: 4px; <?= $highlight ?>">
                <?= $opt ?>. <?= htmlspecialchars($text) ?>
                <?= ($opt === $user_ans) ? ' <strong>(Bạn chọn)</strong>' : '' ?>
                <?= ($opt === $correct) ? ' <strong>(Đáp án đúng)</strong>' : '' ?>
            </div>
        <?php endforeach; ?>
        <p><strong>Kết quả:</strong> <?= $result_icon ?></p>
    </div>
<?php endforeach; ?>

<p><a href="../views/testtaker/dashboard_taker.php">← Quay lại danh sách bài thi</a></p>

