<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location:index.php");
    exit();
}

$isBendahara = ($_SESSION['role'] === 'bendahara');
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Ambil Uang Kas</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=3">
<style>
    * {
    box-sizing: border-box;
}


body{
    background:#0b0b0c;
    font-family:Segoe UI;
    color:white;
}

.container{
    width:330px;
    margin:60px auto;
    background:#111;
    padding:25px;
    border-radius:18px;
    box-shadow:0 0 20px #00e1ff;
}

/* Judul Glow */
h2{
    text-align:center;
    margin-bottom:20px;
    font-size:24px;
    color:#00eaff;
    text-shadow:0 0 8px #00eaff;
}

/* Label */
label{
    font-weight:bold;
    color:#7df9ff;
}

/* Input style */
.input-box, textarea{
    width:100%;
    background:#1c1c1c;
    color:white;
    padding:12px;
    border:none;
    border-radius:10px;
    margin-top:5px;
    margin-bottom:15px;
    font-size:17px;
}

/* Textarea */
textarea{
    height:90px;
    resize:none;
}

/* KEYPAD */
.keypad{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:10px;
    margin-top:10px;
}

.keypad button{
    padding:15px;
    font-size:18px;
    border-radius:10px;
    background:#1e1e1e;
    color:#7df9ff;
    border:none;
    cursor:pointer;
    font-weight:bold;
}

/* Tombol ambil */
.btn-ambil{
    width:100%;
    padding:13px;
    background:#00ff66;
    border:none;
    font-weight:bold;
    color:#000;
    border-radius:10px;
    margin-top:20px;
    box-shadow:0 0 12px #00ff66;
    cursor:pointer;
    font-size:18px;
}

/* Tombol kembali */
.btn-kembali{
    width:100%;
    display:block;
    text-align:center;
    margin-top:15px;
    padding:12px;
    background:#2b0000;
    color:#ff4d4d;
    border-radius:10px;
    font-weight:bold;
    box-shadow:0 0 10px #ff0000;
    text-decoration:none;
}

</style>

</head>
<body>

<div class="container">

<h2>Ambil Uang Kas</h2>

<form action="proses_pengeluaran.php" method="POST">

    <!-- NOMINAL -->
    <label>Nominal yang diambil</label>
    <input type="text" name="jumlah" id="jumlah" class="input-box" readonly required>

    <!-- KEYPAD -->
    <div class="keypad">
        <button type="button" onclick="isi('1')">1</button>
        <button type="button" onclick="isi('2')">2</button>
        <button type="button" onclick="isi('3')">3</button>

        <button type="button" onclick="isi('4')">4</button>
        <button type="button" onclick="isi('5')">5</button>
        <button type="button" onclick="isi('6')">6</button>

        <button type="button" onclick="isi('7')">7</button>
        <button type="button" onclick="isi('8')">8</button>
        <button type="button" onclick="isi('9')">9</button>

        <button type="button" onclick="isi('000')">000</button>
        <button type="button" onclick="resetInput()">C</button>
        <button type="button" onclick="hapus()">⌫</button>
    </div>

    <!-- KETERANGAN -->
    <label>Keterangan</label>
    <input type="text" name="keterangan" class="input-box" required>

    <!-- DETAIL -->
    <label>Detail Belanja</label>
    <textarea name="detail_belanja"></textarea>

    <button
    type="button"
    class="btn-ambil"
    onclick="handleAmbil()"
    style="
        background:#00ff66;
        color:#000;
        <?php if (!$isBendahara) echo ' cursor:not-allowed;'; ?>
    "
>
    AMBIL
</button>

<!-- PESAN -->
<div id="msgRole" style="
    display:none;
    margin-top:10px;
    color:#ff4d4d;
    font-size:16px;
    text-align:center;
    opacity:0.85;
">
    lu ngapain gw tanyak??
</div>

<script>
function handleAmbil(){
    <?php if ($isBendahara) { ?>
        document.querySelector('form').submit();
    <?php } else { ?>
        document.getElementById('msgRole').style.display = 'block';
    <?php } ?>
}
</script>
</form>

<a href="kas.php" class="btn-kembali">Kembali</a>

</div>
<script>
const jInput = document.getElementById('jumlah');

function isi(x){
    let v = jInput.value;        // ambil isi sekarang
    v = v + x;                   // tambahkan angka baru
    v = v.replace(/\D/g,'');     // pastikan hanya angka
    jInput.value = v;            // tampilkan hasil
}

function hapus(){
    let v = jInput.value;
    jInput.value = v.slice(0, -1); // hapus 1 karakter terakhir
}

function resetInput(){
    jInput.value = '';            // bersihkan input
}
</script>


</body>
</html>
