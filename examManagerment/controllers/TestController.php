<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập và quyền
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'creator') {
    header("Location: ../login.php");
    exit();
}

// Hàm tạo mã chia sẻ ngẫu nhiên
function generateShareCode($length = 6) {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $code;
}

// ✅ Xử lý tạo đề
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $duration = intval($_POST['duration']);
    $creator_id = $_SESSION['user']['id'];
    $open_now = isset($_POST['open_now']);

    if ($open_now) {
        $open_time = date('Y-m-d H:i:s');
        $close_time = $_POST['close_time'] ?? null;
    } else {
        $open_time = $_POST['open_time'] ?? null;
        $close_time = $_POST['close_time'] ?? null;
    }

    if (empty($title) || $duration <= 0 || !$close_time || (!$open_now && !$open_time)) {
        $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin và thời gian hợp lệ!";
        header("Location: ../views/testcreator/create_test.php");
        exit();
    }

    do {
        $share_code = generateShareCode();
        $checkStmt = $conn->prepare("SELECT id FROM tests WHERE test_creator_id = ?");
        $checkStmt->bind_param("s", $share_code);
        $checkStmt->execute();
        $checkStmt->store_result();
    } while ($checkStmt->num_rows > 0);

    $stmt = $conn->prepare("INSERT INTO tests (test_creator_id, title, description, duration, share_code, open_time, close_time) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ississs", $creator_id, $title, $desc, $duration, $share_code, $open_time, $close_time);

    if ($stmt->execute()) {
        $_SESSION['success'] = "✅ Đã tạo đề!";
        header("Location: ../views/testcreator/dashboard_testcreator.php");
    } else {
        $_SESSION['error'] = "❌ Lỗi tạo đề: " . $stmt->error;
        header("Location: ../views/testcreator/create_test.php");
    }
    exit();
}

// ✅ Xử lý xoá đề
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && isset($_POST['test_id'])) {
    $test_id = intval($_POST['test_id']);

    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'creator') {
        header("Location: ../login.php");
        exit();
    }

    $check = $conn->prepare("SELECT id FROM tests WHERE id = ? AND test_creator_id = ?");
    $check->bind_param("ii", $test_id, $_SESSION['user']['id']);
    $check->execute();
    $check->store_result();

    if ($check->num_rows === 0) {
        $_SESSION['error'] = "❌ Bạn không có quyền xoá đề này.";
        header("Location: ../views/testcreator/dashboard_testcreator.php");
        exit();
    }

    $conn->query("DELETE FROM questions WHERE test_id = $test_id");

    $stmt = $conn->prepare("DELETE FROM tests WHERE id = ?");
    $stmt->bind_param("i", $test_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "✅ Đã xoá đề thi!";
    } else {
        $_SESSION['error'] = "❌ Lỗi khi xoá: " . $stmt->error;
    }

    header("Location: ../views/testcreator/dashboard_testcreator.php");
    exit();
}

// ✅ Xử lý sửa đề
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit']) && isset($_POST['test_id'])) {
    $test_id = intval($_POST['test_id']);
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $duration = intval($_POST['duration']);
    $open_time = !empty($_POST['open_time']) ? $_POST['open_time'] : null;
    $close_time = !empty($_POST['close_time']) ? $_POST['close_time'] : null;

    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'creator') {
        header("Location: ../login.php");
        exit();
    }

    if (empty($title) || $duration <= 0 || !$close_time || (!$open_time && empty($_POST['open_now']))) {
        $_SESSION['error'] = "❌ Vui lòng nhập đầy đủ thông tin hợp lệ!";
        header("Location: ../views/testcreator/edit_test.php?id=" . $test_id);
        exit();
    }

    if (isset($_POST['open_now'])) {
        $open_time = date('Y-m-d H:i:s');
    }

    $check = $conn->prepare("SELECT id FROM tests WHERE id = ? AND test_creator_id = ?");
    $check->bind_param("ii", $test_id, $_SESSION['user']['id']);
    $check->execute();
    $check->store_result();

    if ($check->num_rows === 0) {
        $_SESSION['error'] = "❌ Bạn không có quyền sửa đề này.";
        header("Location: ../views/testcreator/dashboard_testcreator.php");
        exit();
    }

    $stmt = $conn->prepare("UPDATE tests SET title = ?, description = ?, duration = ?, open_time = ?, close_time = ? WHERE id = ?");
    $stmt->bind_param("ssissi", $title, $desc, $duration, $open_time, $close_time, $test_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "✅ Đã cập nhật đề!";
    } else {
        $_SESSION['error'] = "❌ Lỗi khi cập nhật: " . $stmt->error;
    }

    header("Location: ../views/testcreator/detail_test.php?id=$test_id");
    exit();
}

// ✅ Xử lý thống kê khi bài thi kết thúc
if (isset($_GET['statistics']) && isset($_GET['id'])) {
    $test_id = intval($_GET['id']);

    // Lấy thông tin đề thi
    $stmt = $conn->prepare("SELECT * FROM tests WHERE id = ?");
    $stmt->bind_param("i", $test_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $test = $result->fetch_assoc();

    if (!$test || !isset($test['close_time'])) {
        $_SESSION['error'] = "Không tìm thấy đề thi hoặc thiếu thông tin thời gian kết thúc.";
        header("Location: ../views/testcreator/dashboard_testcreator.php");
        exit();
    }

    $now = time();
    $end_time = strtotime($test['close_time']);

    if ($now < $end_time) {
        $_SESSION['error'] = "Đề thi vẫn đang mở. Thống kê chỉ hiển thị sau khi kết thúc.";
        header("Location: ../views/testcreator/detail_test.php?id=" . $test_id);
        exit();
    }


    // Truy vấn thống kê
    $stmt = $conn->prepare("
        SELECT COUNT(*) as total,
            AVG(score) as average_score,
            MAX(score) as max_score,
            MIN(score) as min_score,
            SUM(score >= 5) as passed,
            SUM(score < 5) as failed
        FROM test_responses
        WHERE test_id = ?
    ");

    

    if (!$stmt) {
        die("Lỗi prepare SQL: " . $conn->error);
    }

    $stmt->bind_param("i", $test_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    // Truy vấn danh sách thí sinh đã làm bài
    $stmt_users = $conn->prepare("
        SELECT u.fullname, r.score, r.submitted_at
        FROM test_responses r
        JOIN users u ON r.test_taker_id = u.id
        WHERE r.test_id = ?
        ORDER BY r.submitted_at ASC
    ");
    if (!$stmt_users) {
        die("Lỗi prepare danh sách thí sinh: " . $conn->error);
    }

    $stmt_users->bind_param("i", $test_id);
    $stmt_users->execute();
    $user_results = $stmt_users->get_result()->fetch_all(MYSQLI_ASSOC);


    $_SESSION['statistics'] = $result; // thống kê tổng
    $_SESSION['test_title'] = $test['title'];
    $_SESSION['user_results'] = $user_results;
    header("Location: ../views/testcreator/statistics.php");
    exit();



}

// 🎯 RESET GIỮ NGUYÊN THỜI GIAN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'reset_attempt') {
    $testId = intval($_POST['test_id']);
    $userId = intval($_POST['user_id']);

<<<<<<< HEAD
    $stmt = $conn->prepare("UPDATE test_responses 
        SET status = 'pending', submitted_at = NULL, score = NULL, force_reset_time = 0
        WHERE test_id = ? AND test_taker_id = ?");
=======
    // Lấy thời gian kết thúc của bài kiểm tra từ cơ sở dữ liệu
    $stmt = $conn->prepare("SELECT end_time FROM tests WHERE id = ?");
    $stmt->bind_param("i", $testId);
    $stmt->execute();
    $result = $stmt->get_result();
    $test = $result->fetch_assoc();
    
    // Kiểm tra nếu thời gian kết thúc đã qua
    $endTime = strtotime($test['end_time']);
    $currentTime = time(); // Thời gian hiện tại

    if ($currentTime > $endTime) {
        $_SESSION['error'] = "❌ Thời gian kết thúc bài kiểm tra đã qua. Bạn không thể làm lại bài kiểm tra.";
        header("Location: ../views/testcreator/detail_test.php?id=$testId");
        exit();
    }

    // Cập nhật status = pending và xoá thời gian + điểm
    $stmt = $conn->prepare("UPDATE test_responses SET status = 'pending', submitted_at = NULL, score = NULL 
                            WHERE test_id = ? AND test_taker_id = ?");
>>>>>>> f9c5ad8447f10d1fc97a7105e6dde39e12371aef
    $stmt->bind_param("ii", $testId, $userId);
    $stmt->execute();

    $stmt2 = $conn->prepare("DELETE FROM question_responses WHERE test_id = ? AND user_id = ?");
    $stmt2->bind_param("ii", $testId, $userId);
    $stmt2->execute();

    $_SESSION['success'] = "✅ Đã cho phép thí sinh làm lại (giữ nguyên thời gian).";
    header("Location: ../views/testcreator/detail_test.php?id=$testId");
    exit();
}

// 🎯 RESET VÀ RESET THỜI GIAN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'reset_attempt_resettime') {
    $testId = intval($_POST['test_id']);
    $userId = intval($_POST['user_id']);

    $stmt = $conn->prepare("UPDATE test_responses 
        SET status = 'pending', submitted_at = NULL, score = NULL, force_reset_time = 1
        WHERE test_id = ? AND test_taker_id = ?");
    $stmt->bind_param("ii", $testId, $userId);
    $stmt->execute();

    $stmt2 = $conn->prepare("DELETE FROM question_responses WHERE test_id = ? AND user_id = ?");
    $stmt2->bind_param("ii", $testId, $userId);
    $stmt2->execute();

    $_SESSION['success'] = "✅ Đã cho phép thí sinh làm lại và reset thời gian.";
    header("Location: ../views/testcreator/detail_test.php?id=$testId");
    exit();
}