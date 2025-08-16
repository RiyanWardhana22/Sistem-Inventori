<?php
include_once(__DIR__ . '/../../../includes/header.php');

// CSRF Protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Get list of bahan
$bahan_list = $pdo->query("SELECT nama_bahan, jumlah_bahan, satuan_jumlah FROM bahan ORDER BY nama_bahan ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <!-- Import Excel Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Import Data Barang Rusak (BS) Bahan</h6>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Petunjuk:</strong> 
                <ol>
                    <li>Download template Excel dibawah</li>
                    <li>Isi data BS bahan sesuai format</li>
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
            <h6 class="m-0 font-weight-bold">Tambah Data BS Bahan Manual</h6>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?= $_SESSION['error_message'] ?>
                    <?php unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <form action="proses_tambah.php" method="POST" id="bsForm">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="action" value="tambah">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggalBs">Tanggal BS <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggalBs" name="tanggal_bs" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_bahan">Nama Bahan <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="nama_bahan" name="nama_bahan" required style="width: 100%;">
                                <option value="">-- Pilih Bahan --</option>
                                <?php foreach ($bahan_list as $bahan): ?>
                                    <option value="<?= htmlspecialchars($bahan['nama_bahan']) ?>"
                                        data-jumlah="<?= $bahan['jumlah_bahan'] ?>"
                                        data-satuan="<?= $bahan['satuan_jumlah'] ?>">
                                        <?= htmlspecialchars($bahan['nama_bahan']) ?> 
                                        (Stok: <?= $bahan['jumlah_bahan'] ?> <?= $bahan['satuan_jumlah'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="jumlah_bahan">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="jumlah_bahan" name="jumlah_bahan" min="1" step="1" required>
                            <small class="text-muted">Stok tersedia: <span id="stokTersedia">-</span></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="satuan_jumlah">Satuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="satuan_jumlah" name="satuan_jumlah" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="1"></textarea>
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

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Inisialisasi Select2 untuk bahan
    $('.select2').select2({
        placeholder: "-- Pilih Bahan --",
        allowClear: true,
        dropdownParent: $('#bsForm'),
        minimumResultsForSearch: 5,
        dropdownCssClass: 'select2-dropdown-scrollable',
        width: 'resolve'
    });

    // Update stok dan satuan saat bahan dipilih
    $('#nama_bahan').on('change', function() {
        const selected = $(this).find('option:selected');
        const stok = selected.data('jumlah');
        const satuan = selected.data('satuan');
        
        $('#stokTersedia').text(stok + ' ' + satuan);
        $('#satuan_jumlah').val(satuan);
        $('#jumlah_bahan').attr('max', stok).val('');
    });

    // Validasi form
    $('#bsForm').on('submit', function() {
        // Validasi jumlah
        const maxStok = parseInt($('#jumlah_bahan').attr('max')) || 0;
        const inputStok = parseInt($('#jumlah_bahan').val()) || 0;
        
        if (inputStok > maxStok) {
            alert('Jumlah BS tidak boleh melebihi stok tersedia (' + maxStok + ')!');
            return false;
        }
        
        if (inputStok <= 0) {
            alert('Jumlah BS harus lebih dari 0!');
            return false;
        }

        // Validasi satuan
        if ($('#satuan_jumlah').val().trim() === '') {
            alert('Harap masukkan satuan!');
            $('#satuan_jumlah').focus();
            return false;
        }
        
        return true;
    });

    // File input label
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
});
</script>

<style>
/* Style untuk dropdown bahan */
.select2-container .select2-selection--single {
    height: 38px;
    color: #000000 !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #000000 !important;
}

.select2-dropdown {
    color: #000000;
}

/* Scrollbar untuk dropdown bahan */
.select2-dropdown-scrollable {
    max-height: 300px;
    overflow-y: auto;
}

/* Style untuk card */
.card-header {
    border-radius: 0.35rem 0.35rem 0 0 !important;
}

/* Style untuk form */
.form-control {
    color: #000000;
}
</style>

<?php
include_once(__DIR__ . '/../../../includes/footer.php');
?>