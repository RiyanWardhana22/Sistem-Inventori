<?php
require_once(__DIR__ . '/../../config/config.php');

if ($_SESSION['level'] != 'admin') die("Akses ditolak.");

$tanggal_mulai = $_GET['tanggal_mulai'] ?? date('Y-m-01');
$tanggal_akhir = $_GET['tanggal_akhir'] ?? date('Y-m-t');

$stmt = $pdo->prepare("SELECT p.nama_produk, SUM(rs.jumlah) as total_terjual, SUM(rs.jumlah * p.harga_jual) as total_pendapatan FROM riwayat_stok rs JOIN produk p ON rs.id_produk = p.id_produk WHERE rs.tipe_transaksi = 'keluar' AND DATE(rs.tanggal_transaksi) BETWEEN ? AND ? GROUP BY p.id_produk ORDER BY p.nama_produk ASC");
$stmt->execute([$tanggal_mulai, $tanggal_akhir]);
$data_penjualan = $stmt->fetchAll(PDO::FETCH_ASSOC);

$filename = "laporan_penjualan_" . date('Y-m-d') . ".csv";
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

fputcsv($output, ['Nama Produk', 'Total Terjual (pcs)', 'Total Pendapatan (Rp)']);

foreach ($data_penjualan as $item) {
            fputcsv($output, [
                        $item['nama_produk'],
                        $item['total_terjual'],
                        $item['total_pendapatan']
            ]);
}

fclose($output);
exit();
