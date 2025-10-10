<?php
// File: modules/siswa/hapus_siswa.php

// Memastikan hanya admin yang bisa mengakses
require_once '../../includes/header.php';
require_once '../../config/koneksi.php';

// Cek apakah ID siswa ada dan valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $student_id = $_GET['id'];

    // Mulai transaksi untuk memastikan integritas data
    mysqli_begin_transaction($koneksi);

    try {
        // 1. Ambil user_id dari siswa yang akan dihapus
        $stmt_get_user = mysqli_prepare($koneksi, "SELECT user_id FROM siswa WHERE id = ?");
        mysqli_stmt_bind_param($stmt_get_user, "i", $student_id);
        mysqli_stmt_execute($stmt_get_user);
        $result_user = mysqli_stmt_get_result($stmt_get_user);
        $user_data = mysqli_fetch_assoc($result_user);
        $user_id_to_delete = $user_data ? $user_data['user_id'] : null;

        // 2. Hapus relasi pertemanan yang terkait (sudah ada, tapi kita pastikan lagi)
        $stmt_del_pertemanan = mysqli_prepare($koneksi, "DELETE FROM pertemanan WHERE id_siswa_pemilih = ? OR id_siswa_dipilih = ?");
        mysqli_stmt_bind_param($stmt_del_pertemanan, "ii", $student_id, $student_id);
        mysqli_stmt_execute($stmt_del_pertemanan);

        // 3. Hapus data siswa dari tabel 'siswa'
        $stmt_del_siswa = mysqli_prepare($koneksi, "DELETE FROM siswa WHERE id = ?");
        mysqli_stmt_bind_param($stmt_del_siswa, "i", $student_id);
        mysqli_stmt_execute($stmt_del_siswa);

        // 4. Jika ada user_id terkait, hapus juga dari tabel 'users'
        if ($user_id_to_delete) {
            $stmt_del_user = mysqli_prepare($koneksi, "DELETE FROM users WHERE id = ?");
            mysqli_stmt_bind_param($stmt_del_user, "i", $user_id_to_delete);
            mysqli_stmt_execute($stmt_del_user);
        }

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
