<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/../../model/Feedback.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? 0;

switch ($action) {
    case 'approve':
        Feedback::updateStatus($id, 'approuve');
        break;
    case 'reject':
        Feedback::updateStatus($id, 'rejete');
        break;
    case 'delete':
        Feedback::delete($id);
        break;
}

header('Location: dashboard.php');
exit();
?>