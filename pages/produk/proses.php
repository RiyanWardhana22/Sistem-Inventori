<?php
require_once(__DIR__ . '/../../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = $_POST['action'];
            $nama_produk = $_POST['nama_produk'];

            if (empty($nama_produk)) {
                        die("Nama produk tidak boleh kosong.");
            }

            if ($action == 'tambah') {
                        $stmt = $pdo->prepare("INSERT INTO produk (nama_produk, kode_sku) VALUES (?, ?)");
                        $stmt->execute([$nama_produk, '']);
            } elseif ($action == 'edit') {
                        $id_produk = $_POST['id_produk'];
                        $stmt = $pdo->prepare("UPDATE produk SET nama_produk = ? WHERE id_produk = ?");
                        $stmt->execute([$nama_produk, $id_produk]);
            }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'hapus') {
            $id_produk = $_GET['id'];
            $stmt = $pdo->prepare("DELETE FROM produk WHERE id_produk = ?");
            $stmt->execute([$id_produk]);
}

redirect(BASE_URL . 'pages/produk/');
