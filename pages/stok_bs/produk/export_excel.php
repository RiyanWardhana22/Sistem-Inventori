<?php
require_once(__DIR__ . '/../../../config/config.php');
require_once(__DIR__ . '/../../../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$search_date = $_GET['search_date'] ?? '';

// Query untuk mengambil data BS
$sql = "SELECT 
            bs.id_bs,
            bs.tanggal_bs,
            bs.kode_produk,
            bs.jumlah,
            bs.keterangan,
            p.nama_produk
        FROM stok_bs bs
        LEFT JOIN produk p ON bs.kode_produk = p.kode_produk";
if (!empty($search_date)) {
    $sql .= " WHERE bs.tanggal_bs = ?";
}
$sql .= " ORDER BY bs.tanggal_bs DESC, bs.kode_produk ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute(!empty($search_date) ? [$search_date] : []);
$bs_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set document properties
$spreadsheet->getProperties()
    ->setCreator('Sistem Inventori')
    ->setTitle('Data Barang Rusak (BS)')
    ->setSubject('Export Data Barang Rusak');

// Set header
$headers = [
    'No',
    'Tanggal BS',
    'Kode Produk',
    'Nama Produk',
    'Jumlah',
    'Keterangan'
];

$sheet->fromArray($headers, null, 'A1');

// Add data
$rowNumber = 2;
foreach ($bs_data as $index => $data) {
    $rowData = [
        $index + 1,
        $data['tanggal_bs'],
        $data['kode_produk'],
        $data['nama_produk'] ?? '-',
        is_numeric($data['jumlah']) ? $data['jumlah'] : 0,
        $data['keterangan'] ?? '-'
    ];
    
    $sheet->fromArray($rowData, null, 'A'.$rowNumber);
    $rowNumber++;
}

// Format columns
$sheet->getStyle('B2:B'.$rowNumber)  // Format tanggal
    ->getNumberFormat()
    ->setFormatCode('yyyy-mm-dd');

$sheet->getStyle('E2:E'.$rowNumber)  // Format angka (jumlah)
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

// Clear any output before sending file
if (ob_get_length()) {
    ob_end_clean();
}

// Output the file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="data_bs_'.date('Y-m-d,H.i.s').'.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: cache, must-revalidate');
header('Pragma: public');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;