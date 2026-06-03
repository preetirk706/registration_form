<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/db.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Lead</title>
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
                            <h1 class="h3 mb-1">Add Lead</h1>
                            <p class="text-secondary mb-0">Create a new lead under your login.</p>
                        </div>

                        <div id="leadAlert" class="alert d-none" role="alert"></div>

                        <form id="leadForm" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="lead_name" class="form-label">Lead Name</label>
                                    <input type="text" class="form-control" id="lead_name" name="lead_name" required>
                                    <div class="invalid-feedback">Please enter lead name.</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="lead_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="lead_email" name="lead_email">
                                    <div class="invalid-feedback">Please enter valid email.</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="lead_phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control" id="lead_phone" name="lead_phone" maxlength="20">
                                </div>

                                <div class="col-md-6">
                                    <label for="lead_source" class="form-label">Source</label>
                                    <input type="text" class="form-control" id="lead_source" name="lead_source" placeholder="Website, Facebook, Referral">
                                </div>

                                <div class="col-md-6">
                                    <label for="lead_status" class="form-label">Status</label>
                                    <select class="form-select" id="lead_status" name="lead_status" required>
                                        <option value="New">New</option>
                                        <option value="Contacted">Contacted</option>
                                        <option value="Interested">Interested</option>
                                        <option value="Converted">Converted</option>
                                        <option value="Closed">Closed</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label for="note" class="form-label">Note</label>
                                    <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                                </div>

                                <div class="col-12 d-flex gap-2">
                                    <button type="submit" id="leadBtn" class="btn btn-primary px-4">Save Lead</button>
                                    <a href="leads.php" class="btn btn-outline-secondary">Cancel</a>
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
            var $form = $('#leadForm');
            var $alert = $('#leadAlert');
            var $leadBtn = $('#leadBtn');

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

                $leadBtn.prop('disabled', true).text('Saving...');

                $.ajax({
                    url: 'save_lead.php',
                    type: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            window.location.href = 'leads.php';
                            return;
                        }

                        showAlert('danger', response.message);
                    },
                    error: function () {
                        showAlert('danger', 'Server error. Please try again.');
                    },
                    complete: function () {
                        $leadBtn.prop('disabled', false).text('Save Lead');
                    }
                });
            });
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>
