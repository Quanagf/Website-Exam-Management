<?php
session_start();
require_once '../../config/database.php';
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Kiểm tra quyền
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'creator') {
    header("Location: ../../index.php");
    exit();
}

$user = $_SESSION['user'];
$test_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lấy thông tin đề thi
$test_result = $conn->query("SELECT * FROM tests WHERE id = $test_id AND test_creator_id = {$user['id']}");
if ($test_result->num_rows === 0) {
    die("❌ Đề thi không tồn tại hoặc bạn không có quyền truy cập.");
}
$test = $test_result->fetch_assoc();

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

$now = new DateTime();
$open_time = new DateTime($test['open_time']);
$is_editable = ($now < $open_time);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>📘 Chi tiết đề thi</title>
    <link rel="stylesheet" href="../../src/css/layout.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>📘 Chi tiết đề thi</h1>
        <div class="menu-container">
            <div class="hamburger">&#9776;</div>
            <div class="menu-items">
                <a href="../../index.php">Trang chủ</a>
                <a href="../../logout.php">Đăng xuất</a>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="main1">
            <div class="menu-items1"><a href="javascript:history.back()"><span class="icon">🔙</span> Quay lại</a></div>
        </div>
        <div class="line"></div>

        <div class="main2">
            <?php if ($success): ?>
                <p class="message success"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>
            <?php if ($error): ?>
                <p class="message error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <h2><?= htmlspecialchars($test['title']) ?></h2>
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
                <strong>⏱ Trạng thái:</strong>
                <span id="js-status"
                      data-open="<?= $test['open_time'] ?>"
                      data-close="<?= $test['close_time'] ?>">Đang xác định...</span>
            </p>

            <p><strong>🕒 Mở lúc:</strong> <?= $test['open_time'] ?: 'Không đặt' ?></p>
            <p><strong>🕓 Đóng lúc:</strong> <?= $test['close_time'] ?: 'Không đặt' ?></p>
            <p><strong>⏳ Thời gian làm bài:</strong> <?= $test['duration'] ?> phút</p>

            <hr>

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
            </p>
            <a href="../../controllers/TestController.php?statistics=1&id=<?= $test_id ?>" class="btn">📊 Xem thống kê</a>

            <hr>

            <h3>📋 Danh sách câu hỏi:</h3>
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
                            <td><?= $row['correct'] ?></td>
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
                <p>❗ Đề thi này chưa có câu hỏi.</p>
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
                                     <form method="post" action="../../controllers/TestController.php" style="display:inline;"
                                        onsubmit="return confirm('Cho phép làm lại và giữ nguyên thời gian?');">
                                        <input type="hidden" name="action" value="reset_attempt">
                                        <input type="hidden" name="test_id" value="<?= $test_id ?>">
                                        <input type="hidden" name="user_id" value="<?= $row['test_taker_id'] ?>">
                                        <button type="submit">🔄 Làm lại (Giữ thời gian)</button>
                                    </form>
                                    <form method="post" action="../../controllers/TestController.php" style="display:inline; margin-left:5px;"
                                        onsubmit="return confirm('Cho phép làm lại và reset thời gian mới?');">
                                        <input type="hidden" name="action" value="reset_attempt_resettime">
                                        <input type="hidden" name="test_id" value="<?= $test_id ?>">
                                        <input type="hidden" name="user_id" value="<?= $row['test_taker_id'] ?>">
                                        <button type="submit">🕒 Làm lại (Reset thời gian)</button>
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
                <p>📭 Chưa có ai làm bài.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer">
        ©2025 Quản lý thi trắc nghiệm
    </div>
</div>

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
</body>
</html>
