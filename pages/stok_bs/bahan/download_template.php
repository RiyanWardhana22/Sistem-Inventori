<?php
require_once(__DIR__ . '/../../../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header dengan kolom untuk BS Bahan
$headers = [
    'Tanggal BS (YYYY-MM-DD)',
    'Nama Bahan',
    'Jumlah',
    'Satuan',
    'Keterangan'
];

$sheet->fromArray($headers, null, 'A1');

// Contoh data
$exampleData = [
    date('Y-m-d'),                   // Tanggal BS
    'Contoh Bahan 1',                // Nama Bahan
    5,                               // Jumlah
    'kg',                            // Satuan
    'Bahan rusak karena penyimpanan' // Keterangan
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
$sheet->getStyle('C2:C200')
    ->getNumberFormat()
    ->setFormatCode('0');

// Add data validation for product name (example)
$validation = $sheet->getCell('B2')->getDataValidation();
$validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_CUSTOM);
$validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
$validation->setAllowBlank(false);
$validation->setShowInputMessage(true);
$validation->setShowErrorMessage(true);
$validation->setErrorTitle('Input error');
$validation->setError('Nama bahan harus valid');
$validation->setPromptTitle('Format Nama Bahan');
$validation->setPrompt('Masukkan nama bahan yang valid');

// Add data validation for jumlah (must be positive number)
$validationQty = $sheet->getCell('C2')->getDataValidation();
$validationQty->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_WHOLE);
$validationQty->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
$validationQty->setAllowBlank(false);
$validationQty->setShowInputMessage(true);
$validationQty->setShowErrorMessage(true);
$validationQty->setErrorTitle('Input error');
$validationQty->setError('Jumlah harus angka positif');
$validationQty->setPromptTitle('Format Jumlah');
$validationQty->setPrompt('Masukkan jumlah bahan rusak (angka positif)');
$validationQty->setOperator(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::OPERATOR_GREATERTHAN);
$validationQty->setFormula1(0);

// Set column widths
$sheet->getColumnDimension('A')->setWidth(20);  // Tanggal BS
$sheet->getColumnDimension('B')->setWidth(25);  // Nama Bahan
$sheet->getColumnDimension('C')->setWidth(12);  // Jumlah
$sheet->getColumnDimension('D')->setWidth(12);  // Satuan
$sheet->getColumnDimension('E')->setWidth(30);  // Keterangan

// Freeze header row
$sheet->freezePane('A2');

// Output the file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="template_import_bs_bahan.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;