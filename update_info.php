<?php
session_start();
require_once '../../config/database.php';//thay đổi đường dẫn

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

$user = $_SESSION['user'];

if (isset($_POST['update'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];

    $sql = "UPDATE users SET fullname=?, email=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $fullname, $email, $user['id']);
    if ($stmt->execute()) {
        $_SESSION['user']['fullname'] = $fullname;
        $_SESSION['user']['email'] = $email;
        echo "Cập nhật thành công!";
    } else {
        echo "Lỗi: " . $stmt->error;
    }
}
?>

<h2>Cập nhật thông tin</h2>
<form method="post">
    Họ tên: <input type="text" name="fullname" value="<?= $user['fullname'] ?>" required><br>
    Email: <input type="email" name="email" value="<?= $user['email'] ?>" required><br>
    <button name="update">Cập nhật</button>
</form>
<a href="profile.php">◀--- Quay lại</a><!-- sửa dấu mũi tên -->
