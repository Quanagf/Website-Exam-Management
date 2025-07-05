<?php
session_start();
require_once '../../config/database.php';
require_once '../../controllers/UsermanController.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

// Lọc email
$emailFilter = $_GET['email'] ?? '';
$sql = "SELECT * FROM users";
if (!empty($emailFilter)) {
    $escaped = $conn->real_escape_string($emailFilter);
    $sql .= " WHERE email LIKE '%$escaped%'";
}
$sql .= " ORDER BY id DESC";
$users = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>👥 Quản lý người dùng</title>
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
        .add-form input, .add-form select {
            margin: 5px 5px 5px 0;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>👑 Quản lý người dùng</h1>
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
            <h2>👥 Quản lý người dùng</h2>

            <!-- Form thêm -->
            <form method="POST" action="../../controllers/UsermanController.php" class="add-form">
                <h3>➕ Thêm người dùng</h3>
                <input type="text" name="fullname" placeholder="Họ tên" required>
                <input type="text" name="username" placeholder="Tên đăng nhập" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Mật khẩu" required>
                <select name="role" required>
                    <option value="taker">Thí sinh</option>
                    <option value="creator">Người tạo đề</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" name="add_user">Thêm</button>
            </form>

            <!-- Form lọc -->
            <form method="GET" action="user_management.php" style="margin-top:15px;">
                <input type="text" name="email" placeholder="Lọc theo email" value="<?= htmlspecialchars($emailFilter) ?>">
                <button type="submit">🔍 Lọc</button>
                <a href="user_management.php">❌ Xóa lọc</a>
            </form>

            <!-- Bảng người dùng -->
            <?php if ($users->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Tên đăng nhập</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                    <?php while ($user = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= htmlspecialchars($user['fullname']) ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['role']) ?></td>
                            <td><?= htmlspecialchars($user['status']) ?></td>
                            <td>
                                <!-- Khóa/Mở -->
                                <form action="../../controllers/UsermanController.php" method="POST" class="inline">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <button name="toggle_status">
                                        <?= $user['status'] === 'active' ? '🔒 Khóa' : '🔓 Mở' ?>
                                    </button>
                                </form>

                                <!-- Xóa -->
                                <form action="../../controllers/UsermanController.php" method="POST" class="inline" onsubmit="return confirm('Xác nhận xóa người dùng này?');">
                                    <input type="hidden" name="delete_user_id" value="<?= $user['id'] ?>">
                                    <button name="delete_user">🗑️ Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>📭 Không có người dùng.</p>
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
