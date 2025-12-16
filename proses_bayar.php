<?php
include 'koneksi.php'; // ✅ Menghubungkan ke database

$member_id = $_POST['member_id']; // ✅ Ambil ID member dari form
$nominal   = $_POST['nominal']; // ✅ Ambil nominal dari form

$nominal = str_replace(',', '', $nominal); // ✅ Hapus koma dari nominal

// Ambil bulan join member
$qMember = mysqli_query($koneksi, "SELECT tanggal_join FROM members WHERE id='$member_id'");
$dMember = mysqli_fetch_assoc($qMember);

$bulanJoin = 8; // default Agustus
$tahunJoin = date('Y');

if (!empty($dMember['tanggal_join']) && $dMember['tanggal_join'] !== '0000-00-00') {
    $bulanJoin = (int)date('n', strtotime($dMember['tanggal_join']));
    $tahunJoin = (int)date('Y', strtotime($dMember['tanggal_join']));
}

$bulan = $bulanJoin;
$tahun = $tahunJoin;


// ✅ Simpan data pembayaran ke tabel pembayaran
mysqli_query($koneksi, "
INSERT INTO pembayaran (member_id, bulan, tahun, jumlah)
VALUES ('$member_id','$bulan','$tahun','$nominal')
");

// ✅ Setelah simpan, kembali ke dashboard
header("Location: dashboard.php");
exit(); // ✅ Stop script
?>
