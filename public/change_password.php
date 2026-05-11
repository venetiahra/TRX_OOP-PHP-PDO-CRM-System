<?php
require_once '../includes/auth_check.php';
require_once '../config/Database.php';
require_once '../classes/Auth.php';

$auth = new Auth((new Database())->connect());
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid CSRF token.';
    } else {
        $result = $auth->changePassword(
            (int) $_SESSION['user_id'],
            $_POST['current_password'] ?? '',
            $_POST['new_password'] ?? '',
            $_POST['confirm_password'] ?? ''
        );

        if ($result === true) {
            set_flash('success', 'Admin password updated.');
            redirect('dashboard.php');
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
            <h2>Change Admin Password</h2>
            <p>Update the current administrator password securely.</p>
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
            <label class="form-label">Current Password</label>
            <input type="password" name="current_password" class="form-control trx-control" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">New Password</label>
            <input type="password" name="new_password" class="form-control trx-control" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control trx-control" required>
        </div>

        <div class="col-12 d-flex gap-2">
            <button class="btn trx-btn-primary" type="submit">Change Password</button>
            <a href="dashboard.php" class="btn btn-outline-light rounded-pill px-4">Back</a>
        </div>
    </form>
</section>

<?php include '../includes/footer.php'; ?>