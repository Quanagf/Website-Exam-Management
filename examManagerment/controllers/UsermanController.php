<?php
require_once(__DIR__ . '/../config/database.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// ✅ Kiểm tra đăng nhập & quyền admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// ✅ Khóa/Mở người dùng
if (isset($_POST['toggle_status'])) {
    $user_id = intval($_POST['user_id']);
    $result = $conn->query("SELECT status FROM users WHERE id = $user_id");

    if ($result && $result->num_rows === 1) {
        $current = $result->fetch_assoc()['status'];
        $newStatus = $current === 'active' ? 'locked' : 'active';
        $conn->query("UPDATE users SET status = '$newStatus' WHERE id = $user_id");
    }

    header("Location: ../views/admin/user_management.php");
    exit();
}

// ✅ Xóa người dùng
if (isset($_POST['delete_user'])) {
    $id = intval($_POST['delete_user_id']);
    $conn->query("DELETE FROM users WHERE id = $id");
    header("Location: ../views/admin/user_management.php");
    exit();
}

// ✅ Thêm người dùng
if (isset($_POST['add_user'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, password, role, status) VALUES (?, ?, ?, ?, ?, 'active')");
    $stmt->bind_param("sssss", $fullname, $username, $email, $password, $role);
    $stmt->execute();

    header("Location: ../views/admin/user_management.php");
    exit();
}
