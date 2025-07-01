<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

// Cập nhật hoạt động hiện tại
if (isset($_SESSION['user'])) {
    $uid = $_SESSION['user']['id'];
    $conn->query("UPDATE users SET last_active = NOW() WHERE id = $uid");
}

function safe_query($conn, $sql) {
    $result = $conn->query($sql);
    if (!$result) {
        die("Lỗi truy vấn: $sql\nChi tiết: " . $conn->error);
    }
    return $result->fetch_assoc()['total'];
}

// Truy vấn thống kê
$total_users = safe_query($conn, "SELECT COUNT(*) AS total FROM users");
$total_creators = safe_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role = 'creator'");
$total_takers = safe_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role = 'taker'");

$total_tests = safe_query($conn, "SELECT COUNT(*) AS total FROM tests");
$total_questions = safe_query($conn, "SELECT COUNT(*) AS total FROM questions");
$total_responses = safe_query($conn, "SELECT COUNT(*) AS total FROM test_responses");
?>

<h2>📊 Thống kê hệ thống</h2>

<table border="1" cellpadding="8" cellspacing="0">
    <tr><th colspan="2">👥 Người dùng</th></tr>
    <tr><td>Tổng số người dùng</td><td><?= $total_users ?></td></tr>
    <tr><td>Người tạo đề</td><td><?= $total_creators ?></td></tr>
    <tr><td>Thí sinh</td><td><?= $total_takers ?></td></tr>

    <tr><th colspan="2">📄 Đề thi & Câu hỏi</th></tr>
    <tr><td>Tổng số đề thi</td><td><?= $total_tests ?></td></tr>
    <tr><td>Tổng số câu hỏi</td><td><?= $total_questions ?></td></tr>

    <tr><th colspan="2">📝 Bài làm</th></tr>
    <tr><td>Tổng số bài làm</td><td><?= $total_responses ?></td></tr>
</table>

<br>
<a href="dashboard_admin.php">← Quay lại trang Admin</a>
