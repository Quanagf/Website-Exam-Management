<?php
session_start();
require_once '../config/database.php';

if ($_SESSION['user']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$totalUsers = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$totalTests = $conn->query("SELECT COUNT(*) AS count FROM tests")->fetch_assoc()['count'];
$totalResponses = $conn->query("SELECT COUNT(*) AS count FROM test_responses")->fetch_assoc()['count'];
?>

<h2>Thống kê hệ thống</h2>
<ul>
    <li>Tổng số người dùng: <strong><?= $totalUsers ?></strong></li>
    <li>Tổng số bài thi được tạo: <strong><?= $totalTests ?></strong></li>
    <li>Số lượt làm bài: <strong><?= $totalResponses ?></strong></li>
</ul>
