<?php
require_once __DIR__ . '/../Config/Database.php';

class StatsController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Statistiques par catégorie
    public function getStatsByCategorie() {
        $stmt = $this->db->query("SELECT categorie, COUNT(*) as total FROM allergies GROUP BY categorie");
        return $stmt->fetchAll();
    }
    
    // Statistiques par gravité
    public function getStatsByGravite() {
        $stmt = $this->db->query("SELECT gravite, COUNT(*) as total FROM allergies GROUP BY gravite");
        return $stmt->fetchAll();
    }
    
    // Top 5 des allergies les plus consultées
    public function getTopAllergies() {
        $stmt = $this->db->query("SELECT nom, vue_count FROM allergies ORDER BY vue_count DESC LIMIT 5");
        return $stmt->fetchAll();
    }
    
    // Moyenne des notes par traitement
    public function getMoyennesNotes() {
        $stmt = $this->db->query("
            SELECT a.nom, t.note_moyenne, t.nb_notes
            FROM traitements t
            JOIN allergies a ON t.allergie_id = a.id
            WHERE t.nb_notes > 0
            ORDER BY t.note_moyenne DESC
        ");
        return $stmt->fetchAll();
    }
}
?>