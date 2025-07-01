<?php
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Ho_Chi_Minh');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['join_test'])) {
    $share_code = strtoupper(trim($_POST['share_code']));

    $stmt = $conn->prepare("SELECT * FROM tests WHERE share_code = ?");
    $stmt->bind_param("s", $share_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $test = $result->fetch_assoc();

    if (!$test) {
        $_SESSION['error'] = "❌ Mã đề không tồn tại.";
        header("Location: ../views/testtaker/dashboard_taker.php");
        exit();
    }

    // Lưu thông tin đề thi và thời gian để dùng ở view
    $_SESSION['selected_test'] = $test;
    $_SESSION['time_check'] = [
        'now' => time(),
        'start' => strtotime($test['open_time']),
        'end' => strtotime($test['close_time'])
    ];

    // Chuyển sang chi tiết đề
    header("Location: ../views/testtaker/detail_test_user.php");
    exit();
}

// ==== DANH SÁCH BÀI THI ĐÃ THAM GIA ====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['view_submitted_tests'])) {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'taker') {
        header("Location: ../login.php");
        exit();
    }

    $user_id = $_SESSION['user']['id'];

    $stmt = $conn->prepare("
        SELECT t.id AS test_id, t.title, r.score, r.submitted_at
        FROM test_responses r
        JOIN tests t ON r.test_id = t.id
        WHERE r.test_taker_id = ?
        ORDER BY r.submitted_at DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $_SESSION['submitted_tests'] = $result->fetch_all(MYSQLI_ASSOC);

    header("Location: ../views/testtaker/list_user_results.php");
    exit();
}

// ==== HIỂN THỊ CHI TIẾT BÀI ĐÃ LÀM ====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['view_test_detail'])) {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'taker') {
        header("Location: ../login.php");
        exit();
    }

    $test_id = intval($_POST['test_id']);
    $user_id = $_SESSION['user']['id'];

    // Lấy thông tin đề thi
    $stmt = $conn->prepare("SELECT * FROM tests WHERE id = ?");
    $stmt->bind_param("i", $test_id);
    $stmt->execute();
    $test = $stmt->get_result()->fetch_assoc();

    // Lấy điểm số
    $stmt2 = $conn->prepare("SELECT score FROM test_responses WHERE test_id = ? AND test_taker_id = ?");
    $stmt2->bind_param("ii", $test_id, $user_id);
    $stmt2->execute();
    $score_result = $stmt2->get_result()->fetch_assoc();
    $score = $score_result['score'] ?? 'Chưa chấm';

    // Lấy câu hỏi và đáp án đúng
    $stmt3 = $conn->prepare("SELECT * FROM questions WHERE test_id = ?");
    $stmt3->bind_param("i", $test_id);
    $stmt3->execute();
    $questions = $stmt3->get_result()->fetch_all(MYSQLI_ASSOC);

    // Lấy đáp án người dùng
    $stmt4 = $conn->prepare("SELECT question_id, selected_option FROM question_responses WHERE test_id = ? AND user_id = ?");
    $stmt4->bind_param("ii", $test_id, $user_id);
    $stmt4->execute();
    $user_answers_raw = $stmt4->get_result()->fetch_all(MYSQLI_ASSOC);

    $user_answers = [];
    foreach ($user_answers_raw as $ua) {
        $user_answers[$ua['question_id']] = $ua['selected_option'];
    }

    // Lưu session
    $_SESSION['detail_questions'] = $questions;
    $_SESSION['user_answers'] = $user_answers;
    $_SESSION['test_info'] = $test;
    $_SESSION['test_score'] = $score;

    header("Location: ../views/testtaker/view_test_detail.php");
    exit();
}


?>
