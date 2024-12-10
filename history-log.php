<!-- history-log.php -->
<?php
include('config.php');
$sql = "SELECT * FROM history_log ORDER BY waktu DESC";
$query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Log</title>
</head>
<body>
    <h1>History Log</h1>
    <a href="index.php">← Kembali ke Menu Utama</a>
    <table>
        <tr>
            <th>Waktu</th>
            <th>Keterangan</th>
        </tr>
        <?php while ($log = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td><?php echo $log['waktu']; ?></td>
                <td><?php echo $log['keterangan']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
