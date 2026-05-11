<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: donate_public.php');
    exit();
}

require_once __DIR__ . '/Don.php';

$type = $_POST['donation_type'] ?? '';
$amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0;
$foodType = trim((string)($_POST['food_type'] ?? ''));
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

$data = [
    'association_id' => (int)($_POST['association_id'] ?? 0),
    'donor_name' => htmlspecialchars(trim((string)($_POST['donor_name'] ?? '')), ENT_QUOTES, 'UTF-8'),
    'donor_email' => trim((string)($_POST['donor_email'] ?? '')),
    'donor_phone' => trim((string)($_POST['donor_phone'] ?? '')),
    'amount' => $type === 'monetary' ? $amount : 0,
    'donation_type' => $type ?: 'monetary',
    'food_type' => $type === 'food' ? $foodType : '',
    'quantity' => $type === 'food' ? $quantity : 0,
    'message' => htmlspecialchars(trim((string)($_POST['message'] ?? '')), ENT_QUOTES, 'UTF-8'),
    'payment_method' => $_POST['payment_method'] ?? 'bank_transfer',
];

if ($data['association_id'] < 1 || $data['donor_name'] === '' || !filter_var($data['donor_email'], FILTER_VALIDATE_EMAIL)) {
    $_SESSION['donation_error'] = 'Please check organization, name and email.';
    header('Location: donate_public.php');
    exit();
}

if (!in_array($data['donation_type'], ['monetary', 'food', 'equipment'], true)) {
    $_SESSION['donation_error'] = 'Invalid donation type.';
    header('Location: donate_public.php');
    exit();
}

if (!in_array($data['payment_method'], ['card', 'paypal', 'bank_transfer'], true)) {
    $data['payment_method'] = 'bank_transfer';
}

try {
    $don = new Don();
    if ($don->create($data)) {
        $_SESSION['donation_success'] = 'Thank you! Your donation was saved.';
        header('Location: donations_hub.php?thanks=1');
        exit();
    }
} catch (Throwable $e) {
    $_SESSION['donation_error'] = 'Database error: ' . $e->getMessage();
}

$_SESSION['donation_error'] = $_SESSION['donation_error'] ?? 'Could not save donation.';
header('Location: donate_public.php');
exit();
