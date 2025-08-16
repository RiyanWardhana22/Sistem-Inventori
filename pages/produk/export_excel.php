<?php
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Query untuk mengambil data produk
$stmt = $pdo->query("SELECT * FROM produk ORDER BY kode_produk ASC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set document properties
$spreadsheet->getProperties()
    ->setCreator('Sistem Inventori')
    ->setTitle('Data Produk')
    ->setSubject('Export Data Produk');

// Set header
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'Kode Produk');
$sheet->setCellValue('C1', 'Nama Produk');
$sheet->setCellValue('D1', 'Jumlah (Buah)');
$sheet->setCellValue('E1', 'Harga per Buah');
$sheet->setCellValue('F1', 'Tanggal Produksi');
$sheet->setCellValue('G1', 'Tanggal Expired');
$sheet->setCellValue('H1', 'Status');

// Add data
$rowNumber = 2;
foreach ($products as $index => $product) {
    $sheet->setCellValue('A'.$rowNumber, $index + 1);
    $sheet->setCellValue('B'.$rowNumber, $product['kode_produk']);
    $sheet->setCellValue('C'.$rowNumber, $product['nama_produk']);
    $sheet->setCellValue('D'.$rowNumber, $product['jumlah_produk']);
    $sheet->setCellValue('E'.$rowNumber, $product['harga_produk']);
    $sheet->setCellValue('F'.$rowNumber, $product['tanggal_produksi']);
    $sheet->setCellValue('G'.$rowNumber, $product['tanggal_expired']);
    
    // Cek status expired
    $today = new DateTime();
    $expired_date = new DateTime($product['tanggal_expired']);
    $status = ($expired_date < $today) ? 'Expired' : $product['keterangan'];
    $sheet->setCellValue('H'.$rowNumber, $status);
    
    $rowNumber++;
}

// Format columns
$sheet->getStyle('F2:G'.$rowNumber)
    ->getNumberFormat()
    ->setFormatCode('yyyy-mm-dd');

$sheet->getStyle('E2:E'.$rowNumber)
    ->getNumberFormat()
    ->setFormatCode('#,##0');

// Auto size columns
foreach (range('A', 'H') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Set header style
$headerStyle = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => 'center'],
    'borders' => ['allBorders' => ['borderStyle' => 'thin']],
    'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FFFF00']]
];
$sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

// Output the file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="data_produk_'.date('Y-m-d,H.i.s').'.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;