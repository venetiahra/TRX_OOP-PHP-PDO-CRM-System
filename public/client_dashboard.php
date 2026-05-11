<?php
require_once '../includes/client_auth_check.php';
require_once '../config/Database.php';
require_once '../classes/Client.php';

$client = new Client((new Database())->connect());
$profile=$client->getById((int)$_SESSION['client_id']);

if(!$profile){
    set_flash('danger','Client record not found.');
    redirect('client_logout.php');
}

$_SESSION['client_full_name']=$profile['full_name'];
$_SESSION['client_company_name']=$profile['company_name'];

$companySummary=$client->getCompanySummary($profile['company_name']);
$companyContacts=$client->getByCompanyName($profile['company_name'], (int)$profile['id']);
$flash=get_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard | TRX Clawd Fortress</title>
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
                    <a href="client_dashboard.php" class="trx-nav-link active">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Overview</span>
                    </a>
                    <a href="client_profile.php" class="trx-nav-link">
                        <i class="bi bi-person-lines-fill"></i>
                        <span>Edit My Profile</span>
                    </a>
                    <a href="client_change_password.php" class="trx-nav-link">
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
                    <div class="topbar-title">
                        Welcome, <?= e($_SESSION['client_full_name']) ?>
                    </div>
                    <div class="topbar-subtitle">
                        Review company data and update personal information.
                    </div>
                </div>
                <div class="topbar-actions">
                    <div class="profile-pill">
                        <span class="profile-avatar">
                            <?= strtoupper(substr($_SESSION['client_full_name'],0,1)) ?>
                        </span>
                        <div>
                            <div class="profile-name"><?= e($_SESSION['client_company_name']) ?></div>
                            <small class="text-secondary">Client Workspace</small>
                        </div>
                    </div>
                </div>
            </header>
            
            <?php if($flash): ?>
            <div class="alert alert-<?= e($flash['type']) ?> border-0 trx-alert">
                <?= e($flash['message']) ?>
            </div>
            <?php endif; ?>
            
            <section class="dashboard-grid">
                <div class="panel panel-xl">
                    <div class="panel-header">
                        <div>
                            <h2>Your Client Workspace</h2>
                          
                        </div>
                        <div class="d-flex gap-2">
                            <a href="client_profile.php" class="btn trx-btn-primary btn-sm">
                                <i class="bi bi-pencil-square"></i> Edit My Info
                            </a>
                            <a href="client_change_password.php" class="btn btn-outline-light rounded-pill px-3">
                                Change Password
                            </a>
                        </div>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-card accent-gold">
                            <div class="stat-icon">
                                <i class="bi bi-person-vcard"></i>
                            </div>
                            <div class="stat-label">Portal Status</div>
                            <div class="stat-value">Open</div>
                            <div class="stat-meta">Self-service access enabled</div>
                        </div>
                        <div class="stat-card accent-cyan">
                            <div class="stat-icon">
                                <i class="bi bi-buildings"></i>
                            </div>
                            <div class="stat-label">Company Contacts</div>
                            <div class="stat-value"><?= (int)$companySummary['total_contacts'] ?></div>
                            <div class="stat-meta">Records under <?= e($profile['company_name']) ?></div>
                        </div>
                        <div class="stat-card accent-pink">
                            <div class="stat-icon">
                                <i class="bi bi-person-check"></i>
                            </div>
                            <div class="stat-label">Active Contacts</div>
                            <div class="stat-value"><?= (int)$companySummary['active_contacts'] ?></div>
                            <div class="stat-meta">Active company records</div>
                        </div>
                        <div class="stat-card accent-mint">
                            <div class="stat-icon">
                                <i class="bi bi-person-dash"></i>
                            </div>
                            <div class="stat-label">Inactive Contacts</div>
                            <div class="stat-value"><?= (int)$companySummary['inactive_contacts'] ?></div>
                            <div class="stat-meta">Inactive company records</div>
                        </div>
                    </div>
                </div>
                
                <div class="panel">
                    <div class="panel-header compact">
                        <div>
                            <h3>My Client Profile</h3>
                            <p>Your personal CRM record</p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table trx-table mb-0">
                            <tbody>
                                <tr>
                                    <th style="width:220px">Full Name</th>
                                    <td><?= e($profile['full_name']) ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?= e($profile['email']) ?></td>
                                </tr>
                                <tr>
                                    <th>Contact Number</th>
                                    <td><?= e($profile['contact_number']) ?></td>
                                </tr>
                                <tr>
                                    <th>Company</th>
                                    <td><?= e($profile['company_name']) ?></td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td><?= e($profile['address']) ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="status-pill <?= $profile['status']==='Active'?'active':'inactive' ?>">
                                            <?= e($profile['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="panel">
                    <div class="panel-header compact">
                        <div>
                            <h3>Company Snapshot</h3>
                            <p>Quick summary for your company data</p>
                        </div>
                    </div>
                    <div class="company-list">
                        <div class="company-item">
                            <div>
                                <strong><?= e($profile['company_name']) ?></strong>
                                <small class="text-secondary d-block">Company registered in CRM</small>
                            </div>
                            <span class="company-badge"><?= (int)$companySummary['total_contacts'] ?></span>
                        </div>
                        <div class="company-item">
                            <div>
                                <strong>Portal Username</strong>
                                <small class="text-secondary d-block">Client login identity</small>
                            </div>
                            <span class="company-badge"><?= e($_SESSION['client_username']) ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="panel panel-xl">
                    <div class="panel-header compact">
                        <div>
                            <h3>Other Company Records</h3>
                            <p>Additional CRM contacts saved under the same company name.</p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table trx-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Contact Number</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($companyContacts): ?>
                                    <?php foreach($companyContacts as $row): ?>
                                    <tr>
                                        <td><?= e($row['full_name']) ?></td>
                                        <td><?= e($row['email']) ?></td>
                                        <td><?= e($row['contact_number']) ?></td>
                                        <td>
                                            <span class="status-pill <?= $row['status']==='Active'?'active':'inactive' ?>">
                                                <?= e($row['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-secondary py-5">
                                            No additional company records were found. Right now, your client record 
                                            is the only contact under this company.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>