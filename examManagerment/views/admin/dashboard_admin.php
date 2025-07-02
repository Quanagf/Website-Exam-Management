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
    <title>👑 Dashboard Admin</title>
    <link rel="stylesheet" href="../../src/css/layout.css">
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Xin chào, Admin</h1>
        <div class="menu-container">
            <div class="hamburger">&#9776;</div>
            <div class="menu-items">
                <a href="../../index.php">Trang chủ</a>
                <a href="../../logout.php">Đăng xuất</a>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="main1">
            <div class="menu-items1"><a href="user_management.php"><span class="icon">👥</span> Quản lý người dùng</a></div>
            <div class="menu-items1"><a href="test_management.php"><span class="icon">🧪</span>Quản lý đề thi</a></div>
            <div class="menu-items1"><a href="submission_management.php"><span class="icon">📋</span> Quản lý bài làm</a></div>
            <div class="menu-items1"><a href="statistics.php"><span class="icon">📊</span> Thống kê hệ thống</a></div>   
        </div>
        <div class="line"></div>
        <div class="main2">
            <h1 style="text-align: center; margin: 0; padding-top: 40vh; font-size:50px ;">Hệ thống Quản lý thi trắc nghiệm</h1>
        </div>
    </div>

    <div class="footer">
        ©2025 Quản lý thi trắc nghiệm
    </div>
</div>
</body>
</html>
