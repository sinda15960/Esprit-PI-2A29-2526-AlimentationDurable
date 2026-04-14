<?php
class Allergie {
    private $id;
    private $nom;
    private $categorie;
    private $description;
    private $symptomes;
    private $declencheurs;
    private $gravite;
    
    public function __construct($nom = null, $categorie = null, $description = null, 
                                $symptomes = null, $declencheurs = null, $gravite = null) {
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
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setNom($nom) { $this->nom = $nom; }
    public function setCategorie($categorie) { $this->categorie = $categorie; }
    public function setDescription($description) { $this->description = $description; }
    public function setSymptomes($symptomes) { $this->symptomes = $symptomes; }
    public function setDeclencheurs($declencheurs) { $this->declencheurs = $declencheurs; }
    public function setGravite($gravite) { $this->gravite = $gravite; }
    
    // Méthode show() comme demandé par la prof
    public function show() {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Propriété</th><th>Valeur</th></tr>";
        echo "<tr><td>ID</td><td>" . $this->id . "</td></tr>";
        echo "<tr><td>Nom</td><td>" . $this->nom . "</td></tr>";
        echo "<tr><td>Catégorie</td><td>" . $this->categorie . "</td></tr>";
        echo "<tr><td>Description</td><td>" . $this->description . "</td></tr>";
        echo "<tr><td>Symptômes</td><td>" . $this->symptomes . "</td></tr>";
        echo "<tr><td>Déclencheurs</td><td>" . $this->declencheurs . "</td></tr>";
        echo "<tr><td>Gravité</td><td>" . $this->gravite . "</td></tr>";
        echo "</table>";
    }
}
?>