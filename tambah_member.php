<?php
// ==============================
// ✅ MEMULAI SESSION
// Digunakan untuk menyimpan status login user
// ==============================
session_start();

// ==============================
// ✅ MEMANGGIL FILE KONEKSI DATABASE
// File koneksi.php berisi pengaturan koneksi ke MySQL
// ==============================
include 'koneksi.php';

// ==============================
// ✅ CEK APAKAH USER SUDAH LOGIN
// Kalau belum login → diarahkan ke halaman index.php
// ==============================
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit(); // menghentikan proses halaman ini
}
?>

<!DOCTYPE html>
<html>
<head>
    <!-- ========================= -->
    <!-- ✅ JUDUL HALAMAN -->
    <!-- ========================= -->
    <title>Tambah Member</title>
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=3, user-scalable=yes">
    <link rel="stylesheet" href="style.css">
    <script src="datetime.js" defer></script>

    <!-- ========================= -->
    <!-- ✅ MEMANGGIL CSS UTAMA -->
    <!-- ========================= -->
   
   
<div class="container">
  <h2>TAMBAH MEMBER</h2><br>

  <form action="proses_tambah_member.php" method="POST">
    
   
<div class="tabel-wrap">

      <tr>
        <td class="label">Nama :</td>
        <td><input type="text" name="nama" required></td>
      </tr>

      <tr>
        <td class="label">Nama Panggilan :</td>
        <td><input type="text" name="panggilan" required></td>
   

      <tr>
        <td class="label">Kelas :</td>
        <td>
          <input type="text" name="kelas" list="kelas_list" placeholder="X / XI / XII" required> 
          <datalist id="kelas_list">
            <option value="X">
            <option value="XI">
            <option value="XII">
          </datalist>
        </td>
      </tr>

      <tr>
        <td class="label">Jurusan :</td>
        <td>
          <input type="text" name="jurusan" list="jurusan_list" placeholder="RPL / TKJ / TJAT / ANIMASI" required>
          <datalist id="jurusan_list">
            <option value="RPL">
            <option value="TKJ">
            <option value="TJAT">
            <option value="ANIMASI">
          </datalist>
        </td>
      </tr>

      <tr>
        <td class="label">Jenis Kelamin :</td>
        <td>
          <select name="gender" required>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
          </select>
        </td>
      </tr>

      <tr>
        <td class="label">Tanggal Join :</td>
        <td><input type="date" name="tanggal_join" required></td>
      </tr>

       </div>
   
      <tr>
        <td colspan="2" class="btn-row">
          <a href="members.php" class="btn-back">Kembali</a>
         
          <button 
    type="submit"
    class="btn-submit"
    <?php if ($_SESSION['role'] !== 'bendahara') echo 'disabled'; ?>
    style="<?php if ($_SESSION['role'] !== 'bendahara') echo 'opacity:0.5; cursor:not-allowed;'; ?>"
>
    Tambah
</button>

<?php if ($_SESSION['role'] !== 'bendahara') { ?>
    <div style="color:red; margin-top:8px;">
        hanya bendahara yang bisa tambah member
    </div>
<?php } ?>

          
          
          <br><br><br><br><br><br>
</div> 
          <div class="datetime-bar"></div>
        </td>
      </tr>

    
  </form>
 
</div>
