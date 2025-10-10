<?php
// File: modules/users/manajemen_user.php
require_once '../../includes/header.php';

// Logika untuk menangani pesan notifikasi
$message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'delete_success') {
        $message = '<div class="alert alert-success" role="alert">User berhasil dihapus.</div>';
    } elseif ($_GET['status'] === 'delete_failed') {
        $message = '<div class="alert alert-danger" role="alert">Gagal menghapus user.</div>';
    } elseif ($_GET['status'] === 'edit_success') {
        $message = '<div class="alert alert-success" role="alert">Data user berhasil diperbarui.</div>';
    }
}

// Ambil semua data user dari database
$query_users = "SELECT id, username, role FROM users ORDER BY role, username ASC";
$result_users = mysqli_query($koneksi, $query_users);
?>

<h1 class="mt-4">Manajemen User</h1>
<p class="lead">Kelola akun login untuk admin dan siswa.</p>

<?php echo $message; ?>

<div class="card shadow-sm mb-4">
    <div class="card-header">
        <i class="bi bi-person-lines-fill me-1"></i>
        Daftar User
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Username</th>
                        <th scope="col">Role</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result_users) > 0): ?>
                        <?php while ($user = mysqli_fetch_assoc($result_users)): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><span class="badge bg-<?php echo $user['role'] === 'admin' ? 'primary' : 'secondary'; ?>"><?php echo ucfirst($user['role']); ?></span></td>
                                <td>
                                    <a href="edit_user?id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="bi bi-key-fill"></i> Edit Password/Role
                                    </a>
                                    <?php
                                    // Jangan izinkan admin utama (id=1) menghapus dirinya sendiri
                                    if ($user['id'] != 1): ?>
                                    <a href="hapus_user?id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('PERINGATAN: Menghapus user siswa juga akan menghapus data siswa terkait. Yakin ingin menghapus user ini?');">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data user.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
require_once '../../includes/footer.php';
?>