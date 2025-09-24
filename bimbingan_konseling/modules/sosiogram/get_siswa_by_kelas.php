<?php
// File: modules/sosiogram/get_siswa_by_kelas.php
// Helper untuk AJAX request, mengembalikan daftar siswa dalam format JSON.

// Set header ke JSON
header('Content-Type: application/json');

require_once '../../config/koneksi.php';

// Inisialisasi array untuk response
$response = [];

// Cek apakah parameter kelas ada
if (isset($_GET['kelas'])) {
    $kelas = mysqli_real_escape_string($koneksi, $_GET['kelas']);

    // Query untuk mengambil siswa berdasarkan kelas
    $query = "SELECT id, nama_lengkap FROM siswa WHERE kelas = '$kelas' ORDER BY nama_lengkap ASC";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $siswa_list = [];
        while ($row = mysqli_fetch_assoc($result)) {
            // Pastikan id adalah integer
            $row['id'] = (int)$row['id'];
            $siswa_list[] = $row;
        }
        $response = $siswa_list;
    } else {
        // Jika query gagal
        $response = ['error' => 'Gagal mengambil data siswa.'];
    }
} else {
    // Jika parameter kelas tidak ada
    $response = ['error' => 'Parameter kelas tidak ditemukan.'];
}

// Kembalikan response sebagai JSON
echo json_encode($response);
exit;
?>
