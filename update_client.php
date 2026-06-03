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
$clientId = (int) ($_POST['id'] ?? 0);
$clientName = trim((string) ($_POST['client_name'] ?? ''));
$clientEmail = trim((string) ($_POST['client_email'] ?? ''));
$clientPhone = trim((string) ($_POST['client_phone'] ?? ''));
$companyName = trim((string) ($_POST['company_name'] ?? ''));
$clientAddress = trim((string) ($_POST['client_address'] ?? ''));

if ($clientId <= 0 || $clientName === '' || $clientEmail === '' || $clientPhone === '' || $clientAddress === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Name, email, phone and address are required.'
    ]);
    exit;
}

if (!filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please enter a valid client email.'
    ]);
    exit;
}

$checkStmt = $conn->prepare(
    'SELECT id FROM clients WHERE user_id = ? AND client_email = ? AND id != ? LIMIT 1'
);
$checkStmt->bind_param('isi', $userId, $clientEmail, $clientId);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'This client email is already registered in your account.'
    ]);
    $checkStmt->close();
    $conn->close();
    exit;
}

$checkStmt->close();

$stmt = $conn->prepare(
    'UPDATE clients
     SET client_name = ?, client_email = ?, client_phone = ?, company_name = ?, client_address = ?
     WHERE id = ? AND user_id = ?'
);
$stmt->bind_param(
    'sssssii',
    $clientName,
    $clientEmail,
    $clientPhone,
    $companyName,
    $clientAddress,
    $clientId,
    $userId
);

if ($stmt->execute() && $stmt->affected_rows >= 0) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Client updated successfully.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Client update failed. Please try again.'
    ]);
}

$stmt->close();
$conn->close();
