<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
  <title>Tambah Pengeluaran</title>
  <link rel="stylesheet" href="kas.css">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=3, user-scalable=yes">
  <script src="datetime.js" defer></script>
</head>
<body>

<div class="tabel-wrap">
  <div class="kas-box">
    <div class="tabel-wrap">

      <h2>Tambah Pengeluaran</h2>

      <!-- ✅ FORM HARUS PAKAI enctype -->
      <form method="POST" action="proses_tambah_kas.php" enctype="multipart/form-data">

        <input type="text" name="keterangan" placeholder="Keterangan pengeluaran" required>
        <input type="number" name="jumlah" placeholder="Jumlah pengeluaran" required>

        <p><b>Paraf Bendahara</b></p>

        <!-- ✅ CORET LANGSUNG -->
        <canvas id="parafCanvas" width="180" height="70"
          style="border:1px solid #00bfff; border-radius:10px;"></canvas><br>

        <button type="button" onclick="clearCanvas()">Reset</button><br><br>

        <input type="hidden" name="paraf_base64" id="paraf_base64">

        <hr>

        <!-- ✅ IMPORT GAMBAR -->
        <input type="file" name="paraf_gambar" accept="image/*">

        <br><br>
        <button type="submit">Simpan</button>

      </form>

      <div class="menu">
        <a href="kas.php">Kembali</a>
        <div class="datetime-bar"></div>
      </div>

      <br><br><br><br>

    </div>
  </div>
</div>

<script>
const canvas = document.getElementById("parafCanvas");
const ctx = canvas.getContext("2d");
let drawing = false;

canvas.addEventListener("mousedown", () => drawing = true);
canvas.addEventListener("mouseup", () => {
  drawing = false;
  document.getElementById("paraf_base64").value = canvas.toDataURL("image/png");
});
canvas.addEventListener("mousemove", draw);

function draw(e){
  if(!drawing) return;
  ctx.lineWidth = 2;
  ctx.lineCap = "round";
  ctx.strokeStyle = "#00bfff";
  ctx.lineTo(e.offsetX, e.offsetY);
  ctx.stroke();
  ctx.beginPath();
  ctx.moveTo(e.offsetX, e.offsetY);
}

function clearCanvas(){
  ctx.clearRect(0, 0, canvas.width, canvas.height);
}
</script>

</body>
</html>
