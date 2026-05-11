<?php
require_once '../includes/auth_check.php';
require_once '../config/Database.php';
require_once '../classes/Client.php';

if (!verify_csrf_token($_GET['csrf_token'] ?? null)) {
    set_flash('danger', 'Invalid CSRF token.');
    redirect('clients.php');
}

$client = new Client((new Database())->connect());

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0 && $client->delete($id)) {
    set_flash('success', 'Client deleted successfully.');
} else {
    set_flash('danger', 'Unable to delete client.');
}

redirect('clients.php');
?>