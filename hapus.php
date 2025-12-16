<?php
session_start();
include 'koneksi.php';

// wajib login
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// hanya bendahara boleh hapus
if ($_SESSION['role'] !== 'bendahara') {
    die('Hanya bendahara yang bisa menghapus data');
}

$id = $_GET['id'] ?? 0;

if ($id == 0) {
    die('ID tidak valid');
}

// hapus data
mysqli_query($koneksi, "DELETE FROM members WHERE id='$id'");

// kembali
header("Location: members.php");
exit;
