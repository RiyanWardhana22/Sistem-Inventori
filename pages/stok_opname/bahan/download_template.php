<?php
require_once(__DIR__ . '/../../../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header with requested columns
$headers = [
    'Kode Opname',
    'Tanggal Opname (YYYY-MM-DD)',
    'Nama Bahan',
    'Stock Awal',
    'Stock Akhir',
    'Penggunaan',
    'BS (Barang Rusak)'
];

$sheet->fromArray($headers, null, 'A1');

// Example data row
$exampleData = [
    'OPN001',                        // Kode Opname
    date('Y-m-d'),                   // Tanggal Opname
    'Contoh Bahan 1',                // Nama Bahan
    100,                             // Stock Awal
    80,                              // Stock Akhir
    15,                              // Penggunaan
    5                                // BS
];

$sheet->fromArray($exampleData, null, 'A2');

// Formatting
$headerStyle = [
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4CAF50']],
    'alignment' => ['horizontal' => 'center'],
    'borders' => ['allBorders' => ['borderStyle' => 'thin']]
];

// Apply header style
$sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

// Format date column
$sheet->getStyle('B2:B100')
    ->getNumberFormat()
    ->setFormatCode('yyyy-mm-dd');

// Format numeric columns
$sheet->getStyle('D2:G100')
    ->getNumberFormat()
    ->setFormatCode('0');

// Add data validation for bahan name (example)
$validation = $sheet->getCell('C2')->getDataValidation();
$validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_CUSTOM);
$validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
$validation->setAllowBlank(false);
$validation->setShowInputMessage(true);
$validation->setShowErrorMessage(true);
$validation->setErrorTitle('Input error');
$validation->setError('Nama bahan harus valid');
$validation->setPromptTitle('Format Nama Bahan');
$validation->setPrompt('Masukkan nama bahan yang valid');

// Set column widths
$sheet->getColumnDimension('A')->setWidth(15);  // Kode Opname
$sheet->getColumnDimension('B')->setWidth(20);  // Tanggal Opname
$sheet->getColumnDimension('C')->setWidth(25);  // Nama Bahan
$sheet->getColumnDimension('D')->setWidth(12);  // Stock Awal
$sheet->getColumnDimension('E')->setWidth(12);  // Stock Akhir
$sheet->getColumnDimension('F')->setWidth(12);  // Penggunaan
$sheet->getColumnDimension('G')->setWidth(12);  // BS

// Freeze header row
$sheet->freezePane('A2');

// Output the file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="template_import_opname_bahan.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;