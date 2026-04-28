<?php
// Model/Rating.php - UNIQUEMENT la structure de la classe
class Rating {
    private $id;
    private $allergie_id;
    private $note;
    private $ip_address;
    private $date_creation;
    
    public function __construct($allergie_id = null, $note = null, $ip_address = null) {
        $this->allergie_id = $allergie_id;
        $this->note = $note;
        $this->ip_address = $ip_address;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getAllergieId() { return $this->allergie_id; }
    public function getNote() { return $this->note; }
    public function getIpAddress() { return $this->ip_address; }
    public function getDateCreation() { return $this->date_creation; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setAllergieId($allergie_id) { $this->allergie_id = $allergie_id; }
    public function setNote($note) { $this->note = $note; }
    public function setIpAddress($ip_address) { $this->ip_address = $ip_address; }
    public function setDateCreation($date_creation) { $this->date_creation = $date_creation; }
    
    public function toArray() {
        return [
            'id' => $this->id,
            'allergie_id' => $this->allergie_id,
            'note' => $this->note,
            'ip_address' => $this->ip_address,
            'date_creation' => $this->date_creation
        ];
    }
}
?>