<?php
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/auth_check.php');
?>
<!doctype html>
<html lang="id">

<head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title><?php echo defined('NAMA_WEBSITE') ? NAMA_WEBSITE : 'Sistem Inventori'; ?></title>
            <link rel="icon" href="<?php echo BASE_URL . (defined('PATH_FAVICON') ? PATH_FAVICON : ''); ?>">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
            <link href="<?php echo BASE_URL; ?>assets/css/style.css" rel="stylesheet">
</head>

<body>
            <div class="sidebar-overlay"></div>
            <div class="top-header">
                        <button class="sidebar-toggle-btn btn btn-light d-lg-none">
                                    <i class="fas fa-bars"></i>
                        </button>

                        <div></div>

                        <div class="dropdown profile">
                                    <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                                                <div class="text-end me-2">
                                                            <span class="fw-bold"><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></span><br>
                                                            <small class="text-muted text-capitalize"><?php echo htmlspecialchars($_SESSION['level']); ?></small>
                                                </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end text-small" aria-labelledby="dropdownUser">
                                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>auth.php?action=logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                                    </ul>
                        </div>
            </div>

            <div class="d-flex">
                        <?php include_once('sidebar.php'); ?>
                        <div class="main-content flex-grow-1">
    
    <!-- Sebelum penutup body -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>