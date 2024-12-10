<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pendaftaran_siswa";

$conn = new mysqli($servername, $username, $password, $dbname);


function tambahLog($conn, $username, $aktivitas) {
    $ip_address = $_SERVER['REMOTE_ADDR'];

    $stmt = $conn->prepare("INSERT INTO log_aktivitas (username, aktivitas, ip_address) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $aktivitas, $ip_address);

    $stmt->execute();
    $stmt->close();
}


if (isset($_SESSION['username'])) {
    tambahLog($conn, $_SESSION['username'], "Logout");
}

$_SESSION = array();

session_destroy();

header("Location: index.php");
exit();
?>