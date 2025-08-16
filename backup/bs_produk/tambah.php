<?php
include_once(__DIR__ . '/../../../includes/header.php');
?>

<div class="page-header mb-4">
            <div class="row align-items-center">
                        <div class="col-sm-12">
                                    <h1 class="h3 mb-0">Tambah Data Produk BS</h1>
                        </div>
            </div>
</div>

<div class="card">
            <div class="card-body">
                        <form action="proses.php" method="POST">
                                    <input type="hidden" name="action" value="tambah">
                                    <div class="row">
                                                <div class="col-md-4 mb-3">
                                                            <label for="tanggal_bs" class="form-label">Tanggal</label>
                                                            <input type="date" class="form-control" id="tanggal_bs" name="tanggal_bs" value="<?php echo date('Y-m-d'); ?>" required>
                                                </div>
                                                <div class="col-md-8 mb-3">
                                                            <label for="kode_produk" class="form-label">Nama Produk</label>
                                                            <select class="form-select" id="kode_produk" name="kode_produk" required>
                                                                        <option value="" disabled selected>-- Pilih Produk --</option>
                                                                        <?php
                                                                        $queryProduk = $pdo->query("SELECT kode_produk, nama_produk FROM produk ORDER BY nama_produk ASC");
                                                                        while ($produk = $queryProduk->fetch(PDO::FETCH_ASSOC)) {
                                                                                    echo "<option value='{$produk['kode_produk']}'>{$produk['nama_produk']}</option>";
                                                                        }
                                                                        ?>
                                                            </select>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                            <label for="jumlah" class="form-label">Jumlah</label>
                                                            <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" required>
                                                </div>
                                                <div class="col-md-8 mb-3">
                                                            <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                                                            <input type="text" class="form-control" id="keterangan" name="keterangan">
                                                </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                                    <a href="index.php" class="btn btn-outline-secondary">Batal</a>
                        </form>
            </div>
</div>

<?php
include_once(__DIR__ . '/../../../includes/footer.php');
?>