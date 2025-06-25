<?php
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Kiểm tra đăng nhập & quyền
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'creator') {
    header("Location: ../index.php");
    exit();
}

// ✅ Xử lý thêm câu hỏi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_question'])) {
    $test_id = intval($_POST['test_id']);
    $content = trim($_POST['content']);
    $option_a = trim($_POST['option_a']);
    $option_b = trim($_POST['option_b']);
    $option_c = trim($_POST['option_c']);
    $option_d = trim($_POST['option_d']);
    $correct  = strtoupper(trim($_POST['correct']));
    $score    = intval($_POST['score']);

    // Kiểm tra hợp lệ
    if (!$test_id || !$content || !$option_a || !$option_b || !$option_c || !$option_d || !in_array($correct, ['A', 'B', 'C', 'D'])) {
        $_SESSION['error'] = "❌ Dữ liệu không hợp lệ!";
        header("Location: ../views/testcreator/add_question.php?test_id=$test_id");
        exit();
    }

    // Chuẩn bị & thực thi truy vấn
    $stmt = $conn->prepare("INSERT INTO questions (test_id, content, option_a, option_b, option_c, option_d, correct, score) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssi", $test_id, $content, $option_a, $option_b, $option_c, $option_d, $correct, $score);

    if ($stmt->execute()) {
        $_SESSION['success'] = "✅ Đã thêm câu hỏi!";
        header("Location: ../views/testcreator/detail_test.php?id=$test_id");
        exit();
    } else {
        $_SESSION['error'] = "❌ Lỗi khi thêm: " . $stmt->error;
        header("Location: ../views/testcreator/add_question.php?test_id=$test_id");
        exit();
    }
}


// ✅ Xử lý xoá câu hỏi
if ($_GET['action'] === 'delete' && isset($_GET['question_id'], $_GET['test_id'])) {
    $question_id = intval($_GET['question_id']);
    $test_id     = intval($_GET['test_id']);

    // Kiểm tra quyền
    if (!$question_id || !$test_id) {
        $_SESSION['error'] = "❌ Dữ liệu không hợp lệ!";
        header("Location: ../views/testcreator/test_detail.php?id=$test_id");
        exit();
    }

    // Xoá câu hỏi
    $stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->bind_param("i", $question_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "✅ Đã xoá câu hỏi!";
    } else {
        $_SESSION['error'] = "❌ Không thể xoá: " . $stmt->error;
    }

    header("Location: ../views/testcreator/detail_test.php?id=$test_id");
    exit();
}

?>
