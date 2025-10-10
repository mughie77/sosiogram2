<?php
// File: dashboard_siswa.php
require_once 'includes/header.php'; // Menggunakan header terpusat

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Ambil data profil siswa dari database
$stmt = mysqli_prepare($koneksi, "SELECT nis, nama_lengkap, kelas, jenis_kelamin, alamat FROM siswa WHERE user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$siswa = mysqli_fetch_assoc($result);

if (!$siswa) {
    // Jika data siswa tidak ditemukan, bisa jadi error atau data belum lengkap
    echo '<div class="alert alert-danger">Data profil siswa tidak ditemukan.</div>';
    require_once 'includes/footer.php'; // FIX: Menggunakan footer terpusat
    exit;
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header" style="background-color: var(--app-accent-color); color: white;">
                <h4 class="mb-0">Selamat Datang, <?php echo htmlspecialchars($siswa['nama_lengkap']); ?>!</h4>
            </div>
            <div class="card-body">
                <h5 class="card-title">Profil Anda</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>NIS:</strong> <?php echo htmlspecialchars($siswa['nis']); ?>
                    </li>
                    <li class="list-group-item">
                        <strong>Nama Lengkap:</strong> <?php echo htmlspecialchars($siswa['nama_lengkap']); ?>
                    </li>
                    <li class="list-group-item">
                        <strong>Kelas:</strong> <?php echo htmlspecialchars($siswa['kelas']); ?>
                    </li>
                </ul>

                <hr>

                <p class="mt-4">
                    Ini adalah halaman dashboard Anda. Silakan gunakan menu di atas untuk melanjutkan.
                </p>
                <a href="<?php echo BASE_URL; ?>/pilih_pertemanan" class="btn btn-primary">
                    <i class="bi bi-people-fill me-1"></i> Mulai Pilih Pertemanan
                </a>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php'; // Menggunakan footer terpusat
?>