<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/db.php';

$userId = (int) $_SESSION['user_id'];
$stmt = $conn->prepare(
    'SELECT id, first_name, last_name, email, phone, password, gender, city, address, created_at
     FROM users
     WHERE id = ?
     LIMIT 1'
);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die('Unable to fetch registered users.');
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registered Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <style>
        .table td,
        .table th {
            vertical-align: middle;
        }

        .address-cell {
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
                    <h1 class="h3 mb-1">My Account</h1>
                    <p class="text-secondary mb-0">
                        Welcome, <?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="dashboard.php" class="btn btn-outline-secondary">Dashboard</a>
                    <a href="index.php" class="btn btn-primary">Add New User</a>
                    <a href="logout.php" class="btn btn-outline-danger">Logout</a>
                </div>
            </div>

            <div class="card page-card">
                <div class="card-body p-0">
                    <?php if ($result->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Password</th>
                                        <th>Gender</th>
                                        <th>City</th>
                                        <th>Address</th>
                                        <th>Registered At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $srNo = 1; ?>
                                    <?php while ($user = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $srNo++; ?></td>
                                            <td>
                                                <?php
                                                echo htmlspecialchars(
                                                    $user['first_name'] . ' ' . $user['last_name'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                );
                                                ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?php echo htmlspecialchars($user['phone'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?php echo htmlspecialchars($user['password'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?php echo htmlspecialchars($user['gender'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?php echo htmlspecialchars($user['city'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td class="address-cell">
                                                <?php echo nl2br(htmlspecialchars($user['address'], ENT_QUOTES, 'UTF-8')); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($user['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="p-4 p-md-5 text-center">
                            <h2 class="h5 mb-2">Account not found</h2>
                            <p class="text-secondary mb-3">Your logged-in account record is not available.</p>
                            <a href="logout.php" class="btn btn-primary">Back to Login</a>
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
