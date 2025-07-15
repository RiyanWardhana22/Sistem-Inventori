<?php
include_once(__DIR__ . '/../../includes/header.php');
?>

<h1 class="h3 mb-3">Tambah Data Produk BS</h1>

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
                                                <div class="col-md-4 mb-3">
                                                            <label for="jumlah" class="form-label">Jumlah</label>
                                                            <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" required>
                                                </div>
                                                <div class="col-md-8 mb-3">
                                                            <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                                                            <input type="text" class="form-control" id="keterangan" name="keterangan">
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