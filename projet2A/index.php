<?php
session_start();
require_once 'config/database.php';

spl_autoload_register(function($class) {
    $paths = [
        'app/model/',
        'app/contoller/',
    ];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

$controller = $_GET['controller'] ?? 'produit';
$action     = $_GET['action']     ?? 'frigo';

$map = [
    'produit'   => 'ProduitController',
    'categorie' => 'CategorieController',
    'commande'  => 'CommandeController',
];

$controllerClass = $map[$controller] ?? null;

if (!$controllerClass) {
    die("Contrôleur introuvable.");
}

require_once 'app/contoller/' . $controllerClass . '.php';

$ctrl = new $controllerClass();

// Liste de toutes les actions autorisées
$actionsAutorisees = [
    'frigo', 'index', 'create', 'store', 'edit', 'update',
    'delete', 'ajouterFrigo', 'ajouterManuel', 'supprimerDuFrigo',
    'envoyerAuPanier', 'modifierQuantiteFrigo',
    'panier', 'ajouterPanier', 'modifierPanier', 'retirerPanier',
    'checkout', 'confirmer', 'annuler', 'updateCommande',
    'deleteCommande',
    'admin', 'store', 'updateCommande'
];

if (!in_array($action, $actionsAutorisees) || !method_exists($ctrl, $action)) {
    die("Action introuvable.");
}

$ctrl->$action();