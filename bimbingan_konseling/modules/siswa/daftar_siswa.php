<?php
// File: modules/siswa/daftar_siswa.php

require_once '../../includes/header.php';
require_once '../../config/koneksi.php';

// Logika untuk menangani pesan notifikasi
$message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success_add') {
        $message = '<div class="alert alert-success" role="alert">Data siswa berhasil ditambahkan.</div>';
    } elseif ($_GET['status'] === 'success_edit') {
        $message = '<div class="alert alert-success" role="alert">Data siswa berhasil diperbarui.</div>';
    } elseif ($_GET['status'] === 'success_delete') {
        $message = '<div class="alert alert-success" role="alert">Data siswa berhasil dihapus.</div>';
    } elseif ($_GET['status'] === 'success_import') {
        $message = '<div class="alert alert-success" role="alert">Data siswa berhasil diimpor dari CSV.</div>';
    } elseif ($_GET['status'] === 'error') {
        $message = '<div class="alert alert-danger" role="alert">Terjadi kesalahan. Silakan coba lagi.</div>';
    }
}


// --- Logika untuk Filter dan Pencarian ---

// Ambil daftar kelas unik untuk dropdown filter
$query_kelas = "SELECT DISTINCT kelas FROM siswa ORDER BY kelas ASC";
$result_kelas = mysqli_query($koneksi, $query_kelas);

// Inisialisasi variabel filter
$filter_kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Bangun query dasar
$query = "SELECT id, nis, nama_lengkap, kelas, jenis_kelamin FROM siswa WHERE 1=1";
$params = [];
$types = '';

// Tambahkan kondisi filter kelas jika ada
if (!empty($filter_kelas)) {
    $query .= " AND kelas = ?";
    $params[] = $filter_kelas;
    $types .= 's';
}

// Tambahkan kondisi pencarian jika ada
if (!empty($search_query)) {
    $query .= " AND (nama_lengkap LIKE ? OR nis LIKE ?)";
    $search_param = "%" . $search_query . "%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= 'ss';
}

$query .= " ORDER BY kelas, nama_lengkap ASC";

// Gunakan prepared statement untuk keamanan
$stmt = mysqli_prepare($koneksi, $query);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<h1 class="mt-4">Data Siswa</h1>
<p class="lead">Kelola data siswa yang terdaftar dalam sistem.</p>

<?php echo $message; // Tampilkan pesan notifikasi ?>

<!-- Form Filter dan Pencarian -->
<div class="card shadow-sm mb-4">
    <div class="card-header">
        <i class="bi bi-funnel-fill me-1"></i>
        Filter & Pencarian
    </div>
    <div class="card-body">
        <form action="daftar_siswa" method="GET" class="row g-3">
            <div class="col-md-5">
                <label for="search" class="form-label">Cari Nama / NIS</label>
                <input type="text" class="form-control" id="search" name="search" placeholder="Masukkan nama atau NIS..." value="<?php echo htmlspecialchars($search_query); ?>">
            </div>
            <div class="col-md-5">
                <label for="kelas" class="form-label">Filter per Kelas</label>
                <select class="form-select" id="kelas" name="kelas">
                    <option value="">-- Semua Kelas --</option>
                    <?php while($row_kelas = mysqli_fetch_assoc($result_kelas)): ?>
                        <option value="<?php echo htmlspecialchars($row_kelas['kelas']); ?>" <?php echo ($filter_kelas === $row_kelas['kelas']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row_kelas['kelas']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2 w-100">Cari</button>
                <a href="daftar_siswa" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header">
        <i class="bi bi-people-fill me-1"></i>
        Daftar Siswa
        <div class="float-end">
            <a href="tambah_siswa" class="btn btn-accent btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Tambah Siswa
            </a>
            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Impor CSV
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">NIS</th>
                        <th scope="col">Nama Lengkap</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">L/P</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <th scope="row"><?php echo $no++; ?></th>
                                <td><?php echo htmlspecialchars($row['nis']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                                <td><?php echo htmlspecialchars($row['kelas']); ?></td>
                                <td><?php echo htmlspecialchars($row['jenis_kelamin']); ?></td>
                                <td>
                                    <a href="edit_siswa?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="hapus_siswa?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?');">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data siswa.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal untuk Impor CSV -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Impor Data Siswa dari CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="impor_siswa" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="csvFile" class="form-label">Pilih File CSV</label>
                        <input class="form-control" type="file" id="csvFile" name="csvFile" accept=".csv" required>
                    </div>
                    <p class="small text-muted">
                        <strong>Format CSV:</strong> Pastikan file CSV Anda memiliki kolom dengan urutan: <code>nis,nama_lengkap,kelas,jenis_kelamin,alamat</code>. Baris pertama (header) akan diabaikan.
                    </p>
                    <button type="submit" class="btn btn-primary">Impor</button>
                </form>
            </div>
        </div>
    </div>
</div>


<?php
require_once '../../includes/footer.php';
?>
