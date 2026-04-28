<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/../../Model/Allergie.php';

$id = $_GET['id'] ?? 0;
$allergie = Allergie::findById($id);

if ($allergie) {
    $allergie->delete();
}

header('Location: dashboard.php');
exit();
?>