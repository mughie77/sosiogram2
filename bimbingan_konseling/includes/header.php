<?php
// Memulai session di awal
session_start();

// --- Pengaturan Path Dinamis ---
// Membuat base_path secara dinamis agar tidak perlu diedit manual.
// Mengambil path dari root dokumen server ke folder 'includes' tempat file ini berada.
$base_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__);
// Naik satu level untuk mendapatkan root folder aplikasi.
$base_path = dirname($base_path);
// Mengganti backslash (Windows) dengan forward slash (URL).
$base_path = str_replace('\\', '/', $base_path);
// Jika aplikasi ada di root folder web, base_path akan menjadi '/'. Untuk konsistensi, kita bisa buat jadi string kosong.
if ($base_path === '/') {
    $base_path = '';
}


// Cek apakah pengguna sudah login dan memiliki role admin
// Jika tidak, redirect ke halaman login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Menggunakan base_path dinamis untuk redirect yang andal
    header('Location: ' . $base_path . '/login.php');
    exit;
}

// --- Logika untuk Menu Aktif ---
$current_page_url = $_SERVER['PHP_SELF'];

function is_active($path, $current_page_url, $base_path) {
    // Cek jika path yang diberikan ada di dalam URL halaman saat ini.
    // Contoh: /bk/modules/siswa/daftar_siswa.php akan cocok dengan path /modules/siswa/
    if (strpos($current_page_url, $base_path . $path) !== false) {
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

<div style='background: #fff3cd; color: #664d03; padding: 15px; border: 1px solid #ffc107; z-index: 9999; position: relative; margin: 10px; border-radius: 5px;'>
    <h5 style='margin-top:0; color: #664d03;'>DEBUGGING INFO (Panel ini bisa dihapus nanti)</h5>
    <pre style='white-space: pre-wrap; word-wrap: break-word; font-size: 14px; margin: 0;'>
DOCUMENT_ROOT: <?php echo htmlspecialchars($_SERVER['DOCUMENT_ROOT']); ?><br>
__DIR__:         <?php echo htmlspecialchars(__DIR__); ?><br>
Calculated \$base_path: <?php echo htmlspecialchars($base_path); ?><br>
Current Page URL: <?php echo htmlspecialchars($current_page_url); ?><br>
CSS Path:      &lt;link rel="stylesheet" href="<?php echo htmlspecialchars($base_path . '/assets/css/style.css?v=' . time()); ?>"&gt;
    </pre>
</div>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-white border-end" id="sidebar-wrapper">
        <div class="sidebar-heading border-bottom bg-light">
            <i class="bi bi-person-circle me-2"></i> Aplikasi BK
        </div>
        <div class="list-group list-group-flush">
            <a class="list-group-item list-group-item-action list-group-item-light p-3 <?php echo is_active('/dashboard.php', $current_page_url, $base_path); ?>" href="<?php echo $base_path; ?>/dashboard.php">
                <i class="bi bi-house-door-fill me-2"></i> Dashboard
            </a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3 <?php echo is_active('/modules/siswa/', $current_page_url, $base_path); ?>" href="<?php echo $base_path; ?>/modules/siswa/daftar_siswa.php">
                <i class="bi bi-people-fill me-2"></i> Data Siswa
            </a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3 <?php echo is_active('/modules/sosiogram/data_pertemanan.php', $current_page_url, $base_path); ?>" href="<?php echo $base_path; ?>/modules/sosiogram/data_pertemanan.php">
                <i class="bi bi-diagram-3-fill me-2"></i> Data Pertemanan
            </a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3 <?php echo is_active('/modules/sosiogram/sosiogram_chart.php', $current_page_url, $base_path); ?>" href="<?php echo $base_path; ?>/modules/sosiogram/sosiogram_chart.php">
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
