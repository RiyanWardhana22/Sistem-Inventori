<?php
require_once(__DIR__ . '/config/config.php');
if (isset($_SESSION['user_id'])) {
            header('Location: index.php');
            exit();
}
?>
<!doctype html>
<html lang="id">

<head>
            <meta charset="utf-g">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Login - <?php echo defined('NAMA_WEBSITE') ? NAMA_WEBSITE : 'Sistem Inventori'; ?></title>
            <link rel="icon" href="<?php echo BASE_URL . (defined('PATH_FAVICON') ? PATH_FAVICON : ''); ?>">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
            <style>
                        :root {
                                    --primary-color: #4361ee;
                                    --input-bg: #f7f8fc;
                        }

                        body {
                                    font-family: 'Poppins', sans-serif;
                        }

                        .login-heading {
                                    font-weight: 600;
                        }

                        .form-control {
                                    border: 1px solid #e9ecef;
                                    background-color: var(--input-bg);
                                    padding: 0.75rem 1rem;
                                    height: auto;
                                    border-radius: 8px;
                        }

                        .form-control:focus {
                                    background-color: #fff;
                                    box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
                        }

                        .btn-primary {
                                    background-color: var(--primary-color);
                                    border: none;
                                    padding: 0.75rem 1rem;
                                    border-radius: 8px;
                                    font-weight: 600;
                        }

                        .bg-image {
                                    background-image: url('./assets/img/login.png');
                                    background-size: cover;
                                    background-position: center;
                        }
            </style>
</head>

<body>
            <div class="container-fluid ps-md-0">
                        <div class="row g-0">
                                    <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>

                                    <div class="col-md-8 col-lg-6">
                                                <div class="d-flex align-items-center py-5" style="min-height: 100vh;">
                                                            <div class="container">
                                                                        <div class="row">
                                                                                    <div class="col-md-9 col-lg-8 mx-auto">
                                                                                                <h3 class="login-heading">Selamat Datang!</h3>
                                                                                                <p class="mb-4">Selamat Datang Di Sistem Inventori <?php echo defined('NAMA_WEBSITE') ? NAMA_WEBSITE : 'Sistem Inventori'; ?></p>

                                                                                                <?php if (isset($_SESSION['error_message'])): ?>
                                                                                                            <div class="alert alert-danger" role="alert">
                                                                                                                        <?php
                                                                                                                        echo $_SESSION['error_message'];
                                                                                                                        unset($_SESSION['error_message']);
                                                                                                                        ?>
                                                                                                            </div>
                                                                                                <?php endif; ?>

                                                                                                <form action="auth.php" method="POST">
                                                                                                            <input type="hidden" name="action" value="login">
                                                                                                            <div class="mb-3">
                                                                                                                        <label for="username" class="form-label">Username</label>
                                                                                                                        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required autofocus>
                                                                                                            </div>
                                                                                                            <div class="mb-3">
                                                                                                                        <label for="password" class="form-label">Password</label>
                                                                                                                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                                                                                                            </div>
                                                                                                            <div class="d-grid mt-4">
                                                                                                                        <button class="btn btn-primary btn-login text-uppercase fw-bold mb-2" type="submit">Login</button>
                                                                                                            </div>
                                                                                                </form>
                                                                                    </div>
                                                                        </div>
                                                            </div>
                                                </div>
                                    </div>
                        </div>
            </div>
</body>

</html>