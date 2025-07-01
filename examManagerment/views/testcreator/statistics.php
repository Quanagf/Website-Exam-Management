<?php
session_start();
// Kiểm tra xem người dùng đã đăng nhập và có quyền truy cập
require_once __DIR__ . '/../../config/database.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'creator') {
    header("Location: ../../index.php");
    exit();
}
if (!isset($_SESSION['statistics']) || !isset($_SESSION['test_title'])) {
    header("Location: dashboard_testcreator.php");
    exit();
}

$stats = $_SESSION['statistics'];
$test_title = $_SESSION['test_title'];
$users = $_SESSION['user_results'] ?? [];
?>

<h2>📊 Thống kê kết quả bài thi: <?= htmlspecialchars($test_title) ?></h2>

<!-- Thống kê tổng -->
<ul>
    <li><strong>Tổng số thí sinh:</strong> <?= $stats['total'] ?? 0 ?></li>
    <li><strong>Điểm trung bình:</strong> <?= number_format($stats['average_score'] ?? 0, 2) ?></li>
    <li><strong>Điểm cao nhất:</strong> <?= $stats['max_score'] ?? 0 ?></li>
    <li><strong>Điểm thấp nhất:</strong> <?= $stats['min_score'] ?? 0 ?></li>
    <li><strong>Số người đạt (>= 5):</strong> <?= $stats['passed'] ?? 0 ?></li>
    <li><strong>Số người trượt (< 5):</strong> <?= $stats['failed'] ?? 0 ?></li>
</ul>

<hr>

<!-- Danh sách thí sinh -->
<h3>📋 Danh sách thí sinh đã làm bài:</h3>

<?php if (!empty($users)): ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>STT</th>
                <th>Họ và tên</th>
                <th>Điểm</th>
                <th>Thời gian nộp</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $index => $row): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($row['fullname']) ?></td>
                    <td><?= htmlspecialchars($row['score']) ?></td>
                    <td><?= htmlspecialchars($row['submitted_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>⚠️ Chưa có thí sinh nào làm bài thi này.</p>
<?php endif; ?>

<br>
<a href="detail_test.php?id=<?= htmlspecialchars($_GET['id'] ?? '') ?>">🔙 Quay lại</a>