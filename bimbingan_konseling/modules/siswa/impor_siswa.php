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
            $check_query = "SELECT id FROM siswa WHERE nis = '$nis'";
            $check_result = mysqli_query($koneksi, $check_query);
            if (mysqli_num_rows($check_result) > 0) {
                // Jika sudah ada, lewati baris ini (atau bisa juga throw exception)
                continue;
            }

            // Insert data ke database
            $query = "INSERT INTO siswa (nis, nama_lengkap, kelas, jenis_kelamin, alamat) VALUES ('$nis', '$nama_lengkap', '$kelas', '$jenis_kelamin', '$alamat')";
            if (!mysqli_query($koneksi, $query)) {
                throw new Exception("Gagal memasukkan data pada baris $row_count: " . mysqli_error($koneksi));
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
