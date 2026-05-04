<?php
echo "Test d'envoi d'email<br>";

// Test simple avec mail()
$to = "ton.email@gmail.com"; // Remplace par ton email
$subject = "Test NutriFlow AI";
$message = "Ceci est un test d'envoi d'email depuis NutriFlow AI";
$headers = "From: noreply@nutriflow.ai\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

if(mail($to, $subject, $message, $headers)) {
    echo "✅ Email envoyé avec succès !";
} else {
    echo "❌ Erreur lors de l'envoi. Vérifie la configuration PHP mail().";
}
?>