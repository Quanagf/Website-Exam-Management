<?php
session_start();
require_once '../../config/database.php';

if ($_SESSION['user']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['delete'])) {
    $id = $_POST['user_id'];
    $conn->query("DELETE FROM users WHERE id=$id");
}

if (isset($_POST['promote'])) {
    $id = $_POST['user_id'];
    $role = $_POST['new_role'];
    $stmt = $conn->prepare("UPDATE users SET role=? WHERE id=?");
    $stmt->bind_param("si", $role, $id);
    $stmt->execute();
}

$users = $conn->query("SELECT * FROM users");
?>

<h2>Quản lý người dùng</h2>
<table border="1">
    <tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Thao tác</th></tr>
    <?php while ($u = $users->fetch_assoc()): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= $u['role'] ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                    <select name="new_role">
                        <option value="admin">Admin</option>
                        <option value="creator">Creator</option>
                        <option value="taker">Taker</option>
                    </select>
                    <button name="promote">Cập nhật quyền</button>
                </form>
                <form method="post" style="display:inline;" onsubmit="return confirm('Xóa người dùng này?');">
                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                    <button name="delete">Xóa</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>