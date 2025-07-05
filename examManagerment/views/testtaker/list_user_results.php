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
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Danh sách bài thi đã làm</title>
    <link rel="stylesheet" href="../../src/css/layout.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>📜 Danh sách bài thi đã làm</h1>
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

                <div class="menu-items1"><a href="javascript:history.back()"><span class="icon">🔙</span> Quay lại</a>
                </div>
                <div class="menu-items1"><a href="../../logout.php"><span class="icon">🚪</span> Đăng xuất</a></div>
            </div>
            <div class="line"></div>

            <div class="main2">
                <?php if (empty($results)): ?>
                    <p>⚠️ Bạn chưa làm bài thi nào.</p>
                <?php else: ?>
                    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
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
                                    <td><?= htmlspecialchars($row['submitted_at']) ?></td>
                                    <td>
                                        <form method="POST" action="../../controllers/JointestController.php"
                                            style="display:inline;">
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


            </div>
        </div>

        <div class="footer">
            ©2025 Hệ thống thi trắc nghiệm
        </div>
    </div>
</body>

</html>