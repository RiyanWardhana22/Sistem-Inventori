<?php
require_once(__DIR__ . '/../../config/config.php');
if ($_SESSION['level'] != 'admin') die("Akses ditolak.");

if (isset($_POST['nama_website'])) {
            $nama_website = $_POST['nama_website'];
            $stmt = $pdo->prepare("UPDATE pengaturan SET nilai_pengaturan = ? WHERE nama_pengaturan = 'NAMA_WEBSITE'");
            $stmt->execute([$nama_website]);
}

if (isset($_FILES['favicon']) && $_FILES['favicon']['error'] == 0) {
            $upload_dir = __DIR__ . '/../../assets/img/';
            $allowed_types = ['image/png', 'image/jpeg', 'image/x-icon', 'image/vnd.microsoft.icon'];

            if (in_array($_FILES['favicon']['type'], $allowed_types)) {
                        if (defined('PATH_FAVICON') && !empty(PATH_FAVICON)) {
                                    $old_favicon_path = __DIR__ . '/../../' . PATH_FAVICON;
                                    if (file_exists($old_favicon_path) && is_file($old_favicon_path)) {
                                                unlink($old_favicon_path);
                                    }
                        }

                        $ext = pathinfo($_FILES['favicon']['name'], PATHINFO_EXTENSION);
                        $new_filename = 'favicon_' . time() . '.' . $ext;
                        $new_filepath = $upload_dir . $new_filename;

                        if (move_uploaded_file($_FILES['favicon']['tmp_name'], $new_filepath)) {
                                    $db_path = 'assets/img/' . $new_filename;
                                    $stmt = $pdo->prepare("UPDATE pengaturan SET nilai_pengaturan = ? WHERE nama_pengaturan = 'PATH_FAVICON'");
                                    $stmt->execute([$db_path]);
                        }
            }
}

redirect(BASE_URL . 'pages/pengaturan_web/');
