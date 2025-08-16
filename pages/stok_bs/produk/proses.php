<?php
require_once(__DIR__ . '/../../../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    // Validasi data
    $required_fields = ['tanggal_bs', 'kode_produk', 'jumlah'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['error_message'] = "Field $field tidak boleh kosong.";
            redirect(BASE_URL . 'pages/stok_bs/');
        }
    }

    if ($action == 'tambah') {
        // Tambah data BS baru
        $stmt = $pdo->prepare("INSERT INTO stok_bs 
                             (tanggal_bs, kode_produk, jumlah, keterangan) 
                             VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $_POST['tanggal_bs'],
            $_POST['kode_produk'],
            $_POST['jumlah'],
            $_POST['keterangan'] ?? null
        ]);
        
        $_SESSION['success_message'] = "Data BS berhasil ditambahkan.";
        
    } elseif ($action == 'edit') {
        // Update data BS
        $stmt = $pdo->prepare("UPDATE stok_bs SET 
                             tanggal_bs = ?,
                             kode_produk = ?,
                             jumlah = ?,
                             keterangan = ?
                             WHERE id_bs = ?");
        $stmt->execute([
            $_POST['tanggal_bs'],
            $_POST['kode_produk'],
            $_POST['jumlah'],
            $_POST['keterangan'] ?? null,
            $_POST['id_bs']
        ]);
        
        $_SESSION['success_message'] = "Data BS berhasil diperbarui.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'hapus') {
    $id_bs = $_GET['id'];
    
    // Hapus data BS
    $stmt = $pdo->prepare("DELETE FROM stok_bs WHERE id_bs = ?");
    $stmt->execute([$id_bs]);
    
    $_SESSION['success_message'] = "Data BS berhasil dihapus.";
}

redirect(BASE_URL . 'pages/stok_bs/produk');