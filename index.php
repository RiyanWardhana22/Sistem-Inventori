<?php
require_once('config/config.php');

$total_jenis_produk = $pdo->query("SELECT COUNT(id_produk) FROM produk")->fetchColumn();
$total_stok_keseluruhan = $pdo->query("SELECT SUM(stok_saat_ini) FROM produk")->fetchColumn() ?: 0;
$total_terjual_hari_ini = $pdo->query("SELECT SUM(jumlah) FROM riwayat_stok WHERE tipe_transaksi = 'keluar' AND DATE(tanggal_transaksi) = CURDATE()")->fetchColumn() ?: 0;

$query_stok_kritis = $pdo->query("
    SELECT nama_produk, stok_saat_ini, stok_minimal 
    FROM produk 
    WHERE stok_saat_ini <= stok_minimal AND stok_minimal > 0 
    ORDER BY stok_saat_ini ASC
");

$query_terlaris = $pdo->query("
    SELECT p.nama_produk, SUM(rs.jumlah) as total_terjual 
    FROM riwayat_stok rs
    JOIN produk p ON rs.id_produk = p.id_produk
    WHERE rs.tipe_transaksi = 'keluar' AND rs.tanggal_transaksi >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY p.id_produk, p.nama_produk
    ORDER BY total_terjual DESC
    LIMIT 5
");

$labels_terlaris = [];
$data_terlaris = [];
while ($row = $query_terlaris->fetch(PDO::FETCH_ASSOC)) {
            $labels_terlaris[] = $row['nama_produk'];
            $data_terlaris[] = $row['total_terjual'];
}
?>
<?php
include_once('includes/header.php');
?>

<div class="row">
            <div class="col-md-4 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Jenis Produk</div>
                                                                        <div class="h5 mb-0 fw-bold text-gray-800"><?php echo $total_jenis_produk; ?></div>
                                                            </div>
                                                            <div class="col-auto">
                                                                        <i class="fas fa-box fa-2x text-gray-300"></i>
                                                            </div>
                                                </div>
                                    </div>
                        </div>
            </div>
            <div class="col-md-4 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Total Stok Keseluruhan</div>
                                                                        <div class="h5 mb-0 fw-bold text-gray-800"><?php echo $total_stok_keseluruhan; ?> pcs</div>
                                                            </div>
                                                            <div class="col-auto">
                                                                        <i class="fas fa-boxes fa-2x text-gray-300"></i>
                                                            </div>
                                                </div>
                                    </div>
                        </div>
            </div>
            <div class="col-md-4 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                        <div class="text-xs fw-bold text-info text-uppercase mb-1">Terjual Hari Ini</div>
                                                                        <div class="h5 mb-0 fw-bold text-gray-800"><?php echo $total_terjual_hari_ini; ?> pcs</div>
                                                            </div>
                                                            <div class="col-auto">
                                                                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                                                            </div>
                                                </div>
                                    </div>
                        </div>
            </div>
</div>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Ringkasan Operasional</h1>
            <div>
                        <a href="<?php echo BASE_URL; ?>pages/stok_masuk/" class="btn btn-primary shadow-sm"><i class="fas fa-plus-circle fa-sm text-white-50"></i> Catat Stok Masuk</a>
                        <a href="<?php echo BASE_URL; ?>pages/stok_keluar/" class="btn btn-success shadow-sm"><i class="fas fa-cart-arrow-down fa-sm text-white-50"></i> Catat Penjualan</a>
            </div>
</div>

<div class="row">
            <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                                <h6 class="m-0 fw-bold text-primary">Produk Terlaris (7 Hari Terakhir)</h6>
                                    </div>
                                    <div class="card-body">
                                                <div class="chart-bar">
                                                            <canvas id="produkTerlarisChart"></canvas>
                                                </div>
                                    </div>
                        </div>
            </div>

            <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                                <h6 class="m-0 fw-bold text-danger">Daftar Stok Kritis</h6>
                                    </div>
                                    <div class="card-body">
                                                <ul class="list-group list-group-flush">
                                                            <?php if ($query_stok_kritis->rowCount() > 0): ?>
                                                                        <?php while ($produk = $query_stok_kritis->fetch(PDO::FETCH_ASSOC)): ?>
                                                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                                <?php echo htmlspecialchars($produk['nama_produk']); ?>
                                                                                                <span class="badge bg-danger rounded-pill"><?php echo $produk['stok_saat_ini']; ?> / <?php echo $produk['stok_minimal']; ?></span>
                                                                                    </li>
                                                                        <?php endwhile; ?>
                                                            <?php else: ?>
                                                                        <li class="list-group-item">Tidak ada produk dengan stok kritis.</li>
                                                            <?php endif; ?>
                                                </ul>
                                    </div>
                        </div>
            </div>
</div>

<script>
            document.addEventListener("DOMContentLoaded", function() {
                        var ctx = document.getElementById("produkTerlarisChart");
                        var myBarChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                                labels: <?php echo json_encode($labels_terlaris); ?>,
                                                datasets: [{
                                                            label: "Jumlah Terjual",
                                                            backgroundColor: "#4e73df",
                                                            hoverBackgroundColor: "#2e59d9",
                                                            borderColor: "#4e73df",
                                                            data: <?php echo json_encode($data_terlaris); ?>,
                                                }],
                                    },
                                    options: {
                                                maintainAspectRatio: false,
                                                scales: {
                                                            y: {
                                                                        beginAtZero: true,
                                                                        ticks: {
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
            });
</script>