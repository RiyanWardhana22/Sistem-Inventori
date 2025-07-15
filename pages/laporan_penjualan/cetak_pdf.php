<?php
require_once(__DIR__ . '/../../config/config.php');

if ($_SESSION['level'] != 'admin') die("Akses ditolak.");

use Dompdf\Dompdf;
use Dompdf\Options;

$tanggal_mulai = $_GET['tanggal_mulai'] ?? date('Y-m-01');
$tanggal_akhir = $_GET['tanggal_akhir'] ?? date('Y-m-t');

$stmt = $pdo->prepare("SELECT p.nama_produk, SUM(rs.jumlah) as total_terjual, SUM(rs.jumlah * p.harga_jual) as total_pendapatan FROM riwayat_stok rs JOIN produk p ON rs.id_produk = p.id_produk WHERE rs.tipe_transaksi = 'keluar' AND DATE(rs.tanggal_transaksi) BETWEEN ? AND ? GROUP BY p.id_produk ORDER BY total_pendapatan DESC");
$stmt->execute([$tanggal_mulai, $tanggal_akhir]);
$data_penjualan = $stmt->fetchAll(PDO::FETCH_ASSOC);

$html = "
<html>
<head>
<style>
    body { font-family: sans-serif; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #dddddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
    h1 { text-align: center; }
    .periode { text-align: center; margin-bottom: 20px; }
    .total { font-weight: bold; }
</style>
</head>
<body>
    <h1>Laporan Penjualan</h1>
    <div class='periode'>Periode: " . date('d F Y', strtotime($tanggal_mulai)) . " - " . date('d F Y', strtotime($tanggal_akhir)) . "</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Total Terjual (pcs)</th>
                <th>Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>";

$no = 1;
$grand_total_pendapatan = 0;
foreach ($data_penjualan as $item) {
            $html .= "
    <tr>
        <td>" . $no++ . "</td>
        <td>" . htmlspecialchars($item['nama_produk']) . "</td>
        <td style='text-align:center;'>" . $item['total_terjual'] . "</td>
        <td style='text-align:right;'>" . format_rupiah($item['total_pendapatan']) . "</td>
    </tr>";
            $grand_total_pendapatan += $item['total_pendapatan'];
}

$html .= "
        </tbody>
        <tfoot>
            <tr>
                <td colspan='3' class='total' style='text-align:right;'>Grand Total</td>
                <td class='total' style='text-align:right;'>" . format_rupiah($grand_total_pendapatan) . "</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>";

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("laporan-penjualan.pdf", array("Attachment" => false));
