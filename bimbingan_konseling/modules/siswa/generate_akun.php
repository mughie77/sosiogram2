<?php
// File: modules/siswa/generate_akun.php
require_once '../../includes/header.php'; // Proteksi admin
require_once '../../config/koneksi.php';

// 1. Ambil semua siswa yang belum punya akun (user_id IS NULL)
$result_siswa_no_account = mysqli_query($koneksi, "SELECT id, nis FROM siswa WHERE user_id IS NULL");

if (!$result_siswa_no_account || mysqli_num_rows($result_siswa_no_account) === 0) {
    // Jika tidak ada siswa yang perlu dibuatkan akun, redirect kembali
    header('Location: daftar_siswa?status=no_accounts_needed');
    exit;
}

$akun_dibuat = 0;
$akun_gagal = 0;

mysqli_begin_transaction($koneksi);
try {
    // Loop melalui setiap siswa
    while ($siswa = mysqli_fetch_assoc($result_siswa_no_account)) {
        $id_siswa = $siswa['id'];
        $nis_siswa = $siswa['nis'];

        // Cek lagi untuk keamanan, jangan buat akun jika username (NIS) sudah ada
        $stmt_check = mysqli_prepare($koneksi, "SELECT id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt_check, "s", $nis_siswa);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        if (mysqli_num_rows($result_check) > 0) {
            $akun_gagal++;
            continue; // Lanjut ke siswa berikutnya
        }

        // 2. Buat akun user baru
        $password_hashed = sha1($nis_siswa);
        $role = 'siswa';

        $stmt_user = mysqli_prepare($koneksi, "INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt_user, "sss", $nis_siswa, $password_hashed, $role);
        mysqli_stmt_execute($stmt_user);

        $new_user_id = mysqli_insert_id($koneksi);
        if ($new_user_id === 0) {
            $akun_gagal++;
            continue;
        }

        // 3. Update record siswa dengan user_id yang baru
        $stmt_update_siswa = mysqli_prepare($koneksi, "UPDATE siswa SET user_id = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt_update_siswa, "ii", $new_user_id, $id_siswa);
        mysqli_stmt_execute($stmt_update_siswa);

        $akun_dibuat++;
    }

    mysqli_commit($koneksi);
    header('Location: daftar_siswa?status=generate_success&created=' . $akun_dibuat . '&failed=' . $akun_gagal);
    exit;

} catch (Exception $e) {
    mysqli_rollback($koneksi);
    header('Location: daftar_siswa?status=generate_error');
    exit;
}
?>