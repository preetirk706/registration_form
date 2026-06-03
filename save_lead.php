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
$leadName = trim((string) ($_POST['lead_name'] ?? ''));
$leadEmail = trim((string) ($_POST['lead_email'] ?? ''));
$leadPhone = trim((string) ($_POST['lead_phone'] ?? ''));
$leadSource = trim((string) ($_POST['lead_source'] ?? ''));
$leadStatus = trim((string) ($_POST['lead_status'] ?? 'New'));
$note = trim((string) ($_POST['note'] ?? ''));

$allowedStatuses = ['New', 'Contacted', 'Interested', 'Converted', 'Closed'];

if ($leadName === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Lead name is required.'
    ]);
    exit;
}

if ($leadEmail !== '' && !filter_var($leadEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please enter a valid email address.'
    ]);
    exit;
}

if (!in_array($leadStatus, $allowedStatuses, true)) {
    $leadStatus = 'New';
}

$stmt = $conn->prepare(
    'INSERT INTO leads (user_id, lead_name, lead_email, lead_phone, lead_source, lead_status, note)
     VALUES (?, ?, ?, ?, ?, ?, ?)'
);
$stmt->bind_param('issssss', $userId, $leadName, $leadEmail, $leadPhone, $leadSource, $leadStatus, $note);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Lead saved successfully.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Lead save failed. Please try again.'
    ]);
}

$stmt->close();
$conn->close();
