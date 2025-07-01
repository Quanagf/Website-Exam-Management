<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$test_id = isset($_GET['test_id']) ? intval($_GET['test_id']) : 0;

require_once '../../controllers/QuestionmanController.php';
?>

<h2>📘 Danh sách câu hỏi cho đề thi: <?= htmlspecialchars($test['title']) ?></h2>

<?php if ($questions->num_rows > 0): ?>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>STT</th>
                <th>Nội dung</th>
                <th>A</th>
                <th>B</th>
                <th>C</th>
                <th>D</th>
                <th>Đáp án đúng</th>
                <th>Điểm</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; while ($q = $questions->fetch_assoc()): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($q['content']) ?></td>
                    <td><?= htmlspecialchars($q['option_a']) ?></td>
                    <td><?= htmlspecialchars($q['option_b']) ?></td>
                    <td><?= htmlspecialchars($q['option_c']) ?></td>
                    <td><?= htmlspecialchars($q['option_d']) ?></td>
                    <td style="color: green; font-weight: bold;"><?= $q['correct'] ?></td>
                    <td><?= $q['score'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>❗ Đề thi này chưa có câu hỏi nào.</p>
<?php endif; ?>

<br>
<a href="detail_test.php?id=<?= $test_id ?>">← Quay lại chi tiết đề thi</a>
