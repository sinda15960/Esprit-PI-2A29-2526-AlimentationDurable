<?php
require_once __DIR__ . '/../Model/Traitement.php';
require_once __DIR__ . '/../Model/Database.php';

class TraitementController {
    private $db_front;
    private $db_back;
    
    public function __construct() {
        $this->db_front = Database::getFrontConnection();
        $this->db_back = Database::getBackConnection();
    }
    
    // Récupérer tous les traitements
    public function getAllTraitements() {
        $stmt = $this->db_front->query("SELECT * FROM traitements ORDER BY allergie_nom");
        $traitements = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $traitement = new Traitement();
            $traitement->setId($row['id']);
            $traitement->setAllergieNom($row['allergie_nom']);
            $traitement->setConseil($row['conseil']);
            $traitement->setInterdits($row['interdits']);
            $traitement->setMedicaments($row['medicaments']);
            $traitement->setDuree($row['duree']);
            $traitement->setNiveauUrgence($row['niveau_urgence']);
            $traitements[] = $traitement;
        }
        return $traitements;
    }
    
    // Récupérer les traitements par allergie
    public function getTraitementByAllergie($allergie_nom) {
        $stmt = $this->db_front->prepare("SELECT * FROM traitements WHERE allergie_nom = ?");
        $stmt->execute([$allergie_nom]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $traitement = new Traitement();
            $traitement->setId($row['id']);
            $traitement->setAllergieNom($row['allergie_nom']);
            $traitement->setConseil($row['conseil']);
            $traitement->setInterdits($row['interdits']);
            $traitement->setMedicaments($row['medicaments']);
            $traitement->setDuree($row['duree']);
            $traitement->setNiveauUrgence($row['niveau_urgence']);
            return $traitement;
        }
        return null;
    }
    
    // Ajouter un traitement (BackOffice)
    public function addTraitement($allergie_nom, $conseil, $interdits, $medicaments, $duree, $niveau_urgence) {
        $stmt = $this->db_back->prepare("
            INSERT INTO traitements (allergie_nom, conseil, interdits, medicaments, duree, niveau_urgence)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$allergie_nom, $conseil, $interdits, $medicaments, $duree, $niveau_urgence]);
    }
    
    // Modifier un traitement
    public function updateTraitement($id, $allergie_nom, $conseil, $interdits, $medicaments, $duree, $niveau_urgence) {
        $stmt = $this->db_back->prepare("
            UPDATE traitements 
            SET allergie_nom = ?, conseil = ?, interdits = ?, medicaments = ?, duree = ?, niveau_urgence = ?
            WHERE id = ?
        ");
        return $stmt->execute([$allergie_nom, $conseil, $interdits, $medicaments, $duree, $niveau_urgence, $id]);
    }
    
    // Supprimer un traitement
    public function deleteTraitement($id) {
        $stmt = $this->db_back->prepare("DELETE FROM traitements WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>