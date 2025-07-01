<?php
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_test'])) {
    $test_id = intval($_POST['test_id']);

    // Xóa câu hỏi liên quan trước (nếu có)
    $conn->query("DELETE FROM questions WHERE test_id = $test_id");

    // Xóa bài thi
    $stmt = $conn->prepare("DELETE FROM tests WHERE id = ?");
    $stmt->bind_param("i", $test_id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "✅ Xóa đề thi thành công!";
    } else {
        $_SESSION['error'] = "❌ Lỗi khi xóa đề thi!";
    }
    header("Location: ../views/admin/test_management.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'reset_attempt') {
    $test_id = intval($_POST['test_id']);
    $user_id = intval($_POST['user_id']);

    $conn->query("DELETE FROM test_responses WHERE test_id = $test_id AND test_taker_id = $user_id");

    $_SESSION['success'] = "✅ Đã cho phép làm lại bài!";
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}

