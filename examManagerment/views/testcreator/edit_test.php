<?php
session_start();
require_once '../../controllers/TestController.php';
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'creator') {
    header("Location: ../../index.php");
    exit();
}

$test_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lấy thông tin đề thi
$stmt = $conn->prepare("SELECT * FROM tests WHERE id = ? AND test_creator_id = ?");
$stmt->bind_param("ii", $test_id, $_SESSION['user']['id']);
$stmt->execute();
$result = $stmt->get_result();
$test = $result->fetch_assoc();

if (!$test) {
    $_SESSION['error'] = "Không tìm thấy đề thi hoặc bạn không có quyền.";
    header("Location: dashboard_creator.php");
    exit();
}

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>✏️ Sửa đề thi</title>
    <link rel="stylesheet" href="../../src/css/layout.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>✏️ Sửa đề thi</h1>
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

                <div class="menu-items1">
                    <a href="detail_test.php?id=<?= $test_id ?>"><span class="icon">📄</span> Xem đề</a>
                </div>
                <div class="menu-items1">
                    <a href="javascript:history.back()"><span class="icon">🔙</span> Quay lại</a>
                </div>
            </div>
            <div class="line"></div>

            <div class="main2">
                <h2>📝 Chỉnh sửa thông tin đề thi</h2>

                <?php if ($success): ?>
                    <p class="message success"><?= htmlspecialchars($success) ?></p>
                <?php endif; ?>
                <?php if ($error): ?>
                    <p class="message error"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>

                <form method="POST" action="../../controllers/TestController.php" class="update-form">
                    <input type="hidden" name="edit" value="1">
                    <input type="hidden" name="test_id" value="<?= $test['id'] ?>">

                    <label>Tiêu đề:</label><br>
                    <input type="text" name="title" value="<?= htmlspecialchars($test['title']) ?>" required><br><br>

                    <label>Mô tả:</label><br>
                    <textarea name="description"><?= htmlspecialchars($test['description']) ?></textarea><br><br>

                    <label>Thời lượng (phút):</label><br>
                    <input type="number" name="duration" value="<?= $test['duration'] ?>" required><br><br>

                    <label>🕒 Thời gian mở bài:</label><br>
                    <input type="datetime-local" name="open_time"
                        value="<?= $test['open_time'] ? date('Y-m-d\TH:i', strtotime($test['open_time'])) : '' ?>"><br><br>

                    <label>🕓 Thời gian đóng bài:</label><br>
                    <input type="datetime-local" name="close_time"
                        value="<?= $test['close_time'] ? date('Y-m-d\TH:i', strtotime($test['close_time'])) : '' ?>"
                        required><br><br>

                    <button type="submit">💾 Cập nhật</button>
                </form>
            </div>
        </div>

        <div class="footer">
            ©2025 Quản lý thi trắc nghiệm
        </div>
    </div>
</body>

</html>