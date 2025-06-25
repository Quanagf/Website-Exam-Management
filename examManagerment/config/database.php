<?php
$host = "localhost";
$user = "root";
$pass = "Quan11092005@";
$dbname = "exam_management";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>