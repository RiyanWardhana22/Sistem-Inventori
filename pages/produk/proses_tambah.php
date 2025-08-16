<?php
require_once(__DIR__ . '/../../config/config.php');

// Validasi CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error_message'] = "Token CSRF tidak valid";
    header("Location: tambah.php");
    exit();
}

// Validasi data
$required_fields = [
    'kode_produk', 'nama_produk', 'jumlah_produk', 
    'harga_produk', 'tanggal_produksi', 'tanggal_expired'
];

foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $_SESSION['error_message'] = "Field $field wajib diisi";
        header("Location: tambah.php");
        exit();
    }
}

// Cek duplikasi kode produk
$stmt = $pdo->prepare("SELECT COUNT(*) FROM produk WHERE kode_produk = ?");
$stmt->execute([$_POST['kode_produk']]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['error_message'] = "Kode produk sudah ada";
    header("Location: tambah.php");
    exit();
}

// Simpan ke database
try {
    $stmt = $pdo->prepare("INSERT INTO produk (
        kode_produk, nama_produk, jumlah_produk, 
        harga_produk, tanggal_produksi, tanggal_expired, keterangan
    ) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->execute([
        $_POST['kode_produk'],
        $_POST['nama_produk'],
        $_POST['jumlah_produk'],
        $_POST['harga_produk'],
        $_POST['tanggal_produksi'],
        $_POST['tanggal_expired'],
        $_POST['keterangan'] ?? 'Layak'
    ]);
    
    $_SESSION['success_message'] = "Produk berhasil ditambahkan";
    header("Location: index.php");
    
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Gagal menyimpan produk: " . $e->getMessage();
    header("Location: tambah.php");
}