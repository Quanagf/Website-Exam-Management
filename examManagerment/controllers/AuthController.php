<?php
require_once __DIR__ . '/../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ XỬ LÝ ĐĂNG KÝ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $fullname = trim($_POST['fullname']); // thêm fullname
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    // Kiểm tra username hoặc email đã tồn tại
    $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult->num_rows > 0) {
        $_SESSION['error'] = "❌ Tên đăng nhập hoặc email đã tồn tại!";
        header("Location: register.php");
        exit();
    }

    // Tiến hành đăng ký
    $sql = "INSERT INTO users (fullname, username, email, password, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $fullname, $username, $email, $password, $role);

    if ($stmt->execute()) {
        $_SESSION['success'] = "✅ Đăng ký thành công! Vui lòng đăng nhập.";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "❌ Lỗi hệ thống: " . $stmt->error;
        header("Location: index.php");
        exit();
    }

}

// ✅ XỬ LÝ QUÊN MẬT KHẨU
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forgot_password'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    // Kiểm tra username và email có trùng khớp hay không
    $check = $conn->prepare("SELECT id FROM users WHERE username = ? AND email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "❌ Sai tên đăng nhập hoặc email!";
        header("Location: ../views/forgot_password.php");
        exit();
    }

    // Đặt lại mật khẩu mới là 123456
    $newPassword = password_hash("123456", PASSWORD_BCRYPT);
    $update = $conn->prepare("UPDATE users SET password = ? WHERE username = ? AND email = ?");
    $update->bind_param("sss", $newPassword, $username, $email);

    if ($update->execute()) {
        $_SESSION['success'] = "✅ Mật khẩu đã được đặt lại thành <strong>123456</strong>. Vui lòng đăng nhập lại và đổi mật khẩu.";
        header("Location: ../login.php");
        exit();
    } else {
        $_SESSION['error'] = "❌ Lỗi khi cập nhật mật khẩu. Vui lòng thử lại.";
        header("Location: ../views/forgot_password.php");
        exit();
    }
}

?>