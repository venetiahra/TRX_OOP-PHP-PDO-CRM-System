<?php
require_once '../includes/auth_check.php';
require_once '../config/Database.php';
require_once '../classes/Client.php';

$client = new Client((new Database())->connect());

$search = trim($_GET['search'] ?? '');

$clients = $client->getAll($search);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Clients PDF | TRX Clawd Fortress</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        .sheet {
            max-width: 1100px;
            margin: 24px auto;
            background: #fff;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,.08);
        }
        @media print {
            .controls {
                display: none !important;
            }
            body {
                background: #fff;
            }
            .sheet {
                box-shadow: none;
                margin: 0;
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="controls d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="mb-1">TRX Clawd Fortress - Client Directory</h2>
                <div class="text-muted">
                    Generated: <?= date('F d, Y h:i A') ?>
                    <?= $search ? ' · Filter: ' . e($search) : '' ?>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-outline-secondary">Print / Save PDF</button>
                <button id="downloadPdf" class="btn btn-danger">Download PDF</button>
                <a href="clients.php" class="btn btn-primary">Back</a>
            </div>
        </div>

        <table class="table table-bordered table-sm align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Company</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($clients as $row): ?>
                <tr>
                    <td><?= (int)$row['id'] ?></td>
                    <td><?= e($row['full_name']) ?></td>
                    <td><?= e($row['email']) ?></td>
                    <td><?= e($row['contact_number']) ?></td>
                    <td><?= e($row['company_name']) ?></td>
                    <td><?= e($row['address']) ?></td>
                    <td><?= e($row['status']) ?></td>
                    <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js"></script>
    <script>
        document.getElementById('downloadPdf').addEventListener('click', function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('landscape');
            
            doc.setFontSize(16);
            doc.text('TRX Clawd Fortress - Client Directory', 14, 16);
            
            doc.setFontSize(10);
            doc.text('Generated report from enterprise CRM', 14, 22);
            
            doc.autoTable({
                html: 'table',
                startY: 28,
                styles: {
                    fontSize: 8
                },
                headStyles: {
                    fillColor: [33,37,41]
                }
            });
            
            doc.save('trx-clawd-fortress-clients.pdf');
        });
    </script>
</body>
</html>