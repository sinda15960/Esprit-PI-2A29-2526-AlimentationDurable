<?php
session_start();
require_once __DIR__ . '/TraitementController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $allergie_id = $_POST['allergie_id'];
    $conseil = trim($_POST['conseil']);
    $interdits = trim($_POST['interdits']);
    $medicaments = trim($_POST['medicaments'] ?? '');
    $duree = trim($_POST['duree'] ?? '');
    $niveau_urgence = $_POST['niveau_urgence'];
    
    $errors = [];
    
    if (empty($conseil) || strlen($conseil) < 10) {
        $errors[] = "Les conseils doivent contenir au moins 10 caractères";
    }
    if (empty($interdits) || strlen($interdits) < 5) {
        $errors[] = "Les interdits doivent contenir au moins 5 caractères";
    }
    if (empty($niveau_urgence)) {
        $errors[] = "Le niveau d'urgence est obligatoire";
    }
    
    if (!empty($errors)) {
        $_SESSION['traitement_error'] = implode(', ', $errors);
        header('Location: ../View/BackOffice/back_allergie_traitement.php');
        exit();
    }
    
    $controller = new TraitementController();
    $result = $controller->saveTraitement($allergie_id, $conseil, $interdits, $medicaments, $duree, $niveau_urgence);
    
    if ($result) {
        $_SESSION['success'] = "✅ Traitement sauvegardé avec succès !";
    } else {
        $_SESSION['error'] = "❌ Erreur lors de la sauvegarde";
    }
    
    header('Location: ../View/BackOffice/back_allergie_traitement.php');
    exit();
}
?>