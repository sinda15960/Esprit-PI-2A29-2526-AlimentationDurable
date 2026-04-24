<?php
session_start();
require_once __DIR__ . '/AllergieController.php';

if (isset($_GET['id'])) {
    $allergie_id = $_GET['id'];
    $controller = new AllergieController();
    $allergie = $controller->getAllergieById($allergie_id);
    
    if ($allergie) {
        $image = $allergie['image_url'];
        if ($image && file_exists(__DIR__ . '/../' . $image)) {
            if (unlink(__DIR__ . '/../' . $image)) {
                $_SESSION['success'] = "✅ Image supprimée avec succès pour " . htmlspecialchars($allergie['nom']) . " !";
            } else {
                $_SESSION['error'] = "❌ Impossible de supprimer le fichier image";
            }
        } else {
            $_SESSION['error'] = "❌ Aucune image trouvée pour cette allergie";
        }
        
        $controller->updateImage($allergie_id, null);
    } else {
        $_SESSION['error'] = "❌ Allergie non trouvée";
    }
}

header('Location: ../View/BackOffice/back_allergie_traitement.php');
exit();
?>