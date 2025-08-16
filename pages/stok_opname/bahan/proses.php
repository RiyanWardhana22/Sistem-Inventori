<?php
require_once(__DIR__ . '/../../../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    // Validasi data wajib
    $required_fields = ['tanggal_opname', 'nama_bahan'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['error_message'] = "Field $field tidak boleh kosong.";
            redirect(BASE_URL . 'pages/stok_opname/bahan/');
        }
    }

    // Fungsi untuk memproses nilai
    function processValue($value) {
        if ($value === '' || $value === null) {
            return null;
        }
        return $value;
    }

    // Proses nilai
    $kode = processValue($_POST['kode'] ?? null);
    $stok_awal = processValue($_POST['stok_awal'] ?? null);
    $stok_akhir = processValue($_POST['stok_akhir'] ?? null);
    $penggunaan = processValue($_POST['penggunaan'] ?? null);
    $bs = processValue($_POST['bs'] ?? null);

    // Validasi stok hanya jika semua field terkait memiliki nilai numerik
    if (is_numeric($stok_awal) && is_numeric($stok_akhir) && is_numeric($penggunaan) && is_numeric($bs)) {
        if ($stok_awal < ($stok_akhir + $penggunaan + $bs)) {
            $_SESSION['error_message'] = "Stok awal tidak boleh lebih kecil dari total (Stok akhir + Penggunaan + BS)";
            redirect(BASE_URL . 'pages/stok_opname/bahan/' . ($action == 'tambah' ? 'tambah.php' : 'edit.php?id='.$_POST['id_opname']));
        }
    }

    if ($action == 'tambah') {
        // Tambah data opname baru
        $stmt = $pdo->prepare("INSERT INTO opname_bahan 
                             (tanggal_opname, nama_bahan, kode, stok_awal, stok_akhir, penggunaan, bs) 
                             VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['tanggal_opname'],
            $_POST['nama_bahan'],
            $kode,
            $stok_awal,
            $stok_akhir,
            $penggunaan,
            $bs
        ]);
        
        $_SESSION['success_message'] = "Data opname berhasil ditambahkan.";
        
    } elseif ($action == 'edit') {
        // Update data opname
        $stmt = $pdo->prepare("UPDATE opname_bahan SET 
                             tanggal_opname = ?,
                             nama_bahan = ?,
                             kode = ?,
                             stok_awal = ?,
                             stok_akhir = ?,
                             penggunaan = ?,
                             bs = ?
                             WHERE id_opname = ?");
        $stmt->execute([
            $_POST['tanggal_opname'],
            $_POST['nama_bahan'],
            $kode,
            $stok_awal,
            $stok_akhir,
            $penggunaan,
            $bs,
            $_POST['id_opname']
        ]);
        
        $_SESSION['success_message'] = "Data opname berhasil diperbarui.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'hapus') {
    $id_opname = $_GET['id'];
    
    // Hapus data opname
    $stmt = $pdo->prepare("DELETE FROM opname_bahan WHERE id_opname = ?");
    $stmt->execute([$id_opname]);
    
    $_SESSION['success_message'] = "Data opname berhasil dihapus.";
}

redirect(BASE_URL . 'pages/stok_opname/bahan/');