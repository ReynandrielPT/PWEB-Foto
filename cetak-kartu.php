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

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];
$sql = "SELECT * FROM siswa WHERE id=$id";
$query = $conn->query($sql);
$siswa = $query->fetch_assoc();
$jenis_kelamin = ucfirst($siswa['jenis_kelamin']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Pendaftaran</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="registration-card">
        <div class="card-header">
            <h2>SMK Coding</h2>
            <h4>Kartu Pendaftaran Siswa Baru</h4>
        </div>
        <a href="list-siswa.php" class="back-button">← Kembali</a>
        <div class="card-content">
            <table>
                <tr>
                    <td><strong></strong></td>
                    <td>
                        <?php if (!empty($siswa['foto'])): ?>
                            <img src="uploads/<?php echo $siswa['foto']; ?>" alt="Foto <?php echo $siswa['nama']; ?>" style="max-width: 150px; max-height: 200px;">
                        <?php else: ?>
                            <em>Tidak ada foto</em>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Nama Lengkap</strong></td>
                    <td>: <?php echo $siswa['nama']; ?></td>
                </tr>
                <tr>
                    <td><strong>Alamat</strong></td>
                    <td>: <?php echo $siswa['alamat']; ?></td>
                </tr>
                <tr>
                    <td><strong>Jenis Kelamin</strong></td>
                    <td>: <?php echo $jenis_kelamin; ?></td>
                </tr>
                <tr>
                    <td><strong>Agama</strong></td>
                    <td>: <?php echo $siswa['agama']; ?></td>
                </tr>
                <tr>
                    <td><strong>Sekolah Asal</strong></td>
                    <td>: <?php echo $siswa['sekolah_asal']; ?></td>
                </tr>

            </table>
        </div>
        <div class="card-footer">
            <p>Harap membawa kartu ini saat verifikasi di sekolah.</p>
        </div>
        <div class="card-actions">
            <a href="cetak-kartu-pdf.php?id=<?php echo $siswa['id']; ?>" class="download-button">Download Kartu</a>
        </div>
    </div>
</body>
</html>