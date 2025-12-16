<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Ambil modal kas
$qManual = mysqli_query($koneksi, "SELECT modal FROM kas_manual WHERE id=1");
$dManual = mysqli_fetch_assoc($qManual);
$modal_kas = isset($dManual['modal']) ? (int)$dManual['modal'] : 0;

// Total masuk dari pembayaran
$qMasuk = mysqli_query($koneksi, "SELECT SUM(jumlah) AS total_masuk FROM pembayaran");
$dMasuk = mysqli_fetch_assoc($qMasuk);
$total_masuk = (int)$dMasuk['total_masuk'];

// Total keluar dari kas_transaksi
$qKeluar = mysqli_query($koneksi, "SELECT SUM(jumlah) AS total_keluar FROM kas_transaksi");
$dKeluar = mysqli_fetch_assoc($qKeluar);
$total_keluar = (int)$dKeluar['total_keluar'];

// Kas saat ini
$uang_kas = $modal_kas + $total_masuk - $total_keluar;

// Ambil data transaksi (masuk + keluar)
$qMasukHarian = mysqli_query($koneksi,"
    SELECT DATE(created_at) AS tanggal, 'masuk' AS keterangan,
           SUM(jumlah) AS jumlah, NULL AS paraf
    FROM pembayaran
    GROUP BY DATE(created_at)
");

$qKeluarData = mysqli_query($koneksi,"
    SELECT tanggal, keterangan, jumlah, paraf
    FROM kas_transaksi
");

$data = [];
while($d = mysqli_fetch_assoc($qMasukHarian)) $data[] = $d;
while($d = mysqli_fetch_assoc($qKeluarData)) $data[] = $d;

// Urutkan terbaru di atas
usort($data, function($a,$b){
    return strtotime($b['tanggal']) - strtotime($a['tanggal']);
});

// Fungsi format tanggal
function tgl_indo($tanggal){
    $hari = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu',
             'Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
    $bulan = ['January'=>'Januari','February'=>'Februari','March'=>'Maret','April'=>'April',
              'May'=>'Mei','June'=>'Juni','July'=>'Juli','August'=>'Agustus','September'=>'September',
              'October'=>'Oktober','November'=>'November','December'=>'Desember'];
    $tgl = date('l, d F Y', strtotime($tanggal));
    return strtr(strtr($tgl,$hari),$bulan);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Kas Saat Ini</title>
 <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=3, user-scalable=yes">
    <link rel="stylesheet" href="style.css">
    <script src="datetime.js" defer></script>
</head>
<body>

<div class="container-members">
<h2 class="judul-kas">KAS SAAT INI</h2>


<div class="box-kas">
    <h1><?= number_format($uang_kas) ?></h1>
    <a href="edit_kas.php" class="btn-edit">Edit Kas</a>
    <p>Keluar Masuk Uang Kas</p>
</div>

<div class="wrap-tabel-transaksi">
    <div class="tabel-wrap">
        <table border="1" cellpadding="10" width="100%">
            <tr>
                <th>No</th>
                <th>Hari, Tanggal</th>
                <th>Keterangan</th>
                <th>Jumlah</th>
                <th>Paraf</th>
                <th>Riwayat</th>
            </tr>

            <?php 
            $no = count($data);
            foreach($data as $d):
                $tgl_fix = date('Y-m-d', strtotime($d['tanggal']));
            ?>
            <tr>
    <td><?= $no-- ?></td>
    <td><?= tgl_indo($tgl_fix) ?></td>
    <td class="<?= $d['keterangan']=='masuk' ? 'ket-masuk' : 'ket-keluar' ?>">
    <?= $d['keterangan']=='masuk' ? 'Masuk' : 'Keluar' ?>
</td>

    <td><?= number_format($d['jumlah']) ?></td>
    <td class="paraf-col"><img src="upload_paraf/ttd.png" width="80" style="border-radius:8px;"></td>
    <td>
    <?php if($d['keterangan']=='masuk'): ?>
        <a href="kas_detail.php?tanggal=<?= $tgl_fix ?>" class="btn-riwayat btn-hijau">Lihat</a>
    <?php else: ?>
        <a href="riwayat_pengeluaran.php?tanggal=<?= $tgl_fix ?>" class="btn-riwayat btn-merah">Lihat</a>
    <?php endif; ?>
</td>

</tr>

            <?php endforeach; ?>
        </table>
    </div>
</div>

<div class="menu-transaksi">
    <a href="dashboard.php" class="btn-kembali">Kembali</a>
    <a href="pengeluaran.php" class="btn-submit">Tambah Pengeluaran</a>
    
<div class="datetime-bar"></div>
    </div>
    
     <a href="saldo_lain.php" class="btn-submit">Saldo Lain</a>
    <br><br><br><br>  <br><br><br><br>  
</body>
</html>
