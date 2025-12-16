<?php
include 'koneksi.php';

// Ambil semua data terbaru dulu
$dataArray = [];
$result = mysqli_query($koneksi, "SELECT * FROM pengeluaran ORDER BY id DESC");
while ($row = mysqli_fetch_assoc($result)) {
    $dataArray[] = $row;
}

$total = count($dataArray); // total baris untuk nomor mundur
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pengeluaran</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=3, user-scalable=yes">
    <link rel="stylesheet" href="style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px;
            vertical-align: top;
        }
        td.detail {
            white-space: pre-wrap; /* Membuat paragraf */
            word-wrap: break-word;  /* Pecah kata panjang */
            max-width: 300px;       /* Batas lebar kolom */
            text-align: left;       /* Rata kiri */
        }
    </style>
</head>
<body>

<div class="container-members">

    <h2>Riwayat Pengeluaran</h2>

    <div class="tabel-wrap">
        <table>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
                <th>Detail</th>
            </tr>

            <?php
            $no = $total;
            foreach ($dataArray as $row):
                $tgl = date('d-m-Y', strtotime($row['tanggal'])); // Hanya tanggal
                $jumlah = number_format($row['jumlah']);
            ?>
            <tr>
                <td><?= $no--; ?></td>
                <td><?= $tgl; ?></td>
                <td><?= $jumlah; ?></td>
                <td><?= htmlspecialchars($row['keterangan']); ?></td>
                <td class="detail"><?= htmlspecialchars($row['detail_belanja']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="menu-transaksi">
        <a href="kas.php" class="btn-kembali">Kembali</a>
    </div>

</div>

</body>
</html>
