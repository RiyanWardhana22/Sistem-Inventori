<?php
require_once(__DIR__ . '/../../config/config.php');

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
            die("Akses ditolak.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = $_POST['action'];

            if ($action == 'tambah') {
                        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare("INSERT INTO pengguna (nama_lengkap, username, password, level) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$_POST['nama_lengkap'], $_POST['username'], $password, $_POST['level']]);
            } elseif ($action == 'edit') {
                        $id_pengguna = $_POST['id_pengguna'];

                        if (!empty($_POST['password'])) {
                                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                                    $stmt = $pdo->prepare("UPDATE pengguna SET nama_lengkap=?, username=?, password=?, level=? WHERE id_pengguna=?");
                                    $stmt->execute([$_POST['nama_lengkap'], $_POST['username'], $password, $_POST['level'], $id_pengguna]);
                        } else {
                                    $stmt = $pdo->prepare("UPDATE pengguna SET nama_lengkap=?, username=?, level=? WHERE id_pengguna=?");
                                    $stmt->execute([$_POST['nama_lengkap'], $_POST['username'], $_POST['level'], $id_pengguna]);
                        }
            }
}

if (isset($_GET['action']) && $_GET['action'] == 'hapus') {
            $id_pengguna = $_GET['id'];

            if ($id_pengguna == $_SESSION['user_id']) {
                        die("Error: Anda tidak dapat menghapus akun Anda sendiri.");
            }

            $stmt = $pdo->prepare("DELETE FROM pengguna WHERE id_pengguna = ?");
            $stmt->execute([$id_pengguna]);
}

redirect(BASE_URL . 'pages/data_staf/');
