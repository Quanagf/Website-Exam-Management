<?php
session_start();
require_once '../config/database.php';

if ($_SESSION['user']['role'] !== 'creator') {
    header("Location: ../index.php");
    exit();
}

function generateRandomCode($length = 8) {
    return strtoupper(substr(md5(rand()), 0, $length));
}

if (isset($_POST['generate'])) {
    $test_id = $_POST['test_id'];
    $code = generateRandomCode();

    $sql = "INSERT INTO invitation_codes (test_id, code) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $test_id, $code);

    if ($stmt->execute()) {
        echo "Mã mời: <strong>$code</strong>";
    } else {
        echo "Lỗi tạo mã: " . $stmt->error;
    }
}
?>

<h2>Tạo mã mời</h2>
<form method="post">
    ID đề thi: <input type="number" name="test_id" required><br>
    <button name="generate">Tạo mã</button>
</form>