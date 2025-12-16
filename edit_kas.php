<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user'])){
    header("Location: index.php");
    exit();
}

$qManual = mysqli_query($koneksi, "SELECT modal FROM kas_manual WHERE id=1");
$dManual = mysqli_fetch_assoc($qManual);
$kas_saat_ini = isset($dManual['modal']) ? (int)$dManual['modal'] : 0;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Kas</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<h2>Edit Kas Manual</h2>

<form action="proses_edit_kas.php" method="post">
    <label>Nominal Kas Baru</label>
    <input type="number" name="kas_baru" required>

    <button type="submit">Simpan Perubahan</button>
</form>



</body>
</html>
