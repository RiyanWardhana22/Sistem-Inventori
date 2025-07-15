<?php
require_once(__DIR__ . '/../../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'tambah_stok_keluar') {
            $id_produk = $_POST['id_produk'];
            $jumlah_keluar = $_POST['jumlah'];

            if (empty($id_produk) || empty($jumlah_keluar) || !is_numeric($jumlah_keluar) || $jumlah_keluar <= 0) {
                        redirect(BASE_URL . 'pages/stok_keluar/');
            }

            $pdo->beginTransaction();

            try {
                        $stmtCek = $pdo->prepare("SELECT stok_saat_ini FROM produk WHERE id_produk = ? FOR UPDATE");
                        $stmtCek->execute([$id_produk]);
                        $stok_sekarang = $stmtCek->fetchColumn();

                        if ($stok_sekarang < $jumlah_keluar) {
                                    $pdo->rollBack();
                                    die("Error: Stok tidak mencukupi! Stok saat ini: {$stok_sekarang}, Anda mencoba menjual: {$jumlah_keluar}. <a href='index.php'>Kembali</a>");
                        }

                        $stmtUpdate = $pdo->prepare("UPDATE produk SET stok_saat_ini = stok_saat_ini - ? WHERE id_produk = ?");
                        $stmtUpdate->execute([$jumlah_keluar, $id_produk]);

                        $keterangan = "Penjualan Harian";
                        $stmtInsert = $pdo->prepare("INSERT INTO riwayat_stok (id_produk, jumlah, tipe_transaksi, keterangan) VALUES (?, ?, 'keluar', ?)");
                        $stmtInsert->execute([$id_produk, $jumlah_keluar, $keterangan]);

                        $pdo->commit();
            } catch (Exception $e) {
                        $pdo->rollBack();
                        die("Error: Gagal menyimpan data penjualan. " . $e->getMessage());
            }

            redirect(BASE_URL . 'pages/stok_keluar/');
} else {
            redirect(BASE_URL);
}
