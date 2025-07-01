<?php
session_start();
require_once(__DIR__ . '/../../config/database.php');


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
</head>
<body>
    <h2>👑 Chào Admin, <?= htmlspecialchars($user['fullname'] ?? $user['username']) ?></h2>

    <ul>
    <li><a href="user_management.php">👥 Quản lý người dùng</a></li>
    <li><a href="test_management.php">🧪 Quản lý đề thi</a></li>
    <li><a href="submission_management.php">📋 Quản lý bài làm</a></li>
    <li><a href="statistics.php">📊 Thống kê hệ thống</a></li>
</ul>

    <p><a href="../../logout.php">🚪 Đăng xuất</a></p>
</body>
</html>
