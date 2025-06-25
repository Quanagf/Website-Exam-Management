<?php
session_start();
require_once 'controllers/AuthController.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
</head>
<body>
    <h2>Đăng ký</h2>
    <?php
    include "views/register_form.php";
    ?>
</body>
</html>