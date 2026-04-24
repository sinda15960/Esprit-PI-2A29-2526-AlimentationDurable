<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

require_once __DIR__ . '/../Controller/FeedbackController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller = new FeedbackController();
    $result = $controller->addFeedback($data);
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}
?>