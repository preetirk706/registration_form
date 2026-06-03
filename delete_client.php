<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: clients.php');
    exit;
}

require_once __DIR__ . '/db.php';

$userId = (int) $_SESSION['user_id'];
$clientId = (int) ($_POST['id'] ?? 0);

if ($clientId > 0) {
    $stmt = $conn->prepare('DELETE FROM clients WHERE id = ? AND user_id = ?');
    $stmt->bind_param('ii', $clientId, $userId);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

header('Location: clients.php');
exit;
