<?php
require_once(__DIR__ . '/config/config.php');

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | <?php echo defined('NAMA_WEBSITE') ? NAMA_WEBSITE : 'Sistem Inventori'; ?></title>
    <link rel="icon" href="<?php echo BASE_URL . (defined('PATH_FAVICON') ? PATH_FAVICON : ''); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-hover: #3a56d4;
            --input-bg: #f7f8fc;
            --text-color: #3b4455;
            --text-muted: #6c757d;
            --image-overlay: rgba(67, 97, 238, 0.15);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        .login-container {
            height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-heading {
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 1rem;
            font-size: 2rem;
            position: relative;
            display: inline-block;
        }

        .login-heading::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 50px;
            height: 4px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .login-subtitle {
            color: var(--text-muted);
            margin-bottom: 2rem;
            font-size: 1rem;
            font-weight: 400;
        }

        .form-control {
            border: 1px solid #e9ecef;
            background-color: var(--input-bg);
            padding: 0.75rem 1rem;
            height: auto;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .form-control:focus {
            background-color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
            border-color: var(--primary-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.2);
        }

        .bg-image {
            background-image: url('./assets/img/login.png');
            background-size: cover;
            background-position: center center;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: flex-end;
        }

        .bg-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, transparent 60%, var(--image-overlay));
        }

        .image-content {
            position: relative;
            z-index: 2;
            padding: 2rem;
            color: white;
            width: 100%;
        }

        .image-title {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .image-description {
            font-size: 0.9rem;
            opacity: 0.9;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .password-toggle-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-muted);
            height: 20px;
            width: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-wrapper {
            position: relative;
        }

        .password-input {
            padding-right: 40px !important;
        }

        .alert {
            border-radius: 8px;
            border-left: 4px solid #dc3545;
        }

        .login-card {
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: none;
            padding: 2.5rem;
            background: white;
            position: relative;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: var(--primary-color);
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--text-color);
            font-size: 0.95rem;
        }

        .brand-logo {
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-logo img {
            height: 40px;
        }

        .brand-logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        @media (max-width: 767.98px) {
            .bg-image {
                height: 200px;
                background-position: center 30%;
            }

            .login-container {
                height: auto;
                padding: 2rem 0;
            }

            .login-card {
                padding: 1.5rem;
            }

            .login-heading {
                font-size: 1.75rem;
            }

            .image-content {
                padding: 1rem;
            }

            .image-title {
                font-size: 1.2rem;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            animation: fadeIn 0.6s ease-out forwards;
        }
    </style>
</head>

<body>
    <div class="container-fluid ps-md-0">
        <div class="row g-0">
            <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image">
            </div>
            <div class="col-md-8 col-lg-6">
                <div class="login-container">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-9 col-lg-8">
                                <div class="login-card">
                                    <div class="brand-logo">
                                        <span class="brand-logo-text"><?php echo defined('NAMA_WEBSITE') ? NAMA_WEBSITE : 'Inventori'; ?></span>
                                    </div>

                                    <h3 class="login-heading">Selamat Datang!</h3>
                                    <p class="login-subtitle">Masukkan akun Anda untuk mengakses sistem</p>

                                    <?php if (isset($_SESSION['error_message'])): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <i class="fas fa-exclamation-circle me-2"></i>
                                            <?php
                                            echo $_SESSION['error_message'];
                                            unset($_SESSION['error_message']);
                                            ?>
                                        </div>
                                    <?php endif; ?>

                                    <form action="auth.php" method="POST">
                                        <input type="hidden" name="action" value="login">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                        <div class="mb-4">
                                            <label for="username" class="form-label">Username</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-transparent"><i class="fas fa-user"></i></span>
                                                <input type="text" class="form-control" id="username" name="username" required autofocus placeholder="Masukkan username">
                                            </div>
                                        </div>

                                        <div class="mb-4 password-wrapper">
                                            <label for="password" class="form-label">Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-transparent"><i class="fas fa-lock"></i></span>
                                                <input type="password" class="form-control password-input" id="password" name="password" required placeholder="Masukkan password">
                                                <i class="fas fa-eye password-toggle-icon" id="password-toggle"></i>
                                            </div>
                                        </div>

                                        <div class="d-grid mt-4">
                                            <button class="btn btn-primary btn-login text-uppercase fw-bold py-2" type="submit">
                                                Login
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const passwordToggle = document.getElementById('password-toggle');

            if (passwordToggle) {
                passwordToggle.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            }
        });
    </script>
</body>

</html>