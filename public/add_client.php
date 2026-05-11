<?php
require_once '../includes/auth_check.php';
require_once '../config/Database.php';
require_once '../classes/Client.php';

$client = new Client((new Database())->connect());
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid CSRF token.';
    } else {
        $result = $client->create($_POST);

        if ($result === true) {
            set_flash('success', 'Client created successfully.');
            redirect('clients.php');
        } elseif (is_array($result)) {
            $errors = $result;
        }
    }
}

include '../includes/header.php';
?>

<section class="panel form-panel">
    <div class="panel-header compact">
        <div>
            <h2>Add New Client</h2>
            <p>Create a new client record. Then assign portal access from the Client Hub.</p>
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

    <form method="POST" class="row g-4">
        <input type="hidden" name="csrf_token" value="<?= e(generate_csrf_token()) ?>">

        <div class="col-md-6">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control trx-control"
                value="<?= e($_POST['full_name'] ?? '') ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control trx-control"
                value="<?= e($_POST['email'] ?? '') ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Contact Number</label>
            <input type="text" name="contact_number" class="form-control trx-control"
                value="<?= e($_POST['contact_number'] ?? '') ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Company Name</label>
            <input type="text" name="company_name" class="form-control trx-control"
                value="<?= e($_POST['company_name'] ?? '') ?>" required>
        </div>

        <div class="col-12">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control trx-control" rows="4" required><?= e($_POST['address'] ?? '') ?></textarea>
        </div>

        <div class="col-md-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-select trx-control">
                <option value="Active" <?= (($_POST['status'] ?? 'Active') === 'Active') ? 'selected' : '' ?>>
                    Active
                </option>
                <option value="Inactive" <?= (($_POST['status'] ?? '') === 'Inactive') ? 'selected' : '' ?>>
                    Inactive
                </option>
            </select>
        </div>

        <div class="col-12 d-flex gap-2">
            <button class="btn trx-btn-primary" type="submit">Save Client</button>
            <a href="clients.php" class="btn btn-outline-light rounded-pill px-4">Back</a>
        </div>
    </form>
</section>

<?php include '../includes/footer.php'; ?>