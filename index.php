<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $_SESSION['user'] = $_POST['nama'];
    $_SESSION['role'] = 'member';
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Kas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="login-box">

    <h2>
        <span class="dolar">$</span> KAS TAEKWONDO <span class="dolar">$</span><br>
        <span class="sekolah">SMK TELKOM LAMPUNG</span>
    </h2>
    <br>

    <!-- LOGIN MEMBER -->
    <form method="POST" class="login-form">
        <h3>Login Sebagai Member</h3>
        <br><br><br>
        <input type="text" name="nama" placeholder="bebas masukan nama mu" required>
        <br>
        <button type="submit" name="login">Masuk</button>
    </form>

    <!-- PINDAH KE LOGIN BENDAHARA -->
    <form action="login_bendahara.php" class="login-form">
        <button type="submit" class="btn-bendahara">
            Login sebagai Bendahara
        </button>
    </form>

</div>

</body>
</html>
