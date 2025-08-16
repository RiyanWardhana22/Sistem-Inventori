<?php
require_once(__DIR__ . '/../../../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action == 'tambah') {
                        $stmt = $pdo->prepare("INSERT INTO opname_produk (kode_produk, tanggal_opname, kode, stok_awal, stok_akhir, penjualan, bs) VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $stmt->execute([
                                    $_POST['kode_produk'],
                                    $_POST['tanggal_opname'],
                                    $_POST['kode'],
                                    $_POST['stok_awal'],
                                    $_POST['stok_akhir'],
                                    $_POST['penjualan'],
                                    $_POST['bs']
                        ]);
            } elseif ($action == 'edit') {
                        $stmt = $pdo->prepare("UPDATE opname_produk SET kode_produk=?, tanggal_opname=?, kode=?, stok_awal=?, stok_akhir=?, penjualan=?, bs=? WHERE id_opname=?");
                        $stmt->execute([
                                    $_POST['kode_produk'],
                                    $_POST['tanggal_opname'],
                                    $_POST['kode'],
                                    $_POST['stok_awal'],
                                    $_POST['stok_akhir'],
                                    $_POST['penjualan'],
                                    $_POST['bs'],
                                    $_POST['id_opname']
                        ]);
            }
}

if (isset($_GET['action']) && $_GET['action'] == 'hapus') {
            $id_opname = $_GET['id'] ?? null;
            if ($id_opname) {
                        $stmt = $pdo->prepare("DELETE FROM opname_produk WHERE id_opname = ?");
                        $stmt->execute([$id_opname]);
            }
}

redirect(BASE_URL . 'pages/stok_opname/produk/');
