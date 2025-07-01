<?php
session_start();
require_once '../../config/database.php';
require_once '../../controllers/UsermanController.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

?>

<h2>👥 Quản lý người dùng</h2>

<!-- Form thêm người dùng -->
<form method="POST" action="../../controllers/UsermanController.php" style="margin-bottom: 20px;">
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

<!-- Form lọc theo email -->
<form method="GET" action="user_management.php" style="margin-bottom: 15px;">
    <input type="text" name="email" placeholder="Lọc theo email" value="<?= htmlspecialchars($emailFilter) ?>">
    <button type="submit">🔍 Lọc</button>
    <a href="user_management.php">❌ Xóa lọc</a>
</form>

<!-- Danh sách người dùng -->
<table border="1" cellpadding="6">
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
            <td><?= $user['role'] ?></td>
            <td><?= $user['status'] ?></td>
            <td>
                <!-- Khóa/Mở khóa -->
                <form action="../../controllers/UsermanController.php" method="POST" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <button name="toggle_status"><?= $user['status'] === 'active' ? '🔒 Khóa' : '🔓 Mở' ?></button>
                </form>

                <!-- Xóa -->
                <form action="../../controllers/UsermanController.php" method="POST" style="display:inline;" onsubmit="return confirm('Xác nhận xóa người dùng này?');">
                    <input type="hidden" name="delete_user_id" value="<?= $user['id'] ?>">
                    <button name="delete_user">🗑️ Xóa</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<a href="dashboard_admin.php">🔙 Về trang admin</a>
