<?php
include 'koneksi.php';

// Ambil semua pengeluaran
$data = mysqli_query($koneksi, "SELECT * FROM pengeluaran ORDER BY id DESC");

// Ambil saldo kas terakhir
$kas = mysqli_query($koneksi, "SELECT jumlah FROM kas ORDER BY id DESC LIMIT 1");
$kasData = mysqli_fetch_assoc($kas);
$saldoSekarang = $kasData['jumlah'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Pengeluaran Kas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        table {border-collapse: collapse; width: 100%;}
        th, td {border:1px solid #00bfff; padding:8px; text-align:center;}
        th {background-color:#00bfff; color:white;}
        img {width:80px;}
    </style>
</head>
<body>

<h2>Riwayat Pengeluaran Kas</h2>
<p><strong>Saldo Kas Sekarang: Rp <?php echo number_format($saldoSekarang,0,",","."); ?></strong></p>

<table>
    <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Jumlah</th>
        <th>Keterangan</th>
        <th>Paraf</th>
    </tr>
    <?php
    $no = 1;
    while($row = mysqli_fetch_assoc($data)){
        echo "<tr>
            <td>{$no}</td>
            <td>{$row['tanggal']}</td>
            <td>Rp ".number_format($row['jumlah'],0,",",".")."</td>
            <td>{$row['keterangan']}</td>
            <td><img src='paraf/{$row['paraf']}' alt='paraf'></td>
        </tr>";
        $no++;
    }
    ?>
</table>

<br>
<a href="tambah_pengeluaran.php"><button>Tambah Pengeluaran</button></a>
<a href="kas.php"><button>Kembali ke Kas</button></a>

</body>
</html>
