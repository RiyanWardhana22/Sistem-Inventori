<?php
include_once(__DIR__ . '/../../../includes/header.php');

if (!isset($_GET['id'])) {
    redirect(BASE_URL . 'pages/stok_opname/bahan/');
}

$id_opname = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM opname_bahan WHERE id_opname = ?");
$stmt->execute([$id_opname]);
$opname = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$opname) {
    redirect(BASE_URL . 'pages/stok_opname/bahan/');
}

// Get bahan list for dropdown
$bahan = $pdo->query("SELECT DISTINCT nama_bahan FROM opname_bahan ORDER BY nama_bahan ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="h3 mb-3">Edit Data Opname Bahan</h1>

<div class="card">
    <div class="card-body">
        <form action="proses.php" method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id_opname" value="<?php echo $opname['id_opname']; ?>">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tanggal_opname" class="form-label">Tanggal Opname</label>
                    <input type="date" class="form-control" id="tanggal_opname" name="tanggal_opname" 
                           value="<?php echo $opname['tanggal_opname']; ?>" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="nama_bahan" class="form-label">Nama Bahan</label>
                    <input type="text" class="form-control" id="nama_bahan" name="nama_bahan" 
                           value="<?php echo htmlspecialchars($opname['nama_bahan']); ?>" list="bahanList" required>
                    <datalist id="bahanList">
                        <?php foreach ($bahan as $item): ?>
                            <option value="<?php echo htmlspecialchars($item['nama_bahan']); ?>">
                        <?php endforeach; ?>
                    </datalist>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="kode" class="form-label">Kode Opname</label>
                    <input type="text" class="form-control" id="kode" name="kode" 
                           value="<?php echo htmlspecialchars($opname['kode'] ?? ''); ?>">
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="stok_awal" class="form-label">Stock Awal</label>
                    <input type="number" class="form-control" id="stok_awal" name="stok_awal" 
                           value="<?php echo $opname['stok_awal']; ?>">
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="stok_akhir" class="form-label">Stock Akhir</label>
                    <input type="number" class="form-control" id="stok_akhir" name="stok_akhir" 
                           value="<?php echo $opname['stok_akhir']; ?>">
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="penggunaan" class="form-label">Penggunaan</label>
                    <input type="number" class="form-control" id="penggunaan" name="penggunaan" 
                           value="<?php echo $opname['penggunaan']; ?>">
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="bs" class="form-label">BS (Barang Rusak)</label>
                    <input type="number" class="form-control" id="bs" name="bs" 
                           value="<?php echo $opname['bs']; ?>">
                </div>
            </div>

            <a href="index.php" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Update Data Opname</button>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Validasi stok jika diisi
    $('#stok_awal, #stok_akhir, #penggunaan, #bs').change(function() {
        const stokAwal = parseInt($('#stok_awal').val()) || 0;
        const stokAkhir = parseInt($('#stok_akhir').val()) || 0;
        const penggunaan = parseInt($('#penggunaan').val()) || 0;
        const bs = parseInt($('#bs').val()) || 0;
        
        if (stokAwal > 0 && stokAwal < (stokAkhir + penggunaan + bs)) {
            alert('Stok awal tidak boleh lebih kecil dari total (Stok akhir + penggunaan + BS)');
            $('#stok_awal').val('');
        }
    });
});
</script>

<?php
include_once(__DIR__ . '/../../../includes/footer.php');
?>