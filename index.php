<?php
require_once('config/config.php');
include_once('includes/header.php');

$total_opname_hari_ini = $pdo->query("SELECT COUNT(id_opname) FROM opname_produk WHERE tanggal_opname = CURDATE()")->fetchColumn() ?: 0;
$total_bs_hari_ini = $pdo->query("SELECT SUM(jumlah) FROM stok_bs WHERE tanggal_bs = CURDATE()")->fetchColumn() ?: 0;
$total_jenis_produk = $pdo->query("SELECT COUNT(id_produk) FROM produk")->fetchColumn() ?: 0;

$query_bs_chart = $pdo->query("
    SELECT p.nama_produk, SUM(bs.jumlah) as total_kuantitas
    FROM stok_bs bs
    JOIN produk p ON bs.id_produk = p.id_produk
    WHERE bs.tanggal_bs >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY p.id_produk, p.nama_produk
    ORDER BY total_kuantitas DESC
    LIMIT 5
");
$labels_bs = [];
$data_bs_chart = [];
while ($row = $query_bs_chart->fetch(PDO::FETCH_ASSOC)) {
    $labels_bs[] = $row['nama_produk'];
    $data_bs_chart[] = $row['total_kuantitas'];
}

$query_opname_terakhir = $pdo->query("SELECT op.*, p.nama_produk FROM opname_produk op JOIN produk p ON op.id_produk = p.id_produk ORDER BY op.id_opname DESC LIMIT 3");
$query_bs_terakhir = $pdo->query("SELECT bs.*, p.nama_produk FROM stok_bs bs JOIN produk p ON bs.id_produk = p.id_produk ORDER BY bs.id_bs DESC LIMIT 3");
?>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Produk Di-Opname (Hari Ini)</div>
                        <div class="h5 mb-0 fw-bold text-gray-800"><?php echo $total_opname_hari_ini; ?> Data</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-clipboard-list fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-danger text-uppercase mb-1">Kuantitas Produk BS (Hari Ini)</div>
                        <div class="h5 mb-0 fw-bold text-gray-800"><?php echo $total_bs_hari_ini; ?> pcs</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-trash-alt fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-info text-uppercase mb-1">Total Jenis Produk</div>
                        <div class="h5 mb-0 fw-bold text-gray-800"><?php echo $total_jenis_produk; ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-box fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Ringkasan Operasional</h1>
    <div>
        <a href="<?php echo BASE_URL; ?>pages/stok_opname/tambah.php" class="btn btn-primary shadow-sm"><i class="fas fa-plus-circle fa-sm text-white-50"></i> Tambah Data Opname</a>
        <a href="<?php echo BASE_URL; ?>pages/stok_bs/tambah.php" class="btn btn-danger shadow-sm"><i class="fas fa-trash fa-sm text-white-50"></i> Tambah Data BS</a>
    </div>
</div>

<div class="row">
    <div class="col-xl-7 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">Top 5 Produk BS (7 Hari Terakhir)</h6>
            </div>
            <div class="card-body">
                <div class="chart-bar" style="height: 320px;">
                    <canvas id="produkBsChart"
                        data-labels='<?php echo json_encode($labels_bs); ?>'
                        data-values='<?php echo json_encode($data_bs_chart); ?>'></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-5 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">Aktivitas Terbaru</h6>
            </div>
            <div class="card-body">
                <h6>Opname Terakhir</h6>
                <ul class="list-group list-group-flush mb-3">
                    <?php while ($row = $query_opname_terakhir->fetch(PDO::FETCH_ASSOC)): ?>
                        <li class="list-group-item"><?php echo htmlspecialchars($row['nama_produk']); ?> <span class="float-end text-muted small"><?php echo date('d M Y', strtotime($row['tanggal_opname'])); ?></span></li>
                    <?php endwhile; ?>
                </ul>
                <h6>Stok BS Terakhir</h6>
                <ul class="list-group list-group-flush">
                    <?php while ($row = $query_bs_terakhir->fetch(PDO::FETCH_ASSOC)): ?>
                        <li class="list-group-item"><?php echo htmlspecialchars($row['nama_produk']); ?> <span class="float-end text-danger small"><?php echo $row['jumlah']; ?> pcs</span></li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const chartCanvas = document.getElementById("produkBsChart");
        if (chartCanvas) {
            const labels = JSON.parse(chartCanvas.getAttribute('data-labels'));
            const dataValues = JSON.parse(chartCanvas.getAttribute('data-values'));

            new Chart(chartCanvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Total Kuantitas BS",
                        backgroundColor: "rgba(231, 74, 59, 0.8)",
                        borderColor: "#e74a3b",
                        data: dataValues,
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