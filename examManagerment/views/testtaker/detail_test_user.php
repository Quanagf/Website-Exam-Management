<?php
session_start();
require_once(__DIR__ . '/../../config/database.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'taker') {
    header("Location: ../../index.php");
    exit();
}

if (!isset($_SESSION['selected_test']) || !isset($_SESSION['time_check'])) {
    echo "<p style='color:red;'>Không có đề thi được chọn.</p>";
    echo "<a href='dashboard_testtaker.php'>Quay lại Dashboard</a>";
    exit();
}

$test = $_SESSION['selected_test'];
$time = $_SESSION['time_check'];
$user_id = $_SESSION['user']['id'];

// ✅ Kiểm tra xem đã nộp bài chưa
$stmt = $conn->prepare("SELECT status FROM test_responses WHERE test_id = ? AND test_taker_id = ?");
$stmt->bind_param("ii", $test['id'], $user_id);
$stmt->execute();
$result = $stmt->get_result();
$response = $result->fetch_assoc();

$has_submitted = false;
$can_retry = false;

if ($response) {
    if ($response['status'] === 'pending') {
        $can_retry = true; // được phép làm lại
    } else {
        $has_submitted = true; // đã nộp, không được làm lại
    }
}
?>

<h2>📄 Chi tiết đề thi: <?= htmlspecialchars($test['title']) ?></h2>
<p><strong>Mô tả: </strong><?= nl2br(htmlspecialchars($test['description'])) ?></p>

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
<br>
<a href="dashboard_taker.php">🔙 Quay lại Dashboard</a>