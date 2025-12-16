<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['user'])){ 
    header("Location:index.php"); 
    exit(); 
}

$user = $_SESSION['user'];
$member_id = isset($_GET['id']) ? $_GET['id'] : 0;

if($member_id == 0){
    echo "<h3 style='color:red;text-align:center;margin-top:100px;'>
        ID member tidak ditemukan!<br>
        Harap klik tombol <b>Bayar</b> dari halaman Member.
    </h3>";
    exit;
}// Ambil data member
$qMember = mysqli_query($koneksi, "SELECT panggilan FROM members WHERE id='$member_id'");
$dMember = mysqli_fetch_assoc($qMember);
$panggilan = $dMember ? $dMember['panggilan'] : 'Member Tidak Ditemukan';

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Bayar Kas</title>
 <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=3, user-scalable=yes">
    <link rel="stylesheet" href="style.css">
    <script src="datetime.js" defer></script>
<style>
body{
    background:#0b0b0c;
    color:white;
    font-family:Segoe UI;
}

.kartu{
    width:320px;
    margin:80px auto;
    background:#121212;
    border-radius:20px;
    box-shadow:0 0 20px #00bfff;
    padding:20px;
    text-align:center;
}

/* INPUT RP */
.input-rp{
    display:flex;
    align-items:center;
    background:#2c2c2c;
    border-radius:10px;
    padding:10px;
    margin-bottom:15px;
}

.input-rp span{
    margin-right:10px;
    color:#7df9ff;
    font-weight:bold;
}

.input-rp input{
    background:transparent;
    border:none;
    outline:none;
    color:white;
    font-size:20px;
    width:100%;
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
}

.menu{
    display:flex;
    justify-content:space-between;
    margin-top:20px;
}

.menu a,.menu button{
    width:48%;
    padding:12px;
    border-radius:10px;
    border:none;
    text-align:center;
    font-weight:bold;
}
</style>
</head>

<body>
    

<div class="kartu">
   <h2>Bayar Kas : <b><?= htmlspecialchars($panggilan) ?></b></h2>

    <br>

    <form action="proses_bayar.php" method="POST">

        <input type="hidden" name="member_id" value="<?= $member_id ?>">

        <!-- INPUT JUMLAH -->
        <div class="input-rp">
            <span>Rp</span>
            <input type="text" name="nominal" id="nominal" readonly required>
            
        </div>

        <!-- KEYPAD SESUAI CONTOH KAMU -->
        <div class="keypad">
            <button type="button" onclick="tambah('1')">1</button>
            <button type="button" onclick="tambah('2')">2</button>
            <button type="button" onclick="tambah('3')">3</button>

            <button type="button" onclick="tambah('4')">4</button>
            <button type="button" onclick="tambah('5')">5</button>
            <button type="button" onclick="tambah('6')">6</button>

            <button type="button" onclick="tambah('7')">7</button>
            <button type="button" onclick="tambah('8')">8</button>
            <button type="button" onclick="tambah('9')">9</button>

        
           
             <button type="button" onclick="tambah('000')">000</button>
            <button type="button" onclick="resetInput()">C</button>
             <button type="button" onclick="hapus()">⌫</button>
        </div>

        <!-- TOMBOL BAWAH -->
       <button
    type="button"
    class="menuk"
    onclick="handleBayar(this)"
    style="
        background:#4dff4d;
        color:#000;
        padding:12px 45px;
        border:none;
        border-radius:10px;
        font-weight:bold;
        <?php if ($_SESSION['role'] !== 'bendahara') echo 'opacity:0.4; cursor:not-allowed;'; ?>
    "
>
    Bayar
</button>


    

    <!-- TEKS DI BAWAH -->
    <div id="msgRole" style="display:none; color:red; margin-top:10px; font-size:14px; opacity:0.8;">
        bayar dulu kepada bendahara yang cantik itu
    </div>
     <a href="members.php" style="background:#333;">Kembali</a>
<div style="text-align:center;"></div>
    
    </div>



<script>
function handleBayar(btn){
    <?php if ($_SESSION['role'] === 'bendahara') { ?>
        btn.form.submit(); // BENDAHARA → BAYAR
    <?php } else { ?>
        document.getElementById('msgRole').style.display = 'block'; // MEMBER → TEKS
    <?php } ?>
}
</script>













            
                     
             <div class="datetime-bar"></div>
        </div>
<br><br><br><br>
    </form>
</div>
<script>
const nominalInput = document.getElementById('nominal');

// Fungsi format angka jadi 12,000
function formatRupiah(value){
    if(!value) return '';
    return parseInt(value.replace(/,/g,''))  // hapus koma dulu
           .toLocaleString('en-US');        // tambahkan koma
}

// Tambah angka dari keypad
function tambah(x){
    let val = nominalInput.value.replace(/,/g,''); // hapus koma dulu
    val += x;                                     // tambah angka
    nominalInput.value = formatRupiah(val);       // format dan tampilkan
}

// Hapus 1 digit
function hapus(){
    let val = nominalInput.value.replace(/,/g,''); // hapus koma
    val = val.slice(0, -1);                        // hapus 1 digit
    nominalInput.value = formatRupiah(val);        // format dan tampilkan
}

// Reset input
function resetInput(){
   document.getElementById('nominal').value = '';
}
</script>


</body>
</html>
