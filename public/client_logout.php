<?php
require_once '../includes/bootstrap.php';
require_once '../config/Database.php';
require_once '../classes/ClientPortal.php';

$portal = new ClientPortal((new Database())->connect());

$portal->logout();

set_flash('info', 'You have been logged out from the client portal.');

redirect('client_login.php');
?>