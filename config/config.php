<?php
session_start();
define('BASE_URL', 'http://localhost/sistem-inventori/');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sistem-inventori');

try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
            die("ERROR: Tidak dapat terhubung ke database. " . $e->getMessage());
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
