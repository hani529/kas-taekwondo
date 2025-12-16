<?php
session_start();
include 'koneksi.php';

// input kas baru
$kas_baru = (int)$_POST['kas_baru'];

// ===== HITUNG KAS LAMA =====
$qManual = mysqli_query($koneksi,"SELECT modal FROM kas_manual WHERE id=1");
$modal = (int)mysqli_fetch_assoc($qManual)['modal'];

$qMasuk = mysqli_query($koneksi,"SELECT SUM(jumlah) total FROM pembayaran");
$total_masuk = (int)mysqli_fetch_assoc($qMasuk)['total'];

$qKeluar = mysqli_query($koneksi,"SELECT SUM(jumlah) total FROM kas_transaksi");
$total_keluar = (int)mysqli_fetch_assoc($qKeluar)['total'];

$kas_lama = $modal + $total_masuk - $total_keluar;

// ===== HITUNG SELISIH =====
$selisih = $kas_baru - $kas_lama;

// ===== SIMPAN PENYESUAIAN =====
mysqli_query($koneksi,"
INSERT INTO kas_transaksi
(tanggal, keterangan, jumlah, jenis)
VALUES
(CURDATE(), 'Reset Kas', $selisih, 'penyesuaian')
");

header("Location: kas.php");
