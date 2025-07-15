<?php
session_start();
?>
<!doctype html>
<html lang="id">

<head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Login - Sistem Inventori Roti</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                        body {
                                    background-color: #f8f9fa;
                        }

                        .login-container {
                                    min-height: 100vh;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                        }

                        .login-card {
                                    width: 100%;
                                    max-width: 400px;
                        }
            </style>
</head>

<body>
            <div class="login-container">
                        <div class="card login-card shadow-sm">
                                    <div class="card-body">
                                                <h3 class="card-title text-center mb-4">Silmarils Login</h3>

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
                                                                        <input type="text" class="form-control" id="username" name="username" required autofocus>
                                                            </div>
                                                            <div class="mb-3">
                                                                        <label for="password" class="form-label">Password</label>
                                                                        <input type="password" class="form-control" id="password" name="password" required>
                                                            </div>
                                                            <div class="d-grid">
                                                                        <button type="submit" class="btn btn-primary">Login</button>
                                                            </div>
                                                </form>
                                    </div>
                        </div>
            </div>
</body>

</html>