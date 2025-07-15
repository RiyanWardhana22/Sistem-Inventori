<?php
include_once(__DIR__ . '/../../includes/header.php');
?>

<div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">Manajemen Produk BS (Barang Sisa/Rusak)</h1>
</div>

<div class="card mb-4">
            <div class="card-header">
                        Form Pencatatan Produk BS
            </div>
            <div class="card-body">
                        <form action="proses.php" method="POST">
                                    <input type="hidden" name="action" value="tambah_stok_bs">

                                    <div class="row">
                                                <div class="col-md-4">
                                                            <div class="mb-3">
                                                                        <label for="id_produk" class="form-label">Nama Produk</label>
                                                                        <select class="form-select" id="id_produk" name="id_produk" required>
                                                                                    <option value="" disabled selected>-- Pilih Produk --</option>
                                                                                    <?php
                                                                                    $queryProduk = $pdo->query("SELECT id_produk, nama_produk, stok_saat_ini FROM produk WHERE stok_saat_ini > 0 ORDER BY nama_produk ASC");
                                                                                    while ($produk = $queryProduk->fetch(PDO::FETCH_ASSOC)) {
                                                                                                echo "<option value='{$produk['id_produk']}'>{$produk['nama_produk']} (Stok: {$produk['stok_saat_ini']})</option>";
                                                                                    }
                                                                                    ?>
                                                                        </select>
                                                            </div>
                                                </div>
                                                <div class="col-md-2">
                                                            <div class="mb-3">
                                                                        <label for="jumlah" class="form-label">Jumlah BS (pcs)</label>
                                                                        <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" required>
                                                            </div>
                                                </div>
                                                <div class="col-md-4">
                                                            <div class="mb-3">
                                                                        <label for="keterangan" class="form-label">Keterangan</label>
                                                                        <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Contoh: Hancur saat pengiriman" required>
                                                            </div>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                            <div class="mb-3">
                                                                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Catat BS</button>
                                                            </div>
                                                </div>
                                    </div>
                        </form>
            </div>
</div>

<div class="card">
            <div class="card-header">
                        Riwayat Produk BS Terakhir
            </div>
            <div class="card-body">
                        <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                                <thead class="table-dark">
                                                            <tr>
                                                                        <th>No</th>
                                                                        <th>Tanggal</th>
                                                                        <th>Nama Produk</th>
                                                                        <th>Jumlah</th>
                                                                        <th>Keterangan</th>
                                                            </tr>
                                                </thead>
                                                <tbody>
                                                            <?php
                                                            $queryRiwayat = $pdo->query("
                        SELECT rs.tanggal_transaksi, rs.jumlah, rs.keterangan, p.nama_produk 
                        FROM riwayat_stok rs
                        JOIN produk p ON rs.id_produk = p.id_produk
                        WHERE rs.tipe_transaksi = 'bs'
                        ORDER BY rs.tanggal_transaksi DESC
                        LIMIT 20
                    ");
                                                            $no = 1;
                                                            while ($data = $queryRiwayat->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                                        <tr>
                                                                                    <td><?php echo $no++; ?></td>
                                                                                    <td><?php echo date('d F Y, H:i', strtotime($data['tanggal_transaksi'])); ?></td>
                                                                                    <td><?php echo htmlspecialchars($data['nama_produk']); ?></td>
                                                                                    <td><strong class="text-warning">-<?php echo $data['jumlah']; ?> pcs</strong></td>
                                                                                    <td><?php echo htmlspecialchars($data['keterangan']); ?></td>
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