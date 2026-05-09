<?php
session_start();
header('Content-Type: application/json');

// Chemin corrigé vers MailController
require_once dirname(__DIR__) . '/../controllers/MailController.php';

$response = ['success' => false, 'message' => ''];

try {
    $mailController = new MailController();
    $result = $mailController->sendWeeklyReport();
    
    if ($result) {
        $response['success'] = true;
        $response['message'] = 'Rapport généré avec succès !';
    } else {
        $response['message'] = 'Erreur lors de la génération du rapport';
    }
} catch(Exception $e) {
    $response['message'] = 'Erreur: ' . $e->getMessage();
}

echo json_encode($response);
?>