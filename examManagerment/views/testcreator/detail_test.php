<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit();
}

$success = '';
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}



$user = $_SESSION['user'];
$test_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lấy thông tin đề thi
$test_result = $conn->query("SELECT * FROM tests WHERE id = $test_id AND test_creator_id = {$user['id']}");
if ($test_result->num_rows === 0) {
    die("❌ Đề thi không tồn tại hoặc bạn không có quyền truy cập.");
}
$test = $test_result->fetch_assoc();
?>

<h2>📘 Chi tiết đề thi: <?= htmlspecialchars($test['title']) ?></h2>
<p><strong>Mô tả:</strong> <?= htmlspecialchars($test['description']) ?></p>
<p><strong>Ngày tạo:</strong> <?= $test['created_at'] ?></p>

<!-- Nút thao tác -->
<p>
    <a href="add_question.php?test_id=<?= $test['id'] ?>">➕ Thêm câu hỏi</a>
    <a href="edit_test.php?id=<?= $test['id'] ?>">✏️ Sửa đề</a>
    <a href="../../controllers/TestController.php?action=delete&id=<?= $test['id'] ?>" onclick="return confirm('Bạn chắc chắn muốn xoá đề này?')">🗑️ Xoá đề</a>
</p>

<?php if (!empty($success)): ?>
    <p style="color: green ;"><?php echo $success; ?></p>
    <?php endif; ?>

<hr>

<h3>📋 Danh sách câu hỏi trong đề:</h3>

<?php
$questions = $conn->query("SELECT * FROM questions WHERE test_id = $test_id ORDER BY id ASC");
if ($questions->num_rows > 0): ?>
    <table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>STT</th>
            <th>Câu hỏi</th>
            <th>A</th>
            <th>B</th>
            <th>C</th>
            <th>D</th>
            <th>Đáp án đúng</th>
            <th>Điểm</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; while ($row = $questions->fetch_assoc()): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['content']) ?></td>
                <td><?= htmlspecialchars($row['option_a']) ?></td>
                <td><?= htmlspecialchars($row['option_b']) ?></td>
                <td><?= htmlspecialchars($row['option_c']) ?></td>
                <td><?= htmlspecialchars($row['option_d']) ?></td>
                <td style="color: green; font-weight: bold;"><?= $row['correct'] ?></td>
                <td><?= $row['score'] ?></td>
                <td>
                    <a href="../../controllers/QuestionController.php?action=delete&question_id=<?= $row['id'] ?>&test_id=<?= $test_id ?>"
                       onclick="return confirm('Bạn có chắc muốn xoá câu hỏi này không?')">🗑️ Xoá</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php else: ?>
    <p>❗ Đề thi này chưa có câu hỏi nào.</p>
<?php endif; ?>