<?php
session_start();
require_once(__DIR__ . '/../../config/database.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'creator') {
    header("Location: ../../index.php");
    exit();
}

$user = $_SESSION['user'];
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Creator</title>
    <link rel="stylesheet" href="../../src/css/layout.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🎓 Quản lý đề thi</h1>
            <div class="menu-container">
                <div class="hamburger">&#9776;</div>
                <div class="menu-items">
                    <a href="../../index.php">Trang chủ</a>
                    <a href="../account/profile.php">Tài khoản</a>
                    <a href="../../logout.php">Đăng xuất</a>
                </div>
            </div>
        </div>

        <div class="main">
            <div class="main1">
                <div class="menu-items1"><a href="create_test.php"><span class="icon">➕</span> Tạo đề mới</a></div>
                <div class="menu-items1"><a href="../account/profile.php"><span class="icon">👤</span> Tài khoản</a>
                </div>
                <div class="menu-items1"><a href="../../logout.php"><span class="icon">🚪</span> Đăng xuất</a></div>
            </div>
            <div class="line"></div>

            <div class="main2">
                <h2>👋 Xin chào, <?= htmlspecialchars($user['fullname'] ?? $user['username']) ?>!</h2>

                <?php if ($error): ?>
                    <p class="message error"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
                <?php if ($success): ?>
                    <p class="message success"><?= htmlspecialchars($success) ?></p>
                <?php endif; ?>

                <h3>📂 Danh sách đề thi bạn đã tạo</h3>
                <?php
                $id = intval($user['id']);
                $tests = $conn->query("SELECT * FROM tests WHERE test_creator_id=$id ORDER BY created_at DESC");
                if ($tests->num_rows > 0): ?>
                    <ul class="test-list">
                        <?php while ($test = $tests->fetch_assoc()): ?>
                            <li>
                                📘
                                <a href="detail_test.php?id=<?= $test['id'] ?>">
                                    <?= htmlspecialchars($test['title']) ?>
                                </a>
                                <small>- <?= htmlspecialchars($test['created_at']) ?></small>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>❗ Bạn chưa tạo đề nào.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="footer">
            ©2025 Quản lý thi trắc nghiệm
        </div>
    </div>
</body>

</html>