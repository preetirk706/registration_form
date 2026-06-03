<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Client</title>
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
                        <h1 class="h3 mb-1">Register Client</h1>
                        <p class="text-secondary mb-0">Add a client under your account.</p>
                    </div>

                    <div id="clientAlert" class="alert d-none" role="alert"></div>

                    <form id="clientForm" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="client_name" class="form-label">Client Name</label>
                                <input type="text" class="form-control" id="client_name" name="client_name" required>
                                <div class="invalid-feedback">Please enter client name.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="client_email" class="form-label">Client Email</label>
                                <input type="email" class="form-control" id="client_email" name="client_email" required>
                                <div class="invalid-feedback">Please enter valid client email.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="client_phone" class="form-label">Client Phone</label>
                                <input type="tel" class="form-control" id="client_phone" name="client_phone" maxlength="20" required>
                                <div class="invalid-feedback">Please enter client phone.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" class="form-control" id="company_name" name="company_name">
                            </div>

                            <div class="col-12">
                                <label for="client_address" class="form-label">Address</label>
                                <textarea class="form-control" id="client_address" name="client_address" rows="3" required></textarea>
                                <div class="invalid-feedback">Please enter client address.</div>
                            </div>

                            <div class="col-12">
                                <button type="submit" id="clientBtn" class="btn btn-primary px-4">Save Client</button>
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
            var $form = $('#clientForm');
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

                $clientBtn.prop('disabled', true).text('Saving...');

                $.ajax({
                    url: 'save_client.php',
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
                        $clientBtn.prop('disabled', false).text('Save Client');
                    }
                });
            });
        });
    </script>
</body>
</html>
