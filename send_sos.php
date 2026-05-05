<?php
session_start();
require_once __DIR__ . '/UrgenceController.php';
 
error_reporting(0);
ini_set('display_errors', 0);
 
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
 
$input = file_get_contents('php://input');
$data = json_decode($input, true);
 
if (!$data) {
    $data = $_POST;
}
 
if (!isset($_SESSION['urgence_session_id'])) {
    $_SESSION['urgence_session_id'] = session_id() . '_urg_' . time();
}
$session_id = $_SESSION['urgence_session_id'];
 
if (!isset($data['latitude']) || !isset($data['longitude']) || !is_numeric($data['latitude']) || !is_numeric($data['longitude'])) {
    echo json_encode(['success' => false, 'message' => 'Position GPS manquante ou invalide']);
    exit();
}
 
$latitude  = floatval($data['latitude']);
$longitude = floatval($data['longitude']);
 
if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
    echo json_encode(['success' => false, 'message' => 'Coordonnées GPS hors limites']);
    exit();
}
 
$controller = new UrgenceController();
$result = $controller->sendSOS($session_id, $latitude, $longitude);
 
$result['latitude']  = $latitude;
$result['longitude'] = $longitude;
$result['maps_link'] = 'https://www.google.com/maps?q=' . $latitude . ',' . $longitude;
 
echo json_encode($result);
?>