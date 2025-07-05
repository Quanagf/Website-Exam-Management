<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

$usernameFilter = $_GET['username'] ?? '';

$sql = "SELECT tr.*, u.username, u.fullname, t.title AS test_title
        FROM test_responses tr
        JOIN users u ON tr.test_taker_id = u.id
        JOIN tests t ON tr.test_id = t.id
        WHERE 1=1";

if (!empty($usernameFilter)) {
    $escapedUsername = $conn->real_escape_string($usernameFilter);
    $sql .= " AND u.username LIKE '%$escapedUsername%'";
}

$sql .= " ORDER BY tr.submitted_at DESC";
$results = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>📄 Quản lý bài làm</title>
    <link rel="stylesheet" href="../../src/css/layout.css">
    <link rel="stylesheet" href="../../src/css/admin/submission_management.css">
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>👑 Quản lý bài làm</h1>
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
                <h2>📄 Danh sách bài làm</h2>

                <form method="GET" action="submission_management.php"
                    style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
                    <input type="text" name="username" placeholder="Lọc theo tài khoản"
                        value="<?= htmlspecialchars($usernameFilter) ?>" />
                    <button type="submit">🔍 Lọc</button>
                    <a href="submission_management.php"
                        style="color: #e74c3c; font-weight: bold; text-decoration: none;">❌ Xóa lọc</a>
                </form>

                <?php if ($results->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Người làm</th>
                                <th>Tên tài khoản</th>
                                <th>Đề thi</th>
                                <th>Điểm</th>
                                <th>Trạng thái</th>
                                <th>Thời gian nộp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            while ($row = $results->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($row['fullname']) ?></td>
                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                    <td><?= htmlspecialchars($row['test_title']) ?></td>
                                    <td><?= is_null($row['score']) ? '...' : htmlspecialchars($row['score']) ?></td>
                                    <td><?= ucfirst(htmlspecialchars($row['status'])) ?></td>
                                    <td><?= htmlspecialchars($row['submitted_at'] ?? '...') ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>📭 Chưa có bài làm nào được ghi nhận.</p>
                <?php endif; ?>

            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            ©2025 Quản lý thi trắc nghiệm
        </div>
    </div>
</body>

</html>