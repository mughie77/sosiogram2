# Aplikasi Bimbingan Konseling dengan Sosiogram

Selamat datang di Aplikasi Bimbingan Konseling (BK) dengan Sosiogram. Aplikasi ini dirancang sebagai alat bantu modern untuk para guru BK dalam memahami dinamika sosial dan pola interaksi antar siswa di dalam kelas.

Dengan antarmuka yang bersih, minimalis, dan responsif, aplikasi ini mempermudah pengelolaan data siswa dan visualisasi hubungan sosial melalui diagram sosiogram yang interaktif.

## ✨ Fitur Utama

- **Sistem Autentikasi Ganda**: Sistem login terpisah untuk Admin/Guru BK dan Siswa.
- **Manajemen Data Siswa**:
    - **CRUD**: Tambah, lihat, edit, dan hapus data siswa.
    - **Impor Massal**: Impor data siswa secara cepat dari file **CSV**.
    - **Pembuatan Akun Otomatis**: Setiap siswa yang ditambahkan (baik manual maupun impor) akan otomatis dibuatkan akun login.
- **Manajemen Interaksi Sosial**:
    - **Input oleh Siswa**: Siswa dapat login dan memilih sendiri teman yang disukai dan tidak disukai.
    - **Input oleh Admin**: Admin juga dapat mencatat interaksi secara manual jika diperlukan.
- **Visualisasi Sosiogram**:
    - **Chart Interaktif**: Hasilkan diagram sosiogram secara otomatis.
    - **Filter & Analisis**: Filter per kelas, lihat dalam mode layar penuh, dan unduh sebagai gambar PNG lengkap dengan legenda nama.

## 🚀 Teknologi yang Digunakan

- **Backend**: PHP Native
- **Frontend**: HTML, CSS, JavaScript
- **Framework UI**: Bootstrap 5
- **Charting Library**: Chart.js, Chart.js Datalabels, html2canvas
- **Database**: MySQL

## ⚙️ Panduan Instalasi dan Setup

1.  **Dapatkan Kode Sumber**: Unduh atau clone repositori ini ke direktori server web Anda (misal: `htdocs`).
2.  **Buat Database**: Buka phpMyAdmin, buat database baru (misal: `db_bimbingan_konseling`), lalu impor file `database.sql` dari proyek ini.
3.  **Konfigurasi Koneksi**: Edit file `config/koneksi.php` jika kredensial database Anda berbeda.
4.  **Konfigurasi URL (WAJIB)**:
    - Buka file `config/app_config.php`.
    - Ubah nilai `define('BASE_URL', 'https://bk.smkn2bondowoso.sch.id/');` agar sesuai dengan alamat lengkap aplikasi Anda. **Pastikan ada garis miring `/` di akhir.**
5.  **Konfigurasi `.htaccess`**:
    - Buka file `.htaccess` di folder utama proyek.
    - Ubah baris `RewriteBase /` agar sesuai dengan path sub-folder Anda. Jika aplikasi ada di `http://localhost/bk-app/`, ubah menjadi `RewriteBase /bk-app/`. Jika di domain utama, biarkan `/`.
    - Pastikan modul `mod_rewrite` di server Apache Anda sudah aktif.
6.  **Jalankan Aplikasi**: Buka alamat yang sudah Anda atur di `BASE_URL`.

## 📖 Cara Menggunakan

### Untuk Admin / Guru BK

1.  **Login**: Gunakan kredensial `admin` / `admin`.
2.  **Tambah Siswa**: Buka menu **Data Siswa**. Saat siswa baru ditambahkan, akun login mereka (username & password = NIS) akan dibuat secara otomatis.
3.  **Generate Akun Siswa Lama**: Jika ada siswa yang datanya sudah ada sebelum fitur login siswa ditambahkan, klik tombol **Generate Akun** untuk membuatkan mereka akun secara massal.
4.  **Lihat Sosiogram**: Buka menu **Sosiogram**, pilih kelas, dan lihat hasilnya.

### Untuk Siswa

1.  **Login**: Gunakan **NIS** sebagai username dan **NIS** sebagai password.
2.  **Dashboard**: Setelah login, siswa akan melihat profil singkatnya.
3.  **Pilih Pertemanan**: Buka menu **Pilih Pertemanan** untuk memilih teman yang disukai dan tidak disukai. Data ini akan langsung digunakan untuk sosiogram.

---
Terima kasih telah menggunakan aplikasi ini!