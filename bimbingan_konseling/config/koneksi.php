<?php
// File: config/koneksi.php

// --- Konfigurasi Database ---
$db_host = 'localhost'; // atau sesuaikan dengan host database Anda
$db_user = 'root';      // atau sesuaikan dengan username database Anda
$db_pass = '';          // atau sesuaikan dengan password database Anda
$db_name = 'db_bimbingan_konseling'; // nama database yang akan digunakan

// --- Membuat Koneksi ke Database ---
$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// --- Cek Koneksi ---
if (!$koneksi) {
    // Jika koneksi gagal, hentikan eksekusi dan tampilkan pesan error
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// --- Pengaturan Tambahan (Opsional) ---
// Set timezone default jika diperlukan
date_default_timezone_set('Asia/Jakarta');

/*
 * Catatan untuk Pengguna:
 * 1. Pastikan Anda telah membuat database dengan nama yang sesuai (contoh: 'db_bimbingan_konseling').
 * 2. Sesuaikan nilai variabel $db_host, $db_user, dan $db_pass dengan konfigurasi server database Anda.
 * 3. File ini akan di-include di setiap halaman yang membutuhkan akses ke database.
 *
 * Contoh Perintah SQL untuk membuat database:
 * CREATE DATABASE db_bimbingan_konseling;
 *
 * Contoh Perintah SQL untuk membuat tabel (akan disediakan di dokumentasi atau file migrasi terpisah):
 *
 * CREATE TABLE users (
 *   id INT AUTO_INCREMENT PRIMARY KEY,
 *   username VARCHAR(50) NOT NULL UNIQUE,
 *   password VARCHAR(255) NOT NULL,
 *   role ENUM('admin', 'siswa') NOT NULL,
 *   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
 * );
 *
 * CREATE TABLE siswa (
 *   id INT AUTO_INCREMENT PRIMARY KEY,
 *   nis VARCHAR(20) NOT NULL UNIQUE,
 *   nama_lengkap VARCHAR(100) NOT NULL,
 *   kelas VARCHAR(20) NOT NULL,
 *   jenis_kelamin ENUM('L', 'P') NOT NULL,
 *   alamat TEXT,
 *   user_id INT,
 *   FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
 * );
 *
 * CREATE TABLE pertemanan (
 *   id INT AUTO_INCREMENT PRIMARY KEY,
 *   id_siswa_pemilih INT NOT NULL,
 *   id_siswa_dipilih INT NOT NULL,
 *   status ENUM('positif', 'negatif') NOT NULL,
 *   kelas VARCHAR(20) NOT NULL,
 *   FOREIGN KEY (id_siswa_pemilih) REFERENCES siswa(id) ON DELETE CASCADE,
 *   FOREIGN KEY (id_siswa_dipilih) REFERENCES siswa(id) ON DELETE CASCADE
 * );
 *
 * -- Menambahkan admin default
 * INSERT INTO users (username, password, role) VALUES ('admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin');
 * -- Catatan: Password untuk 'admin' adalah 'admin' yang di-hash menggunakan sha1().
 * -- Di aplikasi nyata, gunakan password_hash() dan password_verify().
 *
 */

?>
