<?php
require_once __DIR__ . '/../Config/Database.php';

class Feedback {
    private $id;
    private $type;
    private $message;
    private $email;
    private $status;
    private $date_creation;
    
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
    public function getStatus() { return $this->status; }
    public function getDateCreation() { return $this->date_creation; }
    
    // Setters
    public function setType($type) { $this->type = $type; }
    public function setMessage($message) { $this->message = $message; }
    public function setEmail($email) { $this->email = $email; }
    public function setStatus($status) { $this->status = $status; }
    
    public static function findAll() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM feedbacks ORDER BY date_creation DESC");
        return $stmt->fetchAll();
    }
    
    public static function findApproved() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM feedbacks WHERE status = 'approuve' ORDER BY date_creation DESC LIMIT 5");
        return $stmt->fetchAll();
    }
    
    public static function findPending() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM feedbacks WHERE status = 'en_attente' ORDER BY date_creation DESC");
        return $stmt->fetchAll();
    }
    
    public function save() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO feedbacks (type, message, email, status) VALUES (:type, :message, :email, :status)");
        return $stmt->execute([
            ':type' => $this->type,
            ':message' => $this->message,
            ':email' => $this->email,
            ':status' => $this->status
        ]);
    }
    
    public static function updateStatus($id, $status) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE feedbacks SET status = :status WHERE id = :id");
        return $stmt->execute([':id' => $id, ':status' => $status]);
    }
    
    public static function delete($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM feedbacks WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>