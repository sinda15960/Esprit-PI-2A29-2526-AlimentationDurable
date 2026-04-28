<?php
// Model/Evaluation.php - UNIQUEMENT la structure de la classe
class Evaluation {
    private $id;
    private $traitement_id;
    private $note;
    private $ip_address;
    private $date_creation;
    
    public function __construct($traitement_id = null, $note = null, $ip_address = null) {
        $this->traitement_id = $traitement_id;
        $this->note = $note;
        $this->ip_address = $ip_address;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getTraitementId() { return $this->traitement_id; }
    public function getNote() { return $this->note; }
    public function getIpAddress() { return $this->ip_address; }
    public function getDateCreation() { return $this->date_creation; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setTraitementId($traitement_id) { $this->traitement_id = $traitement_id; }
    public function setNote($note) { $this->note = $note; }
    public function setIpAddress($ip_address) { $this->ip_address = $ip_address; }
    public function setDateCreation($date_creation) { $this->date_creation = $date_creation; }
    
    public function toArray() {
        return [
            'id' => $this->id,
            'traitement_id' => $this->traitement_id,
            'note' => $this->note,
            'ip_address' => $this->ip_address,
            'date_creation' => $this->date_creation
        ];
    }
}
?>