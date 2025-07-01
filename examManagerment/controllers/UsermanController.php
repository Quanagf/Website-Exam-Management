<?php
require_once(__DIR__ . '/../config/database.php');

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

// ✅ Kiểm tra đăng nhập & quyền
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// ✅ Thêm người dùng
if (isset($_POST['add_user'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    // Kiểm tra trùng username/email
    $check = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $result = $check->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['error'] = "❌ Tên đăng nhập hoặc email đã tồn tại.";
        header("Location: ../views/admin/user_management.php");
        exit();
    }

    // Thêm mới
    $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fullname, $username, $email, $password, $role);
    $stmt->execute();

    $_SESSION['success'] = "✅ Đã thêm người dùng.";
    header("Location: ../views/admin/user_management.php");
    exit();
}

// ✅ Khóa / Mở khóa người dùng
if (isset($_POST['toggle_status'])) {
    $user_id = intval($_POST['user_id']);
    $current = $conn->query("SELECT status FROM users WHERE id=$user_id")->fetch_assoc()['status'];
    $newStatus = $current === 'active' ? 'locked' : 'active';
    $conn->query("UPDATE users SET status='$newStatus' WHERE id=$user_id");

    $_SESSION['success'] = "✅ Đã cập nhật trạng thái.";
    header("Location: ../views/admin/user_management.php");
    exit();
}

// ✅ Xóa người dùng
if (isset($_POST['delete_user'])) {
    $user_id = intval($_POST['delete_user_id']);
    $conn->query("DELETE FROM users WHERE id=$user_id");

    $_SESSION['success'] = "✅ Đã xóa người dùng.";
    header("Location: ../views/admin/user_management.php");
    exit();
}

// Lấy danh sách người dùng với tùy chọn lọc theo email
$emailFilter = $_GET['email'] ?? '';

if (!empty($emailFilter)) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email LIKE ? ORDER BY role, id DESC");
    $likeEmail = "%$emailFilter%";
    $stmt->bind_param("s", $likeEmail);
    $stmt->execute();
    $users = $stmt->get_result();
} else {
    $users = $conn->query("SELECT * FROM users ORDER BY role, id DESC");
}