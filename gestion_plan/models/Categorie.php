<?php
class Categorie {
    private $id_categorie;
    private $nom;
    private $description;
    
    public function __construct($nom = '', $description = '') {
        $this->nom = $nom;
        $this->description = $description;
    }
    
    // Getters
    public function getId() { return $this->id_categorie; }
    public function getNom() { return $this->nom; }
    public function getDescription() { return $this->description; }
    
    // Setters
    public function setId($id) { $this->id_categorie = $id; }
    public function setNom($nom) { $this->nom = $nom; }
    public function setDescription($description) { $this->description = $description; }
}
?>