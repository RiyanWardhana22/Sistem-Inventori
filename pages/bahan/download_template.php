<?php
require_once(__DIR__ . '/../../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set document properties
$spreadsheet->getProperties()
    ->setCreator('Sistem Inventori')
    ->setTitle('Template Import Bahan')
    ->setDescription('Template untuk mengimport data bahan');

// Set header with style
$headerStyle = [
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4CAF50']],
    'alignment' => ['horizontal' => 'center'],
    'borders' => ['allBorders' => ['borderStyle' => 'thin']]
];

$sheet->setCellValue('A1', 'Nama Bahan');
$sheet->setCellValue('B1', 'Jumlah');
$sheet->setCellValue('C1', 'Satuan');
$sheet->setCellValue('D1', 'Tanggal Expired (YYYY-MM-DD)');
$sheet->setCellValue('E1', 'Status (Layak/Rusak/Expired)');

// Apply header style
$sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

// Example data row
$sheet->setCellValue('A2', 'Tepung Terigu');
$sheet->setCellValue('B2', '50');
$sheet->setCellValue('C2', 'kg');
$sheet->setCellValue('D2', '2025-12-31');
$sheet->setCellValue('E2', 'Layak');

// Data validation for status column
$validation = $sheet->getCell('E2')->getDataValidation();
$validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
$validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
$validation->setAllowBlank(false);
$validation->setShowInputMessage(true);
$validation->setShowErrorMessage(true);
$validation->setShowDropDown(true);
$validation->setErrorTitle('Input error');
$validation->setError('Pilih salah satu: Layak, Rusak, atau Expired');
$validation->setPromptTitle('Pilih Status');
$validation->setPrompt('Pilih status bahan dari dropdown');
$validation->setFormula1('"Layak,Rusak,Expired"');

// Data validation for satuan column
$satuanValidation = $sheet->getCell('C2')->getDataValidation();
$satuanValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
$satuanValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
$satuanValidation->setAllowBlank(false);
$satuanValidation->setShowInputMessage(true);
$satuanValidation->setShowDropDown(true);
$satuanValidation->setFormula1('"kg,gram,liter,ml,pcs,pack,dus,botol,buah"');

// Data validation for jumlah column (must be positive number)
$jumlahValidation = $sheet->getCell('B2')->getDataValidation();
$jumlahValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_WHOLE);
$jumlahValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
$jumlahValidation->setAllowBlank(false);
$jumlahValidation->setShowInputMessage(true);
$jumlahValidation->setOperator(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::OPERATOR_GREATERTHAN);
$jumlahValidation->setFormula1(0);

// Format tanggal
$sheet->getStyle('D2:D200')
    ->getNumberFormat()
    ->setFormatCode('yyyy-mm-dd');

// Set column widths
$sheet->getColumnDimension('A')->setWidth(30); // Nama Bahan
$sheet->getColumnDimension('B')->setWidth(15); // Jumlah
$sheet->getColumnDimension('C')->setWidth(15); // Satuan
$sheet->getColumnDimension('D')->setWidth(25); // Tanggal Expired
$sheet->getColumnDimension('E')->setWidth(20); // Status

// Freeze header row
$sheet->freezePane('A2');

// Style for instructions
$instructionStyle = [
    'font' => ['bold' => true, 'color' => ['rgb' => 'FF0000']],
    'alignment' => ['horizontal' => 'left']
];
$sheet->getStyle('G1')->applyFromArray($instructionStyle);

// Output the file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="template_import_bahan.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;