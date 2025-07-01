<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit();
}

$user = $_SESSION['user'];
$fullname = $user['fullname'] ?? '';
$email = $user['email'] ?? '';
?>

<h2>Cập nhật thông tin</h2>

<form method="post" action="../../controllers/ProfileController.php">
    <label>Họ tên:</label><br>
    <input type="text" name="fullname" value="<?= htmlspecialchars($fullname) ?>" required><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required><br><br>

    <button type="submit" name="update_info">💾 Cập nhật</button>
</form>

<a href="profile.php">🔙 Quay lại</a>
