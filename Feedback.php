<?php
class Feedback {
    private $id;
    private $type;
    private $message;
    private $email;
    private $date_creation;
    private $status;
    
    public function __construct($type = null, $message = null, $email = null) {
        $this->type = $type;
        $this->message = $message;
        $this->email = $email;
        $this->status = 'en_attente';
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getType() { return $this->type; }
    public function getMessage() { return $this->message; }
    public function getEmail() { return $this->email; }
    public function getDateCreation() { return $this->date_creation; }
    public function getStatus() { return $this->status; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setType($type) { $this->type = $type; }
    public function setMessage($message) { $this->message = $message; }
    public function setEmail($email) { $this->email = $email; }
    public function setDateCreation($date_creation) { $this->date_creation = $date_creation; }
    public function setStatus($status) { $this->status = $status; }
}
?>