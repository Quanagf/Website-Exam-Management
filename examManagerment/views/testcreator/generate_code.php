<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'creator') {
    header("Location: ../../index.php");
    exit();
}

function generateRandomCode($length = 8)
{
    return strtoupper(substr(md5(rand()), 0, $length));
}

$success = '';
$error = '';
$generatedCode = '';

if (isset($_POST['generate'])) {
    $test_id = intval($_POST['test_id']);
    $code = generateRandomCode();

    $sql = "INSERT INTO invitation_codes (test_id, code) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $test_id, $code);

    if ($stmt->execute()) {
        $success = "✅ Mã mời được tạo: <strong>$code</strong>";
        $generatedCode = $code;
    } else {
        $error = "❌ Lỗi tạo mã: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Tạo mã mời</title>
    <link rel="stylesheet" href="../../src/css/layout.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🎟️ Tạo mã mời tham gia đề thi</h1>
            <div class="menu-container">
                <div class="hamburger">&#9776;</div>
                <div class="menu-items">
                    <a href="../../index.php">Trang chủ</a>
                    <a href="../logout.php">Đăng xuất</a>
                </div>
            </div>
        </div>

        <div class="main">
            <div class="main1">
                <div class="menu-items1"><a href="javascript:history.back()"><span class="icon">🔙</span> Quay lại</a>
                </div>
            </div>
            <div class="line"></div>

            <div class="main2">
                <?php if ($success): ?>
                    <p style="color: green;"><?= $success ?></p>
                <?php endif; ?>
                <?php if ($error): ?>
                    <p style="color: red;"><?= $error ?></p>
                <?php endif; ?>

                <form method="post">
                    <label><strong>ID đề thi:</strong></label><br>
                    <input type="number" name="test_id" required><br><br>
                    <button name="generate">✨ Tạo mã mời</button>
                </form>

                <?php if ($generatedCode): ?>
                    <p>
                        <label>Mã mời mới:</label><br>
                        <input type="text" value="<?= htmlspecialchars($generatedCode) ?>" readonly
                            style="width:120px; text-align:center;">
                        <button onclick="copyCode()">📋 Sao chép</button>
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <div class="footer">
            ©2025 Quản lý thi trắc nghiệm
        </div>
    </div>

    <script>
        function copyCode() {
            const input = document.querySelector('input[readonly]');
            input.select();
            document.execCommand('copy');
            alert("✅ Mã đã được sao chép: " + input.value);
        }
    </script>
</body>

</html>