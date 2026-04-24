<?php
require_once __DIR__ . '/FeedbackController.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';
    $message = trim($_POST['message'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    $errors = [];
    
    if (empty($message)) {
        $errors[] = "message_obligatoire";
    } elseif (strlen($message) < 5) {
        $errors[] = "message_trop_court";
    }
    
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "email_invalide";
    }
    
    if (!empty($errors)) {
        header('Location: ../View/FrontOffice/front_allergie_traitement.php?error=' . $errors[0]);
        exit();
    }
    
    $controller = new FeedbackController();
    
    if ($controller->addFeedback($type, $message, $email)) {
        header('Location: ../View/FrontOffice/front_allergie_traitement.php?success=1');
    } else {
        header('Location: ../View/FrontOffice/front_allergie_traitement.php?error=erreur_envoi');
    }
    exit();
}
?>