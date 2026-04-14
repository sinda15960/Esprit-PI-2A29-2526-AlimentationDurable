<?php
class Traitement {
    private $id;
    private $allergie_nom;
    private $conseil;
    private $interdits;
    private $medicaments;
    private $duree;
    private $niveau_urgence;
    
    public function __construct($allergie_nom = null, $conseil = null, $interdits = null,
                                $medicaments = null, $duree = null, $niveau_urgence = null) {
        $this->allergie_nom = $allergie_nom;
        $this->conseil = $conseil;
        $this->interdits = $interdits;
        $this->medicaments = $medicaments;
        $this->duree = $duree;
        $this->niveau_urgence = $niveau_urgence;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getAllergieNom() { return $this->allergie_nom; }
    public function getConseil() { return $this->conseil; }
    public function getInterdits() { return $this->interdits; }
    public function getMedicaments() { return $this->medicaments; }
    public function getDuree() { return $this->duree; }
    public function getNiveauUrgence() { return $this->niveau_urgence; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setAllergieNom($allergie_nom) { $this->allergie_nom = $allergie_nom; }
    public function setConseil($conseil) { $this->conseil = $conseil; }
    public function setInterdits($interdits) { $this->interdits = $interdits; }
    public function setMedicaments($medicaments) { $this->medicaments = $medicaments; }
    public function setDuree($duree) { $this->duree = $duree; }
    public function setNiveauUrgence($niveau_urgence) { $this->niveau_urgence = $niveau_urgence; }
    
    // Méthode show() comme demandé par la prof
    public function show() {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Propriété</th><th>Valeur</th></tr>";
        echo "<tr><td>ID</td><td>" . $this->id . "</td></tr>";
        echo "<tr><td>Allergie</td><td>" . $this->allergie_nom . "</td></tr>";
        echo "<tr><td>Conseil</td><td>" . $this->conseil . "</td></tr>";
        echo "<tr><td>Interdits</td><td>" . $this->interdits . "</td></tr>";
        echo "<tr><td>Médicaments</td><td>" . $this->medicaments . "</td></tr>";
        echo "<tr><td>Durée</td><td>" . $this->duree . "</td></tr>";
        echo "<tr><td>Urgence</td><td>" . $this->niveau_urgence . "</td></tr>";
        echo "</table>";
    }
}
?>