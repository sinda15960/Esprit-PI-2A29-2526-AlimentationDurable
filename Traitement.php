<?php
require_once __DIR__ . '/../Config/Database.php';

class Traitement {
    private $id;
    private $allergie_id;
    private $conseil;
    private $interdits;
    private $medicaments;
    private $duree;
    private $niveau_urgence;
    private $created_at;
    private $updated_at;
    public $allergie_nom; // Pour l'affichage (issu de la jointure)
    
    public function __construct($allergie_id = null, $conseil = null, $interdits = null, $medicaments = null, $duree = null, $niveau_urgence = null) {
        $this->allergie_id = $allergie_id;
        $this->conseil = $conseil;
        $this->interdits = $interdits;
        $this->medicaments = $medicaments;
        $this->duree = $duree;
        $this->niveau_urgence = $niveau_urgence;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getAllergieId() { return $this->allergie_id; }
    public function getConseil() { return $this->conseil; }
    public function getInterdits() { return $this->interdits; }
    public function getMedicaments() { return $this->medicaments; }
    public function getDuree() { return $this->duree; }
    public function getNiveauUrgence() { return $this->niveau_urgence; }
    public function getCreatedAt() { return $this->created_at; }
    public function getUpdatedAt() { return $this->updated_at; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setAllergieId($allergie_id) { $this->allergie_id = $allergie_id; }
    public function setConseil($conseil) { $this->conseil = $conseil; }
    public function setInterdits($interdits) { $this->interdits = $interdits; }
    public function setMedicaments($medicaments) { $this->medicaments = $medicaments; }
    public function setDuree($duree) { $this->duree = $duree; }
    public function setNiveauUrgence($niveau_urgence) { $this->niveau_urgence = $niveau_urgence; }
    
    /**
     * Récupérer tous les traitements avec jointure pour avoir le nom de l'allergie
     */
    public static function findAll() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("
            SELECT t.*, a.nom as allergie_nom 
            FROM traitements t 
            JOIN allergies a ON t.allergie_id = a.id 
            ORDER BY t.id
        ");
        $results = $stmt->fetchAll();
        
        $traitements = [];
        foreach ($results as $row) {
            $traitement = new Traitement();
            $traitement->hydrate($row);
            $traitement->allergie_nom = $row['allergie_nom'];
            $traitements[] = $traitement;
        }
        return $traitements;
    }
    
    /**
     * Récupérer un traitement par son ID avec jointure
     */
    public static function findById($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT t.*, a.nom as allergie_nom 
            FROM traitements t 
            JOIN allergies a ON t.allergie_id = a.id 
            WHERE t.id = :id
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        
        if ($row) {
            $traitement = new Traitement();
            $traitement->hydrate($row);
            $traitement->allergie_nom = $row['allergie_nom'];
            return $traitement;
        }
        return null;
    }
    
    /**
     * Récupérer le traitement associé à une allergie (par son ID)
     */
    public static function findByAllergieId($allergie_id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT t.*, a.nom as allergie_nom 
            FROM traitements t 
            JOIN allergies a ON t.allergie_id = a.id 
            WHERE t.allergie_id = :allergie_id
        ");
        $stmt->execute([':allergie_id' => $allergie_id]);
        $row = $stmt->fetch();
        
        if ($row) {
            $traitement = new Traitement();
            $traitement->hydrate($row);
            $traitement->allergie_nom = $row['allergie_nom'];
            return $traitement;
        }
        return null;
    }
    
    /**
     * Récupérer tous les traitements avec leurs allergies (pour recherche par genre)
     * NOUVELLE METHODE pour la jointure
     */
    public static function findAllWithAllergies() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("
            SELECT t.*, a.nom as allergie_nom, a.categorie, a.gravite as allergie_gravite
            FROM traitements t 
            JOIN allergies a ON t.allergie_id = a.id 
            ORDER BY a.nom
        ");
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer les traitements par catégorie d'allergie
     * NOUVELLE METHODE pour filtre avancé
     */
    public static function findByCategorie($categorie) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT t.*, a.nom as allergie_nom, a.categorie
            FROM traitements t 
            JOIN allergies a ON t.allergie_id = a.id 
            WHERE a.categorie = :categorie
            ORDER BY a.nom
        ");
        $stmt->execute([':categorie' => $categorie]);
        return $stmt->fetchAll();
    }
    
    /**
     * Sauvegarder un traitement (ajout ou modification)
     */
    public function save() {
        $db = Database::getInstance()->getConnection();
        
        if ($this->id) {
            // UPDATE
            $stmt = $db->prepare("
                UPDATE traitements 
                SET allergie_id = :allergie_id,
                    conseil = :conseil, 
                    interdits = :interdits, 
                    medicaments = :medicaments, 
                    duree = :duree, 
                    niveau_urgence = :niveau_urgence 
                WHERE id = :id
            ");
            return $stmt->execute([
                ':id' => $this->id,
                ':allergie_id' => $this->allergie_id,
                ':conseil' => $this->conseil,
                ':interdits' => $this->interdits,
                ':medicaments' => $this->medicaments,
                ':duree' => $this->duree,
                ':niveau_urgence' => $this->niveau_urgence
            ]);
        } else {
            // INSERT
            $stmt = $db->prepare("
                INSERT INTO traitements (allergie_id, conseil, interdits, medicaments, duree, niveau_urgence) 
                VALUES (:allergie_id, :conseil, :interdits, :medicaments, :duree, :niveau_urgence)
            ");
            $result = $stmt->execute([
                ':allergie_id' => $this->allergie_id,
                ':conseil' => $this->conseil,
                ':interdits' => $this->interdits,
                ':medicaments' => $this->medicaments,
                ':duree' => $this->duree,
                ':niveau_urgence' => $this->niveau_urgence
            ]);
            if ($result) {
                $this->id = $db->lastInsertId();
            }
            return $result;
        }
    }
    
    /**
     * Supprimer un traitement
     */
    public function delete() {
        if ($this->id) {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("DELETE FROM traitements WHERE id = :id");
            return $stmt->execute([':id' => $this->id]);
        }
        return false;
    }
    
    /**
     * Hydratation de l'objet à partir d'un tableau
     */
    public function hydrate($data) {
        $this->id = $data['id'];
        $this->allergie_id = $data['allergie_id'];
        $this->conseil = $data['conseil'];
        $this->interdits = $data['interdits'];
        $this->medicaments = $data['medicaments'] ?? null;
        $this->duree = $data['duree'] ?? null;
        $this->niveau_urgence = $data['niveau_urgence'];
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }
    
    /**
     * Convertir l'objet en tableau pour JSON
     */
    public function toArray() {
        return [
            'id' => $this->id,
            'allergie_id' => $this->allergie_id,
            'allergie_nom' => $this->allergie_nom ?? null,
            'conseil' => $this->conseil,
            'interdits' => $this->interdits,
            'medicaments' => $this->medicaments,
            'duree' => $this->duree,
            'niveau_urgence' => $this->niveau_urgence
        ];
    }
    
    /**
     * Afficher les informations du traitement (pour débogage)
     */
    public function show() {
        echo "<div class='traitement-info'>";
        echo "<h4>Traitement pour " . htmlspecialchars($this->allergie_nom ?? 'Allergie') . "</h4>";
        echo "<p><strong>Conseils:</strong> " . htmlspecialchars($this->conseil) . "</p>";
        echo "<p><strong>Interdits:</strong> " . htmlspecialchars($this->interdits) . "</p>";
        echo "<p><strong>Médicaments:</strong> " . htmlspecialchars($this->medicaments ?? 'Aucun') . "</p>";
        echo "<p><strong>Durée:</strong> " . htmlspecialchars($this->duree ?? 'Non spécifiée') . "</p>";
        echo "<p><strong>Urgence:</strong> " . htmlspecialchars($this->niveau_urgence) . "</p>";
        echo "</div>";
    }
}
?>