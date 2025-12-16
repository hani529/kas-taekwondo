<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['tanggal'])) {
    die("Tanggal tidak ditemukan");
}

$tanggal = $_GET['tanggal']; // ekspektasi format 'YYYY-MM-DD' dari link di kas.php

// ambil record pembayaran pada tanggal tersebut (pakai DATE(created_at))
$q = mysqli_query($koneksi, "
    SELECT p.*, m.nama, m.panggilan
    FROM pembayaran p
    LEFT JOIN members m ON m.id = p.member_id
    WHERE DATE(p.created_at) = '$tanggal'
    ORDER BY p.created_at ASC
");

if (!$q) {
    die("Query riwayat ERROR: " . mysqli_error($koneksi));
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Pembayaran - <?= htmlspecialchars($tanggal) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Detail Pembayaran - <?= htmlspecialchars($tanggal) ?></h2>

    <table border="1" cellpadding="8" width="100%">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Jumlah</th>
            <th>WIB (Jam)</th>
            <th>Catatan</th>
        </tr>
        <?php $no=1; $total = 0; while ($r = mysqli_fetch_assoc($q)) {
            $total += (int)$r['jumlah'];
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($r['panggilan'] ?: $r['nama']) ?></td>
            <td><?= number_format($r['jumlah']) ?></td>
            <td><?= date('H:i', strtotime($r['created_at'])) ?></td>
            <td><?= htmlspecialchars($r['keterangan'] ?? 'bayar kas') ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="2"><strong>Total</strong></td>
            <td colspan="3"><strong><?= number_format($total) ?></strong></td>
        </tr>
    </table>

    <div style="margin-top:12px;">
        <a href="kas.php">Kembali</a>
    </div>
</div>
</body>
</html>
