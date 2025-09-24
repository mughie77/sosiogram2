<?php
// Memulai session di awal
session_start();

// Cek apakah pengguna sudah login dan memiliki role admin
// Jika tidak, redirect ke halaman login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Set base URL dinamis
$base_url = "http://" . $_SERVER['HTTP_HOST'] . "/bimbingan_konseling";
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
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css">

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
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo $base_url; ?>/dashboard.php">
                <i class="bi bi-house-door-fill me-2"></i> Dashboard
            </a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo $base_url; ?>/modules/siswa/daftar_siswa.php">
                <i class="bi bi-people-fill me-2"></i> Data Siswa
            </a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo $base_url; ?>/modules/sosiogram/data_pertemanan.php">
                <i class="bi bi-diagram-3-fill me-2"></i> Data Pertemanan
            </a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?php echo $base_url; ?>/modules/sosiogram/sosiogram_chart.php">
                <i class="bi bi-bar-chart-line-fill me-2"></i> Sosiogram
            </a>
            <a class="list-group-item list-group-item-action list-group-item-danger p-3" href="<?php echo $base_url; ?>/logout.php">
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
