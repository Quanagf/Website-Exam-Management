<?php
session_start();
require_once './config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$success = '';
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

$error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}


$user = $_SESSION['user'];
$role = $user['role'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; padding: 30px; }
        .box { background: white; padding: 30px; border-radius: 8px; max-width: 800px; margin: auto; }
        h2, h3, h4 { color: #2c3e50; }
        ul { list-style: none; padding-left: 0; }
        li { padding: 5px 0; }
        .stat { margin: 10px 0; }
        .logout { margin-top: 30px; display: inline-block; }
    </style>
</head>
<body>
<div class="box">
    <h2>Chào mừng <?= htmlspecialchars($user['fullname'] ?? $user['username']) ?>!</h2>

    <?php if ($role === 'admin'): ?>
        <h3>Dashboard Admin</h3>
        <ul>
            <li><a href="admin/user_management.php">👥 Quản lý người dùng</a></li>
            <li><a href="admin/code_management.php">🔑 Quản lý mã mời</a></li>
            <li><a href="admin/statistics.php">📊 Thống kê hệ thống</a></li>
        </ul>
        <hr>
        <?php
        $totalUsers = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'];
        $totalTests = $conn->query("SELECT COUNT(*) AS c FROM tests")->fetch_assoc()['c'];
        $totalResponses = $conn->query("SELECT COUNT(*) AS c FROM test_responses")->fetch_assoc()['c'];
        ?>
        <div class="stat">👤 Người dùng: <strong><?= $totalUsers ?></strong></div>
        <div class="stat">📚 Đề thi: <strong><?= $totalTests ?></strong></div>
        <div class="stat">📝 Lượt thi: <strong><?= $totalResponses ?></strong></div>

    <?php elseif ($role === 'creator'): ?>
        <h3>Dashboard Người tạo đề</h3>
        <a href="views/testcreator/create_test.php">➕ Tạo đề mới</a>
        <?php if (!empty($success)): ?>
        <p style="color: green ;"><?php echo $success; ?></p>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
        <p style="color: green ;"><?php echo $error; ?></p>
        <?php endif; ?>
        <h4>📂 Danh sách đề đã tạo</h4>
        <?php
        $id = intval($user['id']);
        $tests = $conn->query("SELECT * FROM tests WHERE test_creator_id=$id ORDER BY created_at DESC");
        if ($tests->num_rows > 0): ?>
            <ul>
                <?php while ($test = $tests->fetch_assoc()): ?>
                <li>
                    📘 
                    <a href="views/testcreator/detail_test.php?id=<?= $test['id'] ?>">
                        <?= htmlspecialchars($test['title']) ?>
                    </a>
                    - <small><?= $test['created_at'] ?></small>
                </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>❗ Bạn chưa tạo đề nào.</p>
        <?php endif; ?>

    <?php elseif ($role === 'taker'): ?>
        <h3>Dashboard Thí sinh</h3>
        <form action="testtaker/enter_code.php" method="post">
            <input type="text" name="code" placeholder="Nhập mã đề" required>
            <button name="enter">🎯 Vào thi</button>
        </form>
        <h4>🕘 Lịch sử bài làm gần đây</h4>
        <?php
        $id = intval($user['id']);
        $results = $conn->query("
            SELECT tr.score, tr.submitted_at, t.title 
            FROM test_responses tr
            JOIN tests t ON tr.test_id = t.id
            WHERE tr.test_taker_id=$id
            ORDER BY tr.submitted_at DESC
            LIMIT 5
        ");
        if ($results->num_rows > 0): ?>
            <ul>
                <?php while ($row = $results->fetch_assoc()): ?>
                    <li>📄 <?= htmlspecialchars($row['title']) ?> - <strong><?= $row['score'] ?></strong> điểm - <?= $row['submitted_at'] ?></li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>❗ Bạn chưa làm bài nào.</p>
        <?php endif; ?>
    <?php endif; ?>

    <a href="logout.php" class="logout">🚪 Đăng xuất</a>
</div>
</body>
</html>
