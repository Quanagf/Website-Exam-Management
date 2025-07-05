<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . '/../config/database.php');

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

// QuestionmanController.php
if (isset($_GET['test_id'])) {
    $test_id = intval($_GET['test_id']);
    $test_query = $conn->query("SELECT title FROM tests WHERE id = $test_id");
    $test = $test_query->fetch_assoc();
    $questions = $conn->query("SELECT * FROM questions WHERE test_id = $test_id ORDER BY id ASC");
}

?>