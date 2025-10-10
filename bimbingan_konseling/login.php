<?php
// File: login.php

// Memulai session
session_start();

// Jika pengguna sudah login, redirect ke dashboard yang sesuai
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: dashboard');
        exit;
    } elseif ($_SESSION['role'] === 'siswa') {
        header('Location: dashboard_siswa');
        exit;
    }
}

// Include file koneksi database
require_once 'config/koneksi.php';

// Variabel untuk menyimpan pesan error
$error_message = '';

// Cek jika form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi dasar
    if (empty($username) || empty($password)) {
        $error_message = 'Username dan password tidak boleh kosong.';
    } else {
        // Gunakan prepared statement untuk keamanan
        $stmt = mysqli_prepare($koneksi, "SELECT id, username, password, role FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            // Verifikasi password (menggunakan sha1 untuk contoh ini)
            if ($user['password'] === sha1($password)) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect berdasarkan role
                if ($user['role'] === 'admin') {
                    header('Location: dashboard');
                    exit;
                } elseif ($user['role'] === 'siswa') {
                    header('Location: dashboard_siswa');
                    exit;
                }
            } else {
                // Jika password salah
                $error_message = 'Username atau password salah.';
            }
        } else {
            // Jika user tidak ditemukan
            $error_message = 'Username atau password salah.';
        }
    }
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

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">

    <title>Login - Aplikasi Bimbingan Konseling</title>
</head>
<body>

    <div class="login-main-wrapper">
        <!-- Form Container -->
        <div class="login-form-container">
            <div class="login-card">
                <div class="text-center mb-5">
                    <h3 class="fw-bold">Login Sistem</h3>
                    <p class="text-muted">Gunakan akun Anda untuk masuk.</p>
                </div>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <form action="login" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username (NIS untuk Siswa)</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username..." required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password..." required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-accent btn-lg">Masuk</button>
                    </div>
                </form>
                <div class="text-center mt-4">
                    <a href="index" class="text-muted small">Kembali ke Halaman Utama</a>
                </div>
            </div>
        </div>

        <!-- Branding Panel -->
        <div class="login-branding-panel">
            <div>
                <i class="bi bi-person-workspace" style="font-size: 4rem; color: var(--app-accent-color);"></i>
                <h2 class="mt-3">BK Sosiogram</h2>
                <p>Memahami Interaksi, Membangun Potensi.</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>