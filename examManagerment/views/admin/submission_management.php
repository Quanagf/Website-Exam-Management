<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

// Lấy danh sách bài làm
$sql = "SELECT tr.*, u.username, u.fullname, t.title AS test_title
        FROM test_responses tr
        JOIN users u ON tr.test_taker_id = u.id
        JOIN tests t ON tr.test_id = t.id
        ORDER BY tr.submitted_at DESC";

$results = $conn->query($sql);
?>

<h2>📄 Quản lý bài làm</h2>

<?php if ($results->num_rows > 0): ?>
    <table border="1" cellpadding="6" cellspacing="0">
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
            <?php $i = 1; while ($row = $results->fetch_assoc()): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['fullname']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['test_title']) ?></td>
                    <td><?= is_null($row['score']) ? '...' : $row['score'] ?></td>
                    <td><?= ucfirst($row['status']) ?></td>
                    <td><?= $row['submitted_at'] ?? '...' ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>📭 Chưa có bài làm nào được ghi nhận.</p>
<?php endif; ?>

<a href="dashboard_admin.php">← Quay lại</a>