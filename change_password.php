<?php
session_start();
require_once '../../config/database.php';//thay đổi đường dẫn

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['change'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $user = $_SESSION['user'];

    $sql = "SELECT password FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!password_verify($current, $row['password'])) {
        echo "Mật khẩu hiện tại không đúng!";
    } elseif ($new !== $confirm) {
        echo "Xác nhận mật khẩu không khớp!";
    } else {
        $newHashed = password_hash($new, PASSWORD_BCRYPT);
        $updateSql = "UPDATE users SET password=? WHERE id=?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("si", $newHashed, $user['id']);
        if ($updateStmt->execute()) {
            echo "Đổi mật khẩu thành công!";
        } else {
            echo "Lỗi khi đổi mật khẩu.";
        }
    }
}
?>

<h2>Đổi mật khẩu</h2>
<form method="post">
    Mật khẩu hiện tại: <input type="password" name="current_password" required><br>
    Mật khẩu mới: <input type="password" name="new_password" required><br>
    Xác nhận mật khẩu mới: <input type="password" name="confirm_password" required><br>
    <button name="change">Đổi mật khẩu</button>
</form>
<a href="profile.php">◀--- Quay lại</a><!-- sửa dấu mũi tên -->
