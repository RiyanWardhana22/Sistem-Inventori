<?php
include_once(__DIR__ . '/../../includes/header.php');

if (!isset($_GET['id'])) {
            redirect(BASE_URL . 'pages/produk/');
}

$id_produk = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM produk WHERE id_produk = ?");
$stmt->execute([$id_produk]);
$produk = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produk) {
            redirect(BASE_URL . 'pages/produk/');
}
?>

<h1 class="h3 mb-3">Edit Produk</h1>

<div class="card">
            <div class="card-body">
                        <form action="proses.php" method="POST">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="id_produk" value="<?php echo $produk['id_produk']; ?>">

                                    <div class="mb-3">
                                                <label for="kode_sku" class="form-label">Kode Produk / SKU</label>
                                                <input type="text" class="form-control" id="kode_sku" name="kode_sku" value="<?php echo htmlspecialchars($produk['kode_sku']); ?>">
                                    </div>
                                    <div class="mb-3">
                                                <label for="nama_produk" class="form-label">Nama Produk</label>
                                                <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?php echo htmlspecialchars($produk['nama_produk']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                                <label for="harga_jual" class="form-label">Harga Jual (Rp)</label>
                                                <input type="number" class="form-control" id="harga_jual" name="harga_jual" value="<?php echo $produk['harga_jual']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                                <label for="stok_minimal" class="form-label">Stok Minimal</label>
                                                <input type="number" class="form-control" id="stok_minimal" name="stok_minimal" value="<?php echo $produk['stok_minimal']; ?>" required>
                                    </div>

                                    <a href="index.php" class="btn btn-secondary">Batal</a>
                                    <button type="submit" class="btn btn-primary">Update Produk</button>
                        </form>
            </div>
</div>

<?php
include_once(__DIR__ . '/../../includes/footer.php');
?>