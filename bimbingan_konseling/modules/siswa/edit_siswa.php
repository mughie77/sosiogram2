<?php
// File: modules/siswa/edit_siswa.php

require_once '../../includes/header.php';
require_once '../../config/koneksi.php';

// Variabel untuk menyimpan pesan error atau data form
$errors = [];
$nis = $nama_lengkap = $kelas = $jenis_kelamin = $alamat = '';
$student_id = null;

// Ambil ID siswa dari URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $student_id = $_GET['id'];
} else {
    // Jika ID tidak ada atau tidak valid, redirect
    header('Location: daftar_siswa?status=error');
    exit;
}

// Cek jika form telah disubmit (untuk update)
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

    // Cek duplikasi NIS (untuk siswa lain)
    $check_query = "SELECT id FROM siswa WHERE nis = '$nis' AND id != $student_id";
    $check_result = mysqli_query($koneksi, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        $errors[] = "NIS sudah terdaftar untuk siswa lain. Harap gunakan NIS yang unik.";
    }

    // Jika tidak ada error, update data di database
    if (empty($errors)) {
        mysqli_begin_transaction($koneksi);
        try {
            // 1. Update tabel siswa
            $stmt_siswa = mysqli_prepare($koneksi, "UPDATE siswa SET nis = ?, nama_lengkap = ?, kelas = ?, jenis_kelamin = ?, alamat = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt_siswa, "sssssi", $nis, $nama_lengkap, $kelas, $jenis_kelamin, $alamat, $student_id);
            mysqli_stmt_execute($stmt_siswa);

            // 2. Ambil user_id dari siswa
            $stmt_get_user = mysqli_prepare($koneksi, "SELECT user_id FROM siswa WHERE id = ?");
            mysqli_stmt_bind_param($stmt_get_user, "i", $student_id);
            mysqli_stmt_execute($stmt_get_user);
            $user_id = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_get_user))['user_id'];

            if ($user_id) {
                // 3. Update username di tabel users
                $stmt_user = mysqli_prepare($koneksi, "UPDATE users SET username = ? WHERE id = ?");
                mysqli_stmt_bind_param($stmt_user, "si", $nis, $user_id);
                mysqli_stmt_execute($stmt_user);

                // 4. Jika password baru diisi, update password
                if (!empty($_POST['password'])) {
                    $new_password_hashed = sha1($_POST['password']);
                    $stmt_pass = mysqli_prepare($koneksi, "UPDATE users SET password = ? WHERE id = ?");
                    mysqli_stmt_bind_param($stmt_pass, "si", $new_password_hashed, $user_id);
                    mysqli_stmt_execute($stmt_pass);
                }
            }

            mysqli_commit($koneksi);
            header('Location: daftar_siswa?status=success_edit');
            exit;

        } catch (Exception $e) {
            mysqli_rollback($koneksi);
            $errors[] = "Gagal memperbarui data: " . $e->getMessage();
        }
    }
} else {
    // Jika bukan POST request, ambil data siswa dari DB untuk ditampilkan di form
    $query_fetch = "SELECT * FROM siswa WHERE id = $student_id";
    $result_fetch = mysqli_query($koneksi, $query_fetch);

    if ($result_fetch && mysqli_num_rows($result_fetch) == 1) {
        $student = mysqli_fetch_assoc($result_fetch);
        $nis = $student['nis'];
        $nama_lengkap = $student['nama_lengkap'];
        $kelas = $student['kelas'];
        $jenis_kelamin = $student['jenis_kelamin'];
        $alamat = $student['alamat'];
    } else {
        // Jika siswa tidak ditemukan
        header('Location: daftar_siswa?status=not_found');
        exit;
    }
}
?>

<h1 class="mt-4">Edit Data Siswa</h1>
<p class="lead">Ubah informasi siswa pada formulir di bawah ini.</p>

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
        <i class="bi bi-pencil-fill me-1"></i>
        Formulir Edit Data Siswa
    </div>
    <div class="card-body">
        <form action="edit_siswa?id=<?php echo $student_id; ?>" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($nama_lengkap); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kelas" class="form-label">Kelas</label>
                    <input type="text" class="form-control" id="kelas" name="kelas" placeholder="Contoh: X-A, XI-IPA-2" value="<?php echo htmlspecialchars($kelas); ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nis" class="form-label">Username (NIS)</label>
                    <input type="text" class="form-control" id="nis" name="nis" value="<?php echo htmlspecialchars($nis); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Password Baru (Opsional)</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Isi hanya jika ingin mengubah">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="" disabled>-- Pilih --</option>
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
                    <i class="bi bi-save-fill me-1"></i> Simpan Perubahan
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
