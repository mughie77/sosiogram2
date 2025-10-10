<?php
// File: config/app_config.php
// Konfigurasi aplikasi terpusat

// --- Pengaturan URL Absolut Dinamis (Versi Final & Paling Andal) ---

// 1. Tentukan protokol (http atau https)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

// 2. Tentukan nama domain dan hapus titik di akhir jika ada (untuk mengatasi error SNI)
$domain = rtrim($_SERVER['HTTP_HOST'], '.');

// 3. Hitung base path dari root folder proyek
// Ini dilakukan dengan mencari path dari DOCUMENT_ROOT ke file config ini, lalu naik satu level.
$doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$dir_name = str_replace('\\', '/', __DIR__); // __DIR__ adalah folder /config
$base_path = str_replace($doc_root, '', $dir_name); // Hasil: /folder_proyek/config
$base_path = dirname($base_path); // Hasil: /folder_proyek

// Menangani kasus jika proyek ada di root direktori web.
if ($base_path === '/' || $base_path === '\\') {
    $base_path = ''; // Jadikan string kosong agar tidak ada slash ganda di URL.
}

// 4. Gabungkan menjadi URL dasar yang absolut dan simpan sebagai konstanta
define('BASE_URL', $protocol . $domain . $base_path);
?>