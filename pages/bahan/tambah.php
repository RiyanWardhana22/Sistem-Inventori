<?php
include_once(__DIR__ . '/../../includes/header.php');

// CSRF Protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<div class="container-fluid">
    <!-- Import Excel Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Import Data Bahan</h6>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Petunjuk:</strong> 
                <ol>
                    <li>Download template Excel dibawah</li>
                    <li>Isi data bahan sesuai format</li>
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
            <h6 class="m-0 font-weight-bold">Tambah Data Bahan Manual</h6>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?= $_SESSION['error_message'] ?>
                    <?php unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <form action="proses.php" method="POST" id="materialForm">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="action" value="tambah">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="namaBahan">Nama Bahan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="namaBahan" name="nama_bahan" required>
                            <small class="form-text text-muted">Nama bahan harus unik</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="jumlahBahan">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="jumlahBahan" name="jumlah_bahan" 
                                   min="1" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="satuanJumlah">Satuan <span class="text-danger">*</span></label>
                            <select class="form-control" id="satuanJumlah" name="satuan_jumlah" required>
                                <option value="kg">kg</option>
                                <option value="gram">gram</option>
                                <option value="liter">liter</option>
                                <option value="ml">ml</option>
                                <option value="pcs">pcs</option>
                                <option value="pack">pack</option>
                                <option value="dus">dus</option>
                                <option value="botol">botol</option>
                                <option value="buah">buah</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tglExpired">Tanggal Expired</label>
                            <input type="date" class="form-control" id="tglExpired" name="tanggal_expired">
                            <small class="form-text text-muted">Kosongkan jika tidak ada tanggal expired</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="statusBahan">Status <span class="text-danger">*</span></label>
                            <select class="form-control" id="statusBahan" name="status" required>
                                <option value="Layak" selected>Layak</option>
                                <option value="Rusak">Rusak</option>
                                <option value="Expired">Expired</option>
                            </select>
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

    // Status color indicator
    $('#statusBahan').change(function() {
        const status = $(this).val();
        let color = '';
        
        if (status === 'Layak') color = '#28a745';
        else if (status === 'Rusak') color = '#fd7e14';
        else if (status === 'Expired') color = '#dc3545';
        
        $(this).css('color', 'white').css('background-color', color);
    }).trigger('change');

    // Date validation for status
    $('#tglExpired').change(function() {
        if ($(this).val()) {
            const today = new Date();
            const expDate = new Date($(this).val());
            
            if (expDate <= today) {
                $('#statusBahan').val('Expired').trigger('change');
            }
        }
    });

    // Form validation
    $('#materialForm').submit(function() {
        let isValid = true;
        $('.is-invalid').removeClass('is-invalid');
        
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            }
        });
        
        if (!isValid) {
            alert('Harap lengkapi semua field yang wajib diisi!');
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
#statusBahan {
    color: black;
    /* font-weight: bold; */
}
</style>

<?php
include_once(__DIR__ . '/../../includes/footer.php');
?>