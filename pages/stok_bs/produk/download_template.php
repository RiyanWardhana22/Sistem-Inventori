<?php
require_once(__DIR__ . '/../../../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header with requested columns for BS (Barang Rusak)
$headers = [
    'Tanggal BS (YYYY-MM-DD)',
    'Kode Produk',
    'Nama Produk',
    'Jumlah',
    'Keterangan'
];

$sheet->fromArray($headers, null, 'A1');

// Example data row
$exampleData = [
    date('Y-m-d'),                   // Tanggal BS
    'PRD001',                        // Kode Produk
    'Contoh Produk 1',               // Nama Produk
    5,                               // Jumlah
    'Barang rusak karena transportasi' // Keterangan
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
$sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

// Format date column
$sheet->getStyle('A2:A200')
    ->getNumberFormat()
    ->setFormatCode('yyyy-mm-dd');

// Format numeric columns (jumlah)
$sheet->getStyle('D2:D100')
    ->getNumberFormat()
    ->setFormatCode('0');

// Add data validation for product code (example)
$validation = $sheet->getCell('B2')->getDataValidation();
$validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_CUSTOM);
$validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
$validation->setAllowBlank(false);
$validation->setShowInputMessage(true);
$validation->setShowErrorMessage(true);
$validation->setErrorTitle('Input error');
$validation->setError('Kode produk harus valid');
$validation->setPromptTitle('Format Kode Produk');
$validation->setPrompt('Masukkan kode produk yang valid (contoh: PRD001)');

// Add data validation for jumlah (must be positive number)
$validationQty = $sheet->getCell('D2')->getDataValidation();
$validationQty->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_WHOLE);
$validationQty->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
$validationQty->setAllowBlank(false);
$validationQty->setShowInputMessage(true);
$validationQty->setShowErrorMessage(true);
$validationQty->setErrorTitle('Input error');
$validationQty->setError('Jumlah harus angka positif');
$validationQty->setPromptTitle('Format Jumlah');
$validationQty->setPrompt('Masukkan jumlah barang rusak (angka positif)');
$validationQty->setOperator(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::OPERATOR_GREATERTHAN);
$validationQty->setFormula1(0);

// Set column widths
$sheet->getColumnDimension('A')->setWidth(20);  // Tanggal BS
$sheet->getColumnDimension('B')->setWidth(15);  // Kode Produk
$sheet->getColumnDimension('C')->setWidth(25);  // Nama Produk
$sheet->getColumnDimension('D')->setWidth(12);  // Jumlah
$sheet->getColumnDimension('E')->setWidth(30);  // Keterangan

// Freeze header row
$sheet->freezePane('A2');

// Add instructions
// $sheet->setCellValue('G1', 'PETUNJUK PENGISIAN:');
// $sheet->setCellValue('G2', '1. Tanggal BS: Format YYYY-MM-DD');
// $sheet->setCellValue('G3', '2. Kode Produk: Sesuai dengan kode di sistem');
// $sheet->setCellValue('G4', '3. Jumlah: Angka positif (tanpa koma/titik)');
// $sheet->setCellValue('G5', '4. Keterangan: Alasan barang rusak (opsional)');

// Style for instructions
$sheet->getStyle('G1:G5')->getFont()->setBold(true);
$sheet->getStyle('G1')->getFont()->setSize(12);

// Output the file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="template_import_barang_rusak.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;