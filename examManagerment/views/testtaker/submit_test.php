
<?php
session_start();
require_once '../config/database.php';

if ($_SESSION['user']['role'] !== 'taker') {
    header("Location: ../index.php");
    exit();
}

$test_id = $_POST['test_id'];
$test_taker_id = $_SESSION['user']['id'];
$answers = $_POST['answers'] ?? [];

$totalScore = 0;

// Lưu bài thi
$sql = "INSERT INTO test_responses (test_id, test_taker_id, score) VALUES (?, ?, 0)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $test_id, $test_taker_id);
$stmt->execute();
$response_id = $conn->insert_id;

// Duyệt câu hỏi
foreach ($answers as $question_id => $option_id) {
    $sql = "SELECT is_correct FROM options WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $option_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $is_correct = $res['is_correct'];

    // Lấy điểm câu hỏi
    $stmt = $conn->prepare("SELECT score FROM questions WHERE id=?");
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $score = $stmt->get_result()->fetch_assoc()['score'];

    if ($is_correct) $totalScore += $score;

    // Lưu câu trả lời
    $insert = $conn->prepare("INSERT INTO question_responses (test_response_id, question_id, selected_option_id, is_correct)
                              VALUES (?, ?, ?, ?)");
    $insert->bind_param("iiii", $response_id, $question_id, $option_id, $is_correct);
    $insert->execute();
}

// Cập nhật điểm
$stmt = $conn->prepare("UPDATE test_responses SET score=? WHERE id=?");
$stmt->bind_param("di", $totalScore, $response_id);
$stmt->execute();

echo "Bài làm đã được nộp! Tổng điểm: <strong>$totalScore</strong><br>";
echo "<a href='view_history.php'>Xem lịch sử bài làm</a>";
