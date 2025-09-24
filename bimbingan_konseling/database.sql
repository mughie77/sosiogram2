-- File: database.sql
-- Deskripsi: Skema database untuk aplikasi Bimbingan Konseling.
-- Target DBMS: MySQL

--
-- Membuat database (jika belum ada)
-- Pengguna disarankan untuk membuat database secara manual terlebih dahulu.
-- Contoh: CREATE DATABASE db_bimbingan_konseling;
--

--
-- Struktur tabel untuk `users`
-- Menyimpan data login untuk admin dan (nantinya) siswa.
--
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','siswa') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Data default untuk tabel `users`
-- Menambahkan admin default dengan password 'admin'
--
INSERT INTO `users` (`username`, `password`, `role`) VALUES
('admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin');
-- Catatan: Password di atas adalah hasil dari sha1('admin').
-- Untuk production, sangat disarankan menggunakan password_hash() dan password_verify().

-- --------------------------------------------------------

--
-- Struktur tabel untuk `siswa`
-- Menyimpan data profil lengkap siswa.
--
CREATE TABLE `siswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nis` varchar(20) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `alamat` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nis` (`nis`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur tabel untuk `pertemanan`
-- Menyimpan data relasi/interaksi antar siswa yang menjadi dasar sosiogram.
--
CREATE TABLE `pertemanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_siswa_pemilih` int(11) NOT NULL,
  `id_siswa_dipilih` int(11) NOT NULL,
  `status` enum('positif','negatif') NOT NULL,
  `kelas` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_siswa_pemilih` (`id_siswa_pemilih`),
  KEY `id_siswa_dipilih` (`id_siswa_dipilih`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pertemanan`
--
ALTER TABLE `pertemanan`
  ADD CONSTRAINT `pertemanan_ibfk_1` FOREIGN KEY (`id_siswa_pemilih`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pertemanan_ibfk_2` FOREIGN KEY (`id_siswa_dipilih`) REFERENCES `siswa` (`id`) ON DELETE CASCADE;
