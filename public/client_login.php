<?php
require_once '../includes/bootstrap.php';
require_once '../config/Database.php';
require_once '../classes/ClientPortal.php';

if(isset($_SESSION['client_id']))
    redirect('client_dashboard.php');

$portal=new ClientPortal((new Database())->connect());
$error='';
$flash=get_flash();

if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verify_csrf_token($_POST['csrf_token']??null))
        $error='Invalid CSRF token.';
    else {
        $result=$portal->login($_POST['username']??'', $_POST['password']??'');
        if($result===true){
            set_flash('success','Welcome to your client portal.');
            redirect('client_dashboard.php');
        }
        $error=$result;
    }
}

$csrf=generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Portal | TRX Clawd Fortress</title>
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
                            <div class="brand-badge">Client Self-Service Portal</div>
                            <h1>TRX Clawd Fortress</h1>
                            <p>
                                Data secured, futured secured.
                            </p>
                            <div class="login-metrics">
                                <div class="metric-card">
                                    <span class="metric-icon">
                                        <i class="bi bi-building"></i>
                                    </span>
                                    <div>
                                        <strong>Want safety?</strong>
                                      
                                    </div>
                                </div>
                                <div class="metric-card">
                                    <span class="metric-icon">
                                        <i class="bi bi-pencil-square"></i>
                                    </span>
                                    <div>
                                        <strong>JOIN now!</strong>
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 login-form-panel">
                            <div class="login-window-dots">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                            <div class="login-form-wrap">
                                <div class="login-title">Client Login</div>
                                <p class="login-subtitle">Only MEMBERS can login succesfully</p>
                                
                                <?php if($flash): ?>
                                <div class="alert alert-<?= e($flash['type']) ?> border-0">
                                    <?= e($flash['message']) ?>
                                </div>
                                <?php endif; ?>
                                
                                <?php if($error): ?>
                                <div class="alert alert-danger border-0">
                                    <?= e($error) ?>
                                </div>
                                <?php endif; ?>
                                
                                <form method="POST">
                                    <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Portal Username</label>
                                        <div class="input-glass">
                                            <i class="bi bi-person-badge"></i>
                                            <input type="text" name="username" class="form-control" 
                                                   placeholder="Enter portal username" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Portal Password</label>
                                        <div class="input-glass">
                                            <i class="bi bi-lock"></i>
                                            <input type="password" name="password" class="form-control" 
                                                   placeholder="Enter portal password" required>
                                        </div>
                                    </div>
                                    
                                    <button class="btn trx-btn-primary w-100" type="submit">
                                        Open Client Portal
                                    </button>
                                </form>
                                
                                <div class="d-flex justify-content-between align-items-center mt-4 small">
                                    <a href="login.php" class="text-decoration-none text-info">
                                        Back to admin login
                                    </a>
                                    <span class="text-secondary">
                                        Ask admin for your portal credentials
                                    </span>
                                </div>
                                
                                <div class="mt-3 text-secondary small">
                                    Sample portal: <strong>patricia.client / admin123</strong>
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