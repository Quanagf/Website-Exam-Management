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

<h2>📘 Chi tiết đề thi (Admin): <?= htmlspecialchars($test['title']) ?></h2>
<p><strong>Người tạo:</strong> <?= htmlspecialchars($test['creator_name']) ?></p>
<p><strong>Mô tả:</strong> <?= htmlspecialchars($test['description']) ?></p>
<p><strong>Ngày tạo:</strong> <?= $test['created_at'] ?></p>
<p><strong>Mã chia sẻ:</strong> <?= $test['share_code'] ?: '<em>Không có</em>' ?></p>
<p><strong>⏱ Trạng thái:</strong> <?= getStatus($now, $open_time, $close_time) ?></p>
<p><strong>Mở lúc:</strong> <?= $test['open_time'] ?></p>
<p><strong>Đóng lúc:</strong> <?= $test['close_time'] ?></p>
<p><strong>Thời gian làm bài:</strong> <?= $test['duration'] ?> phút</p>

<hr>

<!-- Nút chức năng -->
<p>
    <a href="detail_question.php?test_id=<?= $test['id'] ?>" style="margin-right: 10px;">📋 Xem danh sách câu hỏi</a>
</p>

<a href="dashboard_admin.php">← Quay lại trang quản lý</a>