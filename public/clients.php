<?php
require_once '../includes/auth_check.php';
require_once '../config/Database.php';
require_once '../classes/Client.php';

$client = new Client((new Database())->connect());

$search = trim($_GET['search'] ?? '');

$page = max(1, (int)($_GET['page'] ?? 1));

$perPage = 5;

$totalRows = $client->countFiltered($search);

$totalPages = max(1, (int)ceil($totalRows / $perPage));

if ($page > $totalPages) $page = $totalPages;

$clients = $client->getPaginated($search, $page, $perPage);

include '../includes/header.php';
?>

<section class="panel panel-xl">
    <div class="panel-header">
        <div>
            <h2>Client Hub</h2>
            <p>Search, manage, paginate, export, and assign client portal access.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="export_clients_pdf.php?<?= e(build_query(['search' => $search])) ?>" 
               class="btn btn-outline-light rounded-pill px-3">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </a>
            <a href="client_login.php" 
               class="btn btn-outline-info rounded-pill px-3">
                <i class="bi bi-box-arrow-up-right"></i> Open Client Portal
            </a>
            <a href="add_client.php" 
               class="btn trx-btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Add Client
            </a>
        </div>
    </div>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-lg-10">
            <div class="input-glass input-glass-dark">
                <i class="bi bi-search"></i>
                <input type="text" 
                       class="form-control" 
                       name="search" 
                       placeholder="Search by name, company, email, or contact number" 
                       value="<?= e($search) ?>">
            </div>
        </div>
        <div class="col-lg-2 d-grid">
            <button class="btn trx-btn-primary" type="submit">Search</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table trx-table align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Company</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($clients): ?>
                    <?php foreach ($clients as $row): ?>
                    <tr>
                        <td><?= (int)$row['id'] ?></td>
                        <td><?= e($row['full_name']) ?></td>
                        <td><?= e($row['email']) ?></td>
                        <td><?= e($row['contact_number']) ?></td>
                        <td><?= e($row['company_name']) ?></td>
                        <td class="text-wrap" style="max-width:240px;">
                            <?= e($row['address']) ?>
                        </td>
                        <td>
                            <span class="status-pill <?= $row['status'] === 'Active' ? 'active' : 'inactive' ?>">
                                <?= e($row['status']) ?>
                            </span>
                        </td>
                        <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                        <td>
                            <div class="d-flex justify-content-end gap-2 flex-wrap">
                                <a href="manage_portal.php?id=<?= (int)$row['id'] ?>" 
                                   class="btn btn-sm btn-outline-info rounded-pill px-3">
                                    Portal
                                </a>
                                <a href="edit_client.php?id=<?= (int)$row['id'] ?>" 
                                   class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                    Edit
                                </a>
                                <a href="delete_client.php?id=<?= (int)$row['id'] ?>&csrf_token=<?= e(generate_csrf_token()) ?>" 
                                   class="btn btn-sm btn-outline-danger rounded-pill px-3" 
                                   onclick="return confirm('Delete this client record?');">
                                    Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center text-secondary py-5">
                            No client records match the current search.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">
        <div class="text-secondary small">
            Showing page <?= $page ?> of <?= $totalPages ?> · <?= $totalRows ?> record(s)
        </div>
        <nav aria-label="Client pagination">
            <ul class="pagination pagination-dark mb-0">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" 
                       href="?<?= e(build_query(['search' => $search, 'page' => $page - 1])) ?>">
                        Previous
                    </a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                    <a class="page-link" 
                       href="?<?= e(build_query(['search' => $search, 'page' => $i])) ?>">
                        <?= $i ?>
                    </a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" 
                       href="?<?= e(build_query(['search' => $search, 'page' => $page + 1])) ?>">
                        Next
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</section>

<?php include '../includes/footer.php'; ?>