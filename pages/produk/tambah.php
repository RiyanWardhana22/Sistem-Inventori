<?php
include_once(__DIR__ . '/../../includes/header.php');
?>

<h1 class="h3 mb-3">Tambah Produk Baru</h1>

<div class="card">
            <div class="card-body">
                        <form action="proses.php" method="POST">
                                    <input type="hidden" name="action" value="tambah">

                                    <div class="mb-3">
                                                <label for="kode_sku" class="form-label">Kode Produk / SKU</label>
                                                <input type="text" class="form-control" id="kode_sku" name="kode_sku" placeholder="Contoh: RC-001">
                                    </div>
                                    <div class="mb-3">
                                                <label for="nama_produk" class="form-label">Nama Produk</label>
                                                <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
                                    </div>
                                    <div class="mb-3">
                                                <label for="harga_jual" class="form-label">Harga Jual (Rp)</label>
                                                <input type="number" class="form-control" id="harga_jual" name="harga_jual" required>
                                    </div>
                                    <div class="mb-3">
                                                <label for="stok_minimal" class="form-label">Stok Minimal</label>
                                                <input type="number" class="form-control" id="stok_minimal" name="stok_minimal" value="0" required>
                                    </div>

                                    <a href="index.php" class="btn btn-secondary">Batal</a>
                                    <button type="submit" class="btn btn-primary">Simpan Produk</button>
                        </form>
            </div>
</div>

<?php
include_once(__DIR__ . '/../../includes/footer.php');
?>