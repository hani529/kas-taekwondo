<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '2025';

$data = mysqli_query($koneksi, "
SELECT * FROM members 
ORDER BY 
    CASE 
        WHEN jurusan LIKE '%RPL%' 
          OR jurusan LIKE '%TKJ%' 
          OR jurusan LIKE '%TJAT%' 
          OR jurusan LIKE '%ANIMASI%' 
        THEN 1
        ELSE 2
    END,
    CASE
        WHEN kelas = 'XII' THEN 1
        WHEN kelas = 'XI'  THEN 2
        WHEN kelas = 'X'   THEN 3
        ELSE 4
    END,
    id ASC
");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=3, user-scalable=yes">
    <link rel="stylesheet" href="style.css">
    <script src="datetime.js" defer></script>
    <link rel="icon" type="image/png" href="icon kas.png">

</head>

<body>

    <div class="container-members">

        <div class="judul">
            <h2><span class="dolar">$</span>KAS TAEKWONDO<span class="dolar">$</span></h2>
            <h3 class="sekolah">SMK TELKOM LAMPUNG</h3>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:15px;">
            <div>
                <div style="color:#7df9ff; font-weight:bold; margin-bottom:8px;">
                    Halo, <?= htmlspecialchars($user) ?> sayang
                </div>

                <div>
                 
                <?php $aktif = strpos($_SERVER['REQUEST_URI'],'kas2024') !== false; ?>

<?php
$url = $_SERVER['REQUEST_URI'];
function aktif($folder){
  return strpos($_SERVER['REQUEST_URI'],$folder) !== false;
}
?>

<script>
function glowClick(el){
  const orb = document.createElement('span');
  orb.style.position = 'absolute';
  orb.style.width = '8px';
  orb.style.height = '8px';
  orb.style.borderRadius = '50%';
  orb.style.background = 'rgba(255,255,255,0.9)';
  orb.style.left = '-10px';
  orb.style.top = '50%';
  orb.style.transform = 'translateY(-50%)';
  orb.style.boxShadow = '0 0 10px cyan';
  orb.style.transition = 'all .4s ease';

  el.appendChild(orb);
  el.style.boxShadow = '0 0 15px rgba(0,200,255,.8)';

  setTimeout(()=>{
    orb.style.left = '50%';
    orb.style.opacity = '0';
    el.style.boxShadow = '0 0 25px rgba(0,200,255,1)';
  },10);

  setTimeout(()=>orb.remove(),400);
}
</script>



<a href="../kas2024/dashboard.php"
   onclick="glowClick(this)"
   style="<?= aktif('kas2024')
   ? 'background:linear-gradient(to right,#6dd5ff,#00c6ff);
      color:#000;
      padding:6px 18px;
      border-radius:999px;
      display:inline-block;
      position:relative;
      overflow:hidden;
      font-weight:600;'
  : '' ?>">
2024
</a>


 <a href="../kas2025/dashboard.php">2025</a>
 <a href="../kas2026/dashboard.php">2026</a>





                </div>
            </div>
        </div>

        <div class="tabel-wrap">
            <table>
                <tr>
                    <th colspan="29">
                        KAS TAEKWONDO SMK TELKOM LAMPUNG 2024-2025
                    </th>
                </tr>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nama</th>
                    <th rowspan="2">Panggilan</th>
                    <th rowspan="2">Kelas</th>
                    <th rowspan="2">L/P</th>

                    <th colspan="2">Agus</th>
                    <th colspan="2">Sept</th>
                    <th colspan="2">Oktb</th>
                    <th colspan="2">Nove</th>
                    <th colspan="2">Dese</th>
                    <th colspan="2">Janu</th>
                    <th colspan="2">Febr</th>
                    <th colspan="2">Mart</th>
                    <th colspan="2">Aprl</th>
                   <th colspan="6" rowspan="27" 
    style="position: relative; text-align:center; vertical-align:middle; background:#000;">
    
    <!-- Teks horizontal putih -->
    <div style="color:#fff; font-size:18px; margin-bottom:10px;"> Mei Juni Juli
    </div>
     <br>
    <!-- Teks vertikal merah -->
   <div style="color:#ff0000; /* merah terang */
            writing-mode: vertical-rl; 
            text-orientation: mixed; 
            font-size:28px; 
            position:absolute; 
            top:50%; left:50%; 
            transform:translate(-50%,-50%);
            text-shadow: 0 0 5px #ff0000, 0 0 10px #ff0000, 0 0 0px #ff0000, 0 0 2px #ff0000;">
    KAS LIBUR
</div>
                </tr>
                <tr>
                    <th colspan="18" style="color:#7df9ff; font-size:19px;">5k</th>
                </tr>

                <?php $no = 1;
                while ($d = mysqli_fetch_array($data)) { ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="nama"><?= htmlspecialchars($d['nama']) ?></td>
                        <td class="panggilan"><?= htmlspecialchars($d['panggilan']) ?></td>
                        <td><?= $d['kelas'] . ' ' . $d['jurusan'] ?></td>
                        <td class="gender" data-gender="<?= $d['gender'] ?>">
                            <?= htmlspecialchars($d['gender']) ?>
                        </td>

                        <?php
                        // Ambil total uang yang dibayar member per tahun
                        $qTotal = mysqli_query($koneksi, "
                    SELECT SUM(jumlah) as total 
                    FROM pembayaran 
                    WHERE member_id = '{$d['id']}' 
                      AND tahun = '$tahun'
                ");
                        $dTotal = mysqli_fetch_assoc($qTotal);
                        $totalUang = (int) $dTotal['total'];

                        // Ambil bulan & tanggal join
                        // Ambil bulan & tanggal join
                        $bulanJoin = 8; // default Agustus
                        $tglJoin = 1;
                        if (!empty($d['tanggal_join']) && $d['tanggal_join'] !== '0000-00-00') {
                            $bulanJoin = (int) date('n', strtotime($d['tanggal_join']));
                            $tglJoin = (int) date('j', strtotime($d['tanggal_join']));
                        }

                        // Konversi ke index kolom (0-23)
                        if ($bulanJoin >= 8) {
                            $bulanOffset = $bulanJoin - 8; // Agustus = 0
                        } else {
                            $bulanOffset = $bulanJoin + 4; // Jan-Jul
                        }

                        $indexJoin = ($tglJoin <= 15) ? $bulanOffset * 2 : $bulanOffset * 2 + 1;

                        // Tulis "Belum Join" untuk kolom sebelum join
                        if ($indexJoin > 0) {
                            echo "<td class='kolom-belumjoin' colspan='$indexJoin'>Belum <br> Join</td>";
                        }

                        $sisaKolom = 18 - $indexJoin;

                        // Ambil total uang yang dibayar member per tahun
// ====================== FIX CENTANG ======================
                        $centang_per_unit = 5000;
                        $jumlah_unit = floor($totalUang / $centang_per_unit);
                        $sisa_unit = $totalUang % $centang_per_unit;

                        // Loop centang
                        $i = 0;
                        while ($jumlah_unit > 0 && $sisaKolom > 0) {
                            echo "<td class='kolom-centang'>✅</td>";
                            $jumlah_unit--;
                            $sisaKolom--;
                        }

                        // Sisa unit (kurang dari 5000)
                        if ($sisa_unit > 0 && $sisaKolom > 0) {
                            $sisaFormatted = rtrim(rtrim(number_format($sisa_unit / 1000, 1), '0'), '.');
                            echo "<td class='kolom-centang'>{$sisaFormatted}k</td>";
                            $sisaKolom--;
                        }

                        // Sisa kolom kosong
                        for ($i = 0; $i < $sisaKolom; $i++) {
                            echo "<td class='kolom-centang'></td>";
                        }
                        // ====================== END FIX ======================
                    


                        ?>

                    </tr>
                <?php } ?>
            </table>
        </div>

        <div class="menu-bawah">
            <a href="logout.php">Keluar</a>

            <a href="members.php">All Member</a>
            <a href="kas.php">Kas saat ini</a>
            <div class="datetime-bar"></div>
            <h4 class="note">
                <b>☺️PENTINGNYA MEMBAYAR KAS☺️</b><br><br>

                Kas digunakan untuk memenuhi kebutuhan bersama dan peralatan pribadi kegiatan, seperti:<br><br>

                • Pembelian perlengkapan latihan<br>
                • Kebutuhan operasional<br>
                • Kegiatan kebersamaan (makan-makan, acara, dll)<br><br>

                Dengan membayar kas tepat waktu, kita ikut bertanggung jawab,
                menjaga kebersamaan, dan mendukung kelancaran semua kegiatan.<br><br>

                👉 Yuk, bayar kas tepat waktu demi kenyamanan bersama.
            </h4>

        </div>
        <br><br>

    </div>

</body>

</html>