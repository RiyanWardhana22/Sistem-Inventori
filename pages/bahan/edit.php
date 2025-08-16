<?php
include_once(__DIR__ . '/../../includes/header.php');

if (!isset($_GET['id'])) {
    redirect(BASE_URL . 'pages/bahan/');
}

$nama_bahan = urldecode($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM bahan WHERE nama_bahan = ?");
$stmt->execute([$nama_bahan]);
$bahan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bahan) {
    redirect(BASE_URL . 'pages/bahan/');
}
?>

<div class="card">
    <div class="card-header">
        <h1 class="h3 mb-0">Edit Bahan</h1>
    </div>
    <div class="card-body">
        <form action="proses.php" method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="nama_bahan" value="<?php echo htmlspecialchars($bahan['nama_bahan']); ?>">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama_bahan_display" class="form-label">Nama Bahan</label>
                    <input type="text" class="form-control" id="nama_bahan_display" value="<?php echo htmlspecialchars($bahan['nama_bahan']); ?>" readonly>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="jumlah_bahan" class="form-label">Jumlah</label>
                    <input type="number" class="form-control" id="jumlah_bahan" name="jumlah_bahan" value="<?php echo $bahan['jumlah_bahan']; ?>" required>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="satuan_jumlah" class="form-label">Satuan</label>
                    <input type="text" class="form-control" id="satuan_jumlah" name="satuan_jumlah" value="<?php echo htmlspecialchars($bahan['satuan_jumlah']); ?>" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="tanggal_expired" class="form-label">Tanggal Expired (Opsional)</label>
                    <input type="date" class="form-control" id="tanggal_expired" name="tanggal_expired" value="<?php echo $bahan['tanggal_expired'] ? htmlspecialchars($bahan['tanggal_expired']) : ''; ?>">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="Layak" <?php echo ($bahan['status'] == 'Layak') ? 'selected' : ''; ?>>Layak</option>
                        <option value="Expired" <?php echo ($bahan['status'] == 'Expired') ? 'selected' : ''; ?>>Expired</option>
                        <option value="Rusak" <?php echo ($bahan['status'] == 'Rusak') ? 'selected' : ''; ?>>Rusak</option>
                    </select>
                </div>
            </div>

            <a href="index.php" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Update Bahan</button>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Date validation
    $('#tanggal_expired').change(function() {
        const today = new Date();
        const expDate = new Date($(this).val());
        
        if (expDate <= today) {
            $('#status').val('Expired');
        }
    });
});
</script>

<?php
include_once(__DIR__ . '/../../includes/footer.php');
?>