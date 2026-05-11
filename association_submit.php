<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: organization_create_public.php');
    exit();
}

require_once __DIR__ . '/Association.php';

$data = [
    'name' => trim((string)($_POST['name'] ?? '')),
    'email' => trim((string)($_POST['email'] ?? '')),
    'phone' => trim((string)($_POST['phone'] ?? '')),
    'address' => trim((string)($_POST['address'] ?? '')),
    'city' => trim((string)($_POST['city'] ?? '')),
    'postal_code' => trim((string)($_POST['postal_code'] ?? '')),
    'siret' => trim((string)($_POST['siret'] ?? '')),
    'mission' => trim((string)($_POST['mission'] ?? '')),
];

if (strlen($data['name']) < 3 || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $_SESSION['assoc_error'] = 'Please check name and email.';
    header('Location: organization_create_public.php');
    exit();
}

try {
    $model = new Association();
    if ($model->create($data)) {
        $_SESSION['success'] = 'Organization created.';
        header('Location: donations_admin.php');
        exit();
    }
} catch (Throwable $e) {
    $_SESSION['assoc_error'] = $e->getMessage();
}

$_SESSION['assoc_error'] = $_SESSION['assoc_error'] ?? 'Could not create organization.';
header('Location: organization_create_public.php');
exit();
