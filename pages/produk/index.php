<?php
include_once(__DIR__ . '/../../includes/header.php');
?>

<div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">Manajemen Produk</h1>
            <a href="tambah.php" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Produk
            </a>
</div>

<div class="card">
            <div class="card-body">
                        <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                                <thead class="table-dark">
                                                            <tr>
                                                                        <th>No</th>
                                                                        <th>Nama Produk</th>
                                                                        <th class="text-center">Aksi</th>
                                                            </tr>
                                                </thead>
                                                <tbody>
                                                            <?php
                                                            $query = $pdo->query("SELECT * FROM produk ORDER BY nama_produk ASC");
                                                            $no = 1;
                                                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                                        <tr>
                                                                                    <td><?php echo $no++; ?></td>
                                                                                    <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                                                                                    <td class="text-center">
                                                                                                <a href="edit.php?id=<?php echo $row['id_produk']; ?>" class="btn btn-sm btn-warning">
                                                                                                            <i class="fas fa-edit"></i> Edit
                                                                                                </a>
                                                                                                <a href="proses.php?action=hapus&id=<?php echo $row['id_produk']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                                                                                            <i class="fas fa-trash"></i> Hapus
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