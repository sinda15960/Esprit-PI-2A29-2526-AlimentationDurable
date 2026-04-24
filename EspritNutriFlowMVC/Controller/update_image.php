<?php
session_start();
require_once __DIR__ . '/AllergieController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    
    $allergie_id = $_POST['allergie_id'];
    $controller = new AllergieController();
    $allergie = $controller->getAllergieById($allergie_id);
    
    if (!$allergie) {
        $_SESSION['error'] = "❌ Allergie non trouvée";
        header('Location: ../View/BackOffice/back_allergie_traitement.php');
        exit();
    }
    
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = "❌ Erreur lors de l'upload du fichier";
        header('Location: ../View/BackOffice/back_allergie_traitement.php');
        exit();
    }
    
    // Créer le dossier s'il n'existe pas
    $target_dir = __DIR__ . '/../uploads/allergies/';
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    // Vérifier l'extension
    $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!in_array($file_extension, $allowed_extensions)) {
        $_SESSION['error'] = "❌ Format non autorisé. Utilisez JPG, PNG, GIF ou WEBP.";
        header('Location: ../View/BackOffice/back_allergie_traitement.php');
        exit();
    }
    
    // Supprimer l'ancienne image
    $old_image = $controller->getImage($allergie_id);
    if ($old_image && file_exists(__DIR__ . '/../' . $old_image)) {
        unlink(__DIR__ . '/../' . $old_image);
    }
    
    // Générer un nouveau nom
    $new_filename = "allergie_" . $allergie_id . "_" . time() . "_" . rand(1000, 9999) . "." . $file_extension;
    $target_file = $target_dir . $new_filename;
    $relative_path = "uploads/allergies/" . $new_filename;
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        if ($controller->updateImage($allergie_id, $relative_path)) {
            $_SESSION['success'] = "✅ Image ajoutée avec succès pour " . htmlspecialchars($allergie['nom']) . " !";
        } else {
            $_SESSION['error'] = "❌ Erreur lors de l'enregistrement en base de données";
        }
    } else {
        $_SESSION['error'] = "❌ Erreur lors du déplacement du fichier";
    }
}

header('Location: ../View/BackOffice/back_allergie_traitement.php');
exit();
?>