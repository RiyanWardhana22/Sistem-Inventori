<?php
require_once(__DIR__ . '/../../../config/config.php');
require_once(__DIR__ . '/../../../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$search_date = $_GET['search_date'] ?? '';

// Query untuk mengambil data opname produk
$sql = "SELECT 
            op.id_opname,
            op.kode,
            op.tanggal_opname,
            op.kode_produk,
            op.stok_awal,
            op.stok_akhir,
            op.penjualan,
            op.bs,
            p.nama_produk
        FROM opname_produk op
        LEFT JOIN produk p ON op.kode_produk = p.kode_produk";
if (!empty($search_date)) {
    $sql .= " WHERE op.tanggal_opname = ?";
}
$sql .= " ORDER BY op.tanggal_opname DESC, op.kode_produk ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute(!empty($search_date) ? [$search_date] : []);
$opname_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set document properties
$spreadsheet->getProperties()
    ->setCreator('Sistem Inventori')
    ->setTitle('Data Opname Produk')
    ->setSubject('Export Data Opname Produk');

// Set header
$headers = [
    'No',
    'Kode Opname',
    'Tanggal Opname',
    'Kode Produk',
    'Nama Produk',
    'Stock Awal',
    'Stock Akhir',
    'Penjualan',
    'BS'
];

$sheet->fromArray($headers, null, 'A1');

// Add data
$rowNumber = 2;
foreach ($opname_data as $index => $data) {
    $rowData = [
        $index + 1,
        $data['kode'] ?? '-',
        $data['tanggal_opname'],
        $data['kode_produk'],
        $data['nama_produk'] ?? '-',
        is_numeric($data['stok_awal']) ? $data['stok_awal'] : 0,
        is_numeric($data['stok_akhir']) ? $data['stok_akhir'] : 0,
        is_numeric($data['penjualan']) ? $data['penjualan'] : 0,
        is_numeric($data['bs']) ? $data['bs'] : 0
    ];
    
    $sheet->fromArray($rowData, null, 'A'.$rowNumber);
    $rowNumber++;
}

// Format columns
$sheet->getStyle('C2:C'.$rowNumber)  // Format tanggal
    ->getNumberFormat()
    ->setFormatCode('yyyy-mm-dd');

$sheet->getStyle('F2:I'.$rowNumber)  // Format angka (stock awal, akhir, penjualan, bs)
    ->getNumberFormat()
    ->setFormatCode('#,##0');

// Auto size columns
foreach (range('A', 'I') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Set header style
$headerStyle = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => 'center'],
    'borders' => ['allBorders' => ['borderStyle' => 'thin']],
    'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FFFF00']]
];
$sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

// Clear any output before sending file
if (ob_get_length()) {
    ob_end_clean();
}

// Output the file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="data_opname_'.date('Y-m-d,H.i.s').'.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: cache, must-revalidate');
header('Pragma: public');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;