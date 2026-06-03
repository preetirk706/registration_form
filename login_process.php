<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
    exit;
}

require_once __DIR__ . '/db.php';

$email = trim((string) ($_POST['email'] ?? ''));
$password = (string) ($_POST['password'] ?? '');

if ($email === '' || $password === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Email and password are required.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please enter a valid email address.'
    ]);
    exit;
}

$stmt = $conn->prepare(
    'SELECT id, first_name, last_name, email, password
     FROM users
     WHERE email = ?
     LIMIT 1'
);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo json_encode([
        'status' => 'error',
        'type' => 'account_not_found',
        'message' => 'This email ID is not registered.'
    ]);
    $stmt->close();
    $conn->close();
    exit;
}

$passwordMatches = $user && (
    password_verify($password, $user['password']) ||
    $password === $user['password']
);

if (!$passwordMatches) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid email or password.'
    ]);
    $stmt->close();
    $conn->close();
    exit;
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int) $user['id'];
$_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
$_SESSION['user_email'] = $user['email'];

echo json_encode([
    'status' => 'success',
    'message' => 'Login successful.'
]);

$stmt->close();
$conn->close();
