CREATE DATABASE pendaftaran_siswa;
USE pendaftaran_siswa;

CREATE TABLE siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    alamat TEXT,
    jenis_kelamin ENUM('laki-laki', 'perempuan') NOT NULL,
    agama VARCHAR(50),
    sekolah_asal VARCHAR(100),
    foto VARCHAR(255)
);

CREATE TABLE petugas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    namalengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL
);

CREATE TABLE log_aktivitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    aktivitas VARCHAR(100) NOT NULL,
    waktu DATETIME NOT NULL,
    ip_address VARCHAR(45) NOT NULL
);
