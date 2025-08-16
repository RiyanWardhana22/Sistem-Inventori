<?php
require_once(__DIR__ . '/../../../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF Protection for all POST actions
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error_message'] = "Token keamanan tidak valid!";
        header("Location: " . BASE_URL . "pages/stok_bs/bahan/");
        exit();
    }

    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'tambah':
            // Validate input data for add
            $required_fields = ['tanggal_bs', 'nama_bahan', 'jumlah_bahan', 'satuan_jumlah'];
            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    $_SESSION['error_message'] = "Kolom " . str_replace('_', ' ', $field) . " wajib diisi!";
                    header("Location: " . BASE_URL . "pages/stok_bs/bahan/tambah.php");
                    exit();
                }
            }

            // Validate data format
            if (!is_numeric($_POST['jumlah_bahan']) || $_POST['jumlah_bahan'] <= 0) {
                $_SESSION['error_message'] = "Jumlah bahan harus berupa angka positif!";
                header("Location: " . BASE_URL . "pages/stok_bs/bahan/tambah.php");
                exit();
            }

            try {
                // Begin database transaction
                $pdo->beginTransaction();

                // 1. Check material exists (without locking stock)
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM bahan WHERE nama_bahan = ?");
                $stmt->execute([$_POST['nama_bahan']]);
                if ($stmt->fetchColumn() == 0) {
                    throw new Exception("Bahan tidak ditemukan dalam database!");
                }

                // 2. Add BS data (without affecting stock)
                $stmt = $pdo->prepare("INSERT INTO bs_bahan 
                                     (tanggal_bs, nama_bahan, jumlah_bahan, satuan_jumlah, keterangan) 
                                     VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_POST['tanggal_bs'],
                    $_POST['nama_bahan'],
                    $_POST['jumlah_bahan'],
                    $_POST['satuan_jumlah'],
                    $_POST['keterangan'] ?? null
                ]);

                // Commit transaction
                $pdo->commit();

                $_SESSION['success_message'] = "Data BS bahan berhasil ditambahkan!";
                
            } catch (Exception $e) {
                $pdo->rollBack();
                $_SESSION['error_message'] = "Gagal menambahkan BS: " . $e->getMessage();
                header("Location: " . BASE_URL . "pages/stok_bs/bahan/tambah.php");
                exit();
            }
            break;

        case 'edit':
            // Validate input data for edit
            $required_fields = ['id_bs', 'tanggal_bs', 'nama_bahan', 'jumlah_bahan', 'satuan_jumlah'];
            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    $_SESSION['error_message'] = "Kolom " . str_replace('_', ' ', $field) . " wajib diisi!";
                    header("Location: " . BASE_URL . "pages/stok_bs/bahan/edit.php?id=" . $_POST['id_bs']);
                    exit();
                }
            }

            // Validate data format
            if (!is_numeric($_POST['jumlah_bahan']) || $_POST['jumlah_bahan'] <= 0) {
                $_SESSION['error_message'] = "Jumlah bahan harus berupa angka positif!";
                header("Location: " . BASE_URL . "pages/stok_bs/bahan/edit.php?id=" . $_POST['id_bs']);
                exit();
            }

            try {
                // Begin database transaction
                $pdo->beginTransaction();

                // 1. Update BS data (without affecting stock)
                $stmt = $pdo->prepare("UPDATE bs_bahan SET 
                                     tanggal_bs = ?,
                                     nama_bahan = ?,
                                     jumlah_bahan = ?,
                                     satuan_jumlah = ?,
                                     keterangan = ?
                                     WHERE id_bs = ?");
                $stmt->execute([
                    $_POST['tanggal_bs'],
                    $_POST['nama_bahan'],
                    $_POST['jumlah_bahan'],
                    $_POST['satuan_jumlah'],
                    $_POST['keterangan'] ?? null,
                    $_POST['id_bs']
                ]);

                // 2. Validate new material exists if changed
                if (isset($_POST['old_nama_bahan']) && $_POST['old_nama_bahan'] != $_POST['nama_bahan']) {
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM bahan WHERE nama_bahan = ?");
                    $stmt->execute([$_POST['nama_bahan']]);
                    if ($stmt->fetchColumn() == 0) {
                        throw new Exception("Bahan baru tidak ditemukan!");
                    }
                }

                // Commit transaction
                $pdo->commit();

                $_SESSION['success_message'] = "Data BS bahan berhasil diperbarui!";
                
            } catch (Exception $e) {
                $pdo->rollBack();
                $_SESSION['error_message'] = "Gagal memperbarui BS: " . $e->getMessage();
                header("Location: " . BASE_URL . "pages/stok_bs/bahan/edit.php?id=" . $_POST['id_bs']);
                exit();
            }
            break;

        default:
            $_SESSION['error_message'] = "Aksi tidak valid!";
            break;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'hapus') {
    // Validate ID
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        $_SESSION['error_message'] = "ID BS tidak valid!";
        header("Location: " . BASE_URL . "pages/stok_bs/bahan/");
        exit();
    }

    $id_bs = (int)$_GET['id'];

    try {
        // Begin transaction
        $pdo->beginTransaction();

        // 1. Get BS data to be deleted
        $stmt = $pdo->prepare("SELECT * FROM bs_bahan WHERE id_bs = ?");
        $stmt->execute([$id_bs]);
        $bs_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$bs_data) {
            throw new Exception("Data BS tidak ditemukan!");
        }

        // 2. Delete BS data (without restoring stock)
        $stmt = $pdo->prepare("DELETE FROM bs_bahan WHERE id_bs = ?");
        $stmt->execute([$id_bs]);

        // Commit transaction
        $pdo->commit();

        $_SESSION['success_message'] = "Data BS berhasil dihapus!";
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error_message'] = "Gagal menghapus BS: " . $e->getMessage();
    }
}

// Redirect to index page
header("Location: " . BASE_URL . "pages/stok_bs/bahan/");
exit();