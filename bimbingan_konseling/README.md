# Aplikasi Bimbingan Konseling dengan Sosiogram

Selamat datang di Aplikasi Bimbingan Konseling (BK) dengan Sosiogram. Aplikasi ini dirancang sebagai alat bantu modern untuk para guru BK dalam memahami dinamika sosial dan pola interaksi antar siswa di dalam kelas.

Dengan antarmuka yang bersih, minimalis, dan responsif, aplikasi ini mempermudah pengelolaan data siswa dan visualisasi hubungan sosial melalui diagram sosiogram yang interaktif.

## ✨ Fitur Utama

- **Sistem Autentikasi**: Sistem login yang aman khusus untuk Admin/Guru BK.
- **Manajemen Data Siswa**:
    - **CRUD**: Tambah, lihat, edit, dan hapus data siswa dengan mudah.
    - **Impor Massal**: Impor ratusan data siswa secara cepat dari file **CSV**, menghemat waktu entri manual.
- **Manajemen Interaksi Sosial**:
    - Catat pilihan **positif** (pertemanan) dan **negatif** (penolakan) antar siswa.
    - Antarmuka yang intuitif untuk merekam data, dengan filter per kelas untuk mempermudah pencarian siswa.
- **Visualisasi Sosiogram**:
    - **Chart Interaktif**: Hasilkan diagram sosiogram secara otomatis berdasarkan data interaksi yang telah dimasukkan.
    - **Filter per Kelas**: Fokus pada analisis sosial untuk kelas tertentu.
    - **Unduh Laporan**: Simpan sosiogram yang ditampilkan sebagai file gambar (PNG) untuk keperluan dokumentasi atau laporan.

## 🚀 Teknologi yang Digunakan

- **Backend**: PHP Native (murni, tanpa framework)
- **Frontend**: HTML, CSS, JavaScript (Vanilla JS)
- **Framework UI**: Bootstrap 5
- **Charting Library**: Chart.js
- **Database**: MySQL

## ⚙️ Panduan Instalasi dan Setup

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di lingkungan lokal Anda (misalnya menggunakan XAMPP atau WAMP).

### 1. Dapatkan Kode Sumber
- Unduh atau clone repositori ini ke komputer Anda.
- Letakkan folder proyek `bimbingan_konseling` di dalam direktori root server web Anda (misalnya, `C:\xampp\htdocs\` untuk XAMPP).

### 2. Buat Database
- Buka phpMyAdmin (`http://localhost/phpmyadmin`).
- Buat database baru dengan nama `db_bimbingan_konseling`.
- Pilih database yang baru dibuat, lalu buka tab **Import**.
- Klik "Choose File" dan pilih file `database.sql` yang ada di dalam folder proyek ini.
- Klik **Go** untuk memulai proses impor. Tabel (`users`, `siswa`, `pertemanan`) dan data admin default akan otomatis dibuat.

### 3. Konfigurasi Koneksi Database
- Buka file `bimbingan_konseling/config/koneksi.php`.
- Sesuaikan nilai variabel berikut jika kredensial database Anda berbeda dari default:
  ```php
  $db_host = 'localhost';
  $db_user = 'root';
  $db_pass = '';
  $db_name = 'db_bimbingan_konseling';
  ```

### 4. Jalankan Aplikasi
- Buka browser web Anda dan akses alamat: `http://localhost/bimbingan_konseling/`
- Anda akan disambut oleh halaman landing. Klik tombol "Masuk" untuk menuju halaman login.

## 📖 Cara Menggunakan

1.  **Login ke Sistem**:
    - Gunakan kredensial admin default untuk masuk:
      - **Username**: `admin`
      - **Password**: `admin`

2.  **Kelola Data Siswa**:
    - Buka menu **Data Siswa**.
    - Tambahkan beberapa siswa secara manual atau gunakan fitur **Impor CSV** untuk mengunggah data secara massal.
    - **Format CSV**: Pastikan file CSV Anda memiliki 5 kolom dengan urutan: `nis,nama_lengkap,kelas,jenis_kelamin,alamat`. Baris pertama (header) akan diabaikan oleh sistem.

3.  **Catat Interaksi Pertemanan**:
    - Buka menu **Data Pertemanan**.
    - **Pilih kelas** terlebih dahulu untuk memuat daftar siswa.
    - Pilih **siswa pemilih**, lalu pilih satu atau lebih **siswa yang dipilih**.
    - Tentukan status interaksi (**Positif** atau **Negatif**), lalu simpan.
    - Ulangi untuk siswa lain hingga data interaksi cukup untuk dianalisis.

4.  **Lihat Sosiogram**:
    - Buka menu **Sosiogram**.
    - Pilih kelas yang ingin Anda analisis, lalu klik **Tampilkan Sosiogram**.
    - Diagram akan muncul, menampilkan siswa sebagai titik (biru untuk laki-laki, pink untuk perempuan) dan interaksi sebagai garis panah (hijau untuk positif, merah untuk negatif).
    - Gunakan tombol **Unduh** untuk menyimpan diagram sebagai file PNG.

---
Terima kasih telah menggunakan aplikasi ini!
