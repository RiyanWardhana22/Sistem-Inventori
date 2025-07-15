<?php
require_once(__DIR__ . '/../../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'tambah_stok_bs') {
            $id_produk = $_POST['id_produk'];
            $jumlah_bs = $_POST['jumlah'];
            $keterangan = $_POST['keterangan'];

            if (empty($id_produk) || empty($jumlah_bs) || !is_numeric($jumlah_bs) || $jumlah_bs <= 0 || empty($keterangan)) {
                        redirect(BASE_URL . 'pages/stok_bs/');
            }

            $pdo->beginTransaction();

            try {
                        $stmtCek = $pdo->prepare("SELECT stok_saat_ini FROM produk WHERE id_produk = ? FOR UPDATE");
                        $stmtCek->execute([$id_produk]);
                        $stok_sekarang = $stmtCek->fetchColumn();

                        if ($stok_sekarang < $jumlah_bs) {
                                    $pdo->rollBack();
                                    die("Error: Stok tidak mencukupi untuk dicatat sebagai BS! Stok saat ini: {$stok_sekarang}, Anda mencoba mengurangi: {$jumlah_bs}. <a href='index.php'>Kembali</a>");
                        }

                        $stmtUpdate = $pdo->prepare("UPDATE produk SET stok_saat_ini = stok_saat_ini - ? WHERE id_produk = ?");
                        $stmtUpdate->execute([$jumlah_bs, $id_produk]);

                        $stmtInsert = $pdo->prepare("INSERT INTO riwayat_stok (id_produk, jumlah, tipe_transaksi, keterangan) VALUES (?, ?, 'bs', ?)");
                        $stmtInsert->execute([$id_produk, $jumlah_bs, $keterangan]);

                        $pdo->commit();
            } catch (Exception $e) {
                        $pdo->rollBack();
                        die("Error: Gagal menyimpan data produk BS. " . $e->getMessage());
            }

            redirect(BASE_URL . 'pages/stok_bs/');
} else {
            redirect(BASE_URL);
}
