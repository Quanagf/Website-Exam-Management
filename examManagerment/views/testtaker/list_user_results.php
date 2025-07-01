<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$results = $_SESSION['submitted_tests'] ?? [];
unset($_SESSION['submitted_tests']);
// Kiểm tra quyền truy cập
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'taker') {
    header("Location: ../../index.php");
    exit();
}

?>

<h2>📜 Danh sách bài thi bạn đã làm</h2>

<?php if (empty($results)): ?>
    <p>⚠️ Bạn chưa làm bài thi nào.</p>
<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên bài thi</th>
                <th>Điểm</th>
                <th>Thời gian nộp</th>
                <th>Chi tiết</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><strong style="color: green"><?= $row['score'] ?></strong></td>
                    <td><?= $row['submitted_at'] ?></td>
                    <td>
                        <form method="POST" action="../../controllers/JointestController.php" style="display:inline;">
                            <input type="hidden" name="view_test_detail" value="1">
                            <input type="hidden" name="test_id" value="<?= $row['test_id'] ?>">
                            <button type="submit">🔍 Xem</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>