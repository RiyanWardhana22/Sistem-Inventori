<?php
require_once(__DIR__ . '/../../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'sesuaikan') {
            $id_produk = $_POST['id_produk'];
            $stok_fisik = $_POST['stok_fisik'];
            $selisih = $_POST['selisih'];

            if (!isset($id_produk) || !isset($stok_fisik) || !is_numeric($stok_fisik) || $stok_fisik < 0) {
                        redirect(BASE_URL . 'pages/stok_opname/');
            }

            $pdo->beginTransaction();

            try {
                        $stmtUpdate = $pdo->prepare("UPDATE produk SET stok_saat_ini = ? WHERE id_produk = ?");
                        $stmtUpdate->execute([$stok_fisik, $id_produk]);

                        if ($selisih != 0) {
                                    $keterangan = "Stok Opname";
                                    $stmtInsert = $pdo->prepare("INSERT INTO riwayat_stok (id_produk, jumlah, tipe_transaksi, keterangan) VALUES (?, ?, 'penyesuaian', ?)");
                                    $stmtInsert->execute([$id_produk, $selisih, $keterangan]);
                        }

                        $pdo->commit();
            } catch (Exception $e) {
                        $pdo->rollBack();
                        die("Error: Gagal melakukan penyesuaian stok. " . $e->getMessage());
            }

            redirect(BASE_URL . 'pages/stok_opname/');
} else {
            redirect(BASE_URL);
}
