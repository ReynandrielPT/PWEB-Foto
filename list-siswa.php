<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: mainmenu.php");
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

$query = $conn->query("SELECT * FROM siswa");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pendaftar</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="table-container">
        <h1>Daftar Pendaftar</h1>
        <a href="mainmenu.php" class="back-button">← Kembali ke Menu Utama</a>

        <div class="search-bar">
            <input type="text" id="searchInput" class="search-input" placeholder="Cari Nama Siswa...">
        </div>

        <?php if (mysqli_num_rows($query) > 0): ?>
            <table id="siswaTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Jenis Kelamin</th>
                        <th>Agama</th>
                        <th>Sekolah Asal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($siswa = mysqli_fetch_array($query)) {
                        echo "<tr>";
                        echo "<td>".$no++."</td>";
                        echo "<td><img src='uploads/".$siswa['foto']."' alt='Foto Siswa' class='siswa-foto'></td>";
                        echo "<td>".$siswa['nama']."</td>";
                        echo "<td>".$siswa['alamat']."</td>";
                        echo "<td>".$siswa['jenis_kelamin']."</td>";
                        echo "<td>".$siswa['agama']."</td>";
                        echo "<td>".$siswa['sekolah_asal']."</td>";
                        echo "<td><a href='cetak-kartu.php?id=".$siswa['id']."' class='print-button'>Cetak Kartu</a></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class='no-data'>Belum ada data siswa</div>
        <?php endif; ?>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const siswaTable = document.getElementById('siswaTable')?.getElementsByTagName('tbody')[0];

        if (siswaTable) {
            searchInput.addEventListener('keyup', function() {
                const filter = searchInput.value.toLowerCase();
                const rows = siswaTable.getElementsByTagName('tr');

                for (let i = 0; i < rows.length; i++) {
                    const cells = rows[i].getElementsByTagName('td');
                    let rowText = '';
                    for (let j = 0; j < cells.length; j++) {
                        rowText += cells[j].textContent.toLowerCase();
                    }

                    if (rowText.includes(filter)) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            });
        }
    </script>
</body>
</html>