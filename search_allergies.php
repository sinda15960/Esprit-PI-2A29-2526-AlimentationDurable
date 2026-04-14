<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

require_once __DIR__ . '/../Controller/AllergieController.php';

$term = $_GET['term'] ?? '';
$controller = new AllergieController();
$result = $controller->searchAllergies($term);
echo json_encode($result);
?>