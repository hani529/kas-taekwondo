<?php
session_start();

$user = $_POST['username'];
$pass = $_POST['password'];

if ($user == 'honey' && $pass == '132') {
    $_SESSION['role'] = 'bendahara';
    header("Location: dashboard.php");
} else {
    $_SESSION['role'] = 'member';
    header("Location: dashboard.php");
}
