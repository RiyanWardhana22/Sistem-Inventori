<?php
include_once(__DIR__ . '/../../includes/header.php');

if (!isset($_GET['id'])) {
    redirect(BASE_URL . 'pages/produk/');
}

$kode_produk = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM produk WHERE kode_produk = ?");
$stmt->execute([$kode_produk]);
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
            <input type="hidden" name="kode_produk" value="<?php echo $produk['kode_produk']; ?>">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="kode_produk" class="form-label">Kode Produk</label>
                    <input type="number" class="form-control" id="kode_produk" value="<?php echo $produk['kode_produk']; ?>" disabled>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="nama_produk" class="form-label">Nama Produk</label>
                    <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?php echo htmlspecialchars($produk['nama_produk']); ?>" required>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="jumlah_produk" class="form-label">Jumlah Produk</label>
                    <input type="number" class="form-control" id="jumlah_produk" name="jumlah_produk" value="<?php echo $produk['jumlah_produk']; ?>" required>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="harga_produk" class="form-label">Harga Produk (per Buah)</label>
                    <input type="number" class="form-control" id="harga_produk" name="harga_produk" value="<?php echo $produk['harga_produk']; ?>" required>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="keterangan" class="form-label">Status</label>
                    <select class="form-select" id="keterangan" name="keterangan" required>
                        <option value="Layak" <?php echo ($produk['keterangan'] == 'Layak') ? 'selected' : ''; ?>>Layak</option>
                        <option value="Expired" <?php echo ($produk['keterangan'] == 'Expired') ? 'selected' : ''; ?>>Expired</option>
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="tanggal_produksi" class="form-label">Tanggal Produksi</label>
                    <input type="date" class="form-control" id="tanggal_produksi" name="tanggal_produksi" value="<?php echo $produk['tanggal_produksi']; ?>" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="tanggal_expired" class="form-label">Tanggal Expired</label>
                    <input type="date" class="form-control" id="tanggal_expired" name="tanggal_expired" value="<?php echo $produk['tanggal_expired']; ?>" required>
                </div>
            </div>

            <a href="index.php" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Update Produk</button>
        </form>
    </div>
</div>

<?php
include_once(__DIR__ . '/../../includes/footer.php');
?>