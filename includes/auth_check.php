<?php
require_once __DIR__ . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    set_flash('warning', 'Please login first.');
    redirect('login.php');
}
?>