<?php
session_start();

if ($_SESSION['role'] != 'bendahara') {
    echo "Akses ditolak!";
    exit;
}
?>
