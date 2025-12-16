<?php
include 'koneksi.php';

$keterangan = $_POST['keterangan'];
$jumlah     = $_POST['jumlah'];

$nama_file_paraf = null;
$folder = "upload_paraf";

if (!is_dir($folder)) {
  mkdir($folder);
}

/* ✅ JIKA PAKAI CORET */
if (!empty($_POST['paraf_base64'])) {
  $img = $_POST['paraf_base64'];
  $img = str_replace('data:image/png;base64,', '', $img);
  $img = base64_decode($img);

  $nama_file_paraf = "paraf_" . time() . ".png";
  file_put_contents($folder . $nama_file_paraf, $img);
}

/* ✅ JIKA PAKAI UPLOAD GAMBAR */
elseif (!empty($_FILES['paraf_gambar']['name'])) {
  $nama_file_paraf = "paraf_" . time() . "_" . $_FILES['paraf_gambar']['name'];
  move_uploaded_file(
    $_FILES['paraf_gambar']['tmp_name'],
    $folder . $nama_file_paraf
  );
}

/* ✅ SIMPAN KE DATABASE */
mysqli_query($koneksi, "
  INSERT INTO kas_transaksi 
  (tanggal, keterangan, jumlah, saldo, paraf)
  VALUES 
  (NOW(), '$keterangan', '$jumlah', 0, '$nama_file_paraf')
");

header("Location: kas.php");
