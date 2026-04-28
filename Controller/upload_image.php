<?php
session_start();
require_once __DIR__ . '/../Config/Database.php';

$db = Database::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    
    $allergie_id = $_POST['allergie_id'];
    
    // Vérifier que l'allergie existe
    $check = $db->prepare("SELECT id, nom FROM allergies WHERE id = ?");
    $check->execute([$allergie_id]);
    $allergie = $check->fetch();
    
    if (!$allergie) {
        $_SESSION['error'] = "❌ Allergie non trouvée";
        header('Location: ../View/BackOffice/back_allergie_traitement.php');
        exit();
    }
    
    // Vérifier qu'un fichier a bien été uploadé
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
    
    // Supprimer l'ancienne image si elle existe
    $stmt = $db->prepare("SELECT image_url FROM allergies WHERE id = ?");
    $stmt->execute([$allergie_id]);
    $old_image = $stmt->fetchColumn();
    if ($old_image && file_exists(__DIR__ . '/../' . $old_image)) {
        unlink(__DIR__ . '/../' . $old_image);
    }
    
    // Générer un nouveau nom unique
    $new_filename = "allergie_" . $allergie_id . "_" . time() . "_" . rand(1000, 9999) . "." . $file_extension;
    $target_file = $target_dir . $new_filename;
    $relative_path = "uploads/allergies/" . $new_filename;
    
    // Déplacer le fichier
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // Mettre à jour la base de données
        $stmt = $db->prepare("UPDATE allergies SET image_url = ? WHERE id = ?");
        if ($stmt->execute([$relative_path, $allergie_id])) {
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