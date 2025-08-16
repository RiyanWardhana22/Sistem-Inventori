<?php
require_once(__DIR__ . '/../../../config/config.php');

// Validasi CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error_message'] = "Token CSRF tidak valid";
    header("Location: tambah.php");
    exit();
}

// Validasi data wajib
$required_fields = ['tanggal_opname', 'nama_bahan'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $_SESSION['error_message'] = "Field $field wajib diisi";
        header("Location: tambah.php");
        exit();
    }
}

// Fungsi untuk memproses nilai numerik
function processNumericValue($value) {
    if ($value === '' || $value === null) {
        return null;
    }
    return is_numeric($value) ? (int)$value : null;
}

// Proses nilai numerik
$stok_awal = processNumericValue($_POST['stok_awal'] ?? '');
$stok_akhir = processNumericValue($_POST['stok_akhir'] ?? '');
$penggunaan = processNumericValue($_POST['penggunaan'] ?? '');
$bs = processNumericValue($_POST['bs'] ?? '');

// Validasi konsistensi stok (hanya jika semua field terkait memiliki nilai)
if ($stok_awal !== null && $stok_akhir !== null && $penggunaan !== null && $bs !== null) {
    if ($stok_awal < ($stok_akhir + $penggunaan + $bs)) {
        $_SESSION['error_message'] = "Stok awal tidak boleh lebih kecil dari total (Stok akhir + Penggunaan + BS)";
        header("Location: tambah.php");
        exit();
    }
}

// Proses kode (boleh kosong)
$kode = !empty($_POST['kode']) ? $_POST['kode'] : null;

// Simpan ke database
try {
    $stmt = $pdo->prepare("INSERT INTO opname_bahan (
        nama_bahan, 
        tanggal_opname, 
        kode,
        stok_awal, 
        stok_akhir, 
        penggunaan, 
        bs
    ) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->execute([
        $_POST['nama_bahan'],
        $_POST['tanggal_opname'],
        $kode,
        $stok_awal,
        $stok_akhir,
        $penggunaan,
        $bs
    ]);
    
    $_SESSION['success_message'] = "Data opname berhasil ditambahkan";
    header("Location: index.php");
    exit();
    
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Gagal menyimpan data opname: " . $e->getMessage();
    header("Location: tambah.php");
    exit();
}