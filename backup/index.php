<?php
require_once('config/config.php');
include_once('includes/header.php');

$total_opname_hari_ini = $pdo->query("SELECT COUNT(id_opname) FROM opname_produk WHERE tanggal_opname = CURDATE()")->fetchColumn() ?: 0;
$total_bs_hari_ini = $pdo->query("SELECT SUM(jumlah) FROM stok_bs WHERE tanggal_bs = CURDATE()")->fetchColumn() ?: 0;
$total_jenis_produk = $pdo->query("SELECT COUNT(kode_produk) FROM produk")->fetchColumn() ?: 0;

$query_bs_chart = $pdo->query("
    SELECT p.nama_produk, SUM(bs.jumlah) as total_kuantitas
    FROM stok_bs bs
    JOIN produk p ON bs.kode_produk = p.kode_produk
    WHERE bs.tanggal_bs >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY p.kode_produk, p.nama_produk
    ORDER BY total_kuantitas DESC
    LIMIT 5
");
$labels_bs = [];
$data_bs_chart = [];
while ($row = $query_bs_chart->fetch(PDO::FETCH_ASSOC)) {
    $labels_bs[] = $row['nama_produk'];
    $data_bs_chart[] = $row['total_kuantitas'];
}

$query_opname_terakhir = $pdo->query("SELECT op.*, p.nama_produk FROM opname_produk op JOIN produk p ON op.kode_produk = p.kode_produk ORDER BY op.id_opname DESC LIMIT 4");
$query_bs_terakhir = $pdo->query("SELECT bs.*, p.nama_produk FROM stok_bs bs JOIN produk p ON bs.kode_produk = p.kode_produk ORDER BY bs.id_bs DESC LIMIT 4");
?>

<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="h3 mb-0">Dashboard</h1>
        </div>
        <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
            <a href="<?php echo BASE_URL; ?>pages/stok_opname/produk/tambah.php" class="btn btn-primary me-2">
                <i class="fas fa-plus me-2"></i>Tambah Opname
            </a>
            <a href="<?php echo BASE_URL; ?>pages/stok_bs/produk/tambah.php" class="btn btn-danger">
                <i class="fas fa-trash me-2"></i>Tambah BS
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="card-title text-muted mb-1">Opname Hari Ini</h6>
                    <h4 class="fw-bold mb-0"><?php echo $total_opname_hari_ini; ?> Data</h4>
                </div>
                <div class="icon-circle bg-primary text-white">
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="card-title text-muted mb-1">Produk BS Hari Ini</h6>
                    <h4 class="fw-bold mb-0"><?php echo $total_bs_hari_ini; ?> pcs</h4>
                </div>
                <div class="icon-circle bg-danger text-white">
                    <i class="fas fa-trash-alt"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12 mb-4">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="card-title text-muted mb-1">Total Jenis Produk</h6>
                    <h4 class="fw-bold mb-0"><?php echo $total_jenis_produk; ?></h4>
                </div>
                <div class="icon-circle bg-info text-white">
                    <i class="fas fa-box"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-7 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Top 5 Produk BS (7 Hari Terakhir)</h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:300px">
                    <canvas id="produkBsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-5 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Aktivitas Terbaru</h5>
            </div>
            <div class="card-body">
                <h6 class="mb-2">Opname Terakhir</h6>
                <ul class="list-group list-group-flush mb-3">
                    <?php while ($row = $query_opname_terakhir->fetch(PDO::FETCH_ASSOC)): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <?php echo htmlspecialchars($row['nama_produk']); ?>
                            <small class="text-muted"><?php echo format_hari_tanggal($row['tanggal_opname']); ?></small>
                        </li>
                    <?php endwhile; ?>
                </ul>
                <h6 class="mb-2">Stok BS Terakhir</h6>
                <ul class="list-group list-group-flush">
                    <?php while ($row = $query_bs_terakhir->fetch(PDO::FETCH_ASSOC)): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <?php echo htmlspecialchars($row['nama_produk']); ?>
                            <span class="badge bg-danger rounded-pill"><?php echo $row['jumlah']; ?> pcs</span>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const chartCanvas = document.getElementById("produkBsChart");
        if (chartCanvas) {
            new Chart(chartCanvas, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($labels_bs); ?>,
                    datasets: [{
                        label: "Total Kuantitas BS",
                        backgroundColor: "rgba(231, 74, 59, 0.8)",
                        borderColor: "#e74a3b",
                        borderRadius: 4,
                        data: <?php echo json_encode($data_bs_chart); ?>,
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (Number.isInteger(value)) {
                                        return value;
                                    }
                                },
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    });
</script>

<?php
include_once('includes/footer.php');
?>