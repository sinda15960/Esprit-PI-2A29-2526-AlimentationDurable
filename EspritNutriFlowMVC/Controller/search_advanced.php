<?php
require_once __DIR__ . '/AllergieController.php';

$controller = new AllergieController();
$nom = $_GET['nom'] ?? '';
$categorie = $_GET['categorie'] ?? '';
$gravite = $_GET['gravite'] ?? '';

$results = $controller->searchAdvanced($nom, $categorie, $gravite);
header('Content-Type: application/json');
echo json_encode($results);
?>