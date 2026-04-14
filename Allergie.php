<?php
require_once __DIR__ . '/../Config/Database.php';

class Allergie {
    private $id;
    private $nom;
    private $categorie;
    private $description;
    private $symptomes;
    private $declencheurs;
    private $gravite;
    private $date_creation;
    
    // Constructeur
    public function __construct($nom = null, $categorie = null, $description = null, $symptomes = null, $declencheurs = null, $gravite = null) {
        $this->nom = $nom;
        $this->categorie = $categorie;
        $this->description = $description;
        $this->symptomes = $symptomes;
        $this->declencheurs = $declencheurs;
        $this->gravite = $gravite;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getCategorie() { return $this->categorie; }
    public function getDescription() { return $this->description; }
    public function getSymptomes() { return $this->symptomes; }
    public function getDeclencheurs() { return $this->declencheurs; }
    public function getGravite() { return $this->gravite; }
    public function getDateCreation() { return $this->date_creation; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setNom($nom) { $this->nom = $nom; }
    public function setCategorie($categorie) { $this->categorie = $categorie; }
    public function setDescription($description) { $this->description = $description; }
    public function setSymptomes($symptomes) { $this->symptomes = $symptomes; }
    public function setDeclencheurs($declencheurs) { $this->declencheurs = $declencheurs; }
    public function setGravite($gravite) { $this->gravite = $gravite; }
    
    // Récupérer toutes les allergies
    public static function findAll() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM allergies ORDER BY id");
        $results = $stmt->fetchAll();
        
        $allergies = [];
        foreach ($results as $row) {
            $allergie = new Allergie();
            $allergie->hydrate($row);
            $allergies[] = $allergie;
        }
        return $allergies;
    }
    
    // Récupérer une allergie par ID
    public static function findById($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM allergies WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        
        if ($row) {
            $allergie = new Allergie();
            $allergie->hydrate($row);
            return $allergie;
        }
        return null;
    }
    
    // Rechercher des allergies
    public static function search($term) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM allergies WHERE nom LIKE :term OR description LIKE :term OR symptomes LIKE :term");
        $stmt->execute([':term' => '%' . $term . '%']);
        $results = $stmt->fetchAll();
        
        $allergies = [];
        foreach ($results as $row) {
            $allergie = new Allergie();
            $allergie->hydrate($row);
            $allergies[] = $allergie;
        }
        return $allergies;
    }
    
    // Hydratation
    public function hydrate($data) {
        $this->id = $data['id'];
        $this->nom = $data['nom'];
        $this->categorie = $data['categorie'];
        $this->description = $data['description'];
        $this->symptomes = $data['symptomes'];
        $this->declencheurs = $data['declencheurs'];
        $this->gravite = $data['gravite'];
        $this->date_creation = $data['date_creation'] ?? null;
    }
    
    // Convertir en tableau pour JSON
    public function toArray() {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'categorie' => $this->categorie,
            'description' => $this->description,
            'symptomes' => $this->symptomes,
            'declencheurs' => $this->declencheurs,
            'gravite' => $this->gravite
        ];
    }
}
?>