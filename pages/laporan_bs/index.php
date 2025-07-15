<?php
include_once(__DIR__ . '/../../includes/header.php');

$tanggal_mulai = date('Y-m-01');
$tanggal_akhir = date('Y-m-t');

if (isset($_GET['tanggal_mulai']) && !empty($_GET['tanggal_mulai'])) {
            $tanggal_mulai = $_GET['tanggal_mulai'];
}
if (isset($_GET['tanggal_akhir']) && !empty($_GET['tanggal_akhir'])) {
            $tanggal_akhir = $_GET['tanggal_akhir'];
}

$stmt = $pdo->prepare("
    SELECT 
        p.nama_produk,
        SUM(rs.jumlah) as total_bs,
        COUNT(rs.id_riwayat) as frekuensi_kejadian
    FROM riwayat_stok rs
    JOIN produk p ON rs.id_produk = p.id_produk
    WHERE rs.tipe_transaksi = 'bs'
      AND DATE(rs.tanggal_transaksi) BETWEEN ? AND ?
    GROUP BY p.id_produk, p.nama_produk
    ORDER BY total_bs DESC
");
$stmt->execute([$tanggal_mulai, $tanggal_akhir]);
$laporan_bs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">Laporan Produk BS (Barang Sisa/Rusak)</h1>
</div>

<div class="card mb-4">
            <div class="card-body">
                        <form method="GET" action="" class="row g-3 align-items-end">
                                    <div class="col-md-5">
                                                <label for="tanggal_mulai" class="form-label">Dari Tanggal</label>
                                                <input type="date" class="form-control" name="tanggal_mulai" value="<?php echo $tanggal_mulai; ?>">
                                    </div>
                                    <div class="col-md-5">
                                                <label for="tanggal_akhir" class="form-label">Sampai Tanggal</label>
                                                <input type="date" class="form-control" name="tanggal_akhir" value="<?php echo $tanggal_akhir; ?>">
                                    </div>
                                    <div class="col-md-2">
                                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                                    </div>
                        </form>
            </div>
</div>

<div class="card">
            <div class="card-header">
                        Menampilkan Laporan dari Tanggal <strong><?php echo date('d F Y', strtotime($tanggal_mulai)); ?></strong> sampai <strong><?php echo date('d F Y', strtotime($tanggal_akhir)); ?></strong>
            </div>
            <div class="card-body">
                        <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                                <thead class="table-dark">
                                                            <tr>
                                                                        <th>No</th>
                                                                        <th>Nama Produk</th>
                                                                        <th class="text-center">Total Kuantitas BS (pcs)</th>
                                                                        <th class="text-center">Frekuensi Kejadian</th>
                                                            </tr>
                                                </thead>
                                                <tbody>
                                                            <?php
                                                            $no = 1;
                                                            $grand_total_bs = 0;
                                                            if (count($laporan_bs) > 0) {
                                                                        foreach ($laporan_bs as $item) {
                                                                                    $grand_total_bs += $item['total_bs'];
                                                            ?>
                                                                                    <tr>
                                                                                                <td><?php echo $no++; ?></td>
                                                                                                <td><?php echo htmlspecialchars($item['nama_produk']); ?></td>
                                                                                                <td class="text-center fw-bold text-danger"><?php echo $item['total_bs']; ?></td>
                                                                                                <td class="text-center"><?php echo $item['frekuensi_kejadian']; ?> kali</td>
                                                                                    </tr>
                                                            <?php
                                                                        }
                                                            } else {
                                                                        echo "<tr><td colspan='4' class='text-center'>Tidak ada data produk BS pada rentang tanggal yang dipilih.</td></tr>";
                                                            }
                                                            ?>
                                                </tbody>
                                                <tfoot class="table-light">
                                                            <tr>
                                                                        <th colspan="2" class="text-end">Grand Total Kuantitas BS:</th>
                                                                        <th class="text-center fs-5 text-danger"><?php echo $grand_total_bs; ?> pcs</th>
                                                                        <th></th>
                                                            </tr>
                                                </tfoot>
                                    </table>
                        </div>
            </div>
</div>

<?php
include_once(__DIR__ . '/../../includes/footer.php');
?>