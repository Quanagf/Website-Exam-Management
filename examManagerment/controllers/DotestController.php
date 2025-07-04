<?php
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ HIỂN THỊ TRANG LÀM BÀI
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $test_id = intval($_GET['id']);
    $user = $_SESSION['user'] ?? null;

    if (!$user || $user['role'] !== 'taker') {
        $_SESSION['error'] = "Bạn không có quyền truy cập.";
        header("Location: ../login.php");
        exit();
    }

    $user_id = intval($user['id']);

    // ✅ Kiểm tra status
    $stmt_check = $conn->prepare("SELECT status FROM test_responses WHERE test_id = ? AND test_taker_id = ?");
    $stmt_check->bind_param("ii", $test_id, $user_id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['status'] === 'completed') {
            $_SESSION['error'] = "⚠️ Bạn đã hoàn thành bài thi. Không thể làm lại!";
            header("Location: ../views/testtaker/dashboard_taker.php");
            exit();
        }
        // Nếu status = pending => cho phép làm tiếp
    }

    // Lấy câu hỏi
    $stmt = $conn->prepare("SELECT * FROM questions WHERE test_id = ?");
    $stmt->bind_param("i", $test_id);
    $stmt->execute();
    $questions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    include '../views/testtaker/do_test.php';
    exit();
}

// ✅ NỘP BÀI
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_test'])) {
    $user = $_SESSION['user'] ?? null;
    if (!$user || $user['role'] !== 'taker') {
        $_SESSION['error'] = "Bạn không có quyền truy cập.";
        header("Location: ../login.php");
        exit();
    }

    $user_id = intval($user['id']);
    $test_id = intval($_POST['test_id']);
    $answers = $_POST['answers'] ?? [];

    // ✅ Kiểm tra status hiện tại trong test_responses
    $stmt_check = $conn->prepare("SELECT id, status FROM test_responses WHERE test_id = ? AND test_taker_id = ?");
    $stmt_check->bind_param("ii", $test_id, $user_id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        $existing = $result->fetch_assoc();
        if ($existing['status'] === 'completed') {
            $_SESSION['error'] = "⚠️ Bạn đã hoàn thành bài thi trước đó. Không thể nộp lại.";
            header("Location: ../views/testtaker/dashboard_taker.php");
            exit();
        }
    }

    // Lấy danh sách câu hỏi & đáp án đúng
    $stmt = $conn->prepare("SELECT id, correct FROM questions WHERE test_id = ?");
    $stmt->bind_param("i", $test_id);
    $stmt->execute();
    $questions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $correct_count = 0;
    $total_questions = count($questions);

    foreach ($questions as $q) {
        $qid = $q['id'];
        $correct = strtoupper($q['correct']);
        $user_answer = strtoupper($answers[$qid] ?? '');
        if ($user_answer === $correct) {
            $correct_count++;
        }
    }

    $score = round(($correct_count / max($total_questions, 1)) * 10, 2);

    // ✅ Ghi điểm
    if ($result->num_rows > 0) {
        // Đã có và còn ở trạng thái pending → cập nhật
        $stmt_update = $conn->prepare("
            UPDATE test_responses 
            SET score = ?, submitted_at = NOW(), status = 'completed'
            WHERE test_id = ? AND test_taker_id = ?
        ");
        $stmt_update->bind_param("dii", $score, $test_id, $user_id);
        $stmt_update->execute();
    } else {
        // Chưa có bản ghi → thêm mới
        $stmt_insert = $conn->prepare("
            INSERT INTO test_responses (test_id, test_taker_id, score, submitted_at, status) 
            VALUES (?, ?, ?, NOW(), 'completed')
        ");
        $stmt_insert->bind_param("iid", $test_id, $user_id, $score);
        $stmt_insert->execute();
    }

    // ✅ Lưu từng câu trả lời
    foreach ($answers as $question_id => $selected_option) {
        $question_id = intval($question_id);
        $selected_option = strtoupper(trim($selected_option));
        if (!in_array($selected_option, ['A', 'B', 'C', 'D'])) continue;

        $sql = "INSERT INTO question_responses (user_id, test_id, question_id, selected_option) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiis", $user_id, $test_id, $question_id, $selected_option);
        $stmt->execute();
    }

    // 👉 Chuyển đến trang kết quả
    header("Location: DotestController.php?action=result&test_id=$test_id");
    exit();
}


// ✅ XEM ĐIỂM
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'result') {
    $user_id = $_SESSION['user']['id'];
    $test_id = intval($_GET['test_id'] ?? 0);

    $stmt = $conn->prepare("SELECT id, correct FROM questions WHERE test_id = ?");
    $stmt->bind_param("i", $test_id);
    $stmt->execute();
    $questions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $total_questions = count($questions);

    $stmt2 = $conn->prepare("SELECT question_id, selected_option FROM question_responses WHERE user_id = ? AND test_id = ?");
    $stmt2->bind_param("ii", $user_id, $test_id);
    $stmt2->execute();
    $user_answers_raw = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

    $answer_map = [];
    foreach ($user_answers_raw as $a) {
        $answer_map[$a['question_id']] = strtoupper($a['selected_option']);
    }

    $correct_count = 0;
    foreach ($questions as $q) {
        $qid = $q['id'];
        $correct = strtoupper($q['correct']);
        if (($answer_map[$qid] ?? '') === $correct) $correct_count++;
    }

    $score = round(($correct_count / max($total_questions, 1)) * 10, 2);

    include '../views/testtaker/view_result.php';
    exit();
}

// ✅ XEM CHI TIẾT
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'result_detail') {
    $user_id = $_SESSION['user']['id'];
    $test_id = intval($_GET['test_id'] ?? 0);

    $stmt = $conn->prepare("SELECT * FROM questions WHERE test_id = ?");
    $stmt->bind_param("i", $test_id);
    $stmt->execute();
    $questions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $stmt2 = $conn->prepare("SELECT question_id, selected_option FROM question_responses WHERE user_id = ? AND test_id = ?");
    $stmt2->bind_param("ii", $user_id, $test_id);
    $stmt2->execute();
    $user_answers_raw = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

    $user_answers = [];
    foreach ($user_answers_raw as $ua) {
        $user_answers[$ua['question_id']] = strtoupper($ua['selected_option']);
    }

    include '../views/testtaker/view_result_detail.php';
    exit();
}