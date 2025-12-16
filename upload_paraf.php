<?php
session_start();
?>

<form action="proses_upload_paraf.php" method="POST" enctype="multipart/form-data">
    <label>Pilih Gambar Paraf:</label>
    <input type="file" name="paraf" required>

    <input type="hidden" name="id_transaksi" value="<?php echo $_GET['id']; ?>">

    <button type="submit">Upload</button>
</form>
