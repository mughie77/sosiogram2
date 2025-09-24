<?php
// File: modules/sosiogram/sosiogram_chart.php

require_once '../../includes/header.php';
require_once '../../config/koneksi.php';

// Ambil daftar kelas unik untuk filter
$query_kelas = "SELECT DISTINCT kelas FROM siswa ORDER BY kelas ASC";
$result_kelas = mysqli_query($koneksi, $query_kelas);

$selected_kelas = '';
$chart_data_json = '{}'; // Default data kosong

// Jika form disubmit (kelas dipilih)
if (isset($_GET['kelas']) && !empty($_GET['kelas'])) {
    $selected_kelas = mysqli_real_escape_string($koneksi, $_GET['kelas']);

    // 1. Ambil semua siswa di kelas terpilih (Nodes)
    $query_siswa = "SELECT id, nama_lengkap, jenis_kelamin FROM siswa WHERE kelas = '$selected_kelas' ORDER BY nama_lengkap";
    $result_siswa = mysqli_query($koneksi, $query_siswa);

    $nodes = [];
    $node_map = []; // map id siswa ke index
    $index = 0;
    while ($row = mysqli_fetch_assoc($result_siswa)) {
        $nodes[] = [
            'id' => (int)$row['id'],
            'label' => $row['nama_lengkap'],
            'gender' => $row['jenis_kelamin']
        ];
        $node_map[(int)$row['id']] = $index++;
    }

    // 2. Ambil semua relasi di kelas terpilih (Edges)
    $query_relasi = "SELECT id_siswa_pemilih, id_siswa_dipilih, status FROM pertemanan WHERE kelas = '$selected_kelas'";
    $result_relasi = mysqli_query($koneksi, $query_relasi);

    $edges = [];
    while ($row = mysqli_fetch_assoc($result_relasi)) {
        $source_id = (int)$row['id_siswa_pemilih'];
        $target_id = (int)$row['id_siswa_dipilih'];

        // Pastikan kedua siswa ada di dalam kelas yang difilter
        if (isset($node_map[$source_id]) && isset($node_map[$target_id])) {
            $edges[] = [
                'source' => $node_map[$source_id],
                'target' => $node_map[$target_id],
                'status' => $row['status']
            ];
        }
    }

    // Gabungkan data untuk dikirim ke JavaScript
    $chart_data = [
        'nodes' => $nodes,
        'edges' => $edges
    ];
    $chart_data_json = json_encode($chart_data);
}
?>

<h1 class="mt-4">Visualisasi Sosiogram</h1>
<p class="lead">Analisis pola interaksi sosial siswa dalam bentuk diagram.</p>

<!-- Form Filter -->
<div class="card shadow-sm mb-4">
    <div class="card-header">
        <i class="bi bi-filter-circle-fill me-1"></i>
        Filter Sosiogram
    </div>
    <div class="card-body">
        <form action="sosiogram_chart.php" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="kelas" class="form-label">Pilih Kelas untuk Ditampilkan</label>
                <select class="form-select" id="kelas" name="kelas" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php
                    mysqli_data_seek($result_kelas, 0); // Reset pointer
                    while ($row_kelas = mysqli_fetch_assoc($result_kelas)): ?>
                        <option value="<?php echo htmlspecialchars($row_kelas['kelas']); ?>" <?php echo ($selected_kelas === $row_kelas['kelas']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row_kelas['kelas']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-accent">
                    <i class="bi bi-bar-chart-line-fill me-1"></i> Tampilkan Sosiogram
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Area Chart -->
<?php if (!empty($selected_kelas) && !empty(json_decode($chart_data_json, true)['nodes'])): ?>
<div class="card shadow-sm">
    <div class="card-header">
        <i class="bi bi-diagram-3-fill me-1"></i>
        Hasil Sosiogram untuk Kelas: <strong><?php echo htmlspecialchars($selected_kelas); ?></strong>
        <div class="float-end">
            <button id="downloadChart" class="btn btn-success btn-sm">
                <i class="bi bi-download me-1"></i> Unduh sebagai PNG
            </button>
        </div>
    </div>
    <div class="card-body text-center">
        <canvas id="sociogramChart" style="max-width: 800px; max-height: 800px; margin: auto;"></canvas>
    </div>
</div>
<?php elseif (!empty($selected_kelas)): ?>
<div class="alert alert-warning">Tidak ada data siswa atau interaksi untuk kelas '<?php echo htmlspecialchars($selected_kelas); ?>'. Harap isi data siswa dan data pertemanan terlebih dahulu.</div>
<?php else: ?>
<div class="alert alert-info">Pilih kelas dan klik "Tampilkan Sosiogram" untuk melihat hasilnya.</div>
<?php endif; ?>


<script>
// Pastikan Chart.js sudah dimuat dari footer.php

document.addEventListener('DOMContentLoaded', function() {
    const chartData = <?php echo $chart_data_json; ?>;
    const canvas = document.getElementById('sociogramChart');

    if (canvas && chartData.nodes && chartData.nodes.length > 0) {
        const ctx = canvas.getContext('2d');
        const width = canvas.width;
        const height = canvas.height;
        const radius = Math.min(width, height) * 0.4;
        const center = { x: width / 2, y: height / 2 };
        const nodeCount = chartData.nodes.length;

        // Hitung posisi node dalam lingkaran
        chartData.nodes.forEach((node, i) => {
            const angle = (i / nodeCount) * 2 * Math.PI - (Math.PI / 2); // Mulai dari atas
            node.x = center.x + radius * Math.cos(angle);
            node.y = center.y + radius * Math.sin(angle);
        });

        // Plugin untuk menggambar garis (edges) di belakang titik (nodes)
        const backgroundPlugin = {
            id: 'backgroundPlugin',
            beforeDraw: (chart) => {
                const ctx = chart.ctx;
                ctx.save();

                chartData.edges.forEach(edge => {
                    const sourceNode = chartData.nodes[edge.source];
                    const targetNode = chartData.nodes[edge.target];

                    ctx.beginPath();
                    ctx.moveTo(sourceNode.x, sourceNode.y);
                    ctx.lineTo(targetNode.x, targetNode.y);

                    ctx.lineWidth = 1.5;
                    ctx.strokeStyle = (edge.status === 'positif') ? 'rgba(25, 135, 84, 0.6)' : 'rgba(220, 53, 69, 0.6)';

                    // Gambar panah
                    const angle = Math.atan2(targetNode.y - sourceNode.y, targetNode.x - sourceNode.x);
                    const arrowLength = 10;
                    ctx.lineTo(targetNode.x - arrowLength * Math.cos(angle - Math.PI / 6), targetNode.y - arrowLength * Math.sin(angle - Math.PI / 6));
                    ctx.moveTo(targetNode.x, targetNode.y);
                    ctx.lineTo(targetNode.x - arrowLength * Math.cos(angle + Math.PI / 6), targetNode.y - arrowLength * Math.sin(angle + Math.PI / 6));

                    ctx.stroke();
                });
                ctx.restore();
            }
        };

        new Chart(ctx, {
            type: 'scatter',
            data: {
                datasets: [{
                    label: 'Siswa',
                    data: chartData.nodes,
                    pointRadius: 15,
                    pointHoverRadius: 20,
                    pointBackgroundColor: chartData.nodes.map(n => n.gender === 'L' ? 'rgba(54, 162, 235, 0.8)' : 'rgba(255, 99, 132, 0.8)'), // Biru untuk L, Pink untuk P
                    pointBorderColor: chartData.nodes.map(n => n.gender === 'L' ? 'rgb(54, 162, 235)' : 'rgb(255, 99, 132)'),
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    x: { display: false },
                    y: { display: false }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw.label;
                            }
                        }
                    },
                    datalabels: {
                        color: '#fff',
                        font: { weight: 'bold' },
                        formatter: function(value, context) {
                            // Tampilkan inisial nama
                            const nameParts = value.label.split(' ');
                            if (nameParts.length > 1) {
                                return nameParts[0][0] + nameParts[1][0];
                            }
                            return value.label.substring(0, 2);
                        }
                    }
                }
            },
            plugins: [backgroundPlugin, ChartDataLabels]
        });

        // Fungsi download
        const downloadBtn = document.getElementById('downloadChart');
        if(downloadBtn) {
            downloadBtn.addEventListener('click', function() {
                const link = document.createElement('a');
                link.href = canvas.toDataURL('image/png', 1.0);
                link.download = `sosiogram-kelas-<?php echo htmlspecialchars($selected_kelas); ?>.png`;
                link.click();
            });
        }
    }
});
</script>


<?php
require_once '../../includes/footer.php';
?>
