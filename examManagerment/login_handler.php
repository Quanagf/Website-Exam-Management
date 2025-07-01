<?php
session_start();
require_once(__DIR__ . '/config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // ✅ So sánh mật khẩu mã hóa
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = $user;

            // ✅ Điều hướng theo vai trò
            switch ($user['role']) {
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
                    $_SESSION['error'] = "Không xác định được vai trò!";
                    header("Location: login.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Mật khẩu không đúng!";
        }
    } else {
        $_SESSION['error'] = "Tài khoản không tồn tại!";
    }

    header("Location: login.php");
    exit();
}
