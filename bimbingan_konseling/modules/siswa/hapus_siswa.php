<?php
// File: modules/siswa/hapus_siswa.php

// Memastikan hanya admin yang bisa mengakses
require_once '../../includes/header.php';
require_once '../../config/koneksi.php';

// Cek apakah ID siswa ada dan valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $student_id = $_GET['id'];

    // Buat query untuk menghapus siswa
    // Juga hapus relasi pertemanan yang terkait dengan siswa ini untuk menjaga integritas data
    $query_delete_pertemanan = "DELETE FROM pertemanan WHERE id_siswa_pemilih = $student_id OR id_siswa_dipilih = $student_id";
    $query_delete_siswa = "DELETE FROM siswa WHERE id = $student_id";

    // Mulai transaksi
    mysqli_begin_transaction($koneksi);

    try {
        // Hapus relasi pertemanan
        mysqli_query($koneksi, $query_delete_pertemanan);

        // Hapus siswa
        mysqli_query($koneksi, $query_delete_siswa);

        // Jika semua query berhasil, commit transaksi
        mysqli_commit($koneksi);

        // Redirect dengan pesan sukses
        header('Location: daftar_siswa?status=success_delete');
        exit;

    } catch (mysqli_sql_exception $exception) {
        // Jika ada error, rollback transaksi
        mysqli_rollback($koneksi);

        // Redirect dengan pesan error
        header('Location: daftar_siswa?status=error');
        exit;
    }

} else {
    // Jika ID tidak valid atau tidak ada
    header('Location: daftar_siswa?status=error');
    exit;
}
?>
