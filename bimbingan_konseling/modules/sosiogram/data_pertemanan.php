<?php
// File: modules/sosiogram/data_pertemanan.php

require_once '../../includes/header.php';
require_once '../../config/koneksi.php';

$message = '';

// --- Logika untuk Menambah Data Pertemanan ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_siswa_pemilih'])) {
    $id_siswa_pemilih = (int)$_POST['id_siswa_pemilih'];
    $id_siswa_dipilih_arr = isset($_POST['id_siswa_dipilih']) ? $_POST['id_siswa_dipilih'] : [];
    $status = $_POST['status'];
    $kelas = $_POST['filter_kelas'];

    if ($id_siswa_pemilih && !empty($id_siswa_dipilih_arr) && !empty($status) && !empty($kelas)) {
        $stmt = mysqli_prepare($koneksi, "INSERT INTO pertemanan (id_siswa_pemilih, id_siswa_dipilih, status, kelas) VALUES (?, ?, ?, ?)");

        foreach ($id_siswa_dipilih_arr as $id_siswa_dipilih) {
            $id_siswa_dipilih = (int)$id_siswa_dipilih;
            // Pastikan siswa tidak memilih dirinya sendiri
            if ($id_siswa_pemilih !== $id_siswa_dipilih) {
                mysqli_stmt_bind_param($stmt, "iiss", $id_siswa_pemilih, $id_siswa_dipilih, $status, $kelas);
                mysqli_stmt_execute($stmt);
            }
        }
        mysqli_stmt_close($stmt);
        $message = '<div class="alert alert-success">Data pertemanan berhasil disimpan.</div>';
    } else {
        $message = '<div class="alert alert-danger">Semua field harus diisi.</div>';
    }
}

// --- Logika untuk Menghapus Data Pertemanan ---
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id_pertemanan = (int)$_GET['id'];
    $query_delete = "DELETE FROM pertemanan WHERE id = $id_pertemanan";
    if (mysqli_query($koneksi, $query_delete)) {
        $message = '<div class="alert alert-success">Data pertemanan berhasil dihapus.</div>';
    } else {
        $message = '<div class="alert alert-danger">Gagal menghapus data.</div>';
    }
}


// Ambil daftar kelas unik dari tabel siswa untuk kedua form
$query_kelas = "SELECT DISTINCT kelas FROM siswa ORDER BY kelas ASC";
$result_kelas = mysqli_query($koneksi, $query_kelas);

// --- Logika untuk Filter dan Pencarian Daftar Pertemanan ---
$filter_kelas_list = isset($_GET['filter_kelas_list']) ? $_GET['filter_kelas_list'] : '';
$search_nama = isset($_GET['search_nama']) ? $_GET['search_nama'] : '';

$query_pertemanan = "
    SELECT p.id, pemilih.nama_lengkap as nama_pemilih, dipilih.nama_lengkap as nama_dipilih, p.status, p.kelas
    FROM pertemanan p
    JOIN siswa pemilih ON p.id_siswa_pemilih = pemilih.id
    JOIN siswa dipilih ON p.id_siswa_dipilih = dipilih.id
    WHERE 1=1
";
$params = [];
$types = '';

if (!empty($filter_kelas_list)) {
    $query_pertemanan .= " AND p.kelas = ?";
    $params[] = $filter_kelas_list;
    $types .= 's';
}
if (!empty($search_nama)) {
    $query_pertemanan .= " AND (pemilih.nama_lengkap LIKE ? OR dipilih.nama_lengkap LIKE ?)";
    $search_param = "%" . $search_nama . "%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= 'ss';
}
$query_pertemanan .= " ORDER BY p.kelas, nama_pemilih, nama_dipilih";

$stmt_pertemanan = mysqli_prepare($koneksi, $query_pertemanan);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt_pertemanan, $types, ...$params);
}
mysqli_stmt_execute($stmt_pertemanan);
$result_pertemanan = mysqli_stmt_get_result($stmt_pertemanan);

?>

<h1 class="mt-4">Manajemen Data Pertemanan</h1>
<p class="lead">Catat interaksi sosial (pilihan positif/negatif) antar siswa.</p>

<?php echo $message; ?>

<!-- Form untuk menambah data -->
<div class="card shadow-sm mb-4">
    <div class="card-header">
        <i class="bi bi-plus-circle-fill me-1"></i>
        Tambah Data Interaksi
    </div>
    <div class="card-body">
        <form action="data_pertemanan" method="POST" id="formPertemanan">
            <div class="row align-items-end">
                <div class="col-md-3 mb-3">
                    <label for="filter_kelas" class="form-label">1. Pilih Kelas</label>
                    <select class="form-select" id="filter_kelas" name="filter_kelas" required>
                        <option value="">-- Semua Kelas --</option>
                        <?php
                        mysqli_data_seek($result_kelas, 0); // Reset pointer result set
                        while ($row_kelas = mysqli_fetch_assoc($result_kelas)):
                        ?>
                            <option value="<?php echo htmlspecialchars($row_kelas['kelas']); ?>">
                                <?php echo htmlspecialchars($row_kelas['kelas']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="id_siswa_pemilih" class="form-label">2. Siswa Pemilih</label>
                    <select class="form-select" id="id_siswa_pemilih" name="id_siswa_pemilih" required disabled>
                        <option value="">-- Pilih Kelas Dulu --</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="id_siswa_dipilih" class="form-label">3. Siswa yang Dipilih</label>
                    <select class="form-select" id="id_siswa_dipilih" name="id_siswa_dipilih[]" multiple required disabled>
                        <!-- Pilihan akan diisi oleh JavaScript -->
                    </select>
                    <small class="form-text text-muted">Bisa pilih lebih dari satu (tahan Ctrl/Cmd).</small>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">4. Status</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status_positif" value="positif" checked>
                        <label class="form-check-label" for="status_positif">Positif</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status_negatif" value="negatif">
                        <label class="form-check-label" for="status_negatif">Negatif</label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-accent mt-2">
                <i class="bi bi-save-fill me-1"></i> Simpan Interaksi
            </button>
        </form>
    </div>
</div>

<!-- Form Filter dan Pencarian Daftar -->
<div class="card shadow-sm mb-4">
    <div class="card-header">
        <i class="bi bi-funnel-fill me-1"></i>
        Filter & Pencarian Daftar Interaksi
    </div>
    <div class="card-body">
        <form action="data_pertemanan" method="GET" class="row g-3">
            <div class="col-md-5">
                <label for="search_nama" class="form-label">Cari Nama Siswa</label>
                <input type="text" class="form-control" id="search_nama" name="search_nama" placeholder="Masukkan nama pemilih atau yang dipilih..." value="<?php echo htmlspecialchars($search_nama); ?>">
            </div>
            <div class="col-md-5">
                <label for="filter_kelas_list" class="form-label">Filter per Kelas</label>
                <select class="form-select" id="filter_kelas_list" name="filter_kelas_list">
                    <option value="">-- Semua Kelas --</option>
                    <?php
                    mysqli_data_seek($result_kelas, 0); // Reset pointer
                    while($row_kelas = mysqli_fetch_assoc($result_kelas)):
                    ?>
                        <option value="<?php echo htmlspecialchars($row_kelas['kelas']); ?>" <?php echo ($filter_kelas_list === $row_kelas['kelas']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row_kelas['kelas']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2 w-100">Cari</button>
                <a href="data_pertemanan" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>


<!-- Tabel data yang sudah ada -->
<div class="card shadow-sm">
    <div class="card-header">
        <i class="bi bi-list-ul me-1"></i>
        Daftar Interaksi Tercatat
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Kelas</th>
                        <th>Siswa Pemilih</th>
                        <th>Siswa Dipilih</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result_pertemanan) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result_pertemanan)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['kelas']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_pemilih']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_dipilih']); ?></td>
                            <td>
                                <?php if ($row['status'] == 'positif'): ?>
                                    <span class="badge bg-success">Positif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Negatif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="data_pertemanan?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?');">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">Belum ada data interaksi yang tercatat.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Letakkan script JS di sini agar bisa mengakses variabel PHP jika diperlukan
// Atau lebih baik lagi, letakkan di file script.js dan passing data via data-attributes
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kelasSelect = document.getElementById('filter_kelas');
    const pemilihSelect = document.getElementById('id_siswa_pemilih');
    const dipilihSelect = document.getElementById('id_siswa_dipilih');

    kelasSelect.addEventListener('change', function() {
        const kelas = this.value;

        // Kosongkan dan disable select siswa jika tidak ada kelas yang dipilih
        pemilihSelect.innerHTML = '<option value="">-- Pilih Kelas Dulu --</option>';
        pemilihSelect.disabled = true;
        dipilihSelect.innerHTML = '';
        dipilihSelect.disabled = true;

        if (kelas) {
            // Fetch siswa berdasarkan kelas via AJAX
            fetch(`get_siswa_by_kelas?kelas=${encodeURIComponent(kelas)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    // Aktifkan dan isi select siswa pemilih
                    pemilihSelect.disabled = false;
                    pemilihSelect.innerHTML = '<option value="">-- Pilih Siswa --</option>';
                    data.forEach(siswa => {
                        pemilihSelect.innerHTML += `<option value="${siswa.id}">${siswa.nama_lengkap}</option>`;
                    });

                    // Aktifkan dan isi select siswa yang dipilih
                    dipilihSelect.disabled = false;
                    dipilihSelect.innerHTML = ''; // Dikosongkan dulu
                })
                .catch(error => console.error('Error fetching students:', error));
        }
    });

    // Saat siswa pemilih diganti, update daftar siswa yang bisa dipilih
    pemilihSelect.addEventListener('change', function() {
        const pemilihId = this.value;
        dipilihSelect.innerHTML = ''; // Selalu kosongkan dulu

        // Ambil semua opsi dari pemilihSelect (kecuali yang placeholder)
        const allSiswaOptions = Array.from(pemilihSelect.options).filter(opt => opt.value !== '');

        allSiswaOptions.forEach(option => {
            // Jangan tampilkan siswa pemilih di daftar yang bisa dipilih
            if (option.value !== pemilihId) {
                dipilihSelect.innerHTML += `<option value="${option.value}">${option.text}</option>`;
            }
        });
    });
});
</script>


<?php
require_once '../../includes/footer.php';
?>
