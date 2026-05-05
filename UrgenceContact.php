<?php
class UrgenceContact {
    private $id;
    private $session_id;
    private $nom;
    private $relation;
    private $telephone;
    private $email;
    private $is_primary;
    private $created_at;
    private $updated_at;
    
    public function __construct($session_id = null, $nom = null, $telephone = null) {
        $this->session_id = $session_id;
        $this->nom = $nom;
        $this->telephone = $telephone;
        $this->is_primary = false;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getSessionId() { return $this->session_id; }
    public function getNom() { return $this->nom; }
    public function getRelation() { return $this->relation; }
    public function getTelephone() { return $this->telephone; }
    public function getEmail() { return $this->email; }
    public function getIsPrimary() { return $this->is_primary; }
    public function getCreatedAt() { return $this->created_at; }
    public function getUpdatedAt() { return $this->updated_at; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setSessionId($session_id) { $this->session_id = $session_id; }
    public function setNom($nom) { $this->nom = $nom; }
    public function setRelation($relation) { $this->relation = $relation; }
    public function setTelephone($telephone) { $this->telephone = $telephone; }
    public function setEmail($email) { $this->email = $email; }
    public function setIsPrimary($is_primary) { $this->is_primary = $is_primary; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }
    
    public function toArray() {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'relation' => $this->relation,
            'telephone' => $this->telephone,
            'email' => $this->email
        ];
    }
}
?>