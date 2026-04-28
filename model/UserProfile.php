<?php
// Model/UserProfile.php - Profil allergique personnel
class UserProfile {
    private $id;
    private $session_id;
    private $nom;
    private $prenom;
    private $date_naissance;
    private $telephone;
    private $medicament_urgence;
    private $selected_allergies;
    private $critical_allergies;
    private $created_at;
    private $updated_at;
    
    public function __construct($session_id = null) {
        $this->session_id = $session_id;
        $this->selected_allergies = '';
        $this->critical_allergies = '';
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getSessionId() { return $this->session_id; }
    public function getNom() { return $this->nom; }
    public function getPrenom() { return $this->prenom; }
    public function getDateNaissance() { return $this->date_naissance; }
    public function getTelephone() { return $this->telephone; }
    public function getMedicamentUrgence() { return $this->medicament_urgence; }
    public function getSelectedAllergies() { return $this->selected_allergies; }
    public function getCriticalAllergies() { return $this->critical_allergies; }
    public function getCreatedAt() { return $this->created_at; }
    public function getUpdatedAt() { return $this->updated_at; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setSessionId($session_id) { $this->session_id = $session_id; }
    public function setNom($nom) { $this->nom = $nom; }
    public function setPrenom($prenom) { $this->prenom = $prenom; }
    public function setDateNaissance($date_naissance) { $this->date_naissance = $date_naissance; }
    public function setTelephone($telephone) { $this->telephone = $telephone; }
    public function setMedicamentUrgence($medicament_urgence) { $this->medicament_urgence = $medicament_urgence; }
    public function setSelectedAllergies($selected_allergies) { $this->selected_allergies = $selected_allergies; }
    public function setCriticalAllergies($critical_allergies) { $this->critical_allergies = $critical_allergies; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }
    
    public function toArray() {
        return [
            'id' => $this->id,
            'session_id' => $this->session_id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'date_naissance' => $this->date_naissance,
            'telephone' => $this->telephone,
            'medicament_urgence' => $this->medicament_urgence,
            'selected_allergies' => $this->selected_allergies,
            'critical_allergies' => $this->critical_allergies,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
    
    public function show() {
        echo "<table border='1' cellpadding='8'>";
        echo "<tr><th>Propriété</th><th>Valeur</th></tr>";
        echo "<tr><td>Nom</td><td>{$this->prenom} {$this->nom}</td></tr>";
        echo "<tr><td>Téléphone</td><td>{$this->telephone}</td></tr>";
        echo "<tr><td>Médicament urgence</td><td>{$this->medicament_urgence}</td></tr>";
        echo "<tr><td>Allergies sélectionnées</td><td>{$this->selected_allergies}</td></tr>";
        echo "</table>";
    }
}
?>