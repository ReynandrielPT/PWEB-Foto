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

function validatePassword($password) {
    return (
        strlen($password) >= 3 && 
        preg_match("/[A-Z]/", $password) && 
        preg_match("/[a-z]/", $password) &&  
        preg_match("/[0-9]/", $password)
    );
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    $nama_lengkap = sanitizeInput($_POST['nama_lengkap']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    
    $errors = [];
    $berhasil = [];
    
    if (empty($username)) {
        $errors[] = "Username is required";
    }
    
    if (!validatePassword($password)) {
        $errors[] = "Password harus 8 karakter termasuk uppercase, lowercase, and angka";
    }
    
    if (empty($nama_lengkap)) {
        $errors[] = "Nama Lengkap diperlukan!";
    }
    
    if (!$email) {
        $errors[] = "Email tidak valid!";
    }
    
    $check_username = $conn->prepare("SELECT * FROM petugas WHERE username = ?");
    $check_username->bind_param("s", $username);
    $check_username->execute();
    $result = $check_username->get_result();
    if ($result->num_rows > 0) {
        $errors[] = "Username sudah ada!";
    }
    
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO petugas (username, password, namalengkap, email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $hashed_password, $nama_lengkap, $email);
        
        if ($stmt->execute()) {
            tambahLog($conn, $username, "Registrasi Akun Baru");
            $berhasil[] = "Registrasi Berhasil! Silahkan Login";
            $username = "";
            $nama_lengkap = "";
            $email = "";
            $password = "";
        } else {
            $errors[] = "Registration gagal: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Siswa</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        
        <?php
        if (!empty($errors)) {
            echo "<div class='error'>";
            foreach ($errors as $error) {
                echo "<p>" . htmlspecialchars($error) . "</p>";
            }
            echo "</div>";
        }else if (!empty($berhasil)){
            echo "<div class='success'>";
            foreach ($berhasil as $berhasil) {
                echo "<p>" . htmlspecialchars($berhasil) . "</p>";
            }
            echo "</div>";
        }
        ?>
        
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" 
                       required maxlength="50" 
                       value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" 
                       required maxlength="100"
                       value="<?php echo isset($nama_lengkap) ? htmlspecialchars($nama_lengkap) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" 
                       required maxlength="100"
                       value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required minlength="8" >
            </div>
            
            <div class="form-group">
                <input type="checkbox" id="show-password" onclick="togglePassword()">
                <label for="show-password">Tampilkan Password</label>
            </div>
            
            <button type="submit">Daftar</button>
        </form>
        
        <p>Sudah punya akun? <a href="index.php">Login di sini</a></p>
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
session_destroy();

exit;
?>