<?php
include_once(__DIR__ . '/../../includes/header.php');
?>

<div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">Stok Masuk (Produksi Harian)</h1>
</div>

<div class="card mb-4">
            <div class="card-header">
                        Form Pencatatan Produksi
            </div>
            <div class="card-body">
                        <form action="proses.php" method="POST">
                                    <input type="hidden" name="action" value="tambah_stok_masuk">

                                    <div class="row">
                                                <div class="col-md-5">
                                                            <div class="mb-3">
                                                                        <label for="id_produk" class="form-label">Nama Produk</label>
                                                                        <select class="form-select" id="id_produk" name="id_produk" required>
                                                                                    <option value="" disabled selected>-- Pilih Produk --</option>
                                                                                    <?php
                                                                                    // Ambil semua data produk untuk ditampilkan di dropdown
                                                                                    $queryProduk = $pdo->query("SELECT id_produk, nama_produk FROM produk ORDER BY nama_produk ASC");
                                                                                    while ($produk = $queryProduk->fetch(PDO::FETCH_ASSOC)) {
                                                                                                echo "<option value='{$produk['id_produk']}'>{$produk['nama_produk']}</option>";
                                                                                    }
                                                                                    ?>
                                                                        </select>
                                                            </div>
                                                </div>
                                                <div class="col-md-4">
                                                            <div class="mb-3">
                                                                        <label for="jumlah" class="form-label">Jumlah Masuk (pcs)</label>
                                                                        <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" required>
                                                            </div>
                                                </div>
                                                <div class="col-md-3 d-flex align-items-end">
                                                            <div class="mb-3">
                                                                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Tambah ke Stok</button>
                                                            </div>
                                                </div>
                                    </div>
                        </form>
            </div>
</div>

<div class="card">
            <div class="card-header">
                        Riwayat Stok Masuk Terakhir
            </div>
            <div class="card-body">
                        <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                                <thead class="table-dark">
                                                            <tr>
                                                                        <th>No</th>
                                                                        <th>Tanggal</th>
                                                                        <th>Kode SKU</th>
                                                                        <th>Nama Produk</th>
                                                                        <th>Jumlah Masuk</th>
                                                            </tr>
                                                </thead>
                                                <tbody>
                                                            <?php
                                                            // Query untuk mengambil riwayat stok masuk, digabung dengan data produk
                                                            $queryRiwayat = $pdo->query("
                        SELECT rs.tanggal_transaksi, rs.jumlah, p.kode_sku, p.nama_produk 
                        FROM riwayat_stok rs
                        JOIN produk p ON rs.id_produk = p.id_produk
                        WHERE rs.tipe_transaksi = 'masuk'
                        ORDER BY rs.tanggal_transaksi DESC
                        LIMIT 20
                    ");
                                                            $no = 1;
                                                            while ($data = $queryRiwayat->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                                        <tr>
                                                                                    <td><?php echo $no++; ?></td>
                                                                                    <td><?php echo date('d F Y, H:i', strtotime($data['tanggal_transaksi'])); ?></td>
                                                                                    <td><?php echo htmlspecialchars($data['kode_sku']); ?></td>
                                                                                    <td><?php echo htmlspecialchars($data['nama_produk']); ?></td>
                                                                                    <td><strong>+<?php echo $data['jumlah']; ?> pcs</strong></td>
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