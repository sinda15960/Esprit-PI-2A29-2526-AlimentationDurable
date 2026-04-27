<?php
class Programme {
    private $id;
    private $nom;
    private $description;
    private $duree_semaines;
    private $niveau;
    private $objectif_id;
    private $categorie_id;
    private $date_creation;
    
    public function __construct($data = []) {
        $this->hydrate($data);
    }
    
    public function hydrate($data) {
        foreach ($data as $key => $value) {
            $method = 'set' . str_replace('_', '', ucwords($key, '_'));
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getDescription() { return $this->description; }
    public function getDureeSemaines() { return $this->duree_semaines; }
    public function getNiveau() { return $this->niveau; }
    public function getObjectifId() { return $this->objectif_id; }
    public function getCategorieId() { return $this->categorie_id; }
    public function getDateCreation() { return $this->date_creation; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setNom($nom) { $this->nom = $nom; }
    public function setDescription($description) { $this->description = $description; }
    public function setDureeSemaines($duree_semaines) { $this->duree_semaines = $duree_semaines; }
    public function setNiveau($niveau) { $this->niveau = $niveau; }
    public function setObjectifId($objectif_id) { $this->objectif_id = $objectif_id; }
    public function setCategorieId($categorie_id) { $this->categorie_id = $categorie_id; }
    public function setDateCreation($date_creation) { $this->date_creation = $date_creation; }
}
?>