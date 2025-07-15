<?php
include_once(__DIR__ . '/../../includes/header.php');
?>

<div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">Laporan Stok Produk</h1>
</div>

<div class="card">
            <div class="card-header">
                        Posisi Stok Terkini
            </div>
            <div class="card-body">
                        <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                                <thead class="table-dark">
                                                            <tr>
                                                                        <th>No</th>
                                                                        <th>Kode SKU</th>
                                                                        <th>Nama Produk</th>
                                                                        <th class="text-center">Stok Saat Ini</th>
                                                                        <th class="text-center">Aksi</th>
                                                            </tr>
                                                </thead>
                                                <tbody>
                                                            <?php
                                                            $query = $pdo->query("SELECT id_produk, kode_sku, nama_produk, stok_saat_ini FROM produk ORDER BY nama_produk ASC");
                                                            $no = 1;
                                                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                                        <tr>
                                                                                    <td><?php echo $no++; ?></td>
                                                                                    <td><?php echo htmlspecialchars($row['kode_sku']); ?></td>
                                                                                    <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                                                                                    <td class="text-center fw-bold"><?php echo $row['stok_saat_ini']; ?> pcs</td>
                                                                                    <td class="text-center">
                                                                                                <a href="kartu_stok.php?id=<?php echo $row['id_produk']; ?>" class="btn btn-sm btn-info">
                                                                                                            <i class="fas fa-file-alt"></i> Lihat Kartu Stok
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