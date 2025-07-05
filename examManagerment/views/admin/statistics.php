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

function safe_query($conn, $sql)
{
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
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>📊 Thống kê hệ thống</title>
    <link rel="stylesheet" href="../../src/css/layout.css">
    <link rel="stylesheet" href="../../src/css/admin/statistics.css">
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>👑 Thống kê hệ thống</h1>
            <div class="menu-container">
                <div class="hamburger">&#9776;</div>
                <div class="menu-items">
                    <a href="../../index.php">Trang chủ</a>
                    <a href="../../logout.php">Đăng xuất</a>
                </div>
            </div>
        </div>

        <!-- Main -->
        <div class="main">
            <!-- Sidebar -->
            <div class="main1">
                <div class="menu-items1"><a href="user_management.php"><span class="icon">👥</span> Quản lý người
                        dùng</a></div>
                <div class="menu-items1"><a href="test_management.php"><span class="icon">🧪</span> Quản lý đề thi</a>
                </div>
                <div class="menu-items1"><a href="submission_management.php"><span class="icon">📋</span> Quản lý bài
                        làm</a></div>
                <div class="menu-items1"><a href="statistics.php"><span class="icon">📊</span> Thống kê hệ thống</a>
                </div>
            </div>

            <div class="line"></div>
            <div class="main2">
                <h2>📊 Thống kê hệ thống</h2>

                <table>
                    <tr>
                        <th colspan="2">👥 Người dùng</th>
                    </tr>
                    <tr>
                        <td>Tổng số người dùng</td>
                        <td><?= $total_users ?></td>
                    </tr>
                    <tr>
                        <td>Người tạo đề</td>
                        <td><?= $total_creators ?></td>
                    </tr>
                    <tr>
                        <td>Thí sinh</td>
                        <td><?= $total_takers ?></td>
                    </tr>

                    <tr>
                        <th colspan="2">📄 Đề thi & Câu hỏi</th>
                    </tr>
                    <tr>
                        <td>Tổng số đề thi</td>
                        <td><?= $total_tests ?></td>
                    </tr>
                    <tr>
                        <td>Tổng số câu hỏi</td>
                        <td><?= $total_questions ?></td>
                    </tr>

                    <tr>
                        <th colspan="2">📝 Bài làm</th>
                    </tr>
                    <tr>
                        <td>Tổng số bài làm</td>
                        <td><?= $total_responses ?></td>
                    </tr>
                </table>

            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            ©2025 Quản lý thi trắc nghiệm
        </div>
    </div>
</body>

</html>