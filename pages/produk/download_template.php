<?php
require_once(__DIR__ . '/../../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header
$sheet->setCellValue('A1', 'Kode Produk');
$sheet->setCellValue('B1', 'Nama Produk');
$sheet->setCellValue('C1', 'Jumlah (pcs)');
$sheet->setCellValue('D1', 'Harga per pcs');
$sheet->setCellValue('E1', 'Tanggal Produksi (YYYY-MM-DD)');
$sheet->setCellValue('F1', 'Tanggal Expired (YYYY-MM-DD)');
$sheet->setCellValue('G1', 'Keterangan (Layak/Expired)');

// Contoh data
$sheet->setCellValue('A2', '1001');
$sheet->setCellValue('B2', 'Produk Contoh 1');
$sheet->setCellValue('C2', '50');
$sheet->setCellValue('D2', '15000');
$sheet->setCellValue('E2', '2025-08-15');
$sheet->setCellValue('F2', '2025-10-15');
$sheet->setCellValue('G2', 'Layak');

// Data validation untuk kolom keterangan
$validation = $sheet->getCell('G2')->getDataValidation();
$validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
$validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
$validation->setAllowBlank(false);
$validation->setShowInputMessage(true);
$validation->setShowErrorMessage(true);
$validation->setShowDropDown(true);
$validation->setErrorTitle('Input error');
$validation->setError('Value is not in list');
$validation->setPromptTitle('Pilih dari list');
$validation->setPrompt('Pilih status produk');
$validation->setFormula1('"Layak,Expired"');

// Set data validation untuk kolom kode (angka saja)
$validation = $sheet->getCell('A2')->getDataValidation();
$validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_WHOLE);
$validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
$validation->setAllowBlank(false);
$validation->setShowInputMessage(true);
// $validation->setErrorMessage('Kode produk harus angka!');

// Formatting
$headerStyle = [
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4CAF50']],
    'alignment' => ['horizontal' => 'center']
];
$sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

// Format tanggal
$sheet->getStyle('E2:E200')
    ->getNumberFormat()
    ->setFormatCode('yyyy-mm-dd');

$sheet->getStyle('F2:F200')
    ->getNumberFormat()
    ->setFormatCode('yyyy-mm-dd');


// // Format harga
// $sheet->getStyle('D2')
//     ->getNumberFormat()
//     ->setFormatCode('#,##00');

// Auto width
foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Output
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="template_import_produk.xlsx"');
$writer->save('php://output');