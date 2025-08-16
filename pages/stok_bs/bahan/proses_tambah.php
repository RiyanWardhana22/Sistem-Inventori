<?php
require_once(__DIR__ . '/../../../config/config.php');

// Validasi CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error_message'] = "Token CSRF tidak valid";
    header("Location: tambah.php");
    exit();
}

// Validasi data wajib
$required_fields = ['tanggal_bs', 'nama_bahan', 'jumlah_bahan', 'satuan_jumlah'];
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
if (!is_numeric($_POST['jumlah_bahan']) || $_POST['jumlah_bahan'] <= 0) {
    $_SESSION['error_message'] = "Jumlah harus angka > 0";
    header("Location: tambah.php");
    exit();
}
$jumlah_bahan = (int)$_POST['jumlah_bahan'];

// Cek apakah bahan ada di database
$stmt_check = $pdo->prepare("SELECT COUNT(*) FROM bahan WHERE nama_bahan = ?");
$stmt_check->execute([$_POST['nama_bahan']]);
if ($stmt_check->fetchColumn() == 0) {
    $_SESSION['error_message'] = "Bahan dengan nama " . htmlspecialchars($_POST['nama_bahan']) . " tidak ditemukan";
    header("Location: tambah.php");
    exit();
}

// Simpan ke database
try {
    $stmt = $pdo->prepare("INSERT INTO bs_bahan (
        nama_bahan,
        tanggal_bs,
        jumlah_bahan,
        satuan_jumlah,
        keterangan
    ) VALUES (?, ?, ?, ?, ?)");
    
    $stmt->execute([
        $_POST['nama_bahan'],
        $_POST['tanggal_bs'],
        $jumlah_bahan,
        $_POST['satuan_jumlah'],
        $_POST['keterangan'] ?? null
    ]);
    
    $_SESSION['success_message'] = "Data BS Bahan berhasil ditambahkan";
    header("Location: index.php");
    exit();
    
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Gagal menyimpan data BS Bahan: " . $e->getMessage();
    header("Location: tambah.php");
    exit();
}