<?php
require_once __DIR__ . '/Model.php';

class Association extends Model {
    // Attributs
    private $id;
    private $name;
    private $email;
    private $phone;
    private $address;
    private $city;
    private $postal_code;
    private $siret;
    private $mission;
    private $status;
    private $created_at;
    private $updated_at;
    
    protected $table = 'associations';
    
    // Constructeur
    public function __construct($data = array()) {
        parent::__construct();
        if(!empty($data)) {
            $this->hydrate($data);
        }
    }
    
    // Destructeur
    public function __destruct() {
        parent::__destruct();
    }
    
    // Hydratation
    public function hydrate($data) {
        foreach($data as $key => $value) {
            $method = 'set' . str_replace('_', '', ucwords($key, '_'));
            if(method_exists($this, $method)) {
                $this->$method($value);
            }
        }
        return $this;
    }
    
    // ============ GETTERS ============
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    public function getPhone() { return $this->phone; }
    public function getAddress() { return $this->address; }
    public function getCity() { return $this->city; }
    public function getPostalCode() { return $this->postal_code; }
    public function getSiret() { return $this->siret; }
    public function getMission() { return $this->mission; }
    public function getStatus() { return $this->status; }
    public function getCreatedAt() { return $this->created_at; }
    public function getUpdatedAt() { return $this->updated_at; }
    
    // ============ SETTERS ============
    public function setId($id) { $this->id = (int)$id; return $this; }
    public function setName($name) { $this->name = htmlspecialchars(trim($name)); return $this; }
    public function setEmail($email) { $this->email = trim($email); return $this; }
    public function setPhone($phone) { $this->phone = trim($phone); return $this; }
    public function setAddress($address) { $this->address = htmlspecialchars(trim($address)); return $this; }
    public function setCity($city) { $this->city = htmlspecialchars(trim($city)); return $this; }
    public function setPostalCode($postal_code) { $this->postal_code = trim($postal_code); return $this; }
    public function setSiret($siret) { $this->siret = trim($siret); return $this; }
    public function setMission($mission) { $this->mission = htmlspecialchars(trim($mission)); return $this; }
    public function setStatus($status) { $this->status = $status; return $this; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; return $this; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; return $this; }
    
    // Convertir l'objet en tableau
    public function toArray() {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'siret' => $this->siret,
            'mission' => $this->mission,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        );
    }
}
?>
