<?php
// Model/Allergie.php - UNIQUEMENT la structure de la classe
require_once __DIR__ . '/../config/database.php';

class Allergie {
    private $id;
    private $nom;
    private $categorie;
    private $description;
    private $symptomes;
    private $declencheurs;
    private $gravite;
    private $image_url;
    private $vue_count;
    private $date_creation;
    
    public function __construct($nom = null, $categorie = null, $description = null, 
                                $symptomes = null, $declencheurs = null, $gravite = null,
                                $image_url = null) {
        $this->nom = $nom;
        $this->categorie = $categorie;
        $this->description = $description;
        $this->symptomes = $symptomes;
        $this->declencheurs = $declencheurs;
        $this->gravite = $gravite;
        $this->image_url = $image_url;
        $this->vue_count = 0;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getCategorie() { return $this->categorie; }
    public function getDescription() { return $this->description; }
    public function getSymptomes() { return $this->symptomes; }
    public function getDeclencheurs() { return $this->declencheurs; }
    public function getGravite() { return $this->gravite; }
    public function getImageUrl() { return $this->image_url; }
    public function getVueCount() { return $this->vue_count; }
    public function getDateCreation() { return $this->date_creation; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setNom($nom) { $this->nom = $nom; }
    public function setCategorie($categorie) { $this->categorie = $categorie; }
    public function setDescription($description) { $this->description = $description; }
    public function setSymptomes($symptomes) { $this->symptomes = $symptomes; }
    public function setDeclencheurs($declencheurs) { $this->declencheurs = $declencheurs; }
    public function setGravite($gravite) { $this->gravite = $gravite; }
    public function setImageUrl($image_url) { $this->image_url = $image_url; }
    public function setVueCount($vue_count) { $this->vue_count = $vue_count; }
    public function setDateCreation($date_creation) { $this->date_creation = $date_creation; }
    
    // Méthode d'affichage (demandée par la prof)
    public function show() {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Propriété</th><th>Valeur</th></tr>";
        echo "<tr><td>ID</td><td>" . $this->id . "</td></tr>";
        echo "<tr><td>Nom</td><td>" . htmlspecialchars($this->nom) . "</td></tr>";
        echo "<tr><td>Catégorie</td><td>" . htmlspecialchars($this->categorie) . "</td></tr>";
        echo "<tr><td>Description</td><td>" . htmlspecialchars($this->description) . "</td></tr>";
        echo "<tr><td>Symptômes</td><td>" . htmlspecialchars($this->symptomes) . "</td></tr>";
        echo "<tr><td>Déclencheurs</td><td>" . htmlspecialchars($this->declencheurs) . "</td></tr>";
        echo "<tr><td>Gravité</td><td>" . $this->gravite . "</td></tr>";
        echo "<tr><td>Image URL</td><td>" . htmlspecialchars($this->image_url) . "</td></tr>";
        echo "</table>";
    }
    
    public function toArray() {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'categorie' => $this->categorie,
            'description' => $this->description,
            'symptomes' => $this->symptomes,
            'declencheurs' => $this->declencheurs,
            'gravite' => $this->gravite,
            'image_url' => $this->image_url,
            'vue_count' => $this->vue_count
        ];
    }

    /**
     * @return self[]
     */
    public static function findAll() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query('SELECT * FROM allergies ORDER BY nom ASC');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $list = [];
        foreach ($rows as $row) {
            $a = new self();
            $a->setId($row['id']);
            $a->setNom($row['nom']);
            $a->setCategorie($row['categorie']);
            $a->setDescription($row['description']);
            $a->setSymptomes($row['symptomes']);
            $a->setDeclencheurs($row['declencheurs']);
            $a->setGravite($row['gravite']);
            $a->setImageUrl($row['image_url'] ?? null);
            $a->setVueCount((int)($row['vue_count'] ?? 0));
            if (!empty($row['created_at'])) {
                $a->setDateCreation($row['created_at']);
            }
            $list[] = $a;
        }
        return $list;
    }
}
?>