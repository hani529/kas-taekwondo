<?php
// ✅ nyalakan error biar kalau ada salah kelihatan
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ panggil koneksi database
include 'koneksi.php';

// ✅ ambil data dari form
$nama = $_POST['nama'];
$panggilan = $_POST['panggilan'];
$kelas = $_POST['kelas'];
$jurusan = $_POST['jurusan'];
$gender = $_POST['gender'];
$tanggal = $_POST['tanggal_join'];

// ✅ simpan ke database
$query = "INSERT INTO members (nama, panggilan, kelas, jurusan, gender, tanggal_join)
VALUES ('$nama','$panggilan','$kelas','$jurusan','$gender','$tanggal')";

mysqli_query($koneksi, $query);

// ✅ balik ke halaman member
header("Location: members.php");
exit;
