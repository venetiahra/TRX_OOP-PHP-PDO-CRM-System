<?php
require_once __DIR__ . '/bootstrap.php';
$flash = get_flash();
$currentPage = basename($_SERVER['PHP_SELF']);
$nav = [
    'dashboard.php' => ['Dashboard','bi-grid-1x2-fill'],
    'clients.php' => ['Client Hub','bi-people-fill'],
    'add_client.php' => ['Add Client','bi-person-plus-fill'],
    'change_password.php' => ['Change Password','bi-key-fill'],
    'client_login.php' => ['Client Portal','bi-person-badge-fill'],
    'logout.php' => ['Logout','bi-box-arrow-right'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRX Clawd Fortress</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/images/apple-touch-icon.png">
    <link rel="manifest" href="../assets/images/site.webmanifest">
    <meta name="theme-color" content="#070504">
    <link href="../assets/css/branding.css" rel="stylesheet">
</head>
<body class="trx-body">
<div class="trx-shell">
    <aside class="trx-sidebar">
        <div>
            <div class="brand-mark">
                <div class="brand-icon">
                    <img src="../assets/images/logo-mark.png" alt="TRX logo" class="brand-logo-img">
                </div>
                <div>
                    <div class="brand-eyebrow">Enterprise CRM</div>
                    <div class="brand-name">TRX Clawd Fortress</div>
                </div>
            </div>

            <nav class="trx-nav">
                <?php foreach($nav as $file => [$label,$icon]): ?>
                    <a href="<?= e($file) ?>" class="trx-nav-link <?= $currentPage === $file ? 'active' : '' ?>">
                        <i class="bi <?= e($icon) ?>"></i>
                        <span><?= e($label) ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>

        <div class="sidebar-footer">
            <div class="mini-stat">
                <span class="mini-dot"></span>
                <span>Secured Session</span>
            </div>
            <small class="text-secondary">
                Logged in as <?= e($_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Admin') ?>
            </small>
        </div>
    </aside>

    <main class="trx-main">
        <header class="trx-topbar">
            <div>
                <div class="topbar-title">
                    <?= e(str_replace(['.php','_'],['',' '], ucwords($currentPage,'_'))) ?>
                </div>
                <div class="topbar-subtitle">.</div>
            </div>

            <div class="topbar-actions">
                <div class="profile-pill">
                    <img src="../assets/images/logo-mark.png" alt="TRX logo" class="profile-logo-chip">
                    <div>
                        <div class="profile-name"><?= e($_SESSION['username'] ?? 'Admin') ?></div>
                        <small class="text-secondary">Authorized Operator</small>
                    </div>
                </div>
            </div>
        </header>

        <?php if($flash): ?>
            <div class="alert alert-<?= e($flash['type']) ?> border-0 trx-alert">
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>