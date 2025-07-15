<?php
include_once(__DIR__ . '/../../includes/header.php');
?>

<div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">Data Opname Produk</h1>
            <a href="tambah.php" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Data Opname
            </a>
</div>

<div class="card">
            <div class="card-body">
                        <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                                <thead class="table-dark">
                                                            <tr>
                                                                        <th>Tanggal</th>
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
                                                            $query = $pdo->query("SELECT op.*, p.nama_produk FROM opname_produk op JOIN produk p ON op.id_produk = p.id_produk ORDER BY op.tanggal_opname DESC");
                                                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                                        <tr>
                                                                                    <td><?php echo date('d/m/Y', strtotime($row['tanggal_opname'])); ?></td>
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
                                                            <?php } ?>
                                                </tbody>
                                    </table>
                        </div>
            </div>
</div>

<?php
include_once(__DIR__ . '/../../includes/footer.php');
?>