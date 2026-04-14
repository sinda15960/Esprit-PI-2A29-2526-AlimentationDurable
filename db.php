<?php
// Configuration de la base de données
$host = 'localhost'; // Hôte (souvent localhost sous XAMPP/WAMP)
$dbname = 'donsolidaire_db'; // Nom de la base de données que vous avez créée
$username = 'root'; // Utilisateur par défaut de MySQL (XAMPP/WAMP)
$password = ''; // Mot de passe par défaut de MySQL (vide sous XAMPP/WAMP)

try {
    // Création de l'instance PDO pour se connecter à la BDD
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configuration des options PDO pour une meilleure gestion des erreurs
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    // En cas d'erreur de connexion, le script s'arrête et affiche l'erreur
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
