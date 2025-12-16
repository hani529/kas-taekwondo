<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$isBendahara = ($_SESSION['role']=='bendahara' || $_SESSION['role']=='admin');

/* ===== SALDO ===== */
// ambil kas utama
$qKas = mysqli_query($koneksi,"SELECT modal FROM kas_manual WHERE id=1");
$dKas = mysqli_fetch_assoc($qKas);
$kas_manual = (int)($dKas['modal'] ?? 0);

// saldo lain
$qSaldo = mysqli_query($koneksi,"
SELECT SUM(CASE WHEN tipe='masuk' THEN jumlah ELSE -jumlah END) AS saldo
FROM saldo_lain
");
$saldo_lain = (int)(mysqli_fetch_assoc($qSaldo)['saldo'] ?? 0);

$saldo_sekarang = $kas_manual + $saldo_lain;

/* ===== PROSES TAMBAH / AMBIL ===== */
if(isset($_POST['aksi'])){
    $jumlah  = (int)$_POST['jumlah'];
    $catatan = $_POST['catatan'];

    if($_POST['aksi']=='tambah'){
        mysqli_query($koneksi,"
        INSERT INTO saldo_lain(jumlah,tipe,catatan,sisa,status)
        VALUES($jumlah,'masuk','$catatan',$jumlah,'tersedia')
        ");
    }

    if($_POST['aksi']=='ambil'){
        $id = (int)$_POST['id'];
        $d = mysqli_fetch_assoc(mysqli_query($koneksi,
            "SELECT sisa FROM saldo_lain WHERE id=$id"
        ));

        $sisa = $d['sisa'] - $jumlah;
        $status = ($sisa <= 0) ? 'habis' : 'tersedia';

        mysqli_query($koneksi,"
        UPDATE saldo_lain SET sisa=$sisa,status='$status' WHERE id=$id
        ");

        mysqli_query($koneksi,"
        INSERT INTO saldo_lain(jumlah,tipe,catatan,sisa)
        VALUES($jumlah,'keluar','Ambil saldo',0)
        ");
    }

    header("Location: saldo_lain.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Saldo Lain</title>

 <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=3, user-scalable=yes">
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container-members">
<h2 class="judul-kas">SALDO LAIN</h2>

<!-- BOX SALDO -->
<div class="box-kas">
    <h1><?= number_format($saldo_lain) ?></h1>
    <p>Saldo Tambahan (di luar kas utama)</p>
</div>

<!-- FORM TAMBAH -->
<div class="wrap-form">
<form method="post">
    <input type="number" name="jumlah" placeholder="Jumlah" class="input-form" required>
<input type="text" name="catatan" placeholder="Catatan Uang Masuk" class="input-form">



 <button
  name="aksi"
  value="tambah"
  class="btn-submit"
  onclick="return cekAkses(this);">
  Tambah Uang
</button>
<span class="info-akses"></span>
    
</form>
</div>

<!-- TABEL -->
<div class="wrap-tabel-transaksi">
<div class="tabel-wrap">
<table width="100%" border="1" cellpadding="10">
<tr>
  <th>No</th>
  <th>Tanggal</th>
  <th>Jumlah</th>
  <th>Keterangan</th>
  <th>Catatan</th>
  <th>Aksi</th>
  <th>Riwayat</th>
</tr>


<?php
$q = mysqli_query($koneksi,"SELECT * FROM saldo_lain ORDER BY id DESC");
$no = mysqli_num_rows($q);
while($r = mysqli_fetch_assoc($q)):
?>
<tr>
<td><?= $no-- ?></td>
<td><?= date('d-m-Y', strtotime($r['created_at'])) ?></td>

<td><?= number_format($r['jumlah']) ?></td>

<td class="<?= $r['tipe']=='masuk' ? 'ket-masuk':'ket-keluar' ?>">
    <?= strtoupper($r['tipe']) ?>
</td>

<td><?= $r['catatan'] ?></td>

<td>
<?php if($r['tipe']=='masuk'): ?>
    <?php if($r['status']=='tersedia'): ?>
    <form method="post" style="display:flex;gap:5px">
        <input type="hidden" name="id" value="<?= $r['id'] ?>">
        <input type="number" name="jumlah" placeholder="Ambil" required>


     <button
  name="aksi"
  value="ambil"
  class="btn-riwayat btn-merah"
  onclick="return cekAksesAmbil(this);">
  Abl
</button>
<span class="info-akses"></span>

<script>
function cekAksesAmbil(btn){
  document.querySelectorAll('.info-akses').forEach(e => e.textContent='');

  <?php if(!$isBendahara): ?>
    btn.nextElementSibling.textContent = 'diam kamu!';
    return false;
  <?php else: ?>
    return true;
  <?php endif; ?>
}
</script>


<script>
function cekAkses(btn){
  // hapus tulisan lama
  document.querySelectorAll('.info-akses').forEach(e => e.textContent='');

  <?php if(!$isBendahara): ?>
    btn.nextElementSibling.textContent = 'web buatan saya bagus ya? nih saya jual 2jt';
    return false;
  <?php else: ?>
    return true;
  <?php endif; ?>
}
</script>



    </form>
    <?php else: ?>
        <button class="btn-riwayat" disabled style="opacity:.5">sudah di ambil</button>
    <?php endif; ?>
<?php endif; ?>
</td>

<td>
<?php
if($r['tipe']=='masuk'){
    echo ($r['sisa']>0)
        ? 'Sisa '.number_format($r['sisa'])
        : 'Sudah diambil';
}
?>
</td>
</tr>
<?php endwhile; ?>

</table>
</div>
</div>

<!-- MENU -->
<div class="menu-transaksi">
    <a href="kas.php" class="btn-kembali">Kembali</a>
    <a href="kas.php" class="btn-submit">Kas Utama</a>
</div>

<br><br><br>
</div>
</body>
</html>
