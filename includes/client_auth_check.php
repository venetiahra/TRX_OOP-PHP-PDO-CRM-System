<?php
require_once __DIR__ . '/bootstrap.php';
if (!isset($_SESSION['client_id'])) {
    set_flash('warning', 'Please login to the client portal first.');
    redirect('client_login.php');
}
?>