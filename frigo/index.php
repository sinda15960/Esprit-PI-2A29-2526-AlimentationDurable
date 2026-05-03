<?php
session_start();
require_once 'config/database.php';

spl_autoload_register(function($class) {
    $paths = [
        'app/model/',
        'app/controller/',  // Vérifiez que c'est bien "controller" et non "contoller"
    ];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

$mode = $_GET['mode'] ?? 'front';
$controller = $_GET['controller'] ?? 'produit';
$action = $_GET['action'] ?? 'frigo';

$map = [
    'produit'     => 'ProduitController',
    'categorie'   => 'CategorieController',
    'commande'    => 'CommandeController',
    'favori'      => 'FavoriController',
    'statistique' => 'StatistiqueController',
];

$controllerClass = $map[$controller] ?? null;

if (!$controllerClass) {
    die("Contrôleur introuvable.");
}

// Correction du chemin : utilisez le bon dossier
$controllerFile = 'app/controller/' . $controllerClass . '.php';

if (!file_exists($controllerFile)) {
    die("Fichier contrôleur introuvable: " . $controllerFile);
}

require_once $controllerFile;

$ctrl = new $controllerClass();

$actionsAutorisees = [
    // Produit
    'frigo', 'index', 'create', 'store', 'edit', 'update',
    'delete', 'ajouterFrigo', 'ajouterManuel', 'supprimerDuFrigo',
    'envoyerAuPanier', 'modifierQuantiteFrigo', 'ajouterFrigoQR',
    'rechercherFrigo', 'ajouterParScan', 'genererSuggestionsFrigo',
    // Commande
    'panier', 'ajouterPanier', 'modifierPanier', 'retirerPanier',
    'checkout', 'confirmer', 'annuler', 'updateCommande',
    'deleteCommande', 'appliquerPromo', 'supprimerPromo',
    'ajouterPromo', 'togglePromo', 'supprimerCodePromo',
    'genererCodeBienvenue', 'genererCodeRelance',
    // Categorie
    'admin', 'store', 'edit', 'update', 'delete',
    // Favori
    'ajouter', 'supprimer', 'ajouterAuPanier',
    // Statistique
    'index', 'exportCSV'
];

if (!in_array($action, $actionsAutorisees) || !method_exists($ctrl, $action)) {
    die("Action introuvable: $action");
}

$ctrl->$action();
?>