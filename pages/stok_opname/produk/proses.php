<?php
require_once(__DIR__ . '/../../../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $action = $_POST['action'];
        
        // Validasi data
        $required_fields = ['tanggal_opname', 'kode_produk', 'stok_awal', 'stok_akhir', 'penjualan', 'bs'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field])) {
                throw new Exception("Field $field tidak boleh kosong.");
            }
        }

        // Validasi stok
        $stok_awal = (int)$_POST['stok_awal'];
        $stok_akhir = (int)$_POST['stok_akhir'];
        $penjualan = (int)$_POST['penjualan'];
        $bs = (int)$_POST['bs'];
        
        if ($stok_awal < ($stok_akhir + $penjualan + $bs)) {
            throw new Exception("Stok awal tidak boleh lebih kecil dari total (Stok akhir + Penjualan + BS)");
        }

        if ($action == 'edit') {
            // Update data opname
            $stmt = $pdo->prepare("UPDATE opname_produk SET 
                                 tanggal_opname = ?,
                                 kode_produk = ?,
                                 kode = ?,
                                 stok_awal = ?,
                                 stok_akhir = ?,
                                 penjualan = ?,
                                 bs = ?
                                 WHERE id_opname = ?");
            $result = $stmt->execute([
                $_POST['tanggal_opname'],
                $_POST['kode_produk'],
                $_POST['kode'] ?? null,
                $_POST['stok_awal'],
                $_POST['stok_akhir'],
                $_POST['penjualan'],
                $_POST['bs'],
                $_POST['id_opname']
            ]);
            
            if (!$result) {
                throw new Exception("Gagal memperbarui data opname.");
            }
            
            $_SESSION['success_message'] = "Data opname berhasil diperbarui.";
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        if ($action == 'edit' && isset($_POST['id_opname'])) {
            redirect(BASE_URL . 'pages/stok_opname/produk/edit.php?id='.$_POST['id_opname']);
        } else {
            redirect(BASE_URL . 'pages/stok_opname/produk/');
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'hapus') {
    $id_opname = $_GET['id'];
    
    // Hapus data opname
    $stmt = $pdo->prepare("DELETE FROM opname_produk WHERE id_opname = ?");
    $stmt->execute([$id_opname]);
    
    $_SESSION['success_message'] = "Data opname berhasil dihapus.";
}

redirect(BASE_URL . 'pages/stok_opname/produk/');