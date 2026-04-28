<?php
session_start();
require_once __DIR__ . '/UrgenceController.php';

error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    $data = $_POST;
}

if (!isset($_SESSION['urgence_session_id'])) {
    $_SESSION['urgence_session_id'] = session_id() . '_urg_' . time();
}
$session_id = $_SESSION['urgence_session_id'];

$latitude = isset($data['latitude']) ? floatval($data['latitude']) : null;
$longitude = isset($data['longitude']) ? floatval($data['longitude']) : null;

if (!$latitude || !$longitude) {
    echo json_encode(['success' => false, 'message' => 'Position GPS manquante']);
    exit();
}

$controller = new UrgenceController();
$result = $controller->sendSOS($session_id, $latitude, $longitude);

echo json_encode($result);
?>