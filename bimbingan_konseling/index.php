<!doctype html>
<html lang="id">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Google Fonts (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">

    <title>Selamat Datang di Aplikasi Bimbingan Konseling</title>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top landing-navbar">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-person-workspace"></i> BK Sosiogram
            </a>
            <a href="login.php" class="btn btn-accent fw-bold">Masuk</a>
        </div>
    </nav>

    <!-- Header -->
    <header class="landing-header text-white text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Aplikasi Bimbingan Konseling Modern</h1>
            <p class="lead">Memahami dinamika sosial siswa melalui visualisasi sosiogram yang interaktif dan intuitif.</p>
            <a href="login.php" class="btn btn-lg btn-light fw-bold text-dark">Mulai Gunakan Aplikasi</a>
        </div>
    </header>

    <!-- Features Section -->
    <section class="py-5" style="background-color: #f7fafc;">
        <div class="container py-4">
            <div class="row text-center">
                <div class="col-lg-4 mb-4">
                    <div class="landing-feature-card h-100">
                        <div class="feature-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h3 class="h4">Manajemen Siswa</h3>
                        <p class="text-muted">Kelola data siswa dengan mudah, termasuk impor massal dari file CSV untuk efisiensi kerja.</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="landing-feature-card h-100">
                        <div class="feature-icon">
                            <i class="bi bi-diagram-3-fill"></i>
                        </div>
                        <h3 class="h4">Sosiogram Interaktif</h3>
                        <p class="text-muted">Visualisasikan pola pertemanan dan interaksi sosial dalam kelas dengan chart yang dinamis.</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="landing-feature-card h-100">
                        <div class="feature-icon">
                            <i class="bi bi-fullscreen"></i>
                        </div>
                        <h3 class="h4">Analisis Mendalam</h3>
                        <p class="text-muted">Gunakan mode layar penuh untuk melihat detail sosiogram dan unduh hasil sebagai gambar untuk laporan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 landing-footer text-center">
        <div class="container">
            <p class="m-0">Copyright &copy; BK Sosiogram <?php echo date('Y'); ?></p>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
