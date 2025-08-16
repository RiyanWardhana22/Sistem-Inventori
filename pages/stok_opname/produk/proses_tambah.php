<?php
require_once(__DIR__ . '/../../../config/config.php');

// Validasi CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error_message'] = "Token CSRF tidak valid";
    header("Location: tambah.php");
    exit();
}

// Validasi data wajib
$required_fields = ['tanggal_opname', 'kode_produk'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $_SESSION['error_message'] = "Field $field wajib diisi";
        header("Location: tambah.php");
        exit();
    }
}

// Validasi numeric fields (jika diisi)
$numeric_fields = ['stok_awal', 'stok_akhir', 'penjualan', 'bs'];
foreach ($numeric_fields as $field) {
    if (isset($_POST[$field]) && !empty($_POST[$field])) {
        if (!is_numeric($_POST[$field]) || $_POST[$field] < 0) {
            $_SESSION['error_message'] = "$field harus berupa angka ≥ 0";
            header("Location: tambah.php");
            exit();
        }
    }
}

// Cek apakah produk ada di database
$stmt_check = $pdo->prepare("SELECT COUNT(*) FROM produk WHERE kode_produk = ?");
$stmt_check->execute([$_POST['kode_produk']]);
if ($stmt_check->fetchColumn() == 0) {
    $_SESSION['error_message'] = "Produk dengan kode " . htmlspecialchars($_POST['kode_produk']) . " tidak ditemukan";
    header("Location: tambah.php");
    exit();
}

// Konversi nilai ke integer jika diisi, atau NULL jika kosong
$stok_awal = isset($_POST['stok_awal']) && $_POST['stok_awal'] !== '' ? (int)$_POST['stok_awal'] : null;
$stok_akhir = isset($_POST['stok_akhir']) && $_POST['stok_akhir'] !== '' ? (int)$_POST['stok_akhir'] : null;
$penjualan = isset($_POST['penjualan']) && $_POST['penjualan'] !== '' ? (int)$_POST['penjualan'] : null;
$bs = isset($_POST['bs']) && $_POST['bs'] !== '' ? (int)$_POST['bs'] : null;

// Validasi konsistensi stok (hanya jika semua field terkait diisi)
if ($stok_awal !== null && $stok_akhir !== null && $penjualan !== null && $bs !== null) {
    if ($stok_awal < ($stok_akhir + $penjualan + $bs)) {
        $_SESSION['error_message'] = "Stok awal tidak boleh lebih kecil dari total (Stok akhir + Penjualan + BS)";
        header("Location: tambah.php");
        exit();
    }
}

// Simpan ke database
try {
    // Dapatkan nama_produk dari tabel produk
    $stmt_get_product = $pdo->prepare("SELECT nama_produk FROM produk WHERE kode_produk = ?");
    $stmt_get_product->execute([$_POST['kode_produk']]);
    $nama_produk = $stmt_get_product->fetchColumn();

    $stmt = $pdo->prepare("INSERT INTO opname_produk (
        kode_produk, nama_produk, tanggal_opname, kode,
        stok_awal, stok_akhir, penjualan, bs
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->execute([
        $_POST['kode_produk'],
        $nama_produk,
        $_POST['tanggal_opname'],
        $_POST['kode'] ?? null,
        $stok_awal,
        $stok_akhir,
        $penjualan,
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