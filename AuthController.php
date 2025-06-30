<?php
require_once './config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    // XỬ LÝ ĐĂNG NHẬP
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Lỗi prepare: " . $conn->error); // Thêm xử lý prepare
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            // Cho phép mật khẩu mã hóa hoặc không mã hóa
            if (password_verify($password, $user['password']) || $password === $user['password']) {
                $_SESSION['user'] = $user;
                header("Location: dashboard.php");
                exit();
            } else {
                $_SESSION['error'] = "❌ Sai mật khẩu!";//Có thêm biểu tượng ❌ 
                header("Location: index.php");//Thêm header("Location: index.php") sau khi sai mật khẩu hoặc tài khoản không tồn tại
                exit();
            }
        } else {
            $_SESSION['error'] = "❌ Tài khoản không tồn tại!";
            header("Location: index.php");
            exit();
        }
    }

    // XỬ LÝ ĐĂNG KÝ
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = $_POST['role'];

        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Lỗi prepare tại AuthController dòng 40: " . $conn->error);//Thông báo lỗi cụ thể hơn khi prepare trong phần đăng ký bị lỗi
        }

        $stmt->bind_param("ssss", $username, $email, $password, $role);

        if ($stmt->execute()) {
            $_SESSION['success'] = "✅ Đăng ký thành công!";
            header("Location: index.php");
            exit();
        } else {
            echo "❌ Lỗi: " . $stmt->error;
        }
    }
}
?>
