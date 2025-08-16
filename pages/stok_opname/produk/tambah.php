<?php
include_once(__DIR__ . '/../../../includes/header.php');

// CSRF Protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Get product list for dropdown
$products = $pdo->query("SELECT kode_produk, nama_produk FROM produk ORDER BY nama_produk ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <!-- Import Excel Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Import Data Opname Produk</h6>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Petunjuk:</strong> 
                <ol>
                    <li>Download template Excel dibawah</li>
                    <li>Isi data opname produk sesuai format</li>
                    <li>Upload file Excel yang sudah diisi</li>
                    <li>Format file harus .xlsx atau .xls (max 2MB)</li>
                </ol>
            </div>
            
            <form action="proses_import.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="excelFile" class="font-weight-bold">Pilih File Excel</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="excelFile" name="excel_file" accept=".xlsx,.xls" required>
                                <label class="custom-file-label" for="excelFile">Pilih file...</label>
                            </div>
                            <small class="form-text text-muted">Format: .xlsx atau .xls (Maksimal 2MB)</small>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-group w-100">
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-file-import mr-2"></i> Import Data
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            
            <div class="text-center mt-4">
                <a href="download_template.php" class="btn btn-outline-primary">
                    <i class="fas fa-file-download mr-2"></i> Download Template Excel
                </a>
            </div>
        </div>
    </div>

    <!-- Manual Input Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Tambah Data Opname Manual</h6>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?= $_SESSION['error_message'] ?>
                    <?php unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <form action="proses_tambah.php" method="POST" id="opnameForm">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggalOpname">Tanggal Opname <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggalOpname" name="tanggal_opname" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kodeProduk">Produk <span class="text-danger">*</span></label>
                            <select class="form-control" id="kodeProduk" name="kode_produk" required>
                                <option value="">-- Pilih Produk --</option>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?= htmlspecialchars($product['kode_produk']) ?>">
                                        <?= htmlspecialchars($product['kode_produk']) ?> - <?= htmlspecialchars($product['nama_produk']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="kode">Kode (Optional)</label>
                            <input type="text" class="form-control" id="kode" name="kode">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="stokAwal">Stock Awal <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="stokAwal" name="stok_awal" min="0" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="stokAkhir">Stock Akhir <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="stokAkhir" name="stok_akhir" min="0" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="penjualan">Penjualan <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="penjualan" name="penjualan" min="0" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="bs">BS (Barang Rusak) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="bs" name="bs" min="0" required>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-save mr-2"></i> Simpan Data
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // File input label
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // Form validation
    $('#opnameForm').submit(function() {
        let isValid = true;
        $('.is-invalid').removeClass('is-invalid');
        
        // Validate required fields
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            }
        });

        // Validate stock consistency
        const stokAwal = parseInt($('#stokAwal').val());
        const stokAkhir = parseInt($('#stokAkhir').val());
        const penjualan = parseInt($('#penjualan').val());
        const bs = parseInt($('#bs').val());
        
        if (stokAwal < (stokAkhir + penjualan + bs)) {
            alert('Stok awal tidak boleh lebih kecil dari total (Stok akhir + Penjualan + BS)');
            $('#stokAwal').addClass('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            alert('Harap lengkapi semua field yang wajib diisi dengan benar!');
            return false;
        }
        return true;
    });
});
</script>

<style>
.custom-file-label::after {
    content: "Browse";
}
.is-invalid {
    border-color: #dc3545;
}
.card-header {
    border-radius: 0.35rem 0.35rem 0 0 !important;
}
.alert-info {
    background-color: #e7f5fe;
    border-color: #b8e2fb;
}
</style>

<?php
include_once(__DIR__ . '/../../../includes/footer.php');
?>