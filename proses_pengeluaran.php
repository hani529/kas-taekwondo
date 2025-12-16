<?php
session_start();
include 'koneksi.php';

if ($_SESSION['role'] !== 'bendahara') {
    die('Akses ditolak');
}

if (!isset($_POST['jumlah']) || !isset($_POST['keterangan'])) {
    die("ERROR: Data pengeluaran tidak lengkap!");
}

$jumlah = (int)$_POST['jumlah'];
$keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
$detail = isset($_POST['detail_belanja']) ? mysqli_real_escape_string($koneksi, $_POST['detail_belanja']) : '';
$tanggal = date('Y-m-d H:i:s');
$paraf = 'paraf_ketua.png'; // nama file paraf tetap

mysqli_query($koneksi, "INSERT INTO pengeluaran (tanggal, jumlah, keterangan, detail_belanja, paraf) 
VALUES (NOW(), '$jumlah', '$keterangan', '$detail_belanja', '$paraf')");

// 1️⃣ Masuk ke tabel pengeluaran
mysqli_query($koneksi, "INSERT INTO pengeluaran (tanggal, jumlah, keterangan, detail_belanja) 
                        VALUES ('$tanggal', $jumlah, '$keterangan', '$detail')");

// 2️⃣ Masuk juga ke tabel kas_transaksi
mysqli_query($koneksi, "INSERT INTO kas_transaksi (tanggal, keterangan, jumlah, paraf) 
                        VALUES ('$tanggal', '$keterangan', $jumlah, NULL)");

header("Location: riwayat_pengeluaran.php");
exit();
?>
