<?php
class Objectif {
    private $id;
    private $titre;
    private $type_objectif;
    private $description;
    private $maladies;
    private $preferences;
    private $calories_min;
    private $calories_max;
    private $is_personal;
    private $user_id;
    private $poids_actuel;
    private $poids_cible;
    private $taille;
    private $age;
    private $etat_sante;
    private $date_debut;
    private $date_fin_prevue;
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
    public function getTitre() { return $this->titre; }
    public function getTypeObjectif() { return $this->type_objectif; }
    public function getDescription() { return $this->description; }
    public function getMaladies() { return $this->maladies; }
    public function getPreferences() { return $this->preferences; }
    public function getCaloriesMin() { return $this->calories_min; }
    public function getCaloriesMax() { return $this->calories_max; }
    public function getIsPersonal() { return $this->is_personal; }
    public function getUserId() { return $this->user_id; }
    public function getPoidsActuel() { return $this->poids_actuel; }
    public function getPoidsCible() { return $this->poids_cible; }
    public function getTaille() { return $this->taille; }
    public function getAge() { return $this->age; }
    public function getEtatSante() { return $this->etat_sante; }
    public function getDateDebut() { return $this->date_debut; }
    public function getDateFinPrevue() { return $this->date_fin_prevue; }
    public function getDateCreation() { return $this->date_creation; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setTitre($titre) { $this->titre = $titre; }
    public function setTypeObjectif($type_objectif) { $this->type_objectif = $type_objectif; }
    public function setDescription($description) { $this->description = $description; }
    public function setMaladies($maladies) { $this->maladies = $maladies; }
    public function setPreferences($preferences) { $this->preferences = $preferences; }
    public function setCaloriesMin($calories_min) { $this->calories_min = $calories_min; }
    public function setCaloriesMax($calories_max) { $this->calories_max = $calories_max; }
    public function setIsPersonal($is_personal) { $this->is_personal = $is_personal; }
    public function setUserId($user_id) { $this->user_id = $user_id; }
    public function setPoidsActuel($poids_actuel) { $this->poids_actuel = $poids_actuel; }
    public function setPoidsCible($poids_cible) { $this->poids_cible = $poids_cible; }
    public function setTaille($taille) { $this->taille = $taille; }
    public function setAge($age) { $this->age = $age; }
    public function setEtatSante($etat_sante) { $this->etat_sante = $etat_sante; }
    public function setDateDebut($date_debut) { $this->date_debut = $date_debut; }
    public function setDateFinPrevue($date_fin_prevue) { $this->date_fin_prevue = $date_fin_prevue; }
    public function setDateCreation($date_creation) { $this->date_creation = $date_creation; }
}
?>