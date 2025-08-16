<?php
require_once(__DIR__ . '/../../../config/config.php');
require_once(__DIR__ . '/../../../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$search_date = $_GET['search_date'] ?? '';

// Query untuk mengambil data opname bahan
$sql = "SELECT 
            id_opname,
            kode,
            tanggal_opname,
            nama_bahan,
            stok_awal,
            stok_akhir,
            penggunaan,
            bs
        FROM opname_bahan";
if (!empty($search_date)) {
    $sql .= " WHERE tanggal_opname = ?";
}
$sql .= " ORDER BY tanggal_opname DESC, nama_bahan ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute(!empty($search_date) ? [$search_date] : []);
$opname_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set document properties
$spreadsheet->getProperties()
    ->setCreator('Sistem Inventori')
    ->setTitle('Data Opname Bahan')
    ->setSubject('Export Data Opname Bahan');

// Set header
$headers = [
    'No',
    'Kode Opname',
    'Tanggal Opname',
    'Nama Bahan',
    'Stock Awal',
    'Stock Akhir',
    'penggunaan',
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
        $data['nama_bahan'],
        is_numeric($data['stok_awal']) ? $data['stok_awal'] : 0,
        is_numeric($data['stok_akhir']) ? $data['stok_akhir'] : 0,
        is_numeric($data['penggunaan']) ? $data['penggunaan'] : 0,
        is_numeric($data['bs']) ? $data['bs'] : 0
    ];
    
    $sheet->fromArray($rowData, null, 'A'.$rowNumber);
    $rowNumber++;
}

// Format columns
$sheet->getStyle('C2:C'.$rowNumber)  // Format tanggal
    ->getNumberFormat()
    ->setFormatCode('yyyy-mm-dd');

$sheet->getStyle('E2:H'.$rowNumber)  // Format angka (stock awal, akhir, penggunaan, bs)
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

// Clear any output before sending file
if (ob_get_length()) {
    ob_end_clean();
}

// Output the file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="data_opname_bahan_'.date('Y-m-d,H.i.s').'.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: cache, must-revalidate');
header('Pragma: public');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;