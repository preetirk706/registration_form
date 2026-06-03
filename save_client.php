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
$clientName = trim((string) ($_POST['client_name'] ?? ''));
$clientEmail = trim((string) ($_POST['client_email'] ?? ''));
$clientPhone = trim((string) ($_POST['client_phone'] ?? ''));
$companyName = trim((string) ($_POST['company_name'] ?? ''));
$clientAddress = trim((string) ($_POST['client_address'] ?? ''));

if ($clientName === '' || $clientEmail === '' || $clientPhone === '' || $clientAddress === '') {
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

$checkStmt = $conn->prepare('SELECT id FROM clients WHERE user_id = ? AND client_email = ? LIMIT 1');
$checkStmt->bind_param('is', $userId, $clientEmail);
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
    'INSERT INTO clients (user_id, client_name, client_email, client_phone, company_name, client_address)
     VALUES (?, ?, ?, ?, ?, ?)'
);
$stmt->bind_param('isssss', $userId, $clientName, $clientEmail, $clientPhone, $companyName, $clientAddress);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Client registered successfully.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Client registration failed. Please try again.'
    ]);
}

$stmt->close();
$conn->close();
