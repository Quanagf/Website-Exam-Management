<?php
session_start(); // <<< thêm dòng này ngay đầu tiên
require_once(__DIR__ . '/../config/database.php');

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

$user = $_SESSION['user'];

// ✅ Cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_info'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);

    $sql = "UPDATE users SET fullname = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $fullname, $email, $user['id']);

    if ($stmt->execute()) {
        $_SESSION['user']['fullname'] = $fullname;
        $_SESSION['user']['email'] = $email;
        $_SESSION['success'] = "✅ Cập nhật thành công!";
    } else {
        $_SESSION['error'] = "❌ Lỗi: " . $stmt->error;
    }

    header("Location: ../views/account/profile.php");
    exit();
}

// ✅ Xử lý đổi mật khẩu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    // Lấy mật khẩu hiện tại từ DB
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row || !password_verify($current, $row['password'])) {
        $_SESSION['error'] = "❌ Mật khẩu hiện tại không đúng!";
    } elseif ($new !== $confirm) {
        $_SESSION['error'] = "❌ Mật khẩu mới và xác nhận không khớp!";
    } else {
        $hashed = password_hash($new, PASSWORD_BCRYPT);
        $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update->bind_param("si", $hashed, $user['id']);
        if ($update->execute()) {
            $_SESSION['success'] = "✅ Đổi mật khẩu thành công!";
        } else {
            $_SESSION['error'] = "❌ Lỗi khi đổi mật khẩu.";
        }
    }

    header("Location: ../views/account/change_password.php");
    exit();
}
