<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/db.php';

$userId = (int) $_SESSION['user_id'];
$clientId = (int) ($_GET['id'] ?? 0);

if ($clientId <= 0) {
    header('Location: clients.php');
    exit;
}

$stmt = $conn->prepare(
    'SELECT id, client_name, client_email, client_phone, company_name, client_address
     FROM clients
     WHERE id = ? AND user_id = ?
     LIMIT 1'
);
$stmt->bind_param('ii', $clientId, $userId);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();

if (!$client) {
    $stmt->close();
    $conn->close();
    header('Location: clients.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <style>
    </style>
</head>
<body>
    <div class="app-layout">
        <?php require __DIR__ . '/sidebar.php'; ?>

        <main class="app-main py-5">
            <div class="container-fluid px-4">
            <div class="card form-card mx-auto">
                <div class="card-body p-4 p-md-5">
                    <div class="mb-4">
                        <h1 class="h3 mb-1">Update Client</h1>
                        <p class="text-secondary mb-0">Edit client details under your account.</p>
                    </div>

                    <div id="clientAlert" class="alert d-none" role="alert"></div>

                    <form id="editClientForm" novalidate>
                        <input type="hidden" name="id" value="<?php echo (int) $client['id']; ?>">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="client_name" class="form-label">Client Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="client_name"
                                    name="client_name"
                                    value="<?php echo htmlspecialchars($client['client_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                    required
                                >
                                <div class="invalid-feedback">Please enter client name.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="client_email" class="form-label">Client Email</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="client_email"
                                    name="client_email"
                                    value="<?php echo htmlspecialchars($client['client_email'], ENT_QUOTES, 'UTF-8'); ?>"
                                    required
                                >
                                <div class="invalid-feedback">Please enter valid client email.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="client_phone" class="form-label">Client Phone</label>
                                <input
                                    type="tel"
                                    class="form-control"
                                    id="client_phone"
                                    name="client_phone"
                                    maxlength="20"
                                    value="<?php echo htmlspecialchars($client['client_phone'], ENT_QUOTES, 'UTF-8'); ?>"
                                    required
                                >
                                <div class="invalid-feedback">Please enter client phone.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="company_name"
                                    name="company_name"
                                    value="<?php echo htmlspecialchars($client['company_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                >
                            </div>

                            <div class="col-12">
                                <label for="client_address" class="form-label">Address</label>
                                <textarea class="form-control" id="client_address" name="client_address" rows="3" required><?php echo htmlspecialchars($client['client_address'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                                <div class="invalid-feedback">Please enter client address.</div>
                            </div>

                            <div class="col-12 d-flex gap-2">
                                <button type="submit" id="clientBtn" class="btn btn-primary px-4">Update Client</button>
                                <a href="clients.php" class="btn btn-outline-secondary">Cancel</a>
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
            var $form = $('#editClientForm');
            var $alert = $('#clientAlert');
            var $clientBtn = $('#clientBtn');

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

                $clientBtn.prop('disabled', true).text('Updating...');

                $.ajax({
                    url: 'update_client.php',
                    type: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            window.location.href = 'clients.php';
                            return;
                        }

                        showAlert('danger', response.message);
                    },
                    error: function () {
                        showAlert('danger', 'Server error. Please try again.');
                    },
                    complete: function () {
                        $clientBtn.prop('disabled', false).text('Update Client');
                    }
                });
            });
        });
    </script>
</body>
</html>
<?php
$result->free();
$stmt->close();
$conn->close();
?>
