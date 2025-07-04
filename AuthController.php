<?php
require_once './config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Sai mật khẩu!";
            exit();
        }
    } else {
        $_SESSION['error'] = "❌ Tài khoản không tồn tại!";
        exit();
    }
}

if (isset($_POST['register'])) {
    $fullname = $_POST['fullname']; //thêm 1 dòng code dòng số 33
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $sql = "INSERT INTO users ( fullname, username, email, password, role) VALUES (?, ?, ?, ?, ?)";//thêm fullname , 1 dấu ?
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $fullname, $username, $email, $password, $role);//thêm 1 chữ s , $fullname
    if ($stmt->execute()) {
        $_SESSION['success'] = "Đăng ký thành công!";
        header("Location: index.php");
        exit();
    } else {
        echo "Lỗi: " . $stmt->error;
    }
}
?>