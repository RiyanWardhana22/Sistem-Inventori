<?php
include_once(__DIR__ . '/../../includes/header.php');
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Data Stok Produk BS</h1>
    <a href="tambah.php" class="btn btn-primary">
        <i class="fas fa-plus-circle me-2"></i> Tambah Data BS
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Tanggal</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th class="text-center">Jumlah</th>
                        <th>Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = $pdo->query("SELECT bs.*, p.nama_produk, p.kode_sku FROM stok_bs bs JOIN produk p ON bs.id_produk = p.id_produk ORDER BY bs.tanggal_bs DESC");
                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                        <tr>
                            <td><?php echo date('d F Y', strtotime($row['tanggal_bs'])); ?></td>
                            <td><?php echo htmlspecialchars($row['kode_sku']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                            <td class="text-center"><?php echo $row['jumlah']; ?></td>
                            <td><?php echo htmlspecialchars($row['keterangan']); ?></td>
                            <td class="text-center">
                                <a href="edit.php?id=<?php echo $row['id_bs']; ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="proses.php?action=hapus&id=<?php echo $row['id_bs']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include_once(__DIR__ . '/../../includes/footer.php');
?>