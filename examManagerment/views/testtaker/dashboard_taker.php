<?php
session_start();
require_once(__DIR__ . '/../../config/database.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'taker') {
    header("Location: ../../index.php");
    exit();
}

$user = $_SESSION['user'];
$user_id = $user['id'];

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<h2>🎯 Xin chào, <?= htmlspecialchars($user['fullname'] ?? $user['username']) ?>!</h2>

<!-- Nhập mã đề thi -->
<form method="POST" action="../../controllers/JointestController.php">
    <label>🔑 Nhập mã đề thi:</label>
    <input type="text" name="share_code" required>
    <button type="submit" name="join_test">Vào thi</button>
</form>

<!-- Hiển thị lỗi -->
<?php if (!empty($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<hr>

<!-- Xem các bài đã thi -->
<form method="POST" action="../../controllers/JointestController.php">
    <input type="hidden" name="view_submitted_tests" value="1">
    <button type="submit">📊 Xem các bài đã thi</button>
</form>

<br>
<!-- Điều hướng tài khoản -->
<a href="../account/profile.php">👤 Quản lý tài khoản</a> | 
<a href="../../logout.php">🚪 Đăng xuất</a>
