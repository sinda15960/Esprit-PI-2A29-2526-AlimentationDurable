<?php
// Model/UserPollenPrefs.php - UNIQUEMENT la structure de la classe
class UserPollenPrefs {
    private $id;
    private $session_id;
    private $ville;
    private $latitude;
    private $longitude;
    private $pollen_allergy;
    private $acarien_allergy;
    private $moisissure_allergy;
    private $alert_email;
    private $alert_phone;
    private $alert_threshold;
    private $created_at;
    private $updated_at;
    
    public function __construct($session_id = null) {
        $this->session_id = $session_id;
        $this->ville = 'Tunis';
        $this->pollen_allergy = false;
        $this->acarien_allergy = false;
        $this->moisissure_allergy = false;
        $this->alert_threshold = 70;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getSessionId() { return $this->session_id; }
    public function getVille() { return $this->ville; }
    public function getLatitude() { return $this->latitude; }
    public function getLongitude() { return $this->longitude; }
    public function getPollenAllergy() { return $this->pollen_allergy; }
    public function getAcarienAllergy() { return $this->acarien_allergy; }
    public function getMoisissureAllergy() { return $this->moisissure_allergy; }
    public function getAlertEmail() { return $this->alert_email; }
    public function getAlertPhone() { return $this->alert_phone; }
    public function getAlertThreshold() { return $this->alert_threshold; }
    public function getCreatedAt() { return $this->created_at; }
    public function getUpdatedAt() { return $this->updated_at; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setSessionId($session_id) { $this->session_id = $session_id; }
    public function setVille($ville) { $this->ville = $ville; }
    public function setLatitude($latitude) { $this->latitude = $latitude; }
    public function setLongitude($longitude) { $this->longitude = $longitude; }
    public function setPollenAllergy($pollen_allergy) { $this->pollen_allergy = $pollen_allergy; }
    public function setAcarienAllergy($acarien_allergy) { $this->acarien_allergy = $acarien_allergy; }
    public function setMoisissureAllergy($moisissure_allergy) { $this->moisissure_allergy = $moisissure_allergy; }
    public function setAlertEmail($alert_email) { $this->alert_email = $alert_email; }
    public function setAlertPhone($alert_phone) { $this->alert_phone = $alert_phone; }
    public function setAlertThreshold($alert_threshold) { $this->alert_threshold = $alert_threshold; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }
    
    public function toArray() {
        return [
            'id' => $this->id,
            'session_id' => $this->session_id,
            'ville' => $this->ville,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'pollen_allergy' => $this->pollen_allergy,
            'acarien_allergy' => $this->acarien_allergy,
            'moisissure_allergy' => $this->moisissure_allergy,
            'alert_email' => $this->alert_email,
            'alert_phone' => $this->alert_phone,
            'alert_threshold' => $this->alert_threshold,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
    
    public function show() {
        echo "<table border='1' cellpadding='8'>";
        echo "<tr><th>Propriété</th><th>Valeur</th></tr>";
        echo "<tr><td>Session ID</td><td>{$this->session_id}</td></tr>";
        echo "<tr><td>Ville</td><td>{$this->ville}</td></tr>";
        echo "<tr><td>Allergie pollen</td><td>" . ($this->pollen_allergy ? 'Oui' : 'Non') . "</td></tr>";
        echo "<tr><td>Email alertes</td><td>{$this->alert_email}</td></tr>";
        echo "</table>";
    }
}
?>