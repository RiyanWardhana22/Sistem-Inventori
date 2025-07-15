<?php
include_once(__DIR__ . '/../../includes/header.php');
?>

<h1 class="h3 mb-3">Tambah Data Opname</h1>

<div class="card">
            <div class="card-body">
                        <form action="proses.php" method="POST">
                                    <input type="hidden" name="action" value="tambah">
                                    <div class="row">
                                                <div class="col-md-6 mb-3">
                                                            <label for="tanggal_opname" class="form-label">Tanggal</label>
                                                            <input type="date" class="form-control" id="tanggal_opname" name="tanggal_opname" value="<?php echo date('Y-m-d'); ?>" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                            <label for="id_produk" class="form-label">Nama Produk</label>
                                                            <select class="form-select" id="id_produk" name="id_produk" required>
                                                                        <option value="" disabled selected>-- Pilih Produk --</option>
                                                                        <?php
                                                                        $queryProduk = $pdo->query("SELECT id_produk, nama_produk FROM produk ORDER BY nama_produk ASC");
                                                                        while ($produk = $queryProduk->fetch(PDO::FETCH_ASSOC)) {
                                                                                    echo "<option value='{$produk['id_produk']}'>{$produk['nama_produk']}</option>";
                                                                        }
                                                                        ?>
                                                            </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                            <label for="kode" class="form-label">Kode</label>
                                                            <input type="text" class="form-control" id="kode" name="kode">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                            <label for="stok_awal" class="form-label">Stok Awal</label>
                                                            <input type="text" class="form-control" id="stok_awal" name="stok_awal">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                            <label for="stok_akhir" class="form-label">Stok Akhir</label>
                                                            <input type="text" class="form-control" id="stok_akhir" name="stok_akhir">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                            <label for="penjualan" class="form-label">Penjualan</label>
                                                            <input type="text" class="form-control" id="penjualan" name="penjualan">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                            <label for="bs" class="form-label">BS</label>
                                                            <input type="text" class="form-control" id="bs" name="bs">
                                                </div>
                                    </div>
                                    <a href="index.php" class="btn btn-secondary">Batal</a>
                                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                        </form>
            </div>
</div>

<?php
include_once(__DIR__ . '/../../includes/footer.php');
?>