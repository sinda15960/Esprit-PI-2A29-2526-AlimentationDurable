<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

require_once __DIR__ . '/../Controller/TraitementController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller = new TraitementController();
    
    // Si on reçoit allergie_nom au lieu de allergie_id
    if (isset($data['allergie_nom']) && !isset($data['allergie_id'])) {
        require_once __DIR__ . '/../model/Allergie.php';
        $allergie = Allergie::findByNom($data['allergie_nom']);
        if ($allergie) {
            $data['allergie_id'] = $allergie->getId();
        } else {
            echo json_encode(['success' => false, 'message' => 'Allergie non trouvée']);
            exit();
        }
    }
    
    $result = $controller->addTraitement($data);
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}
?>