<?php
require_once '../includes/auth_check.php';
require_once '../config/Database.php';
require_once '../classes/Client.php';
require_once '../classes/ClientPortal.php';

$db = (new Database())->connect();
$client = new Client($db);
$portal = new ClientPortal($db);

$id = (int)($_GET['id'] ?? 0);

$clientData = $client->getById($id);

if (!$clientData) {
    set_flash('danger', 'Client not found.');
    redirect('clients.php');
}

$existing = $portal->getByClientId($id);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid CSRF token.';
    } else {
        $result = $portal->createOrUpdateAccount(
            $id,
            $_POST['username'] ?? '',
            $_POST['password'] ?? ''
        );

        if ($result === true) {
            set_flash('success', 'Client portal access saved successfully.');
            redirect('clients.php');
        } elseif (is_array($result)) {
            $errors = $result;
        }
    }

    $existing = $portal->getByClientId($id);
}

include '../includes/header.php';
?>

<section class="panel form-panel">

    <div class="panel-header compact">
        <div>
            <h2>Client Portal Access</h2>
            <p>
                Create or update portal credentials for
                <?= e($clientData['full_name']) ?>.
            </p>
        </div>
    </div>

    <?php if ($errors): ?>
        <div class="alert alert-danger border-0 trx-alert">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="alert alert-info border-0 trx-alert">
        After saving this account, the client can login at
        <strong>client_login.php</strong> to view personal info,
        edit details, and review company data.
    </div>

    <form method="POST" class="row g-4">

        <input type="hidden" name="csrf_token" value="<?= e(generate_csrf_token()) ?>">

        <div class="col-md-6">
            <label class="form-label">Client Name</label>
            <input type="text" class="form-control trx-control"
                   value="<?= e($clientData['full_name']) ?>" disabled>
        </div>

        <div class="col-md-6">
            <label class="form-label">Company</label>
            <input type="text" class="form-control trx-control"
                   value="<?= e($clientData['company_name']) ?>" disabled>
        </div>

        <div class="col-md-6">
            <label class="form-label">Portal Username</label>
            <input type="text"
                   name="username"
                   class="form-control trx-control"
                   value="<?= e($_POST['username'] ?? ($existing['username'] ?? '')) ?>"
                   required>
        </div>

        <div class="col-md-6">
            <label class="form-label">
                Portal Password
                <?= $existing ? '<small class="text-secondary">(leave blank to keep current password)</small>' : '' ?>
            </label>
            <input type="password"
                   name="password"
                   class="form-control trx-control"
                   <?= $existing ? '' : 'required' ?>>
        </div>

        <div class="col-12 d-flex gap-2 flex-wrap">
            <button class="btn trx-btn-primary" type="submit">
                Save Portal Access
            </button>

            <a href="clients.php" class="btn btn-outline-light rounded-pill px-4">
                Back
            </a>

            <a href="client_login.php" class="btn btn-outline-info rounded-pill px-4">
                Open Client Portal
            </a>
        </div>

    </form>

</section>

<?php include '../includes/footer.php'; ?>