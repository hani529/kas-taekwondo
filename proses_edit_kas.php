<?php
include 'koneksi.php';

$kas_baru = (int)$_POST['kas_baru'];

// SET LANGSUNG
mysqli_query($koneksi,"
UPDATE kas_manual
SET modal = $kas_baru
WHERE id = 1
");

header("Location: kas.php");
exit();
