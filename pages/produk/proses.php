<?php
require_once(__DIR__ . '/../../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    // Validasi data
    $required_fields = ['kode_produk', 'nama_produk', 'jumlah_produk', 'harga_produk', 'tanggal_produksi', 'tanggal_expired'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['error_message'] = "Field $field tidak boleh kosong.";
            redirect(BASE_URL . 'pages/produk/');
        }
    }

    if ($action == 'tambah') {
        // Cek apakah kode produk sudah ada
        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM produk WHERE kode_produk = ?");
        $check_stmt->execute([$_POST['kode_produk']]);
        if ($check_stmt->fetchColumn() > 0) {
            $_SESSION['error_message'] = "Kode produk sudah ada.";
            redirect(BASE_URL . 'pages/produk/tambah.php');
        }

        // Tambah produk baru
        $stmt = $pdo->prepare("INSERT INTO produk 
                             (kode_produk, nama_produk, jumlah_produk, tanggal_produksi, tanggal_expired, harga_produk, keterangan) 
                             VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['kode_produk'],
            $_POST['nama_produk'],
            $_POST['jumlah_produk'],
            $_POST['tanggal_produksi'],
            $_POST['tanggal_expired'],
            $_POST['harga_produk'],
            $_POST['keterangan']
        ]);
        
        $_SESSION['success_message'] = "Produk berhasil ditambahkan.";
        
    } elseif ($action == 'edit') {
        // Update produk
        $stmt = $pdo->prepare("UPDATE produk SET 
                             nama_produk = ?,
                             jumlah_produk = ?,
                             tanggal_produksi = ?,
                             tanggal_expired = ?,
                             harga_produk = ?,
                             keterangan = ?
                             WHERE kode_produk = ?");
        $stmt->execute([
            $_POST['nama_produk'],
            $_POST['jumlah_produk'],
            $_POST['tanggal_produksi'],
            $_POST['tanggal_expired'],
            $_POST['harga_produk'],
            $_POST['keterangan'],
            $_POST['kode_produk']
        ]);
        
        $_SESSION['success_message'] = "Produk berhasil diperbarui.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'hapus') {
    $kode_produk = $_GET['id'];
    
    // Hapus produk
    $stmt = $pdo->prepare("DELETE FROM produk WHERE kode_produk = ?");
    $stmt->execute([$kode_produk]);
    
    $_SESSION['success_message'] = "Produk berhasil dihapus.";
}

redirect(BASE_URL . 'pages/produk/');