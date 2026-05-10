<?php
require_once __DIR__ . '/../model/Rating.php';
require_once __DIR__ . '/../Config/Database.php';

class RatingController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Ajouter une note
    public function addRating($allergie_id, $note, $ip_address) {
        $stmt = $this->db->prepare("
            INSERT INTO ratings (allergie_id, note, ip_address)
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$allergie_id, $note, $ip_address]);
    }
    
    // Vérifier si l'utilisateur a déjà noté
    public function hasRated($allergie_id, $ip_address) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM ratings 
            WHERE allergie_id = ? AND ip_address = ?
        ");
        $stmt->execute([$allergie_id, $ip_address]);
        return $stmt->fetchColumn() > 0;
    }
    
    // Récupérer la note moyenne
    public function getAverageRating($allergie_id) {
        $stmt = $this->db->prepare("
            SELECT AVG(note) as moyenne, COUNT(*) as total
            FROM ratings WHERE allergie_id = ?
        ");
        $stmt->execute([$allergie_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Récupérer les allergies les mieux notées
    public function getTopRatedAllergies($limit = 5) {
        $stmt = $this->db->query("
            SELECT a.id, a.nom, a.categorie, a.gravite, 
                   COALESCE(AVG(r.note), 0) as moyenne, COUNT(r.id) as nb_avis
            FROM allergies a
            LEFT JOIN ratings r ON a.id = r.allergie_id
            GROUP BY a.id
            ORDER BY moyenne DESC, nb_avis DESC
            LIMIT $limit
        ");
        return $stmt->fetchAll();
    }
    
    // Incrémenter le compteur de vues
    public function incrementVueCount($allergie_id) {
        $stmt = $this->db->prepare("UPDATE allergies SET vue_count = vue_count + 1 WHERE id = ?");
        return $stmt->execute([$allergie_id]);
    }
    
    // Récupérer les allergies les plus vues
    public function getMostViewedAllergies($limit = 5) {
        $stmt = $this->db->query("
            SELECT id, nom, categorie, gravite, vue_count
            FROM allergies
            ORDER BY vue_count DESC
            LIMIT $limit
        ");
        return $stmt->fetchAll();
    }
}
?>