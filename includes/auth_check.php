<?php
if (!isset($_SESSION['user_id'])) {
            redirect(BASE_URL . 'login.php');
}
