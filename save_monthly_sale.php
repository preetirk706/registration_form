<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'status' => 'error',
        'message' => 'Please login first.'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
    exit;
}

require_once __DIR__ . '/db.php';

$userId = (int) $_SESSION['user_id'];
$saleMonthInput = trim((string) ($_POST['sale_month'] ?? ''));
$clientId = (int) ($_POST['client_id'] ?? 0);
$amount = trim((string) ($_POST['amount'] ?? ''));
$note = trim((string) ($_POST['note'] ?? ''));

if ($saleMonthInput === '' || $amount === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Month and amount are required.'
    ]);
    exit;
}

if (!preg_match('/^\d{4}-\d{2}$/', $saleMonthInput) || !is_numeric($amount) || (float) $amount < 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please enter valid sale details.'
    ]);
    exit;
}

$saleMonth = $saleMonthInput . '-01';
$clientIdValue = $clientId > 0 ? $clientId : null;

if ($clientId > 0) {
    $clientStmt = $conn->prepare('SELECT id FROM clients WHERE id = ? AND user_id = ? LIMIT 1');
    $clientStmt->bind_param('ii', $clientId, $userId);
    $clientStmt->execute();
    $clientStmt->store_result();

    if ($clientStmt->num_rows === 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Selected client was not found.'
        ]);
        $clientStmt->close();
        $conn->close();
        exit;
    }

    $clientStmt->close();
}

$stmt = $conn->prepare(
    'INSERT INTO monthly_sales (user_id, client_id, sale_month, amount, note)
     VALUES (?, ?, ?, ?, ?)'
);
$stmt->bind_param('iisds', $userId, $clientIdValue, $saleMonth, $amount, $note);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Monthly sale saved successfully.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Monthly sale save failed. Please try again.'
    ]);
}

$stmt->close();
$conn->close();
