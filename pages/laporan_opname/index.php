<?php
include_once(__DIR__ . '/../../includes/header.php');

$tanggal_akhir = $_GET['tanggal_akhir'] ?? date('Y-m-d');
$tanggal_mulai = $_GET['tanggal_mulai'] ?? date('Y-m-d', strtotime('-7 days', strtotime($tanggal_akhir)));
$stmt = $pdo->prepare("
    SELECT op.*, p.nama_produk 
    FROM opname_produk op 
    JOIN produk p ON op.id_produk = p.id_produk 
    WHERE op.tanggal_opname BETWEEN ? AND ? 
    ORDER BY op.tanggal_opname DESC, p.nama_produk ASC
");
$stmt->execute([$tanggal_mulai, $tanggal_akhir]);
?>

<div class="card mb-4">
    <div class="card-header bg-white border-0 pb-0">
        <div class="my-2">
            <h1 class="h3 mb-0">Laporan Opname</h1>
        </div>
        <hr>
        <h5 class="card-title mb-0">Filter Laporan</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="" class="row gx-2 gy-2 align-items-end">
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
    <div class="card-header border-top">
        Menampilkan Laporan dari <strong><?php echo date('d M Y', strtotime($tanggal_mulai)); ?></strong> sampai <strong><?php echo date('d M Y', strtotime($tanggal_akhir)); ?></strong>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Produk</th>
                        <th>Kode</th>
                        <th>Stok Awal</th>
                        <th>Stok Akhir</th>
                        <th>Penjualan</th>
                        <th>BS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($row['tanggal_opname'])); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                                <td><?php echo htmlspecialchars($row['kode']); ?></td>
                                <td><?php echo htmlspecialchars($row['stok_awal']); ?></td>
                                <td><?php echo htmlspecialchars($row['stok_akhir']); ?></td>
                                <td><?php echo htmlspecialchars($row['penjualan']); ?></td>
                                <td><?php echo htmlspecialchars($row['bs']); ?></td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center py-4'>Tidak ada data opname pada rentang tanggal yang dipilih.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include_once(__DIR__ . '/../../includes/footer.php');
?>