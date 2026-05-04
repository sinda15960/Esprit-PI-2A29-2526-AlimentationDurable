<?php
// Script à exécuter automatiquement chaque semaine
// Ce fichier doit être appelé par une tâche CRON

require_once dirname(__DIR__) . '/controllers/MailController.php';

// Activer l'affichage des erreurs pour le débogage (à désactiver en production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "[" . date('Y-m-d H:i:s') . "] Début de l'envoi du rapport hebdomadaire...\n";

try {
    $mailController = new MailController();
    
    // Envoyer le rapport à l'administrateur
    $result = $mailController->sendWeeklyReport();
    
    if ($result) {
        echo "[" . date('Y-m-d H:i:s') . "] ✅ Rapport hebdomadaire envoyé avec succès !\n";
    } else {
        echo "[" . date('Y-m-d H:i:s') . "] ❌ Erreur lors de l'envoi du rapport.\n";
    }
    
    // Optionnel : envoyer aussi à d'autres destinataires
    // $mailController->sendWeeklyReport('autre@email.com');
    
} catch(Exception $e) {
    echo "[" . date('Y-m-d H:i:s') . "] ❌ Erreur : " . $e->getMessage() . "\n";
}

echo "[" . date('Y-m-d H:i:s') . "] Fin du script.\n";
?>