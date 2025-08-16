<?php
require_once(__DIR__ . '/../../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    // Validasi data
    $required_fields = ['nama_bahan', 'jumlah_bahan', 'satuan_jumlah', 'status'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['error_message'] = "Field $field tidak boleh kosong.";
            redirect(BASE_URL . 'pages/bahan/');
        }
    }

    if ($action == 'tambah') {
        // Cek apakah nama bahan sudah ada
        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM bahan WHERE nama_bahan = ?");
        $check_stmt->execute([$_POST['nama_bahan']]);
        if ($check_stmt->fetchColumn() > 0) {
            $_SESSION['error_message'] = "Nama bahan sudah ada.";
            redirect(BASE_URL . 'pages/bahan/tambah.php');
        }

        // Tambah bahan baru
        $stmt = $pdo->prepare("INSERT INTO bahan 
                             (nama_bahan, jumlah_bahan, satuan_jumlah, tanggal_expired, status) 
                             VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['nama_bahan'],
            $_POST['jumlah_bahan'],
            $_POST['satuan_jumlah'],
            $_POST['tanggal_expired'] ?: null,
            $_POST['status']
        ]);
        
        $_SESSION['success_message'] = "Bahan berhasil ditambahkan.";
        
    } elseif ($action == 'edit') {
        // Update bahan
        $stmt = $pdo->prepare("UPDATE bahan SET 
                             jumlah_bahan = ?,
                             satuan_jumlah = ?,
                             tanggal_expired = ?,
                             status = ?
                             WHERE nama_bahan = ?");
        $stmt->execute([
            $_POST['jumlah_bahan'],
            $_POST['satuan_jumlah'],
            $_POST['tanggal_expired'] ?: null,
            $_POST['status'],
            $_POST['nama_bahan']
        ]);
        
        $_SESSION['success_message'] = "Bahan berhasil diperbarui.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'hapus') {
    $nama_bahan = $_GET['id'];
    
    // Hapus bahan
    $stmt = $pdo->prepare("DELETE FROM bahan WHERE nama_bahan = ?");
    $stmt->execute([$nama_bahan]);
    
    $_SESSION['success_message'] = "Bahan berhasil dihapus.";
}

redirect(BASE_URL . 'pages/bahan/');