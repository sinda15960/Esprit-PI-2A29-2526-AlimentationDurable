<?php
// Configuration pour l'envoi d'emails
class MailConfig {
    // Configuration SMTP (pour XAMPP avec Gmail)
    public static $smtp_host = 'smtp.gmail.com';
    public static $smtp_port = 587;
    public static $smtp_auth = true;
    public static $smtp_username = '';  // Laisse vide pour utiliser mail()
    public static $smtp_password = '';  // Laisse vide pour utiliser mail()
    public static $smtp_secure = 'tls';
    
    // Destinataire par défaut
    public static $default_recipient = 'admin@nutriflow.ai';
    public static $default_recipient_name = 'Administrateur NutriFlow';
    
    // Expéditeur
    public static $from_email = 'noreply@nutriflow.ai';
    public static $from_name = 'NutriFlow AI';
}
?>