<?php
require_once('config/config.php');

if (isset($_POST['action']) && $_POST['action'] == 'login') {
            if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                        $_SESSION['error_message'] = "Permintaan tidak valid.";
                        redirect('login.php');
                        exit();
            }

            $username = $_POST['username'];
            $password = $_POST['password'];

            $stmt = $pdo->prepare("SELECT * FROM pengguna WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                        session_regenerate_id(true);
                        unset($_SESSION['csrf_token']);

                        $_SESSION['user_id'] = $user['id_pengguna'];
                        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                        $_SESSION['level'] = $user['level'];

                        redirect('index.php');
            } else {
                        $_SESSION['error_message'] = "Username atau password salah.";
                        redirect('login.php');
            }
}

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
            session_unset();
            session_destroy();
            redirect('login.php');
}
