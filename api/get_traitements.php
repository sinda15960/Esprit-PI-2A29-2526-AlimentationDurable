<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

require_once __DIR__ . '/../Controller/TraitementController.php';

$controller = new TraitementController();
$result = $controller->getAllTraitements();
echo json_encode($result);
?>