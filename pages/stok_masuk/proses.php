<?php
require_once(__DIR__ . '/../../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'tambah_stok_masuk') {
            $id_produk = $_POST['id_produk'];
            $jumlah = $_POST['jumlah'];
            if (empty($id_produk) || empty($jumlah) || !is_numeric($jumlah) || $jumlah <= 0) {
                        redirect(BASE_URL . 'pages/stok_masuk/');
            }

            $pdo->beginTransaction();

            try {
                        $stmtUpdate = $pdo->prepare("UPDATE produk SET stok_saat_ini = stok_saat_ini + ? WHERE id_produk = ?");
                        $stmtUpdate->execute([$jumlah, $id_produk]);
                        $keterangan = "Produksi Harian";
                        $stmtInsert = $pdo->prepare("INSERT INTO riwayat_stok (id_produk, jumlah, tipe_transaksi, keterangan) VALUES (?, ?, 'masuk', ?)");
                        $stmtInsert->execute([$id_produk, $jumlah, $keterangan]);

                        $pdo->commit();
            } catch (Exception $e) {
                        $pdo->rollBack();
                        die("Error: Gagal menyimpan data stok. " . $e->getMessage());
            }

            redirect(BASE_URL . 'pages/stok_masuk/');
} else {
            redirect(BASE_URL);
}
