
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
?>

<h2>🎉 Bạn đã nộp bài thành công!</h2>
<p>✅ Tổng số câu hỏi: <strong><?= $total_questions ?></strong></p>
<p>✅ Trả lời đúng: <strong><?= $correct_count ?></strong></p>
<p>🏆 Điểm của bạn: <strong style="color:green; font-size:20px"><?= $score ?>/10</strong></p>

<a href="../controllers/DotestController.php?action=result_detail&test_id=<?= $test_id ?>">🔍 Xem chi tiết từng câu</a>
<p><a href="../views/testtaker/dashboard_taker.php">← Quay lại trang chính</a></p>
