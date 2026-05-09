<?php
// C:/xampp/htdocs/nutriflow-ai/ajax/send_report.php
session_start();
header('Content-Type: application/json');

// Version de test simple
$response = [
    'success' => true,
    'message' => 'Rapport hebdomadaire envoyé avec succès !'
];

// Ajoute une notification dans la session
if (!isset($_SESSION['notifications'])) {
    $_SESSION['notifications'] = [];
}

$_SESSION['notifications'][] = [
    'id' => time(),
    'title' => '📧 Rapport hebdomadaire',
    'message' => 'Le rapport a été envoyé avec succès',
    'type' => 'success',
    'icon' => 'fas fa-envelope',
    'time' => date('H:i:s'),
    'read' => false
];

echo json_encode($response);
?>