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
    'nama_bahan', 'jumlah_bahan', 'satuan_jumlah'
];

foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $_SESSION['error_message'] = "Field $field wajib diisi";
        header("Location: tambah.php");
        exit();
    }
}

// Validasi jumlah bahan
if (!is_numeric($_POST['jumlah_bahan']) || $_POST['jumlah_bahan'] < 1) {
    $_SESSION['error_message'] = "Jumlah bahan harus angka lebih dari 0";
    header("Location: tambah.php");
    exit();
}

// Validasi tanggal expired jika diisi
if (!empty($_POST['tanggal_expired']) && !DateTime::createFromFormat('Y-m-d', $_POST['tanggal_expired'])) {
    $_SESSION['error_message'] = "Format tanggal expired salah (harus YYYY-MM-DD atau kosong)";
    header("Location: tambah.php");
    exit();
}

// Validasi status
$allowed_status = ['Layak', 'Rusak', 'Expired'];
if (!in_array($_POST['status'], $allowed_status)) {
    $_SESSION['error_message'] = "Status harus Layak, Rusak, atau Expired";
    header("Location: tambah.php");
    exit();
}

// Cek duplikasi nama_bahan (primary key)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM bahan WHERE nama_bahan = ?");
$stmt->execute([$_POST['nama_bahan']]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['error_message'] = "Nama bahan sudah ada";
    header("Location: tambah.php");
    exit();
}

// Simpan ke database
try {
    $stmt = $pdo->prepare("INSERT INTO bahan (
        nama_bahan, jumlah_bahan, 
        satuan_jumlah, tanggal_expired, status
    ) VALUES (?, ?, ?, ?, ?)");
    
    $tanggal_expired = !empty($_POST['tanggal_expired']) ? $_POST['tanggal_expired'] : null;
    
    $stmt->execute([
        $_POST['nama_bahan'],
        $_POST['jumlah_bahan'],
        $_POST['satuan_jumlah'],
        $tanggal_expired,
        $_POST['status']
    ]);
    
    $_SESSION['success_message'] = "Bahan berhasil ditambahkan";
    header("Location: index.php");
    
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Gagal menyimpan bahan: " . $e->getMessage();
    header("Location: tambah.php");
}