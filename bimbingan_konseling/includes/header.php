<?php
<?php
// Memulai session di awal
session_start();

// Cek apakah pengguna sudah login dan memiliki role admin
// Jika tidak, redirect ke halaman login
// Catatan: Pengecekan ini mungkin perlu path yang lebih cerdas jika file login tidak selalu di root
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Untuk file di dalam /modules/, path ke login.php berbeda
    // Solusi sederhana: gunakan path absolut dari root folder proyek
    // Solusi lebih baik: Gunakan path yang dinamis atau konstanta terpusat
    $logout_path = '/bimbingan_konseling/login.php';
    // Cek jika kita berada di dalam folder modules
    if (strpos($_SERVER['PHP_SELF'], '/modules/') !== false) {
        $logout_path = '/bimbingan_konseling/login.php';
    }
    header('Location: ' . $logout_path);
    exit;
}

// Set base path. Ini adalah satu-satunya tempat yang perlu diubah jika aplikasi
// dijalankan di dalam sub-folder yang berbeda.
$base_path = "/bimbingan_konseling";
?>
<!doctype html>
<html lang="id">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $base_path; ?>/assets/css/style.css">

    <title>Dashboard - Aplikasi Bimbingan Konseling</title>
</head>
<body>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-white border-end" id="sidebar-wrapper">
        <div class="sidebar-heading border-bottom bg-light">
            <i class="bi bi-person-circle me-2"></i> Aplikasi BK
        </div>
        <div class="list-group list-group-flush">
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo $base_path; ?>/dashboard.php">
                <i class="bi bi-house-door-fill me-2"></i> Dashboard
            </a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo $base_path; ?>/modules/siswa/daftar_siswa.php">
                <i class="bi bi-people-fill me-2"></i> Data Siswa
            </a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo $base_path; ?>/modules/sosiogram/data_pertemanan.php">
                <i class="bi bi-diagram-3-fill me-2"></i> Data Pertemanan
            </a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo $base_path; ?>/modules/sosiogram/sosiogram_chart.php">
                <i class="bi bi-bar-chart-line-fill me-2"></i> Sosiogram
            </a>
            <a class="list-group-item list-group-item-action list-group-item-danger p-3" href="<?php echo $base_path; ?>/logout.php">
                <i class="bi bi-box-arrow-right me-2"></i> Keluar
            </a>
        </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-fluid">
                <button class="btn btn-primary" id="sidebarToggle"><i class="bi bi-list"></i></button>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                        <li class="nav-item active"><a class="nav-link" href="#!">Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid px-4">
            <!-- Konten utama akan dimulai di sini -->
