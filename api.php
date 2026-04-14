<?php
// Définir les en-têtes pour autoriser les requêtes (CORS) et retourner du JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Autorise l'accès depuis n'importe quelle adresse
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Inclure le fichier de connexion à la base de données
// Assurez-vous que db.php est dans le même dossier
require_once 'db.php';

// Vérifier quelle action est demandée en paramètre GET (ex: api.php?action=associations)
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'associations':
        // Récupérer toutes les associations
        // Le bloc try/catch est utile pour éviter que le site plante si la requête échoue
        try {
            $stmt = $pdo->query('SELECT * FROM associations ORDER BY id DESC');
            $associations = $stmt->fetchAll();
            echo json_encode($associations);
        } catch(PDOException $e) {
            echo json_encode(['error' => 'Erreur lors de la récupération des associations', 'details' => $e->getMessage()]);
        }
        break;

    case 'dons':
        // Récupérer tous les dons
        try {
            $stmt = $pdo->query('SELECT * FROM dons ORDER BY date DESC');
            $dons = $stmt->fetchAll();
            echo json_encode($dons);
        } catch(PDOException $e) {
            echo json_encode(['error' => 'Erreur lors de la récupération des dons', 'details' => $e->getMessage()]);
        }
        break;

    default:
        // Si aucune action valide n'est fournie, renvoyer une erreur
        echo json_encode([
            'status' => 'error', 
            'message' => 'Action non valide. Veuillez spécifier ?action=associations ou ?action=dons'
        ]);
        break;
}
?>
