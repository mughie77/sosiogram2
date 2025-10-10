<?php
// File: pilih_pertemanan.php
require_once 'includes/header.php'; // Menggunakan header terpusat

$user_id = $_SESSION['user_id'];
$message = '';

// 1. Ambil data siswa yang sedang login
$stmt_current_siswa = mysqli_prepare($koneksi, "SELECT id, kelas FROM siswa WHERE user_id = ?");
mysqli_stmt_bind_param($stmt_current_siswa, "i", $user_id);
mysqli_stmt_execute($stmt_current_siswa);
$result_current_siswa = mysqli_stmt_get_result($stmt_current_siswa);
$current_siswa = mysqli_fetch_assoc($result_current_siswa);

if (!$current_siswa) {
    echo '<div class="alert alert-danger">Data profil Anda tidak ditemukan.</div>';
    require_once 'includes/footer.php'; // Menggunakan footer terpusat
    exit;
}

$id_siswa_pemilih = $current_siswa['id'];
$kelas_siswa = $current_siswa['kelas'];

// 2. Ambil daftar teman sekelas (selain diri sendiri)
$stmt_classmates = mysqli_prepare($koneksi, "SELECT id, nama_lengkap FROM siswa WHERE kelas = ? AND id != ? ORDER BY nama_lengkap ASC");
mysqli_stmt_bind_param($stmt_classmates, "si", $kelas_siswa, $id_siswa_pemilih);
mysqli_stmt_execute($stmt_classmates);
$classmates = mysqli_stmt_get_result($stmt_classmates);

// 3. Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pilihan_positif = isset($_POST['pilihan_positif']) ? $_POST['pilihan_positif'] : [];
    $pilihan_negatif = isset($_POST['pilihan_negatif']) ? $_POST['pilihan_negatif'] : [];

    mysqli_begin_transaction($koneksi);
    try {
        // Hapus pilihan lama siswa ini untuk kelas ini
        $stmt_delete = mysqli_prepare($koneksi, "DELETE FROM pertemanan WHERE id_siswa_pemilih = ? AND kelas = ?");
        mysqli_stmt_bind_param($stmt_delete, "is", $id_siswa_pemilih, $kelas_siswa);
        mysqli_stmt_execute($stmt_delete);

        // Insert pilihan positif
        $stmt_insert_pos = mysqli_prepare($koneksi, "INSERT INTO pertemanan (id_siswa_pemilih, id_siswa_dipilih, status, kelas) VALUES (?, ?, 'positif', ?)");
        foreach ($pilihan_positif as $id_dipilih) {
            mysqli_stmt_bind_param($stmt_insert_pos, "iis", $id_siswa_pemilih, $id_dipilih, $kelas_siswa);
            mysqli_stmt_execute($stmt_insert_pos);
        }

        // Insert pilihan negatif
        $stmt_insert_neg = mysqli_prepare($koneksi, "INSERT INTO pertemanan (id_siswa_pemilih, id_siswa_dipilih, status, kelas) VALUES (?, ?, 'negatif', ?)");
        foreach ($pilihan_negatif as $id_dipilih) {
            mysqli_stmt_bind_param($stmt_insert_neg, "iis", $id_siswa_pemilih, $id_dipilih, $kelas_siswa);
            mysqli_stmt_execute($stmt_insert_neg);
        }

        mysqli_commit($koneksi);
        $message = '<div class="alert alert-success">Pilihan Anda berhasil disimpan. Terima kasih!</div>';

    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        $message = '<div class="alert alert-danger">Terjadi kesalahan saat menyimpan pilihan Anda.</div>';
    }
}
?>

<div class="card shadow-sm">
    <div class="card-header">
        <h4>Formulir Pilihan Pertemanan - Kelas <?php echo htmlspecialchars($kelas_siswa); ?></h4>
    </div>
    <div class="card-body">
        <?php echo $message; ?>
        <p>Silakan pilih teman yang Anda sukai dan tidak sukai di kelas ini. Pilihan Anda akan dirahasiakan.</p>

        <form action="pilih_pertemanan" method="POST">
            <div class="row">
                <!-- Pilihan Positif -->
                <div class="col-md-6 mb-4">
                    <h5>Teman yang Disukai</h5>
                    <p class="small text-muted">Pilih teman yang paling Anda sukai untuk belajar atau bermain bersama.</p>
                    <div class="list-group" style="max-height: 300px; overflow-y: auto;">
                        <?php mysqli_data_seek($classmates, 0); ?>
                        <?php while($row = mysqli_fetch_assoc($classmates)): ?>
                            <label class="list-group-item">
                                <input class="form-check-input me-1" type="checkbox" name="pilihan_positif[]" value="<?php echo $row['id']; ?>">
                                <?php echo htmlspecialchars($row['nama_lengkap']); ?>
                            </label>
                        <?php endwhile; ?>
                    </div>
                </div>

                <!-- Pilihan Negatif -->
                <div class="col-md-6 mb-4">
                    <h5>Teman yang Tidak Disukai</h5>
                    <p class="small text-muted">Pilih teman yang paling Anda hindari atau tidak sukai.</p>
                     <div class="list-group" style="max-height: 300px; overflow-y: auto;">
                        <?php mysqli_data_seek($classmates, 0); ?>
                        <?php while($row = mysqli_fetch_assoc($classmates)): ?>
                            <label class="list-group-item">
                                <input class="form-check-input me-1" type="checkbox" name="pilihan_negatif[]" value="<?php echo $row['id']; ?>">
                                <?php echo htmlspecialchars($row['nama_lengkap']); ?>
                            </label>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save-fill me-1"></i> Simpan Pilihan Saya
                </button>
            </div>
        </form>
    </div>
</div>

<?php
require_once 'includes/footer.php'; // Menggunakan footer terpusat
?>