<?php
session_start();
date_default_timezone_set('Asia/Jakarta');

require_once(__DIR__ . '/../vendor/autoload.php');
define('BASE_URL', 'http://localhost/sistem-inventori/');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sistem-inventori');

try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $query_pengaturan = $pdo->query("SELECT nama_pengaturan, nilai_pengaturan FROM pengaturan");
            while ($pengaturan = $query_pengaturan->fetch(PDO::FETCH_ASSOC)) {
                        if (!defined($pengaturan['nama_pengaturan'])) {
                                    define($pengaturan['nama_pengaturan'], $pengaturan['nilai_pengaturan']);
                        }
            }
} catch (PDOException $e) {
            if (!defined('NAMA_WEBSITE')) define('NAMA_WEBSITE', 'Sistem Inventori');
            if (!defined('PATH_FAVICON')) define('PATH_FAVICON', '');
}

function redirect($url)
{
            header("Location: " . $url);
            exit();
}

function format_rupiah($angka)
{
            return "Rp " . number_format($angka, 0, ',', '.');
}

function format_hari_tanggal($tanggal_mysql)
{
            $nama_hari_inggris = date('l', strtotime($tanggal_mysql));
            $daftar_hari = [
                        'Sunday'    => 'Minggu',
                        'Monday'    => 'Senin',
                        'Tuesday'   => 'Selasa',
                        'Wednesday' => 'Rabu',
                        'Thursday'  => 'Kamis',
                        'Friday'    => 'Jumat',
                        'Saturday'  => 'Sabtu'
            ];

            $nama_hari_indonesia = $daftar_hari[$nama_hari_inggris];
            $tanggal_format = date('d/m/Y', strtotime($tanggal_mysql));
            return $nama_hari_indonesia . ', ' . $tanggal_format;
}
