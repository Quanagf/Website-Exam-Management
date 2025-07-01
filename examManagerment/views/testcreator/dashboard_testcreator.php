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
</head>
<body>
    <h2>👋 Chào <?= htmlspecialchars($user['fullname'] ?? $user['username']) ?>!</h2>

    <nav>
        <ul>
            <li><a href="create_test.php">➕ Tạo đề mới</a></li>
            <li><a href="../account/profile.php">👤 Quản lý tài khoản</a></li>
            <li><a href="../../logout.php">🚪 Đăng xuất</a></li>
        </ul>
    </nav>

    <?php if ($error): ?><p style="color: red;"><?= $error ?></p><?php endif; ?>
    <?php if ($success): ?><p style="color: green;"><?= $success ?></p><?php endif; ?>

    <h3>📂 Danh sách đề đã tạo</h3>
    <?php
    $id = intval($user['id']);
    $tests = $conn->query("SELECT * FROM tests WHERE test_creator_id=$id ORDER BY created_at DESC");
    if ($tests->num_rows > 0): ?>
        <ul>
            <?php while ($test = $tests->fetch_assoc()): ?>
                <li>
                    📘 
                    <a href="detail_test.php?id=<?= $test['id'] ?>">
                        <?= htmlspecialchars($test['title']) ?>
                    </a> - <small><?= $test['created_at'] ?></small>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>❗ Bạn chưa tạo đề nào.</p>
    <?php endif; ?>
</body>
</html>
