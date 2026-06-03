<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background:
                linear-gradient(135deg, rgba(37, 99, 235, 0.08), rgba(16, 185, 129, 0.08)),
                #f4f7fb;
        }

        .register-shell {
            min-height: 100vh;
        }

        .register-card {
            max-width: 1080px;
            border: 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 18px 55px rgba(15, 23, 42, 0.16);
        }

        .register-visual {
            background: #111827;
            color: #ffffff;
            min-height: 100%;
            padding: 42px;
        }

        .brand-mark {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: #2563eb;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-bottom: 28px;
        }

        .register-visual h2 {
            font-size: 2rem;
            line-height: 1.2;
        }

        .mini-stat {
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 8px;
            padding: 14px;
            background: rgba(255, 255, 255, 0.05);
        }

        .form-control,
        .form-select {
            min-height: 44px;
            border-color: #d7deea;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.14);
        }

        .form-label {
            font-weight: 600;
            color: #334155;
        }

        .top-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        @media (max-width: 991.98px) {
            .register-visual {
                padding: 28px;
            }
        }
    </style>
</head>
<body>
    <?php if ($isLoggedIn): ?>
        <div class="app-layout">
            <?php require __DIR__ . '/sidebar.php'; ?>
            <main class="app-main register-shell d-flex align-items-center py-5">
                <div class="container-fluid px-4">
    <?php else: ?>
        <main class="register-shell d-flex align-items-center py-5">
            <div class="container">
    <?php endif; ?>
            <div class="card register-card mx-auto">
                <div class="row g-0">
                    <div class="col-lg-4">
                        <div class="register-visual d-flex flex-column justify-content-between">
                            <div>
                                <div class="brand-mark">CP</div>
                                <h2 class="mb-3">Client Panel</h2>
                                <p class="text-white-50 mb-4">Create your account and start managing client records.</p>
                            </div>

                            <div class="row g-3">
                                <div class="col-6 col-lg-12">
                                    <div class="mini-stat">
                                        <div class="fw-semibold">Clients</div>
                                        <small class="text-white-50">Private records</small>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-12">
                                    <div class="mini-stat">
                                        <div class="fw-semibold">Sales</div>
                                        <small class="text-white-50">Monthly tracking</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
                                <div>
                                    <h1 class="h3 mb-1">Create Account</h1>
                                    <p class="text-secondary mb-0">Fill your details to complete registration.</p>
                                </div>
                                <div class="top-actions">
                                    <a href="details.php" class="btn btn-outline-secondary btn-sm">My Account</a>
                                    <a href="login.php" class="btn btn-outline-primary btn-sm">Login</a>
                                </div>
                            </div>

                            <div id="formAlert" class="alert d-none" role="alert"></div>

                            <form id="registrationForm" novalidate>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                                        <div class="invalid-feedback">Please enter your first name.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                                        <div class="invalid-feedback">Please enter your last name.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                        <div class="invalid-feedback">Please enter a valid email.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" maxlength="20" required>
                                        <div class="invalid-feedback">Please enter your phone number.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" minlength="6" required>
                                        <div class="invalid-feedback">Password must be at least 6 characters.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="confirm_password" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="6" required>
                                        <div class="invalid-feedback">Please confirm your password.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-select" id="gender" name="gender" required>
                                            <option value="">Choose...</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <div class="invalid-feedback">Please select your gender.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" class="form-control" id="city" name="city" required>
                                        <div class="invalid-feedback">Please enter your city.</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                                        <div class="invalid-feedback">Please enter your address.</div>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" id="submitBtn" class="btn btn-primary px-4">
                                            Register
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    <?php if ($isLoggedIn): ?>
                </div>
            </main>
        </div>
    <?php else: ?>
            </div>
        </main>
    <?php endif; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(function () {
            var $form = $('#registrationForm');
            var $alert = $('#formAlert');
            var $submitBtn = $('#submitBtn');

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

                if ($('#password').val() !== $('#confirm_password').val()) {
                    showAlert('warning', 'Password and confirm password do not match.');
                    return;
                }

                $submitBtn.prop('disabled', true).text('Registering...');
                // alert("fdasdf");return false;
                $.ajax({
                    url: 'register.php',
                    type: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        showAlert(response.status === 'success' ? 'success' : 'danger', response.message);

                        if (response.status === 'success') {
                            $form[0].reset();
                            $form.removeClass('was-validated');
                            window.location.href = 'login.php';
                        }
                    },
                    error: function () {
                        showAlert('danger', 'Server error. Please try again.');
                    },
                    complete: function () {
                        $submitBtn.prop('disabled', false).text('Register');
                    }
                });
            });
        });
    </script>
</body>
</html>
