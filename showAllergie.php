<?php
require_once __DIR__ . '/../../Model/Allergie.php';
require_once __DIR__ . '/Controller/AllergieController.php';

// Création d'un objet Allergie (comme demandé par la prof)
$allergie = new Allergie(
    "Gluten",
    "Alimentaire",
    "Intolérance au gluten, maladie cœliaque",
    "Ballonnements, douleurs abdominales, fatigue",
    "Blé, orge, seigle, avoine",
    "severe"
);
$allergie->setId(1);

echo "<h2>Test de la méthode show() de la classe Allergie</h2>";
$allergie->show();

echo "<h2>Test avec var_dump()</h2>";
echo "<pre>";
var_dump($allergie);
echo "</pre>";

echo "<h2>Test avec showBook() du contrôleur</h2>";
$controller = new AllergieController();
$controller->showBook($allergie);
?>