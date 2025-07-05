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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_question'])) {
    $test_id = intval($_POST['test_id']);
    $content = trim($_POST['content']);
    $a = trim($_POST['option_a']);
    $b = trim($_POST['option_b']);
    $c = trim($_POST['option_c']);
    $d = trim($_POST['option_d']);
    $correct = $_POST['correct'];


    // Đếm số câu hỏi hiện tại
    $countQuery = $conn->prepare("SELECT COUNT(*) as total FROM questions WHERE test_id = ?");
    $countQuery->bind_param("i", $test_id);
    $countQuery->execute();
    $result = $countQuery->get_result();
    $row = $result->fetch_assoc();
    $totalQuestions = $row['total'] + 1;

    // Tính điểm mỗi câu (float 2 chữ số)
    $score = round(10 / $totalQuestions, 2);

    // Thêm câu hỏi mới
    $stmt = $conn->prepare("INSERT INTO questions (test_id, content, option_a, option_b, option_c, option_d, correct, score) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssd", $test_id, $content, $a, $b, $c, $d, $correct, $score);

    if ($stmt->execute()) {
        // Cập nhật lại toàn bộ điểm cho các câu trong đề
        $query = $conn->prepare("SELECT id FROM questions WHERE test_id = ?");
        $query->bind_param("i", $test_id);
        $query->execute();
        $result = $query->get_result();
        $questionCount = $result->num_rows;
        $newScore = round(10 / $questionCount, 2);

        while ($q = $result->fetch_assoc()) {
            $update = $conn->prepare("UPDATE questions SET score = ? WHERE id = ?");
            $update->bind_param("di", $newScore, $q['id']);
            $update->execute();
        }

        $_SESSION['success'] = "✅ Đã thêm câu hỏi và cập nhật điểm chính xác.";
    } else {
        $_SESSION['error'] = "❌ Lỗi: " . $stmt->error;
    }

    header("Location: ../views/testcreator/detail_test.php?id=$test_id");
    exit();
}


// ✅ Xử lý xoá câu hỏi
if ($_GET['action'] === 'delete' && isset($_GET['question_id'], $_GET['test_id'])) {
    $question_id = intval($_GET['question_id']);
    $test_id = intval($_GET['test_id']);

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