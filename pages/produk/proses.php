<?php
require_once(__DIR__ . '/../../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = $_POST['action'];

            if ($action == 'tambah') {
                        $kode_sku = $_POST['kode_sku'];
                        $nama_produk = $_POST['nama_produk'];
                        $harga_jual = $_POST['harga_jual'];
                        $stok_minimal = $_POST['stok_minimal'];

                        $stmt = $pdo->prepare("INSERT INTO produk (kode_sku, nama_produk, harga_jual, stok_minimal) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$kode_sku, $nama_produk, $harga_jual, $stok_minimal]);
            } elseif ($action == 'edit') {
                        $id_produk = $_POST['id_produk'];
                        $kode_sku = $_POST['kode_sku'];
                        $nama_produk = $_POST['nama_produk'];
                        $harga_jual = $_POST['harga_jual'];
                        $stok_minimal = $_POST['stok_minimal'];

                        $stmt = $pdo->prepare("UPDATE produk SET kode_sku = ?, nama_produk = ?, harga_jual = ?, stok_minimal = ? WHERE id_produk = ?");
                        $stmt->execute([$kode_sku, $nama_produk, $harga_jual, $stok_minimal, $id_produk]);
            }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'hapus') {
            $id_produk = $_GET['id'];
            $stmt = $pdo->prepare("DELETE FROM produk WHERE id_produk = ?");
            $stmt->execute([$id_produk]);
}

redirect(BASE_URL . 'pages/produk/');
