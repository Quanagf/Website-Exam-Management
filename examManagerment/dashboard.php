<?php
session_start();
require_once './config/database.php';

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];
$role = $user['role'];
