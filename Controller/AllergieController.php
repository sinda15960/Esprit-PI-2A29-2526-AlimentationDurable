<?php
require_once __DIR__ . '/../Model/Allergie.php';
require_once __DIR__ . '/../Config/Database.php';

class AllergieController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Récupérer le dernier ID inséré (pour les logs)
     */
    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }
    
    /**
     * Récupérer toutes les allergies
     */
    public function getAllAllergies() {
        $stmt = $this->db->query("SELECT * FROM allergies ORDER BY nom");
        $results = $stmt->fetchAll();
        
        $allergies = [];
        foreach ($results as $row) {
            $allergie = new Allergie();
            $allergie->setId($row['id']);
            $allergie->setNom($row['nom']);
            $allergie->setCategorie($row['categorie']);
            $allergie->setDescription($row['description']);
            $allergie->setSymptomes($row['symptomes']);
            $allergie->setDeclencheurs($row['declencheurs']);
            $allergie->setGravite($row['gravite']);
            $allergie->setImageUrl($row['image_url']);
            $allergie->setVueCount($row['vue_count'] ?? 0);
            $allergies[] = $allergie;
        }
        
        $result = [];
        foreach ($allergies as $allergie) {
            $result[] = $allergie->toArray();
        }
        return $result;
    }
    
    /**
     * Récupérer une allergie par ID
     */
    public function getAllergieById($id) {
        $stmt = $this->db->prepare("SELECT * FROM allergies WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $allergie = new Allergie();
            $allergie->setId($row['id']);
            $allergie->setNom($row['nom']);
            $allergie->setCategorie($row['categorie']);
            $allergie->setDescription($row['description']);
            $allergie->setSymptomes($row['symptomes']);
            $allergie->setDeclencheurs($row['declencheurs']);
            $allergie->setGravite($row['gravite']);
            $allergie->setImageUrl($row['image_url']);
            $allergie->setVueCount($row['vue_count'] ?? 0);
            return $allergie->toArray();
        }
        return null;
    }
    
    /**
     * Ajouter une allergie (retourne l'ID inséré)
     */
    public function addAllergie($nom, $categorie, $description, $symptomes, $declencheurs, $gravite) {
        $stmt = $this->db->prepare("
            INSERT INTO allergies (nom, categorie, description, symptomes, declencheurs, gravite)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        if ($stmt->execute([$nom, $categorie, $description, $symptomes, $declencheurs, $gravite])) {
            return $this->db->lastInsertId();
        }
        return false;
    }
    
    /**
     * Modifier une allergie
     */
    public function updateAllergie($id, $nom, $categorie, $description, $symptomes, $declencheurs, $gravite) {
        $stmt = $this->db->prepare("
            UPDATE allergies 
            SET nom = ?, categorie = ?, description = ?, symptomes = ?, declencheurs = ?, gravite = ?
            WHERE id = ?
        ");
        return $stmt->execute([$nom, $categorie, $description, $symptomes, $declencheurs, $gravite, $id]);
    }
    
    /**
     * Supprimer une allergie (retourne le nom pour les logs)
     */
    public function deleteAllergie($id) {
        // Récupérer le nom et l'image avant suppression
        $stmt = $this->db->prepare("SELECT nom, image_url FROM allergies WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $nom = $data['nom'] ?? null;
        $image = $data['image_url'] ?? null;
        
        // Supprimer l'image associée
        if ($image && file_exists(__DIR__ . '/../' . $image)) {
            unlink(__DIR__ . '/../' . $image);
        }
        
        // Supprimer les traitements associés
        $stmt = $this->db->prepare("DELETE FROM traitements WHERE allergie_id = ?");
        $stmt->execute([$id]);
        
        // Supprimer l'allergie
        $stmt = $this->db->prepare("DELETE FROM allergies WHERE id = ?");
        $stmt->execute([$id]);
        
        return $nom; // Retourner le nom pour l'enregistrement du log
    }
    
    /**
     * Compter les allergies
     */
    public function countAllergies() {
        return $this->db->query("SELECT COUNT(*) FROM allergies")->fetchColumn();
    }
    
    /**
     * Incrémenter le compteur de vues
     */
    public function incrementVueCount($id) {
        $stmt = $this->db->prepare("UPDATE allergies SET vue_count = vue_count + 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Récupérer les statistiques
     */
    public function getStats() {
        $stats = [];
        
        // Par catégorie
        $stmt = $this->db->query("SELECT categorie, COUNT(*) as total FROM allergies GROUP BY categorie");
        $stats['par_categorie'] = $stmt->fetchAll();
        
        // Par gravité
        $stmt = $this->db->query("SELECT gravite, COUNT(*) as total FROM allergies GROUP BY gravite");
        $stats['par_gravite'] = $stmt->fetchAll();
        
        // Top 5 des plus vues
        $stmt = $this->db->query("SELECT nom, vue_count FROM allergies ORDER BY vue_count DESC LIMIT 5");
        $stats['top_vues'] = $stmt->fetchAll();
        
        $stats['total_allergies'] = $this->countAllergies();
        $stats['total_traitements'] = $this->db->query("SELECT COUNT(*) FROM traitements")->fetchColumn();
        
        return $stats;
    }
    
    /**
     * Recherche avancée
     */
    public function searchAdvanced($nom, $categorie, $gravite) {
        $sql = "SELECT * FROM allergies WHERE 1=1";
        $params = [];
        
        if (!empty($nom)) {
            $sql .= " AND nom LIKE ?";
            $params[] = "%$nom%";
        }
        if (!empty($categorie)) {
            $sql .= " AND categorie = ?";
            $params[] = $categorie;
        }
        if (!empty($gravite)) {
            $sql .= " AND gravite = ?";
            $params[] = $gravite;
        }
        
        $sql .= " ORDER BY nom";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Mettre à jour l'image
     */
    public function updateImage($id, $image_url) {
        $stmt = $this->db->prepare("UPDATE allergies SET image_url = ? WHERE id = ?");
        return $stmt->execute([$image_url, $id]);
    }
    
    /**
     * Récupérer l'image
     */
    public function getImage($id) {
        $stmt = $this->db->prepare("SELECT image_url FROM allergies WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ? $result['image_url'] : null;
    }
    
    /**
     * Récupérer une allergie par son nom
     */
    public function getAllergieByNom($nom) {
        $stmt = $this->db->prepare("SELECT * FROM allergies WHERE nom = ?");
        $stmt->execute([$nom]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $allergie = new Allergie();
            $allergie->setId($row['id']);
            $allergie->setNom($row['nom']);
            $allergie->setCategorie($row['categorie']);
            $allergie->setDescription($row['description']);
            $allergie->setSymptomes($row['symptomes']);
            $allergie->setDeclencheurs($row['declencheurs']);
            $allergie->setGravite($row['gravite']);
            $allergie->setImageUrl($row['image_url']);
            $allergie->setVueCount($row['vue_count'] ?? 0);
            return $allergie;
        }
        return null;
    }
    
    /**
     * Récupérer les allergies par catégorie
     */
    public function getAllergiesByCategorie($categorie) {
        $stmt = $this->db->prepare("SELECT * FROM allergies WHERE categorie = ? ORDER BY nom");
        $stmt->execute([$categorie]);
        $results = $stmt->fetchAll();
        
        $allergies = [];
        foreach ($results as $row) {
            $allergie = new Allergie();
            $allergie->setId($row['id']);
            $allergie->setNom($row['nom']);
            $allergie->setCategorie($row['categorie']);
            $allergie->setDescription($row['description']);
            $allergie->setSymptomes($row['symptomes']);
            $allergie->setDeclencheurs($row['declencheurs']);
            $allergie->setGravite($row['gravite']);
            $allergie->setImageUrl($row['image_url']);
            $allergie->setVueCount($row['vue_count'] ?? 0);
            $allergies[] = $allergie;
        }
        
        $result = [];
        foreach ($allergies as $allergie) {
            $result[] = $allergie->toArray();
        }
        return $result;
    }
    
    /**
     * Récupérer les allergies par gravité
     */
    public function getAllergiesByGravite($gravite) {
        $stmt = $this->db->prepare("SELECT * FROM allergies WHERE gravite = ? ORDER BY nom");
        $stmt->execute([$gravite]);
        $results = $stmt->fetchAll();
        
        $allergies = [];
        foreach ($results as $row) {
            $allergie = new Allergie();
            $allergie->setId($row['id']);
            $allergie->setNom($row['nom']);
            $allergie->setCategorie($row['categorie']);
            $allergie->setDescription($row['description']);
            $allergie->setSymptomes($row['symptomes']);
            $allergie->setDeclencheurs($row['declencheurs']);
            $allergie->setGravite($row['gravite']);
            $allergie->setImageUrl($row['image_url']);
            $allergie->setVueCount($row['vue_count'] ?? 0);
            $allergies[] = $allergie;
        }
        
        $result = [];
        foreach ($allergies as $allergie) {
            $result[] = $allergie->toArray();
        }
        return $result;
    }
    
    /**
     * Méthode showBook pour l'affichage (demandée par la prof)
     */
    public function showBook($allergie) {
        $allergie->show();
    }
}
?>