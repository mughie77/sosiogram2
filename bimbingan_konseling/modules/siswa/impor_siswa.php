<?php
// File: modules/siswa/impor_siswa.php

require_once '../../includes/header.php';
require_once '../../config/koneksi.php';

// Cek apakah form disubmit dan file diunggah
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvFile'])) {

    $file = $_FILES['csvFile'];

    // Validasi file
    if ($file['error'] !== UPLOAD_ERR_OK) {
        header('Location: daftar_siswa.php?status=error&msg=upload_failed');
        exit;
    }

    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($file_ext !== 'csv') {
        header('Location: daftar_siswa.php?status=error&msg=invalid_file_type');
        exit;
    }

    // Buka file yang diunggah
    $handle = fopen($file['tmp_name'], 'r');
    if ($handle === false) {
        header('Location: daftar_siswa.php?status=error&msg=cannot_open_file');
        exit;
    }

    // Mulai transaksi database
    mysqli_begin_transaction($koneksi);

    try {
        // Lewati baris header
        fgetcsv($handle);

        $row_count = 1;
        // Loop melalui setiap baris di file CSV
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            $row_count++;
            // Asumsi urutan kolom: nis, nama_lengkap, kelas, jenis_kelamin, alamat
            if (count($data) < 5) {
                throw new Exception("Data tidak lengkap pada baris $row_count.");
            }

            $nis = mysqli_real_escape_string($koneksi, $data[0]);
            $nama_lengkap = mysqli_real_escape_string($koneksi, $data[1]);
            $kelas = mysqli_real_escape_string($koneksi, $data[2]);
            $jenis_kelamin = mysqli_real_escape_string($koneksi, $data[3]);
            $alamat = mysqli_real_escape_string($koneksi, $data[4]);

            // Validasi sederhana
            if (empty($nis) || empty($nama_lengkap) || empty($kelas) || !in_array(strtoupper($jenis_kelamin), ['L', 'P'])) {
                 throw new Exception("Data tidak valid pada baris $row_count (NIS: $nis).");
            }

            // Cek duplikasi NIS sebelum insert
            $check_query = "SELECT id FROM users WHERE username = '$nis'";
            $check_result = mysqli_query($koneksi, $check_query);
            if (mysqli_num_rows($check_result) > 0) {
                // Jika user dengan NIS ini sudah ada, lewati baris ini
                continue;
            }

            // 1. Buat akun user baru
            $password_hashed = sha1($nis);
            $role = 'siswa';
            $stmt_user = mysqli_prepare($koneksi, "INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt_user, "sss", $nis, $password_hashed, $role);
            mysqli_stmt_execute($stmt_user);

            $user_id = mysqli_insert_id($koneksi);
            if ($user_id === 0) {
                // Lanjutkan ke baris berikutnya jika user gagal dibuat, jangan hentikan seluruh proses
                continue;
            }

            // 2. Insert data siswa dengan user_id yang terhubung
            $stmt_siswa = mysqli_prepare($koneksi, "INSERT INTO siswa (nis, nama_lengkap, kelas, jenis_kelamin, alamat, user_id) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt_siswa, "sssssi", $nis, $nama_lengkap, $kelas, $jenis_kelamin, $alamat, $user_id);
            if (!mysqli_stmt_execute($stmt_siswa)) {
                // Jika siswa gagal dibuat, user yang sudah terbuat akan di-rollback oleh transaksi
                throw new Exception("Gagal memasukkan data siswa pada baris $row_count (NIS: $nis).");
            }
        }

        // Jika semua berhasil, commit transaksi
        mysqli_commit($koneksi);
        fclose($handle);
        header('Location: daftar_siswa?status=success_import');
        exit;

    } catch (Exception $e) {
        // Jika ada error, rollback transaksi
        mysqli_rollback($koneksi);
        fclose($handle);
        // Redirect dengan pesan error spesifik
        header('Location: daftar_siswa?status=error&msg=' . urlencode($e->getMessage()));
        exit;
    }

} else {
    // Jika akses langsung atau tidak ada file
    header('Location: daftar_siswa');
    exit;
}
?>
