<?php
require_once(__DIR__ . '/../../../config/config.php');

// Validasi CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error_message'] = "Token CSRF tidak valid";
    header("Location: tambah.php");
    exit();
}

// Validasi data wajib
$required_fields = ['tanggal_bs', 'kode_produk', 'jumlah'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $_SESSION['error_message'] = "Field $field wajib diisi";
        header("Location: tambah.php");
        exit();
    }
}

// Validasi tanggal
if (!DateTime::createFromFormat('Y-m-d', $_POST['tanggal_bs'])) {
    $_SESSION['error_message'] = "Format tanggal BS salah (harus YYYY-MM-DD)";
    header("Location: tambah.php");
    exit();
}

// Validasi jumlah
if (!is_numeric($_POST['jumlah']) || $_POST['jumlah'] <= 0) {
    $_SESSION['error_message'] = "Jumlah harus angka > 0";
    header("Location: tambah.php");
    exit();
}
$jumlah = (int)$_POST['jumlah'];

// Cek apakah produk ada di database
$stmt_check = $pdo->prepare("SELECT COUNT(*) FROM produk WHERE kode_produk = ?");
$stmt_check->execute([$_POST['kode_produk']]);
if ($stmt_check->fetchColumn() == 0) {
    $_SESSION['error_message'] = "Produk dengan kode " . htmlspecialchars($_POST['kode_produk']) . " tidak ditemukan";
    header("Location: tambah.php");
    exit();
}

// Simpan ke database
try {
    $stmt = $pdo->prepare("INSERT INTO stok_bs (
        kode_produk,
        tanggal_bs,
        jumlah,
        keterangan
    ) VALUES (?, ?, ?, ?)");
    
    $stmt->execute([
        $_POST['kode_produk'],
        $_POST['tanggal_bs'],
        $jumlah,
        $_POST['keterangan'] ?? null
    ]);
    
    $_SESSION['success_message'] = "Data BS berhasil ditambahkan";
    header("Location: index.php");
    exit();
    
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Gagal menyimpan data BS: " . $e->getMessage();
    header("Location: tambah.php");
    exit();
}