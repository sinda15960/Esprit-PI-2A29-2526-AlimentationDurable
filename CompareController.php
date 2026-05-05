<?php
require_once __DIR__ . '/../Config/Database.php';

class CompareController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Récupérer deux allergies pour comparaison
    public function compareAllergies($id1, $id2) {
        $stmt = $this->db->prepare("SELECT * FROM allergies WHERE id = ?");
        $stmt->execute([$id1]);
        $allergie1 = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt = $this->db->prepare("SELECT * FROM allergies WHERE id = ?");
        $stmt->execute([$id2]);
        $allergie2 = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$allergie1 || !$allergie2) {
            return null;
        }
        
        // Récupérer les traitements
        $stmt = $this->db->prepare("SELECT * FROM traitements WHERE allergie_id = ?");
        $stmt->execute([$id1]);
        $allergie1['traitement'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt = $this->db->prepare("SELECT * FROM traitements WHERE allergie_id = ?");
        $stmt->execute([$id2]);
        $allergie2['traitement'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Trouver les déclencheurs communs
        $declencheurs1 = explode(',', $allergie1['declencheurs']);
        $declencheurs2 = explode(',', $allergie2['declencheurs']);
        $commun_declencheurs = array_intersect($declencheurs1, $declencheurs2);
        
        // Trouver les symptômes communs
        $symptomes1 = explode(',', $allergie1['symptomes']);
        $symptomes2 = explode(',', $allergie2['symptomes']);
        $commun_symptomes = array_intersect($symptomes1, $symptomes2);
        
        return [
            'allergie1' => $allergie1,
            'allergie2' => $allergie2,
            'commun_declencheurs' => $commun_declencheurs,
            'commun_symptomes' => $commun_symptomes
        ];
    }
    
    // Récupérer toutes les allergies pour le sélecteur
    public function getAllergiesForSelect() {
        $stmt = $this->db->query("SELECT id, nom, gravite FROM allergies ORDER BY nom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>