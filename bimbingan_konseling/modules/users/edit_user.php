<?php
// File: modules/users/edit_user.php
require_once '../../includes/header.php';

$user_id_to_edit = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$errors = [];
$message = '';
$user_data = null;

// Ambil data user yang akan di-edit
$stmt = mysqli_prepare($koneksi, "SELECT id, username, role FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id_to_edit);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);

if (!$user_data) {
    // Redirect jika user tidak ditemukan
    header('Location: manajemen_user?status=not_found');
    exit;
}

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_role = $_POST['role'];
    $new_password = $_POST['password'];

    // Validasi role
    if (!in_array($new_role, ['admin', 'siswa'])) {
        $errors[] = "Role tidak valid.";
    }
    // Jangan izinkan admin utama diubah menjadi siswa
    if ($user_id_to_edit === 1 && $new_role !== 'admin') {
        $errors[] = "Tidak dapat mengubah role admin utama.";
    }

    if (empty($errors)) {
        // Update role
        $stmt_role = mysqli_prepare($koneksi, "UPDATE users SET role = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt_role, "si", $new_role, $user_id_to_edit);
        mysqli_stmt_execute($stmt_role);

        // Jika password baru diisi, update password
        if (!empty($new_password)) {
            $password_hashed = sha1($new_password);
            $stmt_pass = mysqli_prepare($koneksi, "UPDATE users SET password = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt_pass, "si", $password_hashed, $user_id_to_edit);
            mysqli_stmt_execute($stmt_pass);
        }

        header('Location: manajemen_user?status=edit_success');
        exit;
    }
}
?>

<h1 class="mt-4">Edit User</h1>
<p class="lead">Ubah role atau password untuk user: <strong><?php echo htmlspecialchars($user_data['username']); ?></strong></p>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header">
        <i class="bi bi-key-fill me-1"></i>
        Formulir Edit User
    </div>
    <div class="card-body">
        <form action="edit_user?id=<?php echo $user_id_to_edit; ?>" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" disabled>
                <small class="form-text text-muted">Username (NIS) hanya bisa diubah melalui halaman Edit Data Siswa.</small>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password Baru (Opsional)</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Isi hanya jika ingin mengubah password">
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" <?php echo ($user_id_to_edit === 1) ? 'disabled' : ''; ?>>
                    <option value="admin" <?php echo ($user_data['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                    <option value="siswa" <?php echo ($user_data['role'] === 'siswa') ? 'selected' : ''; ?>>Siswa</option>
                </select>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-accent">Simpan Perubahan</button>
                <a href="manajemen_user" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php
require_once '../../includes/footer.php';
?>