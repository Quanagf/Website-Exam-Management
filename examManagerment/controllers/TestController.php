<?php
require_once __DIR__ . '/../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Kiểm tra đăng nhập và quyền
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'creator') {
    header("Location: ../index.php");
    exit();
}

// Xử lý tạo đề thi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $duration = intval($_POST['duration']);
    $creator_id = $_SESSION['user']['id'];

    if (empty($title) || $duration <= 0) {
        $_SESSION['error'] = "Vui lòng nhập đầy đủ và hợp lệ!";
    } else {
        $sql = "INSERT INTO tests (test_creator_id, title, description, duration)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issi", $creator_id, $title, $desc, $duration);

        if ($stmt->execute()) {
            $test_id = $conn->insert_id;
            header("Location: ../../dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Lỗi tạo đề thi:". $stmt->error;
        }
    }
}

// ✅ Xử lý xoá đề
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $test_id = intval($_POST['test_id']);

    // Kiểm tra đăng nhập & quyền
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'creator') {
        header("Location: ../index.php");
        exit();
    }

    // Kiểm tra quyền sở hữu
    $check = $conn->prepare("SELECT id FROM tests WHERE id = ? AND test_creator_id = ?");
    $check->bind_param("ii", $test_id, $_SESSION['user']['id']);
    $check->execute();
    $check->store_result();

    if ($check->num_rows === 0) {
        $_SESSION['error'] = "❌ Bạn không có quyền xoá đề này.";
        header("Location: ../dashboard.php");
        exit();
    }

    // Xoá câu hỏi trước
    $conn->query("DELETE FROM questions WHERE test_id = $test_id");

    // Xoá đề thi
    $stmt = $conn->prepare("DELETE FROM tests WHERE id = ?");
    $stmt->bind_param("i", $test_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "✅ Đã xoá đề thi!";
    } else {
        $_SESSION['error'] = "❌ Lỗi khi xoá: " . $stmt->error;
    }

    header("Location: ../dashboard.php");
    exit();
}
?>