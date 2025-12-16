<?php
// ==============================
// ✅ AKTIFKAN ERROR BIAR KELIATAN JIKA ADA MASALAH
// ==============================
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ==============================
// ✅ PANGGIL KONEKSI DATABASE
// ==============================
include 'koneksi.php';

// ==============================
// ✅ AMBIL DATA DARI FORM EDIT
// ==============================
$id           = $_POST['id'];
$nama         = $_POST['nama'];
$panggilan    = $_POST['panggilan'];
$kelas        = $_POST['kelas'];
$jurusan      = $_POST['jurusan'];
$gender       = $_POST['gender'];
$tanggal_join = $_POST['tanggal_join'];

// ==============================
// ✅ QUERY UPDATE DATA MEMBER
// ==============================
$query = "
UPDATE members SET
    nama='$nama',
    panggilan='$panggilan',
    kelas='$kelas',
    jurusan='$jurusan',
    gender='$gender',
    tanggal_join='$tanggal_join'
WHERE id='$id'
";

// ==============================
// ✅ EKSEKUSI QUERY
// ==============================
mysqli_query($koneksi, $query);

// ==============================
// ✅ BALIK KE HALAMAN MEMBERS
// ==============================
header("Location: members.php");
exit;
