<?php
require_once '../includes/client_auth_check.php';
require_once '../config/Database.php';
require_once '../classes/ClientPortal.php';

$portal=new ClientPortal((new Database())->connect());
$errors=[];

if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verify_csrf_token($_POST['csrf_token']??null))
        $errors[]='Invalid CSRF token.';
    else {
        $result=$portal->changePassword(
            (int)$_SESSION['client_id'], 
            $_POST['current_password']??'', 
            $_POST['new_password']??'', 
            $_POST['confirm_password']??''
        );
        
        if($result===true){
            set_flash('success','Your portal password has been changed.');
            redirect('client_dashboard.php');
        }
        elseif(is_array($result))
            $errors=$result;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Client Password | TRX Clawd Fortress</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"><link href="../assets/css/style.css" rel="stylesheet">
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
                        <i class="bi bi-buildings"></i>
                    </div>
                    <div>
                        <div class="brand-eyebrow">Client Portal</div>
                        <div class="brand-name">TRX Clawd Fortress</div>
                    </div>
                </div>
                <nav class="trx-nav">
                    <a href="client_dashboard.php" class="trx-nav-link">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Overview</span>
                    </a>
                    <a href="client_profile.php" class="trx-nav-link">
                        <i class="bi bi-person-lines-fill"></i>
                        <span>Edit My Profile</span>
                    </a>
                    <a href="client_change_password.php" class="trx-nav-link active">
                        <i class="bi bi-key-fill"></i>
                        <span>Change Password</span>
                    </a>
                    <a href="client_logout.php" class="trx-nav-link">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                </nav>
            </div>
            <div class="sidebar-footer">
                <div class="mini-stat">
                    <span class="mini-dot"></span>
                    <span>Client Session Active</span>
                </div>
                <small class="text-secondary"><?= e($_SESSION['client_full_name']) ?></small>
            </div>
        </aside>
        
        <main class="trx-main">
            <header class="trx-topbar">
                <div>
                    <div class="topbar-title">Change Client Password</div>
                    <div class="topbar-subtitle">Protect your self-service portal with a stronger password.</div>
                </div>
            </header>
            
            <section class="panel form-panel">
                <div class="panel-header compact">
                    <div>
                        <h2>Password Update</h2>
                        <p>Choose a password with at least 8 characters.</p>
                    </div>
                </div>
                
                <?php if($errors): ?>
                <div class="alert alert-danger border-0 trx-alert">
                    <ul class="mb-0">
                        <?php foreach($errors as $error): ?>
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
                        <button class="btn trx-btn-primary" type="submit">
                            Update Password
                        </button>
                        <a href="client_dashboard.php" class="btn btn-outline-light rounded-pill px-4">
                            Back to Dashboard
                        </a>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>