<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}

$data = mysqli_query($koneksi,"
SELECT * FROM members 
ORDER BY 
    -- ✅ KELOMPOK UTAMA vs KELOMPOK 4 (PAKAI LIKE)
    CASE 
        WHEN jurusan LIKE '%RPL%' 
          OR jurusan LIKE '%TKJ%' 
          OR jurusan LIKE '%TJAT%' 
          OR jurusan LIKE '%ANIMASI%' 
        THEN 1   -- ✅ KELOMPOK UTAMA
        ELSE 2   -- ✅ KELOMPOK 4 (PALING BAWAH)
    END,

    -- ✅ URUTAN KELAS
    CASE
        WHEN kelas = 'XII' THEN 1
        WHEN kelas = 'XI'  THEN 2
        WHEN kelas = 'X'   THEN 3
        ELSE 4
    END,

    -- ✅ DATA BARU DI BAWAH SESUAI KELOMPOKNYA
    id ASC
");

?>

<!DOCTYPE html>
<html>
<head>
    <title>All Member</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=3, user-scalable=yes">
    <link rel="stylesheet" href="style.css">
    <script src="datetime.js" defer></script>

</head>
<body>

<div class="container-members">

    <div class="judul">
        <h2>ALL MEMBER</h2>
    </div>

    <div class="tabel-wrap">
        <table>

            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Nama</th>
                <th rowspan="2">Kelas</th>
                <th rowspan="2">L/P</th>
                    <th rowspan="2">Tanggal Join</th> <!-- kolom baru -->
                <th colspan="3" rowspan="2">Keterangan</th>
                <th colspan="2" rowspan="2">Aksi</th>
            </tr>

            <tr>
            </tr>

            <?php $no=1; while($d=mysqli_fetch_array($data)){ ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $d['nama'] ?></td>
                <td><?= $d['kelas'] . ' ' . $d['jurusan'] ?></td>
                <td class="gender" data-gender="<?= $d['gender'] ?>">
    <?= htmlspecialchars($d['gender']) ?>
</td>


                <td>
    <?= (!empty($d['tanggal_join']) && $d['tanggal_join'] !== '0000-00-00') 
        ? date('d-m-Y', strtotime($d['tanggal_join'])) 
        : '-' ?>
</td>


<td>
    <a class="tunggakan" href="tunggakan.php?id=<?= $d['id'] ?>">
        Tunggakan
    </a>
</td>

<td><a href="bayar_kas.php?id=<?= $d['id'] ?>" class="bayar">Bayar</a></td>
<td><a href="transaksi.php?id=<?= $d['id'] ?>"class="transaksi">Transaksi</a></td>
<td><a class="edit" href="edit_member.php?id=<?= $d['id'] ?>">Edit</a>
</td>
<td><a class="hapus" <?php if ($_SESSION['role'] === 'bendahara') { ?>
    <a class="hapus"
       href="hapus.php?id=<?= $d['id'] ?>"
       onclick="return confirm('Hapus member?')">
       Hapus
    </a>
<?php } else { ?>
    <span style="opacity:0.4; cursor:not-allowed;">Hapus</span>
<?php } ?>
</a></td>

            </tr>
            <?php } ?>

        </table>
    </div>

    <div class="menu-bawah">
        <a href="dashboard.php">Kembali</a>
        <a href="tambah_member.php">Tambah Member</a>
         <div class="datetime-bar"></div>
    </div>
<br><br>
</div>

</body>
</html>
