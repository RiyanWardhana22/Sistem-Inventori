<?php
require_once(__DIR__ . '/../../../config/config.php');
require_once(__DIR__ . '/../../../vendor/autoload.php');
require_once(__DIR__ . '/../../../includes/header.php');

use PhpOffice\PhpSpreadsheet\IOFactory;

// Set timeout untuk file besar
set_time_limit(300);

// Inisialisasi variabel
$title = "Hasil Import Opname Bahan";
$icon = "info";
$message = "";
$errors = [];
$imported = 0;
$total_rows = 0;

// Validasi CSRF
if (!isset($_POST['csrf_token'])) {
    $icon = "error";
    $title = "Error!";
    $message = "Token CSRF tidak ditemukan";
    $errors[] = "Request tidak valid";
} elseif ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $icon = "error";
    $title = "Error!";
    $message = "Token CSRF tidak valid";
    $errors[] = "Session mungkin telah expired";
} 

// Validasi file upload
elseif (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
    $icon = "error";
    $title = "Error!";
    $message = "Gagal upload file";
    $errors[] = "Error code: " . $_FILES['excel_file']['error'];
}

// Validasi ekstensi file
else {
    $allowed_ext = ['xlsx', 'xls'];
    $file_ext = pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION);
    
    if (!in_array(strtolower($file_ext), $allowed_ext)) {
        $icon = "error";
        $title = "Error!";
        $message = "Format file tidak didukung";
        $errors[] = "Hanya file .xlsx atau .xls yang diperbolehkan";
    } else {
        try {
            // Load file Excel
            $spreadsheet = IOFactory::load($_FILES['excel_file']['tmp_name']);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Hapus header
            array_shift($rows);
            $total_rows = count($rows);

            // Mulai transaction
            $pdo->beginTransaction();

            foreach ($rows as $row_num => $row) {
                $row_num += 2; // Karena header di baris 1

                // Skip baris kosong
                if (empty(array_filter($row))) continue;

                // Validasi minimal 7 kolom (sesuai template)
                if (count($row) < 7) {
                    $errors[] = "Baris $row_num: Data tidak lengkap (harus 7 kolom)";
                    continue;
                }

                // Mapping data sesuai template
                $data = [
                    'kode_opname' => trim($row[0]) !== '' ? trim($row[0]) : null,        // Kode Opname
                    'tanggal_opname' => trim($row[1]),      // Tanggal Opname
                    'nama_bahan' => trim($row[2]),          // Nama Bahan
                    'stok_awal' => trim($row[3]) !== '' ? (int)trim($row[3]) : null,     // Stock Awal
                    'stok_akhir' => trim($row[4]) !== '' ? (int)trim($row[4]) : null,    // Stock Akhir
                    'penggunaan' => trim($row[5]) !== '' ? (int)trim($row[5]) : null,    // penggunaan
                    'bs' => trim($row[6]) !== '' ? (int)trim($row[6]) : null             // BS
                ];

                // Validasi data
                if (empty($data['tanggal_opname'])) {
                    $errors[] = "Baris $row_num: Tanggal Opname wajib diisi";
                    continue;
                }

                if (!DateTime::createFromFormat('Y-m-d', $data['tanggal_opname'])) {
                    $errors[] = "Baris $row_num: Format tanggal opname salah (harus YYYY-MM-DD)";
                    continue;
                }

                if (empty($data['nama_bahan'])) {
                    $errors[] = "Baris $row_num: Nama Bahan wajib diisi";
                    continue;
                }

                // Validasi numeric fields jika diisi
                $numeric_fields = ['stok_awal', 'stok_akhir', 'penggunaan', 'bs'];
                foreach ($numeric_fields as $field) {
                    if ($data[$field] !== null && (!is_numeric($data[$field]) || $data[$field] < 0)) {
                        $errors[] = "Baris $row_num: $field harus angka ≥ 0";
                        continue 2;
                    }
                }

                // Validasi stok konsistensi jika diisi
                if ($data['stok_awal'] !== null && 
                    $data['stok_awal'] < (($data['stok_akhir'] ?? 0) + ($data['penggunaan'] ?? 0) + ($data['bs'] ?? 0))) {
                    $errors[] = "Baris $row_num: Stok awal tidak boleh lebih kecil dari total (Stok akhir + Penggunaan + BS)";
                    continue;
                }

                // Cek apakah data opname sudah ada (berdasarkan kode opname atau kombinasi nama_bahan + tanggal)
                $stmt_check_opname = $pdo->prepare("SELECT COUNT(*) FROM opname_bahan 
                                                   WHERE (kode = ? OR (nama_bahan = ? AND tanggal_opname = ?))");
                $stmt_check_opname->execute([
                    $data['kode_opname'],
                    $data['nama_bahan'],
                    $data['tanggal_opname']
                ]);
                $opname_exists = $stmt_check_opname->fetchColumn();

                if ($opname_exists) {
                    // Update existing record
                    $sql = "UPDATE opname_bahan SET 
                            nama_bahan = ?,
                            tanggal_opname = ?,
                            stok_awal = ?,
                            stok_akhir = ?,
                            penggunaan = ?,
                            bs = ?
                            WHERE kode = ? OR (nama_bahan = ? AND tanggal_opname = ?)";
                    
                    $params = [
                        $data['nama_bahan'],
                        $data['tanggal_opname'],
                        $data['stok_awal'],
                        $data['stok_akhir'],
                        $data['penggunaan'],
                        $data['bs'],
                        $data['kode_opname'],
                        $data['nama_bahan'],
                        $data['tanggal_opname']
                    ];
                } else {
                    // Insert new record
                    $sql = "INSERT INTO opname_bahan (
                            kode,
                            nama_bahan,
                            tanggal_opname,
                            stok_awal,
                            stok_akhir,
                            penggunaan,
                            bs
                            ) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    
                    $params = [
                        $data['kode_opname'],
                        $data['nama_bahan'],
                        $data['tanggal_opname'],
                        $data['stok_awal'],
                        $data['stok_akhir'],
                        $data['penggunaan'],
                        $data['bs']
                    ];
                }

                $stmt = $pdo->prepare($sql);
                if ($stmt->execute($params)) {
                    $imported++;
                } else {
                    $errors[] = "Baris $row_num: Gagal menyimpan - " . implode(" ", $stmt->errorInfo());
                }
            }

            $pdo->commit();
            
            // Set hasil untuk tampilan
            if ($imported > 0) {
                $icon = "success";
                $title = "Import Berhasil!";
                $message = "$imported dari $total_rows data opname berhasil diimport";
                
                if (!empty($errors)) {
                    $icon = "warning";
                    $title = "Import Sebagian Berhasil!";
                }
            } else {
                $icon = "error";
                $title = "Import Gagal!";
                $message = "Tidak ada data opname yang berhasil diimport";
            }

        } catch (Exception $e) {
            $pdo->rollBack();
            $icon = "error";
            $title = "Error!";
            $message = "Terjadi kesalahan sistem";
            $errors[] = $e->getMessage();
        }
    }
}
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary"><?= $title ?></h6>
            <a href="index.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <!-- Notifikasi Utama -->
            <div class="alert alert-<?= $icon == 'success' ? 'success' : ($icon == 'warning' ? 'warning' : 'danger') ?>">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <?php if ($icon == 'success'): ?>
                            <i class="fas fa-check-circle fa-3x text-success"></i>
                        <?php elseif ($icon == 'warning'): ?>
                            <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                        <?php else: ?>
                            <i class="fas fa-times-circle fa-3x text-danger"></i>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h4 class="alert-heading font-weight-bold"><?= $title ?></h4>
                        <p><?= $message ?></p>
                        <?php if ($imported > 0 && !empty($errors)): ?>
                            <p class="mb-0">Beberapa data tidak dapat diimport karena error</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Detail Error -->
            <?php if (!empty($errors)): ?>
                <div class="mt-4">
                    <h5 class="text-danger mb-3">
                        <i class="fas fa-exclamation-circle"></i> Daftar Error (<?= count($errors) ?>):
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Deskripsi Error</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($errors as $i => $error): ?>
                                <tr>
                                    <td class="text-center"><?= $i+1 ?></td>
                                    <td><?= htmlspecialchars($error) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Tombol Aksi -->
            <div class="mt-4 d-flex justify-content-between">
                <a href="tambah.php" class="btn btn-primary">
                    <i class="fas fa-file-upload"></i> Import Data Lagi
                </a>
                <a href="index.php" class="btn btn-success">
                    <i class="fas fa-list"></i> Lihat Daftar Opname
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.alert {
    border-left: 5px solid;
    border-radius: 0.35rem;
}
.alert-success {
    border-left-color: #28a745;
    background-color: rgba(40, 167, 69, 0.1);
}
.alert-warning {
    border-left-color: #ffc107;
    background-color: rgba(255, 193, 7, 0.1);
}
.alert-danger {
    border-left-color: #dc3545;
    background-color: rgba(220, 53, 69, 0.1);
}
.fa-3x {
    font-size: 3em;
}
.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.03);
}
</style>

<?php
require_once(__DIR__ . '/../../../includes/footer.php');
?>