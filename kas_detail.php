<?php
session_start();
include 'koneksi.php';

// ✅ FUNGSI TANGGAL INDONESIA
function tgl_indo($tanggal){
    $hari = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];

    $bulan = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    ];

    $tgl = date('l, d F Y H:i', strtotime($tanggal));
    $tgl = strtr($tgl, $hari);
    $tgl = strtr($tgl, $bulan);

    return $tgl;
}


if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['tanggal'])) {
    die("Tanggal tidak valid");
}

$tanggal = $_GET['tanggal'];

/* =========================
   ✅ AMBIL DETAIL PEMBAYARAN HARI ITU
========================= */
$data = mysqli_query($koneksi, "
    SELECT m.nama, p.jumlah, p.created_at
    FROM pembayaran p
    JOIN members m ON p.member_id = m.id
    WHERE DATE(p.created_at) = '$tanggal'
    ORDER BY p.created_at DESC
");

if (!$data) {
    die("Query ERROR: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Pemasukan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=3, user-scalable=yes">
    <link rel="stylesheet" href="style.css">
    <script src="datetime.js" defer></script>
</head>
<body>

<div class="container-members">

    <h2>Riwayat Pemasukan</h2><br>
    <p>Tanggal: <?= strtr(date('l, d F Y', strtotime($tanggal)), [
    'Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu',
    'Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu',
    'January'=>'Januari','February'=>'Februari','March'=>'Maret','April'=>'April',
    'May'=>'Mei','June'=>'Juni','July'=>'Juli','August'=>'Agustus',
    'September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Desember'
]); ?></p>



    <div class="wrap-tabel-transaksi">
        <div class="tabel-wrap">
        <table border="1" cellpadding="10" width="100%">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jumlah</th>
                <th>Jam</th>
                <th>Catatan</th>
            </tr>

            <?php 
           $jumlah_data = mysqli_num_rows($data);
$no = $jumlah_data;
            $total = 0;
            while ($d = mysqli_fetch_assoc($data)) { 
                $total += $d['jumlah'];
            ?>
            <tr>
                <td><?= $no-- ?></td>
                <td><?= $d['nama'] ?></td>
                <td><?= number_format($d['jumlah']) ?></td>
               <td><?= date('H:i', strtotime($d['created_at'])) ?></td>


                <td>Bayar Kas</td>
            </tr>
            <?php } ?>

            <tr>
                <td colspan="2"><b>TOTAL</b></td>
                <td colspan="3"><b><?= number_format($total) ?></b></td>
            </tr>

        </table>
        </div>
    </div>

    <div class="menu-transaksi">
        <a href="kas.php" class="btn-kembali">Kembali</a> 
      
       <div class="datetime-bar"></div>
    </div>
<br><br><br><br>
</div>
 
</body>
</html>
