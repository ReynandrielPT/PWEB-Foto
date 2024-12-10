<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$username = htmlspecialchars($_SESSION['username']);
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Siswa Baru</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1 class="header">SMK Coding</h1> 
    <h1 class="header1">Selamat Datang, <?php echo $username; ?>!</h1>
    <div class="container">
        <h1>Pendaftaran Siswa Baru</h1>

        <a href="form-daftar.php" class="menu-button">Daftar Baru</a>

        <a href="list-siswa.php" class="menu-button">Lihat Daftar</a>
        
       <a href="logout.php" class="menu-button" >Logout</a>
    </div>

</body>
</html>