<?php



session_start();
include 'koneksi.php';


error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID tidak ditemukan");
}
$id = (int)$_GET['id'];

$qMember = mysqli_query($koneksi, "SELECT * FROM members WHERE id='$id'");
$member = mysqli_fetch_assoc($qMember);
if (!$member) die("Member tidak ditemukan");


/* ===============================
   PROSES PEMBAYARAN (FORM BAYAR)
=============================== */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID tidak valid");
}
$id = (int)$_GET['id'];

$tahun = date('Y');

if (isset($_POST['bayar'])) {
    $jumlah = (int)$_POST['jumlah'];
    $bulan  = date('n');

    mysqli_query($koneksi, "
        INSERT INTO pembayaran (member_id, bulan, tahun, jumlah, created_at)
        VALUES ('$id', '$bulan', '$tahun', '$jumlah', NOW())
    ");

    header("Location: tunggakan.php?id=$id");
    exit;
    }










$bulan_sekarang = (int)date('n');
$hari_sekarang = (int)date('j');
$tahun = date('Y');

/* ===============================
   AMBIL TOTAL PEMBAYARAN
=============================== */
$qTotal = mysqli_query($koneksi, "
    SELECT SUM(jumlah) AS total 
    FROM pembayaran 
    WHERE member_id='$id' AND tahun='$tahun'
");
$dTotal = mysqli_fetch_assoc($qTotal);
$totalBayar = (int)($dTotal['total'] ?? 0);

/* ===============================
   HITUNG BULAN WAJIB
=============================== */
$tgl_join = strtotime($member['tanggal_join'] ?? "$tahun-08-01");
$bulan_join = (int)date('n', $tgl_join);
$hari_join = (int)date('j', $tgl_join);

$bulan_wajib = ($bulan_sekarang - $bulan_join) + 1;
if ($bulan_wajib < 1) $bulan_wajib = 1;




/* ===============================
   HITUNG TOTAL KEWAJIBAN PER BULAN
=============================== */
$kewajiban = [];

for ($i = 1; $i <= $bulan_wajib; $i++) {
    $bulanAktif = $bulan_join + ($i - 1);

    if ($bulanAktif > 12) continue;

    // ===== BULAN PERTAMA (JOIN MONTH) =====
    if ($i == 1) {
        if ($hari_join >= 16) {
            // join tanggal 16–31 → hanya 5k bulan pertama
            $kewajiban[$bulanAktif] = 5000;
        } else {
            // join tanggal 1–15 → full 10k
            $kewajiban[$bulanAktif] = 10000;
        }
        continue;
    }

    // ===== BULAN SEKARANG =====
    if ($bulanAktif == $bulan_sekarang) {
        if ($hari_sekarang <= 15) {
            $kewajiban[$bulanAktif] = 5000;
        } else {
            $kewajiban[$bulanAktif] = 10000;
        }
        continue;
    }

    // ===== BULAN NORMAL (FULL 10k) =====
    $kewajiban[$bulanAktif] = 10000;
}

/* ===============================
   KURANGI PEMBAYARAN DARI BULAN TERLAMA
=============================== */
$sisaBayar = $totalBayar;
$sisaTunggakanPerBulan = [];

foreach ($kewajiban as $bulan => $tagihan) {
    if ($sisaBayar >= $tagihan) {
        $sisaBayar -= $tagihan;
        $sisaTunggakanPerBulan[$bulan] = 0;
    } else {
        $sisaTunggakanPerBulan[$bulan] = $tagihan - $sisaBayar;
        $sisaBayar = 0;
    }
}

/* ===============================
   FILTER YANG MASIH NUNGGAK SAJA
=============================== */
$bulanNunggak = [];
$totalTunggakan = 0;

foreach ($sisaTunggakanPerBulan as $bulan => $sisa) {
    if ($sisa > 0) {
        $bulanNunggak[] = [
            'bulan' => date('F', mktime(0,0,0,$bulan,1)),
            'jumlah' => $sisa
        ];
        $totalTunggakan += $sisa;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tunggakan</title>
    <link rel="stylesheet" href="style.css">
       <script src="datetime.js" defer></script>
       <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=3, user-scalable=yes">
</head>
<body>

<div class="container-members">

    <h2 class="judul-transaksi">
        Tunggakan : <?= htmlspecialchars($member['panggilan']) ?>
    </h2>

<h4 class="10k">
       10k/bulan
     
    </h4>
<h4 class="5k">
       
       01-15=5k <br>
       16-31=5k
    </h4>

    <div class="tabel-wrap">
        <table border="1" cellpadding="10" width="100%">
            <tr>
                <th>Bulan</th>
                <th>Total Tunggakan</th>
            </tr>

            <?php if (count($bulanNunggak) == 0) { ?>
                <tr>
                    <td colspan="2" align="center">
                        ✅ Bagus! Tidak ada tunggakan, rajin-rajin ya manis ku
                    </td>
                </tr>
            <?php } else { ?>
                <?php foreach ($bulanNunggak as $d) { ?>
                <tr>
                    <td><?= $d['bulan'] ?></td>
                    <td><?= number_format($d['jumlah']) ?></td>
                </tr>
                
                <?php } ?>
                
            <?php } ?>
            
        </table>
    </div>

    

    <h3 style="margin-top:15px;">
        Total Tunggakan: Rp <b><?= number_format($totalTunggakan) ?></b>
    </h3>

 
<?php if ($totalTunggakan > 0) { ?>
<h4 class="notice-tunggakan">
    Segera lunasin tunggakan ya manis,bisa di cicil kok
</h4>

<?php } ?>

<!-- ✅ FORM BAYAR -->
    <div class="form-transaksi">
        <form method="POST">
            <input type="number" name="jumlah" placeholder="Masukkan Nominal" required>
            <button
    type="<?= ($_SESSION['role'] === 'bendahara') ? 'submit' : 'button'; ?>"
    name="bayar"
    onclick="showMsg()"
    style="
        background:#4dff4d;
        color:#000;
        padding:12px 50px;
        border:none;
        border-radius:10px;
        font-weight:bold;
        text-align:center;
        <?php if ($_SESSION['role'] !== 'bendahara') echo 'opacity:0.4; cursor:not-allowed;'; ?>
    "
>
    Bayar
</button>

<!-- TEKS (AWALNYA HILANG) -->
<div id="msgRole" style="
    display:none;
    color:red;
    margin-top:10px;
    font-size:14px;
    text-align:center;
">
    bayar dulu ke bendahara
</div>

<script>
function showMsg(){
    <?php if ($_SESSION['role'] !== 'bendahara') { ?>
        document.getElementById('msgRole').style.display = 'block';
    <?php } ?>
}
</script>
             
    </div>

    <div class="menu-transaksi">
        <a href="members.php" class="btn-kembali">Kembali</a>
          <div class="datetime-bar"></div>
    </div>
<br><br><br><br>
</div>
</body>
</html>
