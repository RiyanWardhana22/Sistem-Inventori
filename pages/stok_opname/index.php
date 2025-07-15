<?php
include_once(__DIR__ . '/../../includes/header.php');
$tanggal_filter = $_GET['tanggal'] ?? date('Y-m-d');

$stmt = $pdo->prepare("
    SELECT op.*, p.nama_produk 
    FROM opname_produk op 
    JOIN produk p ON op.id_produk = p.id_produk 
    WHERE op.tanggal_opname = ? 
    ORDER BY p.nama_produk ASC
");
$stmt->execute([$tanggal_filter]);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">Data Opname Produk</h1>
            <a href="tambah.php" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Data Opname
            </a>
</div>

<div class="card mb-4">
            <div class="card-body">
                        <form method="GET" action="" class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                                <label for="tanggal" class="form-label">Pilih Tanggal</label>
                                                <input type="date" class="form-control" name="tanggal" value="<?php echo $tanggal_filter; ?>">
                                    </div>
                                    <div class="col-md-2">
                                                <button type="submit" class="btn btn-primary w-100">Lihat Data</button>
                                    </div>
                        </form>
            </div>
</div>

<div class="card">
            <div class="card-header">
                        Menampilkan Data untuk Tanggal: <strong><?php echo format_hari_tanggal($tanggal_filter); ?></strong>
            </div>
            <div class="card-body">
                        <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                                <thead class="table-dark">
                                                            <tr>
                                                                        <th>Nama Produk</th>
                                                                        <th>Kode</th>
                                                                        <th>Stok Awal</th>
                                                                        <th>Stok Akhir</th>
                                                                        <th>Penjualan</th>
                                                                        <th>BS</th>
                                                                        <th class="text-center">Aksi</th>
                                                            </tr>
                                                </thead>
                                                <tbody>
                                                            <?php
                                                            if ($stmt->rowCount() > 0) {
                                                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                                                    <tr>
                                                                                                <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                                                                                                <td><?php echo htmlspecialchars($row['kode']); ?></td>
                                                                                                <td><?php echo htmlspecialchars($row['stok_awal']); ?></td>
                                                                                                <td><?php echo htmlspecialchars($row['stok_akhir']); ?></td>
                                                                                                <td><?php echo htmlspecialchars($row['penjualan']); ?></td>
                                                                                                <td><?php echo htmlspecialchars($row['bs']); ?></td>
                                                                                                <td class="text-center">
                                                                                                            <a href="edit.php?id=<?php echo $row['id_opname']; ?>" class="btn btn-sm btn-warning">
                                                                                                                        <i class="fas fa-edit"></i>
                                                                                                            </a>
                                                                                                            <a href="proses.php?action=hapus&id=<?php echo $row['id_opname']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?');">
                                                                                                                        <i class="fas fa-trash"></i>
                                                                                                            </a>
                                                                                                </td>
                                                                                    </tr>
                                                            <?php
                                                                        }
                                                            } else {
                                                                        echo "<tr><td colspan='7' class='text-center'>Tidak ada data opname untuk tanggal yang dipilih.</td></tr>";
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