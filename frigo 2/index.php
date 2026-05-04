<?php
session_start();
require_once 'config/database.php';

spl_autoload_register(function($class) {
    $paths = [
        'app/model/',
        'app/controller/',
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
    'produit'      => 'ProduitController',
    'categorie'    => 'CategorieController',
    'commande'     => 'CommandeController',
    'favori'       => 'FavoriController',
    'statistique'  => 'StatistiqueController',
];

$controllerClass = $map[$controller] ?? null;

if (!$controllerClass) {
    die("Contrôleur introuvable : $controller");
}

$found = false;
foreach (['app/controller/', 'app/contoller/'] as $path) {
    $file = $path . $controllerClass . '.php';
    if (file_exists($file)) {
        require_once $file;
        $found = true;
        break;
    }
}

if (!$found) {
    die("Fichier contrôleur introuvable : $controllerClass");
}

$actionsAutorisees = [
    // ProduitController
    'frigo', 'index', 'create', 'store', 'edit', 'update',
    'delete', 'ajouterFrigo', 'ajouterManuel', 'supprimerDuFrigo',
    'envoyerAuPanier', 'modifierQuantiteFrigo', 'ajouterFrigoQR',
    'ajouterParScan', 'rechercherFrigo',
    'ajouterParVoix', 'confirmerAjoutVoix',  // Actions voix off
    
    // CommandeController
    'panier', 'ajouterPanier', 'modifierPanier', 'retirerPanier',
    'checkout', 'confirmer', 'annuler', 'updateCommande',
    'deleteCommande', 'appliquerPromo', 'supprimerPromo',
    'ajouterPromo', 'togglePromo', 'supprimerCodePromo',
    'genererCodeBienvenue',
    
    // CategorieController
    'admin', 
    
    // FavoriController
    'ajouter', 'supprimer', 'ajouterAuPanier',
    
    // StatistiqueController
    'exportCSV',
];

$ctrl = new $controllerClass();

if (!in_array($action, $actionsAutorisees) || !method_exists($ctrl, $action)) {
    die("Action introuvable : $action");
}

$ctrl->$action();