<?php
require_once __DIR__ . '/../model/UserProfile.php';
require_once __DIR__ . '/../Config/Database.php';

class ProfileController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Créer ou mettre à jour un profil
    public function saveProfile($session_id, $data) {
        // Vérifier si le profil existe déjà
        $stmt = $this->db->prepare("SELECT id FROM user_profiles WHERE session_id = ?");
        $stmt->execute([$session_id]);
        $existing = $stmt->fetch();
        
        $selected_allergies = is_array($data['selected_allergies']) 
            ? implode(',', $data['selected_allergies']) 
            : $data['selected_allergies'];
        
        $critical_allergies = is_array($data['critical_allergies'] ?? []) 
            ? implode(',', $data['critical_allergies']) 
            : ($data['critical_allergies'] ?? '');
        
        if ($existing) {
            // Mettre à jour
            $stmt = $this->db->prepare("
                UPDATE user_profiles 
                SET nom = ?, prenom = ?, date_naissance = ?, telephone = ?, 
                    medicament_urgence = ?, selected_allergies = ?, critical_allergies = ?
                WHERE session_id = ?
            ");
            return $stmt->execute([
                $data['nom'], $data['prenom'], $data['date_naissance'], $data['telephone'],
                $data['medicament_urgence'], $selected_allergies, $critical_allergies, $session_id
            ]);
        } else {
            // Créer
            $stmt = $this->db->prepare("
                INSERT INTO user_profiles (session_id, nom, prenom, date_naissance, telephone, 
                                          medicament_urgence, selected_allergies, critical_allergies)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            return $stmt->execute([
                $session_id, $data['nom'], $data['prenom'], $data['date_naissance'], $data['telephone'],
                $data['medicament_urgence'], $selected_allergies, $critical_allergies
            ]);
        }
    }
    
    // Récupérer un profil par session
    public function getProfile($session_id) {
        $stmt = $this->db->prepare("SELECT * FROM user_profiles WHERE session_id = ?");
        $stmt->execute([$session_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $row['selected_allergies_array'] = $row['selected_allergies'] ? explode(',', $row['selected_allergies']) : [];
            $row['critical_allergies_array'] = $row['critical_allergies'] ? explode(',', $row['critical_allergies']) : [];
        }
        return $row;
    }
    
    // Récupérer les allergies critiques avec leurs traitements
    public function getCriticalAllergiesWithTreatments($session_id) {
        $profile = $this->getProfile($session_id);
        if (!$profile || empty($profile['critical_allergies'])) {
            return [];
        }
        
        $ids = $profile['critical_allergies_array'];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        $stmt = $this->db->prepare("
            SELECT a.id, a.nom, a.gravite, a.symptomes, 
                   t.conseil, t.medicaments, t.medicaments_urgence, t.niveau_urgence
            FROM allergies a
            LEFT JOIN traitements t ON a.id = t.allergie_id
            WHERE a.id IN ($placeholders)
            ORDER BY FIELD(a.gravite, 'severe', 'moderate', 'legere')
        ");
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>