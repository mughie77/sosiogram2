<?php
// Memulai session di awal
session_start();

// --- Pengaturan Path Dinamis (Versi Final) ---
// Menyamakan format pemisah direktori untuk kalkulasi yang andal di semua OS.
$doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$dir_name = str_replace('\\', '/', __DIR__);

// Menghitung path URL relatif dari document root.
$base_path = str_replace($doc_root, '', $dir_name); // Hasil: /folder_proyek/includes
$base_path = dirname($base_path); // Hasil: /folder_proyek

// Menangani kasus jika proyek ada di root direktori web.
if ($base_path === '/' || $base_path === '\\') {
    $base_path = ''; // Jadikan string kosong agar tidak ada slash ganda di URL.
}


// Cek apakah pengguna sudah login dan memiliki role admin
// Jika tidak, redirect ke halaman login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Menggunakan base_path dinamis untuk redirect yang andal
    header('Location: ' . $base_path . '/login');
    exit;
}

// --- Logika untuk Menu Aktif ---
$current_page_url = $_SERVER['REQUEST_URI']; // Gunakan REQUEST_URI untuk URL bersih

function is_active($path, $current_page_url, $base_path) {
    // Cek jika path yang diberikan ada di dalam URL halaman saat ini.
    $url_to_check = $base_path . $path;
    if (strpos($current_page_url, $url_to_check) !== false) {
        return 'active';
    }
    // Khusus untuk dashboard, cek path yang sama persis
    if ($path === '/dashboard' && $current_page_url === $base_path . '/dashboard') {
        return 'active';
    }
    return '';
}
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

    <!-- Custom CSS with Cache Busting -->
    <link rel="stylesheet" href="<?php echo $base_path; ?>/assets/css/style.css?v=<?php echo time(); ?>">

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
            <a class="list-group-item list-group-item-action list-group-item-light p-3 <?php echo is_active('/dashboard', $current_page_url, $base_path); ?>" href="<?php echo $base_path; ?>/dashboard">
                <i class="bi bi-house-door-fill me-2"></i> Dashboard
            </a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3 <?php echo is_active('/modules/siswa', $current_page_url, $base_path); ?>" href="<?php echo $base_path; ?>/modules/siswa/daftar_siswa">
                <i class="bi bi-people-fill me-2"></i> Data Siswa
            </a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3 <?php echo is_active('/modules/sosiogram/data_pertemanan', $current_page_url, $base_path); ?>" href="<?php echo $base_path; ?>/modules/sosiogram/data_pertemanan">
                <i class="bi bi-diagram-3-fill me-2"></i> Data Pertemanan
            </a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3 <?php echo is_active('/modules/sosiogram/sosiogram_chart', $current_page_url, $base_path); ?>" href="<?php echo $base_path; ?>/modules/sosiogram/sosiogram_chart">
                <i class="bi bi-bar-chart-line-fill me-2"></i> Sosiogram
            </a>
            <a class="list-group-item list-group-item-action list-group-item-danger p-3" href="<?php echo $base_path; ?>/logout">
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
