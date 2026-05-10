<?php
require_once __DIR__ . '/../model/Traitement.php';
require_once __DIR__ . '/../Config/Database.php';

class TraitementController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Récupérer tous les traitements
    public function getAllTraitements() {
        $stmt = $this->db->query("SELECT * FROM traitements ORDER BY id");
        $results = $stmt->fetchAll();
        
        $traitements = [];
        foreach ($results as $row) {
            $traitement = new Traitement();
            $traitement->setId($row['id']);
            $traitement->setAllergieId($row['allergie_id']);
            $traitement->setConseil($row['conseil']);
            $traitement->setInterdits($row['interdits']);
            $traitement->setMedicaments($row['medicaments']);
            $traitement->setDuree($row['duree']);
            $traitement->setNiveauUrgence($row['niveau_urgence']);
            $traitement->setNoteMoyenne($row['note_moyenne'] ?? 0);
            $traitement->setNbNotes($row['nb_notes'] ?? 0);
            $traitements[] = $traitement;
        }
        
        $result = [];
        foreach ($traitements as $traitement) {
            $result[] = $traitement->toArray();
        }
        return $result;
    }
    
    // Récupérer tous les traitements avec jointure
    public function getAllTraitementsWithAllergies() {
        $stmt = $this->db->query("
            SELECT t.*, a.nom as allergie_nom 
            FROM traitements t 
            JOIN allergies a ON t.allergie_id = a.id 
            ORDER BY a.nom
        ");
        return $stmt->fetchAll();
    }
    
    // Récupérer un traitement par ID d'allergie
    public function getTraitementByAllergieId($allergie_id) {
        $stmt = $this->db->prepare("SELECT * FROM traitements WHERE allergie_id = ?");
        $stmt->execute([$allergie_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $traitement = new Traitement();
            $traitement->setId($row['id']);
            $traitement->setAllergieId($row['allergie_id']);
            $traitement->setConseil($row['conseil']);
            $traitement->setInterdits($row['interdits']);
            $traitement->setMedicaments($row['medicaments']);
            $traitement->setDuree($row['duree']);
            $traitement->setNiveauUrgence($row['niveau_urgence']);
            $traitement->setNoteMoyenne($row['note_moyenne'] ?? 0);
            $traitement->setNbNotes($row['nb_notes'] ?? 0);
            return $traitement->toArray();
        }
        return null;
    }
    
    // Récupérer un traitement par ID
    public function getTraitementById($id) {
        $stmt = $this->db->prepare("SELECT * FROM traitements WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $traitement = new Traitement();
            $traitement->setId($row['id']);
            $traitement->setAllergieId($row['allergie_id']);
            $traitement->setConseil($row['conseil']);
            $traitement->setInterdits($row['interdits']);
            $traitement->setMedicaments($row['medicaments']);
            $traitement->setDuree($row['duree']);
            $traitement->setNiveauUrgence($row['niveau_urgence']);
            return $traitement->toArray();
        }
        return null;
    }
    
    // Ajouter un traitement
    public function addTraitement($allergie_id, $conseil, $interdits, $medicaments, $duree, $niveau_urgence) {
        $stmt = $this->db->prepare("
            INSERT INTO traitements (allergie_id, conseil, interdits, medicaments, duree, niveau_urgence)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$allergie_id, $conseil, $interdits, $medicaments, $duree, $niveau_urgence]);
    }
    
    // Mettre à jour un traitement
    public function updateTraitement($allergie_id, $conseil, $interdits, $medicaments, $duree, $niveau_urgence) {
        $stmt = $this->db->prepare("
            UPDATE traitements 
            SET conseil = ?, interdits = ?, medicaments = ?, duree = ?, niveau_urgence = ?
            WHERE allergie_id = ?
        ");
        return $stmt->execute([$conseil, $interdits, $medicaments, $duree, $niveau_urgence, $allergie_id]);
    }
    
    // Supprimer un traitement
    public function deleteTraitement($id) {
        $stmt = $this->db->prepare("DELETE FROM traitements WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // Compter les traitements
    public function countTraitements() {
        return $this->db->query("SELECT COUNT(*) FROM traitements")->fetchColumn();
    }
    
    // Ajouter une note
    public function addNote($traitement_id, $note) {
        $note = min(5, max(1, $note));
        
        $stmt = $this->db->prepare("SELECT note_moyenne, nb_notes FROM traitements WHERE id = ?");
        $stmt->execute([$traitement_id]);
        $current = $stmt->fetch();
        
        if ($current) {
            $newCount = $current['nb_notes'] + 1;
            $newAvg = round(($current['note_moyenne'] * $current['nb_notes'] + $note) / $newCount, 1);
            
            $stmt = $this->db->prepare("UPDATE traitements SET note_moyenne = ?, nb_notes = ? WHERE id = ?");
            return $stmt->execute([$newAvg, $newCount, $traitement_id]);
        }
        return false;
    }
    
    // Sauvegarder ou mettre à jour un traitement
    public function saveTraitement($allergie_id, $conseil, $interdits, $medicaments, $duree, $niveau_urgence) {
        $existant = $this->getTraitementByAllergieId($allergie_id);
        
        if ($existant) {
            return $this->updateTraitement($allergie_id, $conseil, $interdits, $medicaments, $duree, $niveau_urgence);
        } else {
            return $this->addTraitement($allergie_id, $conseil, $interdits, $medicaments, $duree, $niveau_urgence);
        }
    }
}
?>