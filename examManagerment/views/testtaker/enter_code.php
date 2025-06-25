<?php
session_start();
require_once '../config/database.php';

if ($_SESSION['user']['role'] !== 'taker') {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['enter'])) {
    $code = $_POST['code'];
    $sql = "SELECT * FROM invitation_codes WHERE code=? AND status='open'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        header("Location: take_test.php?test_id=" . $row['test_id']);
    } else {
        echo "Mã không hợp lệ hoặc đã đóng!";
    }
}
?>

<h2>Nhập mã thi</h2>
<form method="post">
    <input type="text" name="code" required placeholder="Mã bài thi">
    <button name="enter">Bắt đầu</button>
</form>
