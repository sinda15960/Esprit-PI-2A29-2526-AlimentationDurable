<?php
// Model/Traitement.php - UNIQUEMENT la structure de la classe
class Traitement {
    private $id;
    private $allergie_id;
    private $conseil;
    private $interdits;
    private $medicaments;
    private $duree;
    private $niveau_urgence;
    private $note_moyenne;
    private $nb_notes;
    private $date_creation;
    
    public function __construct($allergie_id = null, $conseil = null, $interdits = null, 
                                $medicaments = null, $duree = null, $niveau_urgence = null) {
        $this->allergie_id = $allergie_id;
        $this->conseil = $conseil;
        $this->interdits = $interdits;
        $this->medicaments = $medicaments;
        $this->duree = $duree;
        $this->niveau_urgence = $niveau_urgence;
        $this->note_moyenne = 0;
        $this->nb_notes = 0;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getAllergieId() { return $this->allergie_id; }
    public function getConseil() { return $this->conseil; }
    public function getInterdits() { return $this->interdits; }
    public function getMedicaments() { return $this->medicaments; }
    public function getDuree() { return $this->duree; }
    public function getNiveauUrgence() { return $this->niveau_urgence; }
    public function getNoteMoyenne() { return $this->note_moyenne; }
    public function getNbNotes() { return $this->nb_notes; }
    public function getDateCreation() { return $this->date_creation; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setAllergieId($allergie_id) { $this->allergie_id = $allergie_id; }
    public function setConseil($conseil) { $this->conseil = $conseil; }
    public function setInterdits($interdits) { $this->interdits = $interdits; }
    public function setMedicaments($medicaments) { $this->medicaments = $medicaments; }
    public function setDuree($duree) { $this->duree = $duree; }
    public function setNiveauUrgence($niveau_urgence) { $this->niveau_urgence = $niveau_urgence; }
    public function setNoteMoyenne($note_moyenne) { $this->note_moyenne = $note_moyenne; }
    public function setNbNotes($nb_notes) { $this->nb_notes = $nb_notes; }
    public function setDateCreation($date_creation) { $this->date_creation = $date_creation; }
    
    public function toArray() {
        return [
            'id' => $this->id,
            'allergie_id' => $this->allergie_id,
            'conseil' => $this->conseil,
            'interdits' => $this->interdits,
            'medicaments' => $this->medicaments,
            'duree' => $this->duree,
            'niveau_urgence' => $this->niveau_urgence,
            'note_moyenne' => $this->note_moyenne,
            'nb_notes' => $this->nb_notes
        ];
    }
}
?>