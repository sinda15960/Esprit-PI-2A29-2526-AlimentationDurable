<?php
require_once __DIR__ . '/model/UrgenceContact.php';
require_once __DIR__ . '/../Config/Database.php';

class UrgenceController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getContacts($session_id) {
        $stmt = $this->db->prepare("SELECT * FROM urgence_contacts WHERE session_id = ? ORDER BY is_primary DESC, id ASC");
        $stmt->execute([$session_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function addContact($session_id, $nom, $relation, $telephone, $email = null) {
        $stmt = $this->db->prepare("
            INSERT INTO urgence_contacts (session_id, nom, relation, telephone, email)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$session_id, $nom, $relation, $telephone, $email]);
    }
    
    public function deleteContact($id, $session_id) {
        $stmt = $this->db->prepare("DELETE FROM urgence_contacts WHERE id = ? AND session_id = ?");
        return $stmt->execute([$id, $session_id]);
    }
    
    public function setPrimaryContact($id, $session_id) {
        $stmt = $this->db->prepare("UPDATE urgence_contacts SET is_primary = FALSE WHERE session_id = ?");
        $stmt->execute([$session_id]);
        $stmt = $this->db->prepare("UPDATE urgence_contacts SET is_primary = TRUE WHERE id = ? AND session_id = ?");
        return $stmt->execute([$id, $session_id]);
    }
    
    public function getUserProfile($session_id) {
        $stmt = $this->db->prepare("SELECT nom, prenom, telephone FROM user_profiles WHERE session_id = ?");
        $stmt->execute([$session_id]);
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$profile) {
            return ['prenom' => 'Utilisateur', 'nom' => '', 'telephone' => 'Non renseigné'];
        }
        return $profile;
    }
    
    public function getCriticalAllergies($session_id) {
        $stmt = $this->db->prepare("
            SELECT a.nom, a.gravite, t.medicaments_urgence 
            FROM user_profiles up
            JOIN allergies a ON FIND_IN_SET(a.id, up.critical_allergies)
            LEFT JOIN traitements t ON a.id = t.allergie_id
            WHERE up.session_id = ?
            LIMIT 3
        ");
        $stmt->execute([$session_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function sendSOS($session_id, $latitude, $longitude) {
        $contacts = $this->getContacts($session_id);
        if (empty($contacts)) {
            return ['success' => false, 'message' => 'Aucun contact configuré'];
        }
        
        $mapsLink = "https://www.google.com/maps?q={$latitude},{$longitude}";
        $userProfile = $this->getUserProfile($session_id);
        $allergiesCritiques = $this->getCriticalAllergies($session_id);
        
        $notified = [];
        
        foreach ($contacts as $contact) {
            if (!empty($contact['telephone'])) {
                $notified[] = $contact['telephone'];
                $this->sendSMSAlert($contact, $userProfile, $allergiesCritiques, $mapsLink, $latitude, $longitude);
            }
        }
        
        return [
            'success' => true,
            'maps_link' => $mapsLink,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'contacts' => $notified
        ];
    }
    
    private function sendSMSAlert($contact, $user, $allergies, $mapsLink, $lat, $lng) {
        $nomComplet = trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? ''));
        if (empty($nomComplet)) $nomComplet = 'Un utilisateur NutriFlow';
        
        $phone = preg_replace('/[^0-9]/', '', $contact['telephone']);
        
        $allergiesText = "";
        foreach ($allergies as $a) {
            $medicament = $a['medicaments_urgence'] ?? 'Consulter médecin';
            $allergiesText .= "• " . $a['nom'] . ": " . $medicament . "\n";
        }
        
        $message = "🚨 URGENCE ALLERGIQUE - {$nomComplet} a besoin d'aide !\n\n";
        $message .= "📍 Position: {$mapsLink}\n";
        $message .= "📍 Coordonnées GPS: {$lat}, {$lng}\n\n";
        $message .= "📋 Allergies critiques:\n{$allergiesText}\n";
        $message .= "📞 Téléphone: " . ($user['telephone'] ?? 'Non renseigné') . "\n\n";
        $message .= "🚨 CONDUITE À TENIR:\n";
        $message .= "1. Appelez le 15 (SAMU)\n";
        $message .= "2. Aidez la personne à prendre son EpiPen\n";
        $message .= "3. Allongez-la, jambes surélevées\n";
        $message .= "4. Ne la laissez pas seule\n\n";
        $message .= "---\nNutriFlow AI - Alerte automatique";
        
        // Sauvegarder le SMS
        $logFile = __DIR__ . '/../sms_envoyes.txt';
        $logContent = "========================================\n";
        $logContent .= "Date: " . date('Y-m-d H:i:s') . "\n";
        $logContent .= "À: {$contact['nom']} ({$contact['relation']})\n";
        $logContent .= "Téléphone: {$phone}\n";
        $logContent .= "========================================\n";
        $logContent .= $message . "\n\n";
        file_put_contents($logFile, $logContent, FILE_APPEND);
        
        error_log("SMS envoyé à {$phone}: " . substr($message, 0, 100));
        
        return true;
    }
}
?>