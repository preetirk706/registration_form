<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/db.php';

$userId = (int) $_SESSION['user_id'];
$clientCount = 0;
$countStmt = $conn->prepare('SELECT COUNT(*) AS total_clients FROM clients WHERE user_id = ?');
$countStmt->bind_param('i', $userId);
$countStmt->execute();
$countResult = $countStmt->get_result();
$countRow = $countResult->fetch_assoc();
$clientCount = (int) ($countRow['total_clients'] ?? 0);
$monthlySales = '5';
$TotalLeads = '10';
$countStmt->close();
$conn->close();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
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
            <div class="card dashboard-card mb-4">
                <div class="card-body p-4 p-md-5">
                    <h1 class="h3 mb-2">
                        Welcome, <?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?>
                    </h1>
                   <!-- <p class="text-secondary mb-0">
                        You are logged in with <php echo htmlspecialchars($_SESSION['user_email'], ENT_QUOTES, 'UTF-8'); ?>.
                    </p>        -->
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <h2 class="h5">Total Clients</h2>
                            <p class="display-6 fw-semibold mb-2"><?php echo $clientCount; ?></p>
                            <a href="clients.php" class="btn btn-primary">View Clients</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <h2 class="h5">Monthly Sales</h2>
                            <!-- <p class="text-secondary">Add a client under your login.</p> -->
                            <p class="display-6 fw-semibold mb-2"><?php echo $monthlySales; ?></p>
                            <a href="#" class="btn btn-primary">View Sales</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <h2 class="h5">Total Leads</h2>
                            <!-- <p class="text-secondary">View your registered login details.</p> -->
                            <p class="display-6 fw-semibold mb-2"><?php echo $TotalLeads; ?></p>

                            <a href="details.php" class="btn btn-primary">View Leads</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
