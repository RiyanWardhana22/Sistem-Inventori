<?php
include_once(__DIR__ . '/../../../includes/header.php');

if (!isset($_GET['id'])) {
    redirect(BASE_URL . 'pages/stok_opname/produk/');
}

$id_opname = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM opname_produk WHERE id_opname = ?");
$stmt->execute([$id_opname]);
$opname = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$opname) {
    redirect(BASE_URL . 'pages/stok_opname/produk/');
}

// Get product list for dropdown
$products = $pdo->query("SELECT kode_produk, nama_produk FROM produk ORDER BY nama_produk ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="h3 mb-3">Edit Data Opname Produk</h1>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="proses.php" method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id_opname" value="<?php echo $opname['id_opname']; ?>">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tanggal_opname" class="form-label">Tanggal Opname</label>
                    <input type="date" class="form-control" id="tanggal_opname" name="tanggal_opname" 
                           value="<?php echo htmlspecialchars($opname['tanggal_opname'] ?? date('Y-m-d')); ?>" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="kode_produk" class="form-label">Produk</label>
                    <select class="form-select" id="kode_produk" name="kode_produk" required>
                        <option value="">-- Pilih Produk --</option>
                        <?php foreach ($products as $product): ?>
                            <option value="<?php echo htmlspecialchars($product['kode_produk']); ?>"
                                <?php echo ($product['kode_produk'] == $opname['kode_produk']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($product['kode_produk']) ?> - <?php echo htmlspecialchars($product['nama_produk']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="kode" class="form-label">Kode Opname</label>
                    <input type="text" class="form-control" id="kode" name="kode" 
                           value="<?php echo htmlspecialchars($opname['kode'] ?? ''); ?>">
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="stok_awal" class="form-label">Stock Awal</label>
                    <input type="number" class="form-control" id="stok_awal" name="stok_awal" 
                           value="<?php echo htmlspecialchars($opname['stok_awal'] ?? 0); ?>" min="0" required>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="stok_akhir" class="form-label">Stock Akhir</label>
                    <input type="number" class="form-control" id="stok_akhir" name="stok_akhir" 
                           value="<?php echo htmlspecialchars($opname['stok_akhir'] ?? 0); ?>" min="0" required>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="penjualan" class="form-label">Penjualan</label>
                    <input type="number" class="form-control" id="penjualan" name="penjualan" 
                           value="<?php echo htmlspecialchars($opname['penjualan'] ?? 0); ?>" min="0" required>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="bs" class="form-label">BS (Barang Rusak)</label>
                    <input type="number" class="form-control" id="bs" name="bs" 
                           value="<?php echo htmlspecialchars($opname['bs'] ?? 0); ?>" min="0" required>
                </div>
            </div>

            <a href="index.php" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Update Data Opname</button>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Validasi stok
    $('#stok_awal, #stok_akhir, #penjualan, #bs').change(function() {
        const stokAwal = parseInt($('#stok_awal').val()) || 0;
        const stokAkhir = parseInt($('#stok_akhir').val()) || 0;
        const penjualan = parseInt($('#penjualan').val()) || 0;
        const bs = parseInt($('#bs').val()) || 0;
        
        if (stokAwal < (stokAkhir + penjualan + bs)) {
            alert('Stok awal tidak boleh lebih kecil dari total (Stok akhir + Penjualan + BS)');
            $('#stok_awal').focus();
        }
    });
});
</script>

<?php
include_once(__DIR__ . '/../../../includes/footer.php');
?>