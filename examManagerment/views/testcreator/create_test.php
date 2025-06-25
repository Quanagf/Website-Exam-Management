<?php
session_start();
require_once '../../controllers/TestController.php';
require_once __DIR__ . '/../../config/database.php';
$error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tạo đề thi</title>
</head>
<body>
    <h2>Tạo đề thi</h2>

    <form method="post">
        Tiêu đề: <input type="text" name="title" required><br>
        Mô tả: <textarea name="description"></textarea><br>
        Thời gian làm bài (phút): <input type="number" name="duration" required><br>
        <button name="create">Tạo đề</button>
    </form>
    <?php if (!empty($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
</body>
</html>