<?php
require_once '../includes/bootstrap.php';
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Access | TRX Clawd Fortress</title>
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
<body class="login-body">
    <div class="login-grid"></div>
    <div class="container py-5">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-12 col-lg-8">
                <div class="login-card">
                    <div class="row g-0 align-items-stretch">
                        <div class="col-lg-5 login-brand-panel">
                            <div class="brand-badge">Client Access</div>
                            <h1>TRX Clawd Fortress</h1>
                        
                            <div class="login-metrics">
                                <div class="metric-card">
                                    <span class="metric-icon"><i class="bi bi-shield-lock"></i></span>
                                    <div>
                                        <strong>Safer Setup</strong>
                                        <small>Client accounts are created by the administrator/server only.</small>
                                    </div>
                                </div>
                                <div class="metric-card">
                                    <span class="metric-icon"><i class="bi bi-person-badge"></i></span>
                                    <div>
                                        <strong>Client Portal</strong>
                                        <small>Clients use assigned portal credentials to login securely.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 login-form-panel">
                            <div class="login-window-dots"><span></span><span></span><span></span></div>
                            <div class="login-form-wrap">
                                <div class="login-title">Client Access Information</div>
                                <p class="login-subtitle">For safety purposes, public self-registration is disabled.</p>

                                <?php if ($flash): ?>
                                    <div class="alert alert-<?= e($flash['type']) ?> border-0"><?= e($flash['message']) ?></div>
                                <?php endif; ?>

                                

                                <div class="sample-login">
                                    <div><span>If you already have a portal account</span> <strong>Use Client Login</strong></div>
                                    <div><span>If you do not have an account yet</span> <strong>Contact the administrator</strong></div>
                                </div>


                                
                                <div class="d-grid gap-3 mt-4">
                                    <a href="client_login.php" class="btn trx-btn-primary">Go to Client Login</a>
                                    <a href="login.php" class="btn btn-outline-light rounded-pill px-4">Back to Admin Login</a>
                                </div>

                           
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
