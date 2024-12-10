<?php
session_start();

$host = 'localhost';
$db_username = 'root';
$db_password = '';
$database = 'pendaftaran_siswa';

$conn = new mysqli($host, $db_username, $db_password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function tambahLog($conn, $username, $aktivitas) {
    $ip_address = $_SERVER['REMOTE_ADDR'];

    $stmt = $conn->prepare("INSERT INTO log_aktivitas (username, aktivitas, ip_address) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $aktivitas, $ip_address);
    
    $stmt->execute();
    $stmt->close();

    
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error_message = "Username dan password harus diisi";
    } else {
        $stmt = $conn->prepare("SELECT * FROM petugas WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
  
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];

                tambahLog($conn, $user['username'], "Login Berhasil");
                header("Location: mainmenu.php");
              
                exit();
            } else {
                $error_message = "Username atau password salah";
            }
        } else {
            $error_message = "Username tidak ditemukan";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pendaftaran Siswa</title>
    <link rel="stylesheet" href="css/styles.css">

</head>
<body>
    <div class="container">
        <h1>Login</h1>
        
        <?php
        if (!empty($error_message)) {
            echo "<div class='error'>" . htmlspecialchars($error_message) . "</div>";
        }
        ?>

        <form action="index.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" 
                       placeholder="Masukkan Username" 
                       required 
                       value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" 
                       placeholder="Masukkan Password" 
                       required>
            </div>

            <div class="form-group">
                <input type="checkbox" id="show-password" onclick="togglePassword()">
                <label for="show-password">Tampilkan Password</label>
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <p>Belum punya akun? <a href="register.php" class="register-link">Daftar di sini</a></p>
    </div>

    <script>
    function togglePassword() {
        var passwordField = document.getElementById("password");
        passwordField.type = passwordField.type === "password" ? "text" : "password";
    }
    </script>
</body>
</html>

<?php
$conn->close();
?>