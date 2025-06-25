<?php
session_start();
require_once '../config/database.php';

if ($_SESSION['user']['role'] !== 'taker') {
    header("Location: ../index.php");
    exit();
}

$test_id = $_GET['test_id'] ?? 0;

// Lấy đề thi và câu hỏi
$sql = "SELECT * FROM tests WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $test_id);
$stmt->execute();
$test = $stmt->get_result()->fetch_assoc();

$sql = "SELECT * FROM questions WHERE test_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $test_id);
$stmt->execute();
$questions = $stmt->get_result();
?>

<h2><?= htmlspecialchars($test['title']) ?></h2>
<form method="post" action="submit_test.php">
    <input type="hidden" name="test_id" value="<?= $test_id ?>">
    <?php while ($q = $questions->fetch_assoc()): ?>
        <p><strong><?= htmlspecialchars($q['content']) ?></strong></p>
        <?php
        $optStmt = $conn->prepare("SELECT * FROM options WHERE question_id=?");
        $optStmt->bind_param("i", $q['id']);
        $optStmt->execute();
        $options = $optStmt->get_result();
        while ($opt = $options->fetch_assoc()):
        ?>
            <label>
                <input type="radio" name="answers[<?= $q['id'] ?>]" value="<?= $opt['id'] ?>" required>
                <?= htmlspecialchars($opt['content']) ?>
            </label><br>
        <?php endwhile; ?>
    <?php endwhile; ?>
    <button type="submit" name="submit_test">Nộp bài</button>
</form>
