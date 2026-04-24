<?php
require_once __DIR__ . '/RatingController.php';

$data = json_decode(file_get_contents('php://input'), true);
$allergie_id = $data['allergie_id'] ?? 0;
$note = $data['note'] ?? 0;
$ip_address = $_SERVER['REMOTE_ADDR'];

$controller = new RatingController();

if ($controller->hasRated($allergie_id, $ip_address)) {
    echo json_encode(['success' => false, 'message' => 'Vous avez déjà noté ce traitement']);
    exit();
}

if ($note < 1 || $note > 5) {
    echo json_encode(['success' => false, 'message' => 'Note invalide']);
    exit();
}

if ($controller->addRating($allergie_id, $note, $ip_address)) {
    $rating = $controller->getAverageRating($allergie_id);
    echo json_encode(['success' => true, 'moyenne' => number_format($rating['moyenne'] ?? 0, 1)]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement']);
}
?>