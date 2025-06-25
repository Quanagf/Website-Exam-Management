<?php
session_start();
require_once '../config/database.php';

if ($_SESSION['user']['role'] !== 'taker') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

$sql = "SELECT tr.*, t.title 
        FROM test_responses tr
        JOIN tests t ON tr.test_id = t.id
        WHERE tr.test_taker_id = ?
        ORDER BY tr.submitted_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Lịch sử bài làm</h2>
<table border="1">
    <tr>
        <th>Đề thi</th>
        <th>Điểm</th>
        <th>Ngày làm</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= $row['score'] ?></td>
            <td><?= $row['submitted_at'] ?></td>
        </tr>
    <?php endwhile; ?>
</table>
