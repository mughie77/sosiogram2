<?php
// File: includes/header.php (Unified Header)
session_start();
require_once __DIR__ . '/../config/app_config.php';
require_once __DIR__ . '/../config/koneksi.php';

// Universal redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

// --- Logika untuk Menu Aktif ---
$current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
function is_active($path_segment, $current_path) {
    if (strpos($current_path, $path_segment) !== false) {
        return 'active';
    }
    return '';
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aplikasi Bimbingan Konseling</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body>

<?php if ($_SESSION['role'] === 'admin'): ?>
    <!-- ================================= -->
    <!-- TAMPILAN UNTUK ADMIN (SIDEBAR)    -->
    <!-- ================================= -->
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
                <i class="bi bi-person-workspace"></i> BK Sosiogram
            </div>
            <div class="list-group list-group-flush">
                <a class="list-group-item <?php echo is_active('/dashboard', $current_path); ?>" href="<?php echo BASE_URL; ?>/dashboard">
                    <i class="bi bi-house-door-fill"></i> Dashboard
                </a>
                <a class="list-group-item <?php echo is_active('/modules/siswa', $current_path); ?>" href="<?php echo BASE_URL; ?>/modules/siswa/daftar_siswa">
                    <i class="bi bi-people-fill"></i> Data Siswa
                </a>
                <a class="list-group-item <?php echo is_active('/modules/sosiogram/data_pertemanan', $current_path); ?>" href="<?php echo BASE_URL; ?>/modules/sosiogram/data_pertemanan">
                    <i class="bi bi-diagram-3-fill"></i> Data Pertemanan
                </a>
                <a class="list-group-item <?php echo is_active('/modules/sosiogram/sosiogram_chart', $current_path); ?>" href="<?php echo BASE_URL; ?>/modules/sosiogram/sosiogram_chart">
                    <i class="bi bi-bar-chart-line-fill"></i> Sosiogram
                </a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3 <?php echo is_active('/modules/users', $current_path); ?>" href="<?php echo BASE_URL; ?>/modules/users/manajemen_user">
                    <i class="bi bi-person-badge"></i> Manajemen User
                </a>
                <a class="list-group-item list-group-item-logout" href="<?php echo BASE_URL; ?>/logout">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </a>
            </div>
        </div>
        <!-- Page Content Wrapper -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-sm btn-accent" id="sidebarToggle"><i class="bi bi-list"></i></button>
                    <div class="ms-auto">Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
                </div>
            </nav>
            <div class="container-fluid p-4">
                <!-- Konten Admin Dimulai Di Sini -->

<?php else: // Pengguna adalah 'siswa' ?>
    <!-- ================================= -->
    <!-- TAMPILAN UNTUK SISWA (TOP NAVBAR) -->
    <!-- ================================= -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--app-sidebar-bg);">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?php echo BASE_URL; ?>/dashboard_siswa">
                <i class="bi bi-person-workspace"></i> BK Sosiogram
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo is_active('/dashboard_siswa', $current_path); ?>" href="<?php echo BASE_URL; ?>/dashboard_siswa">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo is_active('/pilih_pertemanan', $current_path); ?>" href="<?php echo BASE_URL; ?>/pilih_pertemanan">Pilih Pertemanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/logout">Keluar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <!-- Konten Siswa Dimulai Di Sini -->
<?php endif; ?>