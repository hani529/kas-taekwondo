<?php
session_start();
include 'koneksi.php';

function tgl_indo($tanggal){
    $hari = [
        'Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa',
        'Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'
    ];
    $bulan = [
        'January'=>'Januari','February'=>'Februari','March'=>'Maret','April'=>'April',
        'May'=>'Mei','June'=>'Juni','July'=>'Juli','August'=>'Agustus',
        'September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Desember'
    ];
    $tgl = date('l, d F Y H:i', strtotime($tanggal));
    $tgl = strtr($tgl, $hari);
    $tgl = strtr($tgl, $bulan);
    return $tgl;
}

// ✅ VALIDASI ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) die("ID tidak valid");
$id = (int)$_GET['id'];

// ✅ DATA MEMBER
$qMember = mysqli_query($koneksi, "SELECT * FROM members WHERE id='$id'");
$member = mysqli_fetch_assoc($qMember);
if (!$member) die("Member tidak ditemukan");

$tahun = date('Y');

// ✅ TOTAL BAYAR TAHUN INI
$qTotal = mysqli_query($koneksi, "SELECT SUM(jumlah) AS total FROM pembayaran WHERE member_id='$id' AND tahun='$tahun'");
$dTotal = mysqli_fetch_assoc($qTotal);
$totalBayar = (int)$dTotal['total'];

// ✅ HITUNG TUNGGAKAN
$tgl_join = !empty($member['tanggal_join']) ? strtotime($member['tanggal_join']) : false;
$bulan_join = $tgl_join ? (int)date('n', $tgl_join) : 8;
$hari_join  = $tgl_join ? (int)date('j', $tgl_join) : 1;
$bulan_sekarang = (int)date('n');
$bulan_wajib = ($bulan_sekarang - $bulan_join) + 1;
if ($hari_join >= 16) $bulan_wajib--;
$total_wajib = $bulan_wajib * 10000;
$tunggakan = $total_wajib - $totalBayar;
if ($tunggakan < 0) $tunggakan = 0;

// ✅ PROSES BAYAR
if (isset($_POST['bayar'])) {
    $jumlah = (int)$_POST['jumlah'];
    $bulan  = date('n');

    // 1️⃣ Masuk ke pembayaran
    mysqli_query($koneksi, "INSERT INTO pembayaran (member_id, bulan, tahun, jumlah, created_at) VALUES ('$id','$bulan','$tahun','$jumlah',NOW())");

    // 2️⃣ Hitung saldo terbaru dari kas_transaksi
    $qSaldoTerakhir = mysqli_query($koneksi, "SELECT saldo FROM kas_transaksi ORDER BY id DESC LIMIT 1");
    $dSaldoTerakhir = mysqli_fetch_assoc($qSaldoTerakhir);
    $saldo_terakhir = !empty($dSaldoTerakhir['saldo']) ? (int)$dSaldoTerakhir['saldo'] : 0;
    $saldo_baru = $saldo_terakhir + $jumlah;

    // 3️⃣ Masuk ke kas_transaksi
    mysqli_query($koneksi, "INSERT INTO kas_transaksi (tanggal, keterangan, jumlah, saldo, paraf) VALUES (NOW(), 'Masuk dari pembayaran member', $jumlah, $saldo_baru, NULL)");

    header("Location: transaksi.php?id=$id");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transaksi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=3, user-scalable=yes">
    <link rel="stylesheet" href="style.css">
    <script src="datetime.js" defer></script>
</head>
<body>
<div class="container-members">

    <h2 class="judul-transaksi">Transaksi : <?= htmlspecialchars($member['panggilan']) ?></h2>

    <!-- FORM BAYAR -->
    
    <!-- TABEL RIWAYAT -->
    <div class="wrap-tabel-transaksi">
        <div class="tabel-wrap">
            <table border="1" cellpadding="10" width="100%">
                <tr>
                    <th>No</th>
                    <th>Hari, Tanggal Bulan Tahun Jam</th>
                    <th>Jumlah</th>
                </tr>
                <?php
                $qJumlah = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pembayaran WHERE member_id='$id'");
                $dJumlah = mysqli_fetch_assoc($qJumlah);
                $no = $dJumlah['total'];

                $qData = mysqli_query($koneksi, "SELECT * FROM pembayaran WHERE member_id='$id' ORDER BY created_at DESC");
                while ($d = mysqli_fetch_assoc($qData)) {
                    echo "<tr>
                        <td>{$no}</td>
                        <td>".tgl_indo($d['created_at'])."</td>
                        <td>".number_format($d['jumlah'])."</td>
                    </tr>";
                    $no--;
                }
                ?>
            </table>
        </div>
    </div>

    <h3 style="margin-top:15px; text-align:center;">Lihat Tunggakan Mu</h3>

    <div class="menu-transaksi">
        <a href="members.php" class="btn-kembali">Kembali</a>
        <a class="tunggakan" href="tunggakan.php?id=<?= $id ?>">Tunggakan</a>
        
    </div>
<br><br><br><br>
</div>
</body>
</html>
