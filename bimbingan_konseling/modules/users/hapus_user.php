<?php
// File: modules/users/hapus_user.php
require_once '../../includes/header.php'; // Proteksi admin
require_once '../../config/koneksi.php';

// Cek apakah ID user ada dan valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manajemen_user?status=delete_failed');
    exit;
}

$user_id_to_delete = (int)$_GET['id'];

// PENTING: Jangan izinkan admin utama (id 1) dihapus
if ($user_id_to_delete === 1) {
    header('Location: manajemen_user?status=delete_failed&reason=main_admin');
    exit;
}

mysqli_begin_transaction($koneksi);
try {
    // 1. Cek role user yang akan dihapus
    $stmt_get_role = mysqli_prepare($koneksi, "SELECT role FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt_get_role, "i", $user_id_to_delete);
    mysqli_stmt_execute($stmt_get_role);
    $result_role = mysqli_stmt_get_result($stmt_get_role);
    $user = mysqli_fetch_assoc($result_role);

    if ($user && $user['role'] === 'siswa') {
        // 2. Jika user adalah siswa, hapus juga data siswa terkait
        // Hapus relasi pertemanan dulu
        $stmt_get_siswa_id = mysqli_prepare($koneksi, "SELECT id FROM siswa WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt_get_siswa_id, "i", $user_id_to_delete);
        mysqli_stmt_execute($stmt_get_siswa_id);
        $result_siswa_id = mysqli_stmt_get_result($stmt_get_siswa_id);
        if ($siswa_id_data = mysqli_fetch_assoc($result_siswa_id)) {
            $siswa_id = $siswa_id_data['id'];
            $stmt_del_pertemanan = mysqli_prepare($koneksi, "DELETE FROM pertemanan WHERE id_siswa_pemilih = ? OR id_siswa_dipilih = ?");
            mysqli_stmt_bind_param($stmt_del_pertemanan, "ii", $siswa_id, $siswa_id);
            mysqli_stmt_execute($stmt_del_pertemanan);
        }

        // Hapus data siswa
        $stmt_del_siswa = mysqli_prepare($koneksi, "DELETE FROM siswa WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt_del_siswa, "i", $user_id_to_delete);
        mysqli_stmt_execute($stmt_del_siswa);
    }

    // 3. Hapus user dari tabel 'users'
    $stmt_del_user = mysqli_prepare($koneksi, "DELETE FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt_del_user, "i", $user_id_to_delete);
    mysqli_stmt_execute($stmt_del_user);

    mysqli_commit($koneksi);
    header('Location: manajemen_user?status=delete_success');
    exit;

} catch (Exception $e) {
    mysqli_rollback($koneksi);
    header('Location: manajemen_user?status=delete_failed');
    exit;
}
?>