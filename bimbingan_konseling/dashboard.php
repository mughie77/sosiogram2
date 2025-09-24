<?php
// File: dashboard.php
// Halaman ini adalah halaman utama setelah admin berhasil login.

// Memasukkan header.php, yang sudah berisi session_start() dan pengecekan otentikasi
require_once 'includes/header.php';
require_once 'config/koneksi.php';

// --- Logika untuk mengambil data ringkasan ---
// Contoh: Menghitung jumlah siswa
$query_total_siswa = "SELECT COUNT(id) as total_siswa FROM siswa";
$result_total_siswa = mysqli_query($koneksi, $query_total_siswa);
$total_siswa = mysqli_fetch_assoc($result_total_siswa)['total_siswa'];

// Contoh: Menghitung jumlah relasi pertemanan yang tercatat
$query_total_relasi = "SELECT COUNT(id) as total_relasi FROM pertemanan";
$result_total_relasi = mysqli_query($koneksi, $query_total_relasi);
$total_relasi = mysqli_fetch_assoc($result_total_relasi)['total_relasi'];

// Contoh: Menghitung jumlah kelas yang ada
$query_total_kelas = "SELECT COUNT(DISTINCT kelas) as total_kelas FROM siswa";
$result_total_kelas = mysqli_query($koneksi, $query_total_kelas);
$total_kelas = mysqli_fetch_assoc($result_total_kelas)['total_kelas'];

?>

<h1 class="mt-4">Dashboard</h1>
<p class="lead">Selamat datang di sistem informasi bimbingan konseling.</p>

<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Siswa</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_siswa; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people-fill fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Relasi Tercatat</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_relasi; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-diagram-3-fill fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Jumlah Kelas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_kelas; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-person-video3 fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header">
                <i class="bi bi-info-circle-fill me-1"></i>
                Petunjuk Penggunaan
            </div>
            <div class="card-body">
                <p>Aplikasi ini dirancang untuk membantu Guru BK dalam menganalisis interaksi sosial siswa melalui sosiogram.</p>
                <ul>
                    <li><strong>Data Siswa:</strong> Gunakan menu ini untuk mengelola semua data siswa. Anda bisa menambah, mengubah, menghapus, dan mengimpor data siswa dari file CSV.</li>
                    <li><strong>Data Pertemanan:</strong> Catat interaksi pilihan positif (pertemanan) dan negatif (penolakan) antar siswa di menu ini.</li>
                    <li><strong>Sosiogram:</strong> Visualisasikan data pertemanan yang telah Anda masukkan dalam bentuk diagram interaktif. Anda dapat memfilter sosiogram berdasarkan kelas.</li>
                </ul>
                <p>Pastikan data siswa dan data pertemanan sudah terisi dengan benar untuk menghasilkan sosiogram yang akurat.</p>
            </div>
        </div>
    </div>
</div>


<?php
// Memasukkan footer.php
require_once 'includes/footer.php';
?>
