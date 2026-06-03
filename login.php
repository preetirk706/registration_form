<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: #f4f7fb;
        }

        .login-shell {
            min-height: 100vh;
        }

        .login-card {
            max-width: 460px;
            border: 0;
            border-radius: 8px;
            box-shadow: 0 12px 40px rgba(15, 23, 42, 0.12);
        }
    </style>
</head>
<body>
    <main class="login-shell d-flex align-items-center py-5">
        <div class="container">
            <div class="card login-card mx-auto">
                <div class="card-body p-4 p-md-5">
                    <div class="mb-4">
                        <h1 class="h3 mb-1">Login</h1>
                        <p class="text-secondary mb-0">Enter your email and password.</p>
                    </div>

                    <div id="loginAlert" class="alert d-none" role="alert"></div>

                    <form id="loginForm" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">Please enter your email.</div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="invalid-feedback">Please enter your password.</div>
                        </div>

                        <div class="d-flex flex-column flex-sm-row gap-2 align-items-sm-center justify-content-between">
                            <button type="submit" id="loginBtn" class="btn btn-primary px-4">Login</button>
                            <a href="index.php" class="btn btn-outline-secondary">Create Account</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(function () {
            var $form = $('#loginForm');
            var $alert = $('#loginAlert');
            var $loginBtn = $('#loginBtn');

            function showAlert(type, message) {
                $alert
                    .removeClass('d-none alert-success alert-danger alert-warning')
                    .addClass('alert-' + type)
                    .text(message);
            }

            function showAlertHtml(type, messageHtml) {
                $alert
                    .removeClass('d-none alert-success alert-danger alert-warning')
                    .addClass('alert-' + type)
                    .html(messageHtml);
            }

            $form.on('submit', function (event) {
                event.preventDefault();
                $alert.addClass('d-none').text('');

                if (this.checkValidity() === false) {
                    $form.addClass('was-validated');
                    return;
                }

                $loginBtn.prop('disabled', true).text('Logging in...');

                $.ajax({
                    url: 'login_process.php',
                    type: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            window.location.href = 'dashboard.php';
                            return;
                        }

                        if (response.type === 'account_not_found') {
                            showAlertHtml(
                                'warning',
                                'This email ID is not registered. <a href="index.php" class="alert-link">Create an account</a>.'
                            );
                            return;
                        }

                        showAlert('danger', response.message);
                    },
                    error: function () {
                        showAlert('danger', 'Server error. Please try again.');
                    },
                    complete: function () {
                        $loginBtn.prop('disabled', false).text('Login');
                    }
                });
            });
        });
    </script>
</body>
</html>
