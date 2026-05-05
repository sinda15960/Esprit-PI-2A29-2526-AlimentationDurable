<?php
require_once __DIR__ . '/TraitementController.php';

$allergie_id = $_GET['allergie_id'] ?? 0;
$controller = new TraitementController();
$traitement = $controller->getTraitementByAllergieId($allergie_id);

header('Content-Type: application/json');
echo json_encode($traitement ?: null);
?>