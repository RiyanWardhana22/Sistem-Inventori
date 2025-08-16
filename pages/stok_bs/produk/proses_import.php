<?php
require_once(__DIR__ . '/../../../config/config.php');
require_once(__DIR__ . '/../../../vendor/autoload.php');
require_once(__DIR__ . '/../../../includes/header.php');

use PhpOffice\PhpSpreadsheet\IOFactory;

// Set timeout untuk file besar
set_time_limit(300);

// Inisialisasi variabel
$title = "Hasil Import Barang Rusak (BS)";
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

                // Validasi minimal 5 kolom (sesuai template)
                if (count($row) < 5) {
                    $errors[] = "Baris $row_num: Data tidak lengkap (harus 5 kolom)";
                    continue;
                }

                // Mapping data sesuai template BS
                $data = [
                    'tanggal_bs' => trim($row[0]),      // Tanggal BS
                    'kode_produk' => trim($row[1]),      // Kode Produk
                    'nama_produk' => trim($row[2]),      // Nama Produk (untuk referensi)
                    'jumlah' => trim($row[3]),           // Jumlah
                    'keterangan' => trim($row[4] ?? '')  // Keterangan (opsional)
                ];

                // Validasi data
                if (!DateTime::createFromFormat('Y-m-d', $data['tanggal_bs'])) {
                    $errors[] = "Baris $row_num: Format tanggal BS salah (harus YYYY-MM-DD)";
                    continue;
                }

                if (empty($data['kode_produk'])) {
                    $errors[] = "Baris $row_num: Kode Produk wajib diisi";
                    continue;
                }

                // Cek apakah produk ada di database
                $stmt_check_produk = $pdo->prepare("SELECT COUNT(*) FROM produk WHERE kode_produk = ?");
                $stmt_check_produk->execute([$data['kode_produk']]);
                $produk_exists = $stmt_check_produk->fetchColumn();

                if (!$produk_exists) {
                    $errors[] = "Baris $row_num: Produk dengan kode {$data['kode_produk']} tidak ditemukan";
                    continue;
                }

                // Validasi jumlah
                if (!is_numeric($data['jumlah']) || $data['jumlah'] <= 0) {
                    $errors[] = "Baris $row_num: Jumlah harus angka > 0";
                    continue;
                }
                $data['jumlah'] = (int)$data['jumlah'];

                // Cek apakah data BS sudah ada (berdasarkan kode_produk + tanggal)
                $stmt_check_bs = $pdo->prepare("SELECT COUNT(*) FROM stok_bs 
                                               WHERE kode_produk = ? AND tanggal_bs = ?");
                $stmt_check_bs->execute([
                    $data['kode_produk'],
                    $data['tanggal_bs']
                ]);
                $bs_exists = $stmt_check_bs->fetchColumn();

                if ($bs_exists) {
                    // Update existing record
                    $sql = "UPDATE stok_bs SET 
                            jumlah = ?,
                            keterangan = ?
                            WHERE kode_produk = ? AND tanggal_bs = ?";
                    
                    $params = [
                        $data['jumlah'],
                        $data['keterangan'],
                        $data['kode_produk'],
                        $data['tanggal_bs']
                    ];
                } else {
                    // Insert new record
                    $sql = "INSERT INTO stok_bs (
                            kode_produk,
                            tanggal_bs,
                            jumlah,
                            keterangan
                            ) VALUES (?, ?, ?, ?)";
                    
                    $params = [
                        $data['kode_produk'],
                        $data['tanggal_bs'],
                        $data['jumlah'],
                        $data['keterangan']
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
                $message = "$imported dari $total_rows data BS berhasil diimport";
                
                if (!empty($errors)) {
                    $icon = "warning";
                    $title = "Import Sebagian Berhasil!";
                }
            } else {
                $icon = "error";
                $title = "Import Gagal!";
                $message = "Tidak ada data BS yang berhasil diimport";
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
                    <i class="fas fa-list"></i> Lihat Daftar BS
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