<?php
include_once(__DIR__ . '/../../../includes/header.php');

// CSRF Protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!isset($_GET['id'])) {
    redirect(BASE_URL . 'pages/stok_bs/bahan');
}

$id_bs = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM bs_bahan WHERE id_bs = ?");
$stmt->execute([$id_bs]);
$bs = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bs) {
    redirect(BASE_URL . 'pages/stok_bs/bahan');
}

// Get list of bahan
$bahan_list = $pdo->query("SELECT nama_bahan FROM bahan ORDER BY nama_bahan ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="h3 mb-3">Edit Data Bad Stock Bahan</h1>

<div class="card">
    <div class="card-body">
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?= $_SESSION['error_message'] ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <form action="proses.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id_bs" value="<?= $bs['id_bs'] ?>">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tanggal_bs" class="form-label">Tanggal BS <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="tanggal_bs" name="tanggal_bs" 
                           value="<?= htmlspecialchars($bs['tanggal_bs']) ?>" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="nama_bahan" class="form-label">Nama Bahan <span class="text-danger">*</span></label>
                    <select class="form-control select2" id="nama_bahan" name="nama_bahan" required>
                        <option value="">-- Pilih Bahan --</option>
                        <?php foreach ($bahan_list as $bahan): ?>
                            <option value="<?= htmlspecialchars($bahan['nama_bahan']) ?>"
                                <?= ($bahan['nama_bahan'] == $bs['nama_bahan']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($bahan['nama_bahan']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="jumlah_bahan" class="form-label">Jumlah BS <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="jumlah_bahan" name="jumlah_bahan" 
                           value="<?= htmlspecialchars($bs['jumlah_bahan']) ?>" min="1" required>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="satuan_jumlah" class="form-label">Satuan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="satuan_jumlah" name="satuan_jumlah" 
                           value="<?= htmlspecialchars($bs['satuan_jumlah']) ?>" required>
                </div>
                
                <div class="col-md-12 mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?= htmlspecialchars($bs['keterangan'] ?? '') ?></textarea>
                </div>
            </div>

            <a href="index.php" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Update Data BS Bahan</button>
        </form>
    </div>
</div>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        placeholder: "-- Pilih Bahan --",
        allowClear: true,
        width: '100%'
    });
});
</script>

<?php
include_once(__DIR__ . '/../../../includes/footer.php');
?>