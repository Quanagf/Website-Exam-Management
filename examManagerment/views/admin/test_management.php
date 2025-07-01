<?php
session_start();
require_once '../../config/database.php';
require_once '../../controllers/TestmanController.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

// Lấy danh sách người tạo (role = 'creator')
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

<h2>🧪 Quản lý bài thi</h2>

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

<table border="1" cellpadding="6">
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
            <td><?= $test['open_time'] ?></td>
            <td><?= $test['close_time'] ?></td>
            <td>
                <a href="detail_test.php?id=<?= $test['id'] ?>" target="_blank">🔗 Xem</a>
            </td>
            <td>
                <form method="POST" action="../../controllers/TestmanController.php" style="display:inline;">
                    <input type="hidden" name="test_id" value="<?= $test['id'] ?>">
                    <button type="submit" name="delete_test" onclick="return confirm('Xác nhận xóa đề thi này?')">🗑️ Xóa</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<a href="dashboard_admin.php">🔙 Về trang admin</a>