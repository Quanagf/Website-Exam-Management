<?php
session_start();
require_once '../../controllers/TestController.php';
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'creator') {
    header("Location: ../../index.php");
    exit();
}

$test_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lấy thông tin đề thi
$stmt = $conn->prepare("SELECT * FROM tests WHERE id = ? AND test_creator_id = ?");
$stmt->bind_param("ii", $test_id, $_SESSION['user']['id']);
$stmt->execute();
$result = $stmt->get_result();
$test = $result->fetch_assoc();

if (!$test) {
    $_SESSION['error'] = "Không tìm thấy đề thi hoặc bạn không có quyền.";
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>✏️ Sửa đề thi</title>
</head>
<body>
    <h2>✏️ Sửa đề thi</h2>
    <form method="POST" action="../../controllers/TestController.php">
        <input type="hidden" name="edit" value="1">
        <input type="hidden" name="test_id" value="<?= $test['id'] ?>">

        <label>Tiêu đề:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($test['title']) ?>" required><br>

        <label>Mô tả:</label><br>
        <textarea name="description"><?= htmlspecialchars($test['description']) ?></textarea><br>

        <label>Thời lượng (phút):</label><br>
        <input type="number" name="duration" value="<?= $test['duration'] ?>" required><br>

        <label>🕒 Thời gian mở bài:</label><br>
        <input type="datetime-local" name="open_time"
               value="<?= $test['open_time'] ? date('Y-m-d\TH:i', strtotime($test['open_time'])) : '' ?>"><br><br>

        <label>🕓 Thời gian đóng bài:</label><br>
        <input type="datetime-local" name="close_time"
               value="<?= $test['close_time'] ? date('Y-m-d\TH:i', strtotime($test['close_time'])) : '' ?>" required><br><br>

        <button type="submit">💾 Cập nhật</button>
    </form>
</body>
</html>
