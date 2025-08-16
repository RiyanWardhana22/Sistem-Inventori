<?php
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Query untuk mengambil data bahan
$stmt = $pdo->query("SELECT * FROM bahan ORDER BY nama_bahan ASC");
$materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set document properties
$spreadsheet->getProperties()
    ->setCreator('Sistem Inventori')
    ->setTitle('Data Bahan')
    ->setSubject('Export Data Bahan');

// Set header
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'Nama Bahan');
$sheet->setCellValue('C1', 'Jumlah');
$sheet->setCellValue('D1', 'Satuan');
$sheet->setCellValue('E1', 'Tanggal Expired');
$sheet->setCellValue('F1', 'Status');

// Add data
$rowNumber = 2;
foreach ($materials as $index => $material) {
    // Cek status expired jika ada tanggal expired
    $status = $material['status'];
    if ($material['tanggal_expired'] !== null) {
        $today = new DateTime();
        $expired_date = new DateTime($material['tanggal_expired']);
        if ($expired_date < $today) {
            $status = 'Expired';
        }
    }

    $sheet->setCellValue('A'.$rowNumber, $index + 1);
    $sheet->setCellValue('B'.$rowNumber, $material['nama_bahan']);
    $sheet->setCellValue('C'.$rowNumber, $material['jumlah_bahan']);
    $sheet->setCellValue('D'.$rowNumber, $material['satuan_jumlah']);
    $sheet->setCellValue('E'.$rowNumber, $material['tanggal_expired'] ?: '-');
    $sheet->setCellValue('F'.$rowNumber, $status);
    
    $rowNumber++;
}

// Format columns
$sheet->getStyle('E2:E'.$rowNumber)
    ->getNumberFormat()
    ->setFormatCode('yyyy-mm-dd');

$sheet->getStyle('C2:C'.$rowNumber)
    ->getNumberFormat()
    ->setFormatCode('#,##0');

// Auto size columns
foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Set header style
$headerStyle = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => 'center'],
    'borders' => ['allBorders' => ['borderStyle' => 'thin']],
    'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FFFF00']]
];
$sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

// Output the file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="data_bahan_'.date('Y-m-d,H.i.s').'.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;