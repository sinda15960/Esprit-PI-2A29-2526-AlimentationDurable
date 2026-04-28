<?php
require_once __DIR__ . '/Model.php';

class Don extends Model {
    // Attributs
    private $id;
    private $association_id;
    private $donor_name;
    private $donor_email;
    private $donor_phone;
    private $amount;
    private $donation_type;
    private $food_type;
    private $quantity;
    private $message;
    private $status;
    private $payment_method;
    private $created_at;
    
    protected $table = 'dons';
    
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
    public function getAssociationId() { return $this->association_id; }
    public function getDonorName() { return $this->donor_name; }
    public function getDonorEmail() { return $this->donor_email; }
    public function getDonorPhone() { return $this->donor_phone; }
    public function getAmount() { return $this->amount; }
    public function getDonationType() { return $this->donation_type; }
    public function getFoodType() { return $this->food_type; }
    public function getQuantity() { return $this->quantity; }
    public function getMessage() { return $this->message; }
    public function getStatus() { return $this->status; }
    public function getPaymentMethod() { return $this->payment_method; }
    public function getCreatedAt() { return $this->created_at; }
    
    // ============ SETTERS ============
    public function setId($id) { $this->id = (int)$id; return $this; }
    public function setAssociationId($association_id) { $this->association_id = (int)$association_id; return $this; }
    public function setDonorName($donor_name) { $this->donor_name = htmlspecialchars(trim($donor_name)); return $this; }
    public function setDonorEmail($donor_email) { $this->donor_email = trim($donor_email); return $this; }
    public function setDonorPhone($donor_phone) { $this->donor_phone = trim($donor_phone); return $this; }
    public function setAmount($amount) { $this->amount = (float)$amount; return $this; }
    public function setDonationType($donation_type) { $this->donation_type = $donation_type; return $this; }
    public function setFoodType($food_type) { $this->food_type = htmlspecialchars(trim($food_type)); return $this; }
    public function setQuantity($quantity) { $this->quantity = (int)$quantity; return $this; }
    public function setMessage($message) { $this->message = htmlspecialchars(trim($message)); return $this; }
    public function setStatus($status) { $this->status = $status; return $this; }
    public function setPaymentMethod($payment_method) { $this->payment_method = $payment_method; return $this; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; return $this; }
    
    // Convertir l'objet en tableau
    public function toArray() {
        return array(
            'id' => $this->id,
            'association_id' => $this->association_id,
            'donor_name' => $this->donor_name,
            'donor_email' => $this->donor_email,
            'donor_phone' => $this->donor_phone,
            'amount' => $this->amount,
            'donation_type' => $this->donation_type,
            'food_type' => $this->food_type,
            'quantity' => $this->quantity,
            'message' => $this->message,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'created_at' => $this->created_at
        );
    }
}
?>
