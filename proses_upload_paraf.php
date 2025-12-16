<?php
session_start();
include 'koneksi.php';

$id_transaksi = $_POST['id_transaksi'];

// Folder penyimpanan
$target_dir = "uploads/paraf/";

// Bikin folder kalau belum ada
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$nama_file = basename($_FILES["paraf"]["name"]);
$target_file = $target_dir . time() . "_" . $nama_file;

if (move_uploaded_file($_FILES["paraf"]["tmp_name"], $target_file)) {

    mysqli_query($koneksi, "
        UPDATE transaksi SET paraf='$target_file'
        WHERE id='$id_transaksi'
    ");

    header("Location: transaksi.php?id=$id_transaksi&upload=success");
    exit();

} else {
    echo "Gagal upload bro";
}
?>
