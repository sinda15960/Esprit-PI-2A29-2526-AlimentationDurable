<?php
require_once dirname(__DIR__) . '/config/database.php';

class User {
    private $conn;
    private $table = "users";

    public $id;
    public $username;
    public $email;
    public $password;
    public $full_name;
    public $phone;
    public $age;
    public $weight;
    public $height;
    public $dietary_preference;
    public $role;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Vérifier si l'email est un email admin (contient _admin@gmail.com)
    public function isAdminEmail($email) {
        return strpos($email, '_admin@gmail.com') !== false;
    }

    public function register() {
        // Déterminer le rôle basé sur l'email
        $role = $this->isAdminEmail($this->email) ? 'admin' : 'user';
        
        $query = "INSERT INTO " . $this->table . " 
                  SET username=:username, email=:email, password=:password, 
                      full_name=:full_name, phone=:phone, age=:age, 
                      weight=:weight, height=:height, dietary_preference=:dietary_preference,
                      role=:role";
        
        $stmt = $this->conn->prepare($query);
        
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":full_name", $this->full_name);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":age", $this->age);
        $stmt->bindParam(":weight", $this->weight);
        $stmt->bindParam(":height", $this->height);
        $stmt->bindParam(":dietary_preference", $this->dietary_preference);
        $stmt->bindParam(":role", $role);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function login() {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            if(password_verify($this->password, $row['password'])) {
                $this->id = $row['id'];
                $this->username = $row['username'];
                $this->email = $row['email'];
                $this->full_name = $row['full_name'];
                $this->phone = $row['phone'];
                $this->age = $row['age'];
                $this->weight = $row['weight'];
                $this->height = $row['height'];
                $this->dietary_preference = $row['dietary_preference'];
                $this->role = $row['role'];
                return true;
            }
        }
        return false;
    }

    public function updateProfile() {
        $query = "UPDATE " . $this->table . " 
                  SET username=:username, full_name=:full_name, phone=:phone, 
                      age=:age, weight=:weight, height=:height, 
                      dietary_preference=:dietary_preference 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":full_name", $this->full_name);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":age", $this->age);
        $stmt->bindParam(":weight", $this->weight);
        $stmt->bindParam(":height", $this->height);
        $stmt->bindParam(":dietary_preference", $this->dietary_preference);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getAllUsers() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUserById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function updateUserByAdmin($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET username=:username, email=:email, full_name=:full_name, 
                      phone=:phone, age=:age, role=:role 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $data['username']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":full_name", $data['full_name']);
        $stmt->bindParam(":phone", $data['phone']);
        $stmt->bindParam(":age", $data['age']);
        $stmt->bindParam(":role", $data['role']);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }

    public function deleteUser($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
    
    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    public function deleteAccount($id) {
    $query = "DELETE FROM " . $this->table . " WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id", $id);
    return $stmt->execute();
}
}
?>