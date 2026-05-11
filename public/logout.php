<?php
require_once '../includes/bootstrap.php';
require_once '../config/Database.php';
require_once '../classes/Auth.php';

$auth = new Auth((new Database())->connect());

$auth->logout();

session_start();

set_flash('info', 'Logged out successfully.');

redirect('login.php');
?>