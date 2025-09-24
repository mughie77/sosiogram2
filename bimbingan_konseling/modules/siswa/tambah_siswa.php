<?php
// File: modules/siswa/tambah_siswa.php

require_once '../../includes/header.php';
require_once '../../config/koneksi.php';

// Variabel untuk menyimpan pesan error atau data form
$errors = [];
$nis = $nama_lengkap = $kelas = $jenis_kelamin = $alamat = '';

// Cek jika form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil dan sanitasi data dari form
    $nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    // Validasi dasar
    if (empty($nis)) $errors[] = "NIS tidak boleh kosong.";
    if (empty($nama_lengkap)) $errors[] = "Nama lengkap tidak boleh kosong.";
    if (empty($kelas)) $errors[] = "Kelas tidak boleh kosong.";
    if (empty($jenis_kelamin)) $errors[] = "Jenis kelamin tidak boleh kosong.";

    // Cek duplikasi NIS
    $check_query = "SELECT id FROM siswa WHERE nis = '$nis'";
    $check_result = mysqli_query($koneksi, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        $errors[] = "NIS sudah terdaftar. Harap gunakan NIS yang lain.";
    }

    // Jika tidak ada error, masukkan data ke database
    if (empty($errors)) {
        $query = "INSERT INTO siswa (nis, nama_lengkap, kelas, jenis_kelamin, alamat) VALUES ('$nis', '$nama_lengkap', '$kelas', '$jenis_kelamin', '$alamat')";

        if (mysqli_query($koneksi, $query)) {
            // Jika berhasil, redirect ke halaman daftar siswa dengan pesan sukses
            header('Location: daftar_siswa?status=success_add');
            exit;
        } else {
            // Jika gagal
            $errors[] = "Gagal menyimpan data ke database: " . mysqli_error($koneksi);
        }
    }
}
?>

<h1 class="mt-4">Tambah Data Siswa Baru</h1>
<p class="lead">Isi formulir di bawah ini untuk menambahkan siswa baru.</p>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <strong>Terjadi kesalahan:</strong>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header">
        <i class="bi bi-person-plus-fill me-1"></i>
        Formulir Data Siswa
    </div>
    <div class="card-body">
        <form action="tambah_siswa" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nis" class="form-label">NIS (Nomor Induk Siswa)</label>
                    <input type="text" class="form-control" id="nis" name="nis" value="<?php echo htmlspecialchars($nis); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($nama_lengkap); ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="kelas" class="form-label">Kelas</label>
                    <input type="text" class="form-control" id="kelas" name="kelas" placeholder="Contoh: X-A, XI-IPA-2" value="<?php echo htmlspecialchars($kelas); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="" disabled selected>-- Pilih --</option>
                        <option value="L" <?php echo ($jenis_kelamin === 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                        <option value="P" <?php echo ($jenis_kelamin === 'P') ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3"><?php echo htmlspecialchars($alamat); ?></textarea>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-accent">
                    <i class="bi bi-save-fill me-1"></i> Simpan Data
                </button>
                <a href="daftar_siswa" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-1"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?php
require_once '../../includes/footer.php';
?>
