<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/ChatbotController.php';

$data = json_decode(file_get_contents('php://input'), true);
$message = $data['message'] ?? '';
$session_id = $data['session_id'] ?? session_id();

if (empty($message)) {
    echo json_encode(['success' => false, 'response' => 'Veuillez entrer un message.']);
    exit();
}

$controller = new ChatbotController();
$result = $controller->processMessage($message, $session_id);

echo json_encode($result);
?>