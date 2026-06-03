<?php
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

function clean_input($value)
{
    return trim((string) $value);
}

$firstName = clean_input($_POST['first_name'] ?? '');
$lastName = clean_input($_POST['last_name'] ?? '');
$email = clean_input($_POST['email'] ?? '');
$phone = clean_input($_POST['phone'] ?? '');
$password = (string) ($_POST['password'] ?? '');
$confirmPassword = (string) ($_POST['confirm_password'] ?? '');
$gender = clean_input($_POST['gender'] ?? '');
$city = clean_input($_POST['city'] ?? '');
$address = clean_input($_POST['address'] ?? '');

if (
    $firstName === '' ||
    $lastName === '' ||
    $email === '' ||
    $phone === '' ||
    $password === '' ||
    $confirmPassword === '' ||
    $gender === '' ||
    $city === '' ||
    $address === ''
) {
    echo json_encode([
        'status' => 'error',
        'message' => 'All fields are required.'
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

if (strlen($password) < 6) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Password must be at least 6 characters.'
    ]);
    exit;
}

if ($password !== $confirmPassword) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Password and confirm password do not match.'
    ]);
    exit;
}

$checkStmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$checkStmt->bind_param('s', $email);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'This email is already registered.'
    ]);
    $checkStmt->close();
    $conn->close();
    exit;
}

$checkStmt->close();

$insertStmt = $conn->prepare(
    'INSERT INTO users (first_name, last_name, email, phone, password, gender, city, address)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
);
$insertStmt->bind_param(
    'ssssssss',
    $firstName,
    $lastName,
    $email,
    $phone,
    $password,
    $gender,
    $city,
    $address
);

if ($insertStmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Registration successful.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Registration failed. Please try again.'
    ]);
}

$insertStmt->close();
$conn->close();
