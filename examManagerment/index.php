
<?php
session_start();
if (isset($_SESSION['success'])) {
    echo "<p style='color: green'>" . $_SESSION['success'] . "</p>";
    unset($_SESSION['success']);
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hệ thống thi trắc nghiệm</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- nếu có file CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            text-align: center;
            padding: 50px;
        }
        .container {
            max-width: 600px;
            background: #fff;
            padding: 40px;
            margin: 0 auto;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
        }
        p {
            color: #555;
            margin: 20px 0;
        }
        .btn {
            padding: 12px 25px;
            margin: 10px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-login {
            background-color: #3498db;
            color: white;
        }
        .btn-register {
            background-color: #2ecc71;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Chào mừng đến với Hệ thống Thi Trắc Nghiệm</h1>
        <p>Nền tảng giúp bạn thi thử, làm bài trắc nghiệm online, quản lý điểm số và nhiều hơn nữa.</p>
        <a href="login.php"><button class="btn btn-login">Đăng nhập</button></a>
        <a href="register.php"><button class="btn btn-register">Đăng ký</button></a>
    </div>
</body>
</html>

