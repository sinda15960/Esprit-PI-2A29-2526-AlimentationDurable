<?php
require_once __DIR__ . '/../Model/Allergie.php';
require_once __DIR__ . '/../Model/Database.php';

class AllergieController {
    private $db_front;
    private $db_back;
    
    public function __construct() {
        $this->db_front = Database::getFrontConnection();
        $this->db_back = Database::getBackConnection();
    }
    
    // Méthode showBook comme demandé par la prof (affiche une allergie dans un tableau)
    public function showBook($allergie) {
        if ($allergie instanceof Allergie) {
            $allergie->show();
        } else {
            echo "L'objet passé n'est pas une instance de Allergie";
        }
    }
    
    // Récupérer toutes les allergies (FrontOffice)
    public function getAllAllergies() {
        $stmt = $this->db_front->query("SELECT * FROM allergies ORDER BY nom");
        $allergies = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $allergie = new Allergie();
            $allergie->setId($row['id']);
            $allergie->setNom($row['nom']);
            $allergie->setCategorie($row['categorie']);
            $allergie->setDescription($row['description']);
            $allergie->setSymptomes($row['symptomes']);
            $allergie->setDeclencheurs($row['declencheurs']);
            $allergie->setGravite($row['gravite']);
            $allergies[] = $allergie;
        }
        return $allergies;
    }
    
    // Récupérer une allergie par ID
    public function getAllergieById($id) {
        $stmt = $this->db_front->prepare("SELECT * FROM allergies WHERE id = ?");
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
            return $allergie;
        }
        return null;
    }
    
    // Ajouter une allergie (BackOffice)
    public function addAllergie($nom, $categorie, $description, $symptomes, $declencheurs, $gravite) {
        $stmt = $this->db_back->prepare("
            INSERT INTO allergies (nom, categorie, description, symptomes, declencheurs, gravite)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$nom, $categorie, $description, $symptomes, $declencheurs, $gravite]);
    }
    
    // Modifier une allergie (BackOffice)
    public function updateAllergie($id, $nom, $categorie, $description, $symptomes, $declencheurs, $gravite) {
        $stmt = $this->db_back->prepare("
            UPDATE allergies 
            SET nom = ?, categorie = ?, description = ?, symptomes = ?, declencheurs = ?, gravite = ?
            WHERE id = ?
        ");
        return $stmt->execute([$nom, $categorie, $description, $symptomes, $declencheurs, $gravite, $id]);
    }
    
    // Supprimer une allergie (BackOffice)
    public function deleteAllergie($id) {
        $stmt = $this->db_back->prepare("DELETE FROM allergies WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>