<?php
session_start();
include 'koneksi.php';

$error = '';

if (isset($_POST['login_bendahara'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $q = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    $u = mysqli_fetch_assoc($q);

    if ($u && password_verify($password, $u['password'])) {
        $_SESSION['user'] = $u['username'];
        $_SESSION['role'] = 'bendahara';
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "apa ya kak??";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Bendahara</title>
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

    <!-- LOGIN BENDAHARA -->
    <form method="POST" class="login-form">
        <h3>✨Silahkan Login Bendahara Yang Mulia✨</h3>
        <br><br><br>

        <?php if ($error): ?>
            <div style="color:red; margin-bottom:10px;">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <input type="text" name="username" placeholder="Username" required>
        <br>
        <input type="password" name="password" placeholder="Password" required>
        <br>
        <button type="submit" name="login_bendahara">
            Login
        </button>
    </form>

    <!-- BALIK KE MEMBER -->
    <form action="index.php" class="login-form">
        <button type="submit">
            Login sebagai Member
        </button>
    </form>

</div>

</body>
</html>
