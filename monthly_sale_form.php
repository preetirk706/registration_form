<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/db.php';

$userId = (int) $_SESSION['user_id'];
$clientStmt = $conn->prepare(
    'SELECT id, client_name
     FROM clients
     WHERE user_id = ?
     ORDER BY client_name ASC'
);
$clientStmt->bind_param('i', $userId);
$clientStmt->execute();
$clients = $clientStmt->get_result();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Monthly Sale</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="app-layout">
        <?php require __DIR__ . '/sidebar.php'; ?>

        <main class="app-main py-5">
            <div class="container-fluid px-4">
                <div class="card form-card mx-auto">
                    <div class="card-body p-4 p-md-5">
                        <div class="mb-4">
                            <h1 class="h3 mb-1">Add Monthly Sale</h1>
                            <p class="text-secondary mb-0">Add a sale record under your account.</p>
                        </div>

                        <div id="saleAlert" class="alert d-none" role="alert"></div>

                        <form id="monthlySaleForm" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="sale_month" class="form-label">Month</label>
                                    <input type="month" class="form-control" id="sale_month" name="sale_month" required>
                                    <div class="invalid-feedback">Please select month.</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="client_id" class="form-label">Client</label>
                                    <select class="form-select" id="client_id" name="client_id">
                                        <option value="">No client</option>
                                        <?php while ($client = $clients->fetch_assoc()): ?>
                                            <option value="<?php echo (int) $client['id']; ?>">
                                                <?php echo htmlspecialchars($client['client_name'], ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount" min="0" step="0.01" required>
                                    <div class="invalid-feedback">Please enter sale amount.</div>
                                </div>

                                <div class="col-12">
                                    <label for="note" class="form-label">Note</label>
                                    <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                                </div>

                                <div class="col-12 d-flex gap-2">
                                    <button type="submit" id="saleBtn" class="btn btn-primary px-4">Save Sale</button>
                                    <a href="monthly_sales.php" class="btn btn-outline-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(function () {
            var $form = $('#monthlySaleForm');
            var $alert = $('#saleAlert');
            var $saleBtn = $('#saleBtn');

            function showAlert(type, message) {
                $alert
                    .removeClass('d-none alert-success alert-danger alert-warning')
                    .addClass('alert-' + type)
                    .text(message);
            }

            $form.on('submit', function (event) {
                event.preventDefault();
                $alert.addClass('d-none').text('');

                if (this.checkValidity() === false) {
                    $form.addClass('was-validated');
                    return;
                }

                $saleBtn.prop('disabled', true).text('Saving...');

                $.ajax({
                    url: 'save_monthly_sale.php',
                    type: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            window.location.href = 'monthly_sales.php';
                            return;
                        }

                        showAlert('danger', response.message);
                    },
                    error: function () {
                        showAlert('danger', 'Server error. Please try again.');
                    },
                    complete: function () {
                        $saleBtn.prop('disabled', false).text('Save Sale');
                    }
                });
            });
        });
    </script>
</body>
</html>
<?php
$clients->free();
$clientStmt->close();
$conn->close();
?>
