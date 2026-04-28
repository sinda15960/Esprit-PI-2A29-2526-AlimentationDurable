<?php
require_once __DIR__ . '/AllergieController.php';

$id = $_GET['id'] ?? 0;
$controller = new AllergieController();
$allergie = $controller->getAllergieById($id);

header('Content-Type: application/json');
echo json_encode($allergie);
?>