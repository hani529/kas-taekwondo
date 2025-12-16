<?php
include 'koneksi.php';
$id=$_GET['id'];

mysqli_query($koneksi,"UPDATE kas SET paraf='✓' WHERE id='$id'");
header("Location: kas.php");
