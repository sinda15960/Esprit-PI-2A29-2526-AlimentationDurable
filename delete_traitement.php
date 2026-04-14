<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

require_once __DIR__ . '/../Controller/TraitementController.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? 0;
    
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'ID du traitement manquant']);
        exit();
    }
    
    $controller = new TraitementController();
    $result = $controller->deleteTraitement($id);
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}
?>