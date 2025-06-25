<?php
session_start();
require_once '../../config/database.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'creator') {
    header("Location: ../../index.php");
    exit();
}

$test_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user']['id'];

// Lấy thông tin đề thi
$stmt = $conn->prepare("SELECT * FROM tests WHERE id = ? AND test_creator_id = ?");
$stmt->bind_param("ii", $test_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ Không tìm thấy đề hoặc bạn không có quyền.");
}

$test = $result->fetch_assoc();
?>

<h2>✏️ Chỉnh sửa đề thi</h2>

<form action="../../controllers/TestController.php?action=edit" method="POST">
    <input type="hidden" name="id" value="<?= $test['id'] ?>">

    <label>Tiêu đề đề thi:</label>
    <input type="text" name="title" value="<?= htmlspecialchars($test['title']) ?>" required>

    <label>Mô tả:</label>
    <textarea name="description"><?= htmlspecialchars($test['description']) ?></textarea>

    <label>Thời gian làm bài (phút):</label>
    <input type="number" name="duration" value="<?= $test['duration'] ?>" min="1" required>

    <button type="submit" name="update_test">💾 Lưu thay đổi</button>
</form>