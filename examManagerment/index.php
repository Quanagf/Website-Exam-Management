<?php
session_start();
if (isset($_SESSION['success'])) {
    echo "<p style='color: green'>" . $_SESSION['success'] . "</p>";
    unset($_SESSION['success']);
}

// Nếu đã đăng nhập → tự động vào dashboard
if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'];
    switch ($role) {
        case 'admin':
            header("Location: views/admin/dashboard_admin.php");
            break;
        case 'creator':
            header("Location: views/testcreator/dashboard_testcreator.php");
            break;
        case 'taker':
            header("Location: views/testtaker/dashboard_taker.php");
            break;
        default:
            echo "Không xác định được vai trò.";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đăng nhập - Hệ thống Thi Trắc Nghiệm</title>
<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}
body, html {
    height: 100%;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
.background {
    background: url('src/img/view.jpg') no-repeat center center;
    background-size: cover;
    height: 100%;
    position: relative;
}
.overlay {
    position: absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
}
.login-box {
    position: absolute;
    right:50px;
    top:50%;
    transform: translateY(-50%);
    background: #fff;
    padding:50px;
    border-radius:10px;
    box-shadow:0 0 20px rgba(0,0,0,0.3);
    width:300px;
}
.login-box h2 {
    margin-bottom:20px;
}
.login-box input[type="text"],
.login-box input[type="password"] {
    width:100%;
    padding:10px;
    margin:10px 0;
    border:1px solid #ccc;
    border-radius:5px;
}
.login-box button {
    width:100%;
    background:#27ae60;
    color:#fff;
    padding:10px;
    border:none;
    border-radius:5px;
    font-size:16px;
    cursor:pointer;
}
.login-box button:hover {
    background:#219150;
}
.login-box .link {
    margin-top:10px;
    text-align:center;
}
.login-box .link a {
    color:black;
    text-decoration:none;
}
.login-box .link a:hover {
    text-decoration:underline;
}
</style>
</head>
<body>
<div class="background">
    <div class="overlay"></div>
    <div class="login-box">
        <h2 style="font-size:20px;">Đăng nhập hệ thống</h2>
        
        <form method="POST" action="login_handler.php">
    <input type="text" name="username" placeholder="Tên đăng nhập" required>
    <input type="password" name="password" placeholder="Mật khẩu" required>
    <button type="submit" name="login">Đăng nhập</button>
        </form>
        <div class="link">
            <a href="views/forgot_password.php">Quên mật khẩu?</a>
        </div>
        <div class="link" style="margin-top:5px;">
            <a href="register.php">Tạo tài khoản mới</a>
        </div>
    </div>
</div>
</body>
</html>
