<?php
include_once(__DIR__ . '/../../../includes/header.php');

if (!isset($_GET['id'])) {
    redirect(BASE_URL . 'pages/stok_bs/produk');
}

$id_bs = $_GET['id'];
$stmt = $pdo->prepare("SELECT bs.*, p.nama_produk 
                      FROM stok_bs bs
                      LEFT JOIN produk p ON bs.kode_produk = p.kode_produk
                      WHERE bs.id_bs = ?");
$stmt->execute([$id_bs]);
$bs = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bs) {
    redirect(BASE_URL . 'pages/stok_bs/');
}

// Get product list for dropdown
$products = $pdo->query("SELECT kode_produk, nama_produk FROM produk ORDER BY nama_produk ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="h3 mb-3">Edit Data Bad Stock</h1>

<div class="card">
    <div class="card-body">
        <form action="proses.php" method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id_bs" value="<?php echo $bs['id_bs']; ?>">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tanggal_bs" class="form-label">Tanggal BS</label>
                    <input type="date" class="form-control" id="tanggal_bs" name="tanggal_bs" 
                           value="<?php echo $bs['tanggal_bs']; ?>" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="kode_produk" class="form-label">Produk</label>
                    <select class="form-select" id="kode_produk" name="kode_produk" required>
                        <option value="">-- Pilih Produk --</option>
                        <?php foreach ($products as $product): ?>
                            <option value="<?php echo htmlspecialchars($product['kode_produk']); ?>"
                                <?php echo ($product['kode_produk'] == $bs['kode_produk']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($product['kode_produk']) ?> - <?php echo htmlspecialchars($product['nama_produk']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="jumlah" class="form-label">Jumlah</label>
                    <input type="number" class="form-control" id="jumlah" name="jumlah" 
                           value="<?php echo $bs['jumlah']; ?>" min="1" required>
                </div>
                
                <div class="col-md-12 mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?php echo htmlspecialchars($bs['keterangan'] ?? ''); ?></textarea>
                </div>
            </div>

            <a href="index.php" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Update Data BS</button>
        </form>
    </div>
</div>

<?php
include_once(__DIR__ . '/../../../includes/footer.php');
?>