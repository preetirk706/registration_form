<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/db.php';

$userId = (int) $_SESSION['user_id'];
$stmt = $conn->prepare(
    'SELECT id, client_name, client_email, client_phone, company_name, client_address, created_at
     FROM clients
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
    <title>My Clients</title>
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
                    <h1 class="h3 mb-1">My Clients</h1>
                    <p class="text-secondary mb-0">Clients registered by you.</p>
                </div>
                <a href="client_form.php" class="btn btn-primary">Add New Client</a>
            </div>

            <div class="card page-card">
                <div class="card-body p-0">
                    <?php if ($result->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Client Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Company</th>
                                        <th>Address</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $srNo = 1; ?>
                                    <?php while ($client = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $srNo++; ?></td>
                                            <td><?php echo htmlspecialchars($client['client_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?php echo htmlspecialchars($client['client_email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?php echo htmlspecialchars($client['client_phone'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?php echo htmlspecialchars($client['company_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?php echo nl2br(htmlspecialchars($client['client_address'], ENT_QUOTES, 'UTF-8')); ?></td>
                                            <td><?php echo htmlspecialchars($client['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a
                                                        href="edit_client.php?id=<?php echo (int) $client['id']; ?>"
                                                        class="btn btn-sm btn-outline-primary"
                                                    >
                                                        Update
                                                    </a>
                                                    <form action="delete_client.php" method="post" class="m-0">
                                                        <input type="hidden" name="id" value="<?php echo (int) $client['id']; ?>">
                                                        <button
                                                            type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Are you sure you want to delete this client?');"
                                                        >
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="p-4 p-md-5 text-center">
                            <h2 class="h5 mb-2">No clients found</h2>
                            <p class="text-secondary mb-3">You have not registered any clients yet.</p>
                            <a href="client_form.php" class="btn btn-primary">Register Client</a>
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
