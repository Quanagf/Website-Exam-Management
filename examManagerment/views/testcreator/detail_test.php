<?php
session_start();
require_once '../../config/database.php';
date_default_timezone_set('Asia/Ho_Chi_Minh');
// Kiểm tra quyền truy cập
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'creator') {
    header("Location: ../../index.php");
    exit();
}

if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit();
}

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

$user = $_SESSION['user'];
$test_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$test_result = $conn->query("SELECT * FROM tests WHERE id = $test_id AND test_creator_id = {$user['id']}");
if ($test_result->num_rows === 0) {
    die("❌ Đề thi không tồn tại hoặc bạn không có quyền truy cập.");
}
$test = $test_result->fetch_assoc();

?>

<h2>📘 Chi tiết đề thi: <?= htmlspecialchars($test['title']) ?></h2>
<p><strong>Mô tả:</strong> <?= htmlspecialchars($test['description']) ?></p>
<p><strong>Ngày tạo:</strong> <?= $test['created_at'] ?></p>

<?php if (!empty($test['share_code'])): ?>
    <p>
        <strong>🔗 Mã chia sẻ:</strong>
        <input type="text" id="shareCode" value="<?= htmlspecialchars($test['share_code']) ?>" readonly style="width: 120px; text-align: center;">
        <button onclick="copyShareCode()">📋 Sao chép</button>
    </p>
<?php endif; ?>

<p>
    <strong>⏱ Trạng thái (tự động):</strong>
    <span id="js-status"
          data-open="<?= $test['open_time'] ?>"
          data-close="<?= $test['close_time'] ?>">
        Đang xác định...
    </span>
</p>

<p><strong>🕒 Mở lúc:</strong> <?= $test['open_time'] ?: 'Không đặt' ?></p>
<p><strong>🕓 Đóng lúc:</strong> <?= $test['close_time'] ?: 'Không đặt' ?></p>
<p><strong>⏳ Thời gian làm bài:</strong> <?= $test['duration'] ?> phút</p>


<hr>
<?php 
    $now = new DateTime();
    $open_time = new DateTime($test['open_time']);
    $close_time = new DateTime($test['close_time']);
    $is_editable = ($now < $open_time);
?>
<!-- Các thao tác -->
<p>
    <?php if ($is_editable): ?>
        <a href="add_question.php?test_id=<?= $test['id'] ?>">➕ Thêm câu hỏi</a>
        <a href="edit_test.php?id=<?= $test['id'] ?>">✏️ Sửa đề</a>
        <form method="POST" action="../../controllers/TestController.php" onsubmit="return confirm('Bạn chắc chắn muốn xoá đề này?');" style="display:inline;">
            <input type="hidden" name="delete" value="1">
            <input type="hidden" name="test_id" value="<?= $test['id'] ?>">
            <button type="submit" style="border:none; background:none; color:red; cursor:pointer;">🗑️ Xoá đề</button>
        </form>
    <?php else: ?>
        <span style="color:gray;">⛔ Không thể chỉnh sửa khi đề đang mở</span>
    <?php endif; ?>
    <br>
    <a href="../../controllers/TestController.php?statistics=1&id=<?= $test_id ?>" class="btn">📊 Xem thống kê</a>
    <br>
    <a href="dashboard_testcreator.php">← Quay lại</a>
</p>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?= $error ?></p>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <p style="color: green;"><?= $success ?></p>
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

<hr>

<h3>📋 Danh sách thí sinh đã nộp bài:</h3>
<?php
$stmt = $conn->prepare("
    SELECT tr.test_taker_id, u.username, tr.score, tr.status, tr.submitted_at
    FROM test_responses tr
    JOIN users u ON tr.test_taker_id = u.id
    WHERE tr.test_id = ?
");
$stmt->bind_param("i", $test_id);
$stmt->execute();
$results = $stmt->get_result();

if ($results->num_rows > 0): ?>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>STT</th>
                <th>Username</th>
                <th>Điểm</th>
                <th>Trạng thái</th>
                <th>Thời gian nộp</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php $i = 1; while ($row = $results->fetch_assoc()): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= is_null($row['score']) ? '...' : $row['score'] ?></td>
                <td><?= ucfirst($row['status']) ?></td>
                <td><?= $row['submitted_at'] ?? '...' ?></td>
                <td>
                    <?php if ($row['status'] === 'completed'): ?>
                        <form method="post" action="../../controllers/TestController.php" onsubmit="return confirm('Cho phép làm lại bài này?')">
                            <input type="hidden" name="action" value="reset_attempt">
                            <input type="hidden" name="test_id" value="<?= $test_id ?>">
                            <input type="hidden" name="user_id" value="<?= $row['test_taker_id'] ?>">
                            <button type="submit">🔁 Cho làm lại</button>
                        </form>
                    <?php else: ?>
                        <em style="color: green;">✔️ Đang chờ làm lại</em>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>📭 Chưa có ai làm bài thi này.</p>
<?php endif; ?>

<!-- SCRIPT -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function updateTestStatus() {
    const $status = $('#js-status');
    const openStr = $status.data('open');
    const closeStr = $status.data('close');

    const now = new Date();
    const openTime = new Date(openStr);
    const closeTime = new Date(closeStr);

    if (isNaN(openTime) || isNaN(closeTime)) {
        $status.text('❓ Không hợp lệ').css('color', 'gray');
        return;
    }

    if (now < openTime) {
        $status.text('🕒 Chưa mở').css('color', 'blue');
    } else if (now >= openTime && now <= closeTime) {
        $status.text('✅ Đang mở').css('color', 'green');
    } else {
        $status.text('🔒 Đã đóng').css('color', 'red');
    }
}

function copyShareCode() {
    const input = document.getElementById("shareCode");
    input.select();
    input.setSelectionRange(0, 99999);
    document.execCommand("copy");
    alert("✅ Mã đã sao chép: " + input.value);
}

$(document).ready(function () {
    updateTestStatus();
    setInterval(updateTestStatus, 1000);
});
</script>
