<?php
require_once(__DIR__ . '/../../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action == 'tambah') {
                        $stmt = $pdo->prepare("INSERT INTO stok_bs (id_produk, tanggal_bs, jumlah, keterangan) VALUES (?, ?, ?, ?)");
                        $stmt->execute([
                                    $_POST['id_produk'],
                                    $_POST['tanggal_bs'],
                                    $_POST['jumlah'],
                                    $_POST['keterangan']
                        ]);
            } elseif ($action == 'edit') {
                        $stmt = $pdo->prepare("UPDATE stok_bs SET id_produk=?, tanggal_bs=?, jumlah=?, keterangan=? WHERE id_bs=?");
                        $stmt->execute([
                                    $_POST['id_produk'],
                                    $_POST['tanggal_bs'],
                                    $_POST['jumlah'],
                                    $_POST['keterangan'],
                                    $_POST['id_bs']
                        ]);
            }
}

if (isset($_GET['action']) && $_GET['action'] == 'hapus') {
            $id_bs = $_GET['id'] ?? null;
            if ($id_bs) {
                        $stmt = $pdo->prepare("DELETE FROM stok_bs WHERE id_bs = ?");
                        $stmt->execute([$id_bs]);
            }
}

redirect(BASE_URL . 'pages/stok_bs/');
