<?php
session_start();
require_once '../config/database.php';

if ($_SESSION['user']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$codes = $conn->query("SELECT ic.*, t.title 
    FROM invitation_codes ic
    JOIN tests t ON ic.test_id = t.id
    ORDER BY ic.created_at DESC");
?>

<h2>Quản lý mã mời</h2>
<table border="1">
    <tr><th>Mã</th><th>Đề thi</th><th>Trạng thái</th><th>Ngày tạo</th></tr>
    <?php while ($row = $codes->fetch_assoc()): ?>
        <tr>
            <td><?= $row['code'] ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= $row['status'] ?></td>
            <td><?= $row['created_at'] ?></td>
        </tr>
    <?php endwhile; ?>
</table>
