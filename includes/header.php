<?php
require_once(__DIR__ . '/../config/config.php');
?>
<!doctype html>
<html lang="id">

<head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Sistem Inventori Roti</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
            <link href="<?php echo BASE_URL; ?>assets/css/style.css" rel="stylesheet">
</head>

<body>

            <div class="top-header">
                        <div>
                                    <h5 class="mb-0">Dashboard</h5>
                        </div>
                        <div class="d-flex align-items-center">
                                    <div class="me-3">
                                                <span class="fw-bold">Riyan Wardhana</span><br>
                                                <small class="text-muted">Admin</small>
                                    </div>
                        </div>
            </div>

            <div class="d-flex">
                        <?php include_once('sidebar.php');
                        ?>

                        <div class="main-content flex-grow-1">