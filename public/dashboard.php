<?php 
require_once '../includes/auth_check.php';
require_once '../config/Database.php';
require_once '../classes/Client.php';

$client = new Client((new Database())->connect());

$totalClients   = $client->countAll();
$activeClients  = $client->countByStatus('Active');
$inactiveClients= $client->countByStatus('Inactive');
$recentClients  = $client->recent(6);
$monthlyStats   = $client->monthlyStats();
$topCompanies   = $client->topCompanies(5);

include '../includes/header.php';
?>

<section class="dashboard-grid">

    <div class="panel panel-xl">
        <div class="panel-header">
            <div>
                <h2>Today's Command Center</h2>
                <p>Operational view of your client ecosystem and portal-ready records.</p>
            </div>
            <a href="add_client.php" class="btn trx-btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> New Client
            </a>
        </div>

        <div class="stats-grid">
            <div class="stat-card accent-gold">
                <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                <div class="stat-label">Total Clients</div>
                <div class="stat-value"><?= (int)$totalClients ?></div>
                <div class="stat-meta">Entire database footprint</div>
            </div>

            <div class="stat-card accent-cyan">
                <div class="stat-icon"><i class="bi bi-person-check-fill"></i></div>
                <div class="stat-label">Active Clients</div>
                <div class="stat-value"><?= (int)$activeClients ?></div>
                <div class="stat-meta">Currently engaged accounts</div>
            </div>

            <div class="stat-card accent-pink">
                <div class="stat-icon"><i class="bi bi-person-dash-fill"></i></div>
                <div class="stat-label">Inactive</div>
                <div class="stat-value"><?= (int)$inactiveClients ?></div>
                <div class="stat-meta">Dormant or archived contacts</div>
            </div>

            <div class="stat-card accent-mint">
                <div class="stat-icon"><i class="bi bi-buildings-fill"></i></div>
                <div class="stat-label">Top Companies</div>
                <div class="stat-value"><?= count($topCompanies) ?></div>
                <div class="stat-meta">Most represented organizations</div>
            </div>
        </div>
    </div>

    <div class="panel chart-panel">
        <div class="panel-header compact">
            <div>
                <h3>Client Growth</h3>
                <p>Monthly acquisition trend</p>
            </div>
        </div>
        <canvas id="monthlyChart" height="240"></canvas>
    </div>

    <div class="panel chart-panel">
        <div class="panel-header compact">
            <div>
                <h3>Status Breakdown</h3>
                <p>Active versus inactive distribution</p>
            </div>
        </div>
        <canvas id="statusChart" height="240"></canvas>
    </div>

    <div class="panel">
        <div class="panel-header compact">
            <div>
                <h3>Recent Clients</h3>
                <p>Latest records entering the fortress</p>
            </div>
            <a href="clients.php" class="link-arrow">
                Open client hub <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table trx-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($recentClients): foreach($recentClients as $row): ?>
                        <tr>
                            <td><?= e($row['full_name']) ?></td>
                            <td><?= e($row['company_name']) ?></td>
                            <td><?= e($row['email']) ?></td>
                            <td>
                                <span class="status-pill <?= $row['status']==='Active'?'active':'inactive' ?>">
                                    <?= e($row['status']) ?>
                                </span>
                            </td>
                            <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-secondary py-4">
                                No clients yet.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header compact">
            <div>
                <h3>Company Density</h3>
                <p>Most frequent organizations</p>
            </div>
        </div>

        <div class="company-list">
            <?php if($topCompanies): foreach($topCompanies as $company): ?>
                <div class="company-item">
                    <div>
                        <strong><?= e($company['company_name']) ?></strong>
                        <small class="text-secondary d-block">Enterprise account grouping</small>
                    </div>
                    <span class="company-badge"><?= (int)$company['total'] ?></span>
                </div>
            <?php endforeach; else: ?>
                <div class="empty-block">No company insights available yet.</div>
            <?php endif; ?>
        </div>
    </div>

</section>

<script>
window.TRX_DASHBOARD = {
    monthlyLabels: <?= json_encode(array_keys($monthlyStats)) ?>,
    monthlyValues: <?= json_encode(array_values($monthlyStats)) ?>,
    statusValues: <?= json_encode([$activeClients, $inactiveClients]) ?>
};
</script>

<?php include '../includes/footer.php'; ?>