<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Formulir Pendaftaran</h1>
        <a href="mainmenu.php" class="back-button">← Kembali ke Menu Utama</a>

        <div id="confirmationMessage" class="confirmation"></div>
        <form action="simpan.php" method="POST" id="formDaftar" novalidate>
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" placeholder="Nama Lengkap" 
                       required minlength="3" maxlength="100">
            </div>

            <div class="form-group">
                <label for="alamat">Alamat Lengkap</label>
                <textarea id="alamat" name="alamat" placeholder="Alamat Lengkap" 
                          required minlength="10" maxlength="300"></textarea>
            </div>

            <div class="form-group rad">
                <label>Jenis Kelamin:</label>
                <div class="radop">
                    <label>
                        <input type="radio" name="jenis_kelamin" value="Laki-laki" required> Laki-laki
                    </label>
                    <label>
                        <input type="radio" name="jenis_kelamin" value="Perempuan" required> Perempuan
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="agama">Agama</label>
                <select id="agama" name="agama" required>
                    <option value="" disabled selected>Pilih Agama</option>
                    <option value="Islam">Islam</option>
                    <option value="Kristen">Kristen</option>
                    <option value="Katolik">Katolik</option>
                    <option value="Hindu">Hindu</option>
                    <option value="Buddha">Buddha</option>
                    <option value="Konghucu">Konghucu</option>
                </select>
            </div>

            <div class="form-group">
                <label for="sekolah_asal">Sekolah Asal</label>
                <input type="text" id="sekolah_asal" name="sekolah_asal" 
                       placeholder="Sekolah Asal" required minlength="3" maxlength="100">
            </div>

            <div class="form-group">
                <label for="foto">Foto 3x4</label>
                <input type="file" id="foto" name="foto" accept="image/*" required>
            </div>

            <div class="form-group">
                <input type="submit" value="Daftar" class="submit-btn">
            </div>
        </form>

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formDaftar');
        const confirmationMessage = document.getElementById('confirmationMessage');

        form.addEventListener('submit', function(event) {
            form.querySelectorAll('.invalid').forEach(el => el.classList.remove('invalid'));
            confirmationMessage.textContent = '';
            confirmationMessage.classList.remove('error', 'success');

            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('invalid');
                    isValid = false;
                }
            });

            if (!isValid) {
                event.preventDefault();
                confirmationMessage.textContent = 'Harap isi semua field yang wajib.';
                confirmationMessage.classList.add('error');
                return;
            }

            event.preventDefault();

            const formData = new FormData(form);

            fetch('simpan.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                confirmationMessage.classList.remove('error', 'success');

                if (data.status === 'success') {
                    confirmationMessage.textContent = data.message || "Pendaftaran Siswa Berhasil!";
                    confirmationMessage.classList.add('success');
                    form.reset(); 
                } else {
                    confirmationMessage.textContent = data.message || "Terjadi kesalahan, silakan coba lagi.";
                    confirmationMessage.classList.add('error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                confirmationMessage.textContent = "Terjadi kesalahan koneksi.";
                confirmationMessage.classList.add('error');
            });
        });

        form.querySelectorAll('input, textarea, select').forEach(field => {
            field.addEventListener('input', function() {
                this.classList.remove('invalid');
                confirmationMessage.textContent = '';
                confirmationMessage.classList.remove('error', 'success');
            });
        });
    });
    </script>
</body>
</html>