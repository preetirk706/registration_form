<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/db.php';

$userId = (int) $_SESSION['user_id'];
$stmt = $conn->prepare(
    'SELECT monthly_sales.id, monthly_sales.sale_month, monthly_sales.amount, monthly_sales.note,
            monthly_sales.created_at, clients.client_name
     FROM monthly_sales
     LEFT JOIN clients ON clients.id = monthly_sales.client_id
     WHERE monthly_sales.user_id = ?
     ORDER BY monthly_sales.sale_month DESC, monthly_sales.id DESC'
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
    <title>Monthly Sales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <style>
        .table td,
        .table th {
            vertical-align: middle;
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
                        <h1 class="h3 mb-1">Monthly Sales</h1>
                        <p class="text-secondary mb-0">Sales records added by you.</p>
                    </div>
                    <a href="monthly_sale_form.php" class="btn btn-primary">Add Monthly Sale</a>
                </div>

                <div class="card page-card">
                    <div class="card-body p-0">
                        <?php if ($result->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Month</th>
                                            <th>Client</th>
                                            <th>Amount</th>
                                            <th>Note</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $srNo = 1; ?>
                                        <?php while ($sale = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $srNo++; ?></td>
                                                <td><?php echo date('F Y', strtotime($sale['sale_month'])); ?></td>
                                                <td><?php echo htmlspecialchars($sale['client_name'] ?? 'No client', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo number_format((float) $sale['amount'], 2); ?></td>
                                                <td><?php echo nl2br(htmlspecialchars($sale['note'] ?? '', ENT_QUOTES, 'UTF-8')); ?></td>
                                                <td><?php echo htmlspecialchars($sale['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="p-4 p-md-5 text-center">
                                <h2 class="h5 mb-2">No sales found</h2>
                                <p class="text-secondary mb-3">You have not added any monthly sales yet.</p>
                                <a href="monthly_sale_form.php" class="btn btn-primary">Add Monthly Sale</a>
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
