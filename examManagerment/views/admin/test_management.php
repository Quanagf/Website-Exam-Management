<?php
session_start();
require_once '../../config/database.php';
require_once '../../controllers/TestmanController.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

// Lấy danh sách người tạo
$creators = $conn->query("SELECT id, fullname FROM users WHERE role = 'creator'");
$selected_creator = $_GET['creator_id'] ?? '';

$sql = "SELECT tests.*, users.fullname AS creator_name 
        FROM tests 
        JOIN users ON tests.test_creator_id = users.id";

if (!empty($selected_creator)) {
    $selected_creator = intval($selected_creator);
    $sql .= " WHERE tests.test_creator_id = $selected_creator";
}

$sql .= " ORDER BY tests.id DESC";

$tests = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>🧪 Quản lý đề thi</title>
    <link rel="stylesheet" href="../../src/css/layout.css">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background: #f0f0f0;
        }
        form.inline {
            display: inline;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #336699;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>👑 Quản lý đề thi</h1>
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
            <div class="menu-items1"><a href="user_management.php"><span class="icon">👥</span> Quản lý người dùng</a></div>
            <div class="menu-items1"><a href="test_management.php"><span class="icon">🧪</span> Quản lý đề thi</a></div>
            <div class="menu-items1"><a href="submission_management.php"><span class="icon">📋</span> Quản lý bài làm</a></div>
            <div class="menu-items1"><a href="statistics.php"><span class="icon">📊</span> Thống kê hệ thống</a></div>
        </div>

        <div class="line"></div>
        <div class="main2">
            <h2>🧪 Quản lý đề thi</h2>

            <form method="GET" action="test_management.php" style="margin-bottom: 15px;">
                <label for="creator_id">Lọc theo người tạo:</label>
                <select name="creator_id" id="creator_id" onchange="this.form.submit()">
                    <option value="">Tất cả</option>
                    <?php while ($creator = $creators->fetch_assoc()): ?>
                        <option value="<?= $creator['id'] ?>" <?= $selected_creator == $creator['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($creator['fullname']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <noscript><button type="submit">🔍 Lọc</button></noscript>
            </form>

            <?php if ($tests->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Tiêu đề</th>
                        <th>Mô tả</th>
                        <th>Người tạo</th>
                        <th>Thời lượng (phút)</th>
                        <th>Mở lúc</th>
                        <th>Đóng lúc</th>
                        <th>Chi tiết</th>
                        <th>Thao tác</th>
                    </tr>
                    <?php while ($test = $tests->fetch_assoc()): ?>
                        <tr>
                            <td><?= $test['id'] ?></td>
                            <td><?= htmlspecialchars($test['title']) ?></td>
                            <td><?= htmlspecialchars($test['description']) ?></td>
                            <td><?= htmlspecialchars($test['creator_name']) ?></td>
                            <td><?= $test['duration'] ?></td>
                            <td><?= htmlspecialchars($test['open_time']) ?></td>
                            <td><?= htmlspecialchars($test['close_time']) ?></td>
                            <td>
                                <a href="detail_test.php?id=<?= $test['id'] ?>" target="_blank">🔗 Xem</a>
                            </td>
                            <td>
                                <form method="POST" action="../../controllers/TestmanController.php" class="inline" onsubmit="return confirm('Xác nhận xóa đề thi này?');">
                                    <input type="hidden" name="test_id" value="<?= $test['id'] ?>">
                                    <button type="submit" name="delete_test">🗑️ Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>📭 Không có đề thi nào.</p>
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
