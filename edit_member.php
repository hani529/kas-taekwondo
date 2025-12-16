<?php
// ==============================
// ✅ MEMULAI SESSION
// ==============================
session_start();

// ==============================
// ✅ MEMANGGIL FILE KONEKSI DATABASE
// ==============================
include 'koneksi.php';

// ==============================
// ✅ CEK LOGIN
// Kalau belum login → lempar ke index.php
// ==============================
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// ==============================
// ✅ CEK ID MEMBER DARI URL
// ==============================
if (!isset($_GET['id'])) {
    die("ID member tidak ditemukan!");
}

// ==============================
// ✅ AMBIL ID MEMBER
// ==============================
$id = (int) $_GET['id'];

// ==============================
// ✅ AMBIL DATA MEMBER DARI DATABASE
// ==============================
$q = mysqli_query($koneksi, "SELECT * FROM members WHERE id='$id'");

// ==============================
// ✅ CEK JIKA QUERY ERROR
// ==============================
if (!$q) {
    die("Query error: " . mysqli_error($koneksi));
}

// ==============================
// ✅ AMBIL DATA DALAM BENTUK ARRAY
// ==============================
$data = mysqli_fetch_assoc($q);

// ==============================
// ✅ JIKA MEMBER TIDAK ADA
// ==============================
if (!$data) {
    die("Data member tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Member</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=3, user-scalable=yes">
    <link rel="stylesheet" href="style.css">
     <script src="datetime.js" defer></script>
</head>

<body>

<div class="container-members">
  <h2>EDIT MEMBER</h2>

  <!-- ✅ FORM EDIT -->
  <form action="proses_edit_member.php" method="POST">

    <!-- ✅ KIRIM ID MEMEBER SECARA TERSEMBUNYI -->
    <input type="hidden" name="id" value="<?= $data['id'] ?>">
<div class="tabel-wrap">

    <class="label">


      <tr>
        <td class="label">Nama :</td>
        <td>
          <input type="text" name="nama" value="<?= $data['nama'] ?>" required>
        </td>
      </tr>

      <tr>
        <td class="label">Nama Panggilan :</td>
        <td>
          <input type="text" name="panggilan" value="<?= $data['panggilan'] ?>" required>
        </td>
      </tr>

      <tr>
        <td class="label">Kelas :</td>
        <td>
          <input type="text" name="kelas" value="<?= $data['kelas'] ?>" required>
        </td>
      </tr>

      <tr>
        <td class="label">Jurusan :</td>
        <td>
          <input type="text" name="jurusan" value="<?= $data['jurusan'] ?>" required>
        </td>
      </tr>

      <tr>
        <td class="label">Jenis Kelamin :</td>
        <td>
          <select name="gender" required>
            <option value="L" <?= $data['gender']=='L'?'selected':'' ?>>Laki-laki</option>
            <option value="P" <?= $data['gender']=='P'?'selected':'' ?>>Perempuan</option>
          </select>
        </td>
      </tr>

      <tr>
        <td class="label">Tanggal Join :</td>
        <td>
          <input type="date" name="tanggal_join" value="<?= $data['tanggal_join'] ?>" required>
        </td>
      </tr>
   
      <tr>
        <td colspan="2" class="btn-row">
          <a href="members.php" class="btn-back">Kembali</a>
        <button
    type="button"
    id="btnSimpan"
    onclick="cekRole()"
    style="
        background:#4dff4d;
        color:#000;
        padding:10px 20px;
        border:none;
        border-radius:6px;
        font-weight:bold;
        <?php if ($_SESSION['role'] !== 'bendahara') echo 'opacity:0.4; cursor:not-allowed;'; ?>
    "
>
    Simpan
</button>

<div id="msgRole"
     style="display:none; margin-top:8px; color:red; font-size:14px; opacity:0.8;">
    tangan lu gausah graitil ya
</div>


<script>
function cekRole(){
    <?php if ($_SESSION['role'] === 'bendahara') { ?>
        document.querySelector('form').submit();
    <?php } else { ?>
        document.getElementById('msgRole').style.display = 'block';
    <?php } ?>
}
</script>

        </td>
      </tr>

 
</div>
  </form>
  <br> <br> <br> <br> <br> <br>
</div>

</body>
</html>
