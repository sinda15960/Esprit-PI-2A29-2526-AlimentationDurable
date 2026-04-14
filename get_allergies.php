<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../Controller/AllergieController.php';

$controller = new AllergieController();
$result = $controller->getAllAllergies();

echo json_encode($result);
?>