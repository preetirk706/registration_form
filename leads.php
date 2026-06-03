<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/db.php';

$userId = (int) $_SESSION['user_id'];
$stmt = $conn->prepare(
    'SELECT id, lead_name, lead_email, lead_phone, lead_source, lead_status, note, created_at
     FROM leads
     WHERE user_id = ?
     ORDER BY id DESC'
);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Leads</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <style>
        .table td,
        .table th {
            vertical-align: middle;
        }

        .note-cell {
            min-width: 220px;
            white-space: normal;
        }
    </style>
</head>
<body>
    <div class="app-layout">
        <?php require __DIR__ . '/sidebar.php'; ?>

        <main class="app-main py-5">
            <div class="container-fluid px-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                    <div>
                        <h1 class="h3 mb-1">View Leads</h1>
                        <p class="text-secondary mb-0">Leads created under your login.</p>
                    </div>
                    <a href="add_lead.php" class="btn btn-primary">Add Lead</a>
                </div>

                <div class="card page-card">
                    <div class="card-body p-0">
                        <?php if ($result->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Lead Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Source</th>
                                            <th>Status</th>
                                            <th>Note</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $srNo = 1; ?>
                                        <?php while ($lead = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $srNo++; ?></td>
                                                <td><?php echo htmlspecialchars($lead['lead_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($lead['lead_email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($lead['lead_phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($lead['lead_source'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <span class="badge text-bg-primary">
                                                        <?php echo htmlspecialchars($lead['lead_status'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </span>
                                                </td>
                                                <td class="note-cell">
                                                    <?php echo nl2br(htmlspecialchars($lead['note'] ?? '', ENT_QUOTES, 'UTF-8')); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($lead['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="p-4 p-md-5 text-center">
                                <h2 class="h5 mb-2">No leads found</h2>
                                <p class="text-secondary mb-0">No lead records are available yet.</p>
                                <a href="add_lead.php" class="btn btn-primary mt-3">Add First Lead</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$result->free();
$stmt->close();
$conn->close();
?>
