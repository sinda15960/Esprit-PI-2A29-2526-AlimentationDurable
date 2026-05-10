<?php
require_once dirname(__DIR__) . '/config/database.php';

class User {
    private $conn;
    private $table = "users";

    // Attributs
    private $id;
    private $username;
    private $email;
    private $password;
    private $full_name;
    private $phone;
    private $age;
    private $weight;
    private $height;
    private $dietary_preference;
    private $role;

    // Constructeur
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getFullName() {
        return $this->full_name;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getAge() {
        return $this->age;
    }

    public function getWeight() {
        return $this->weight;
    }

    public function getHeight() {
        return $this->height;
    }

    public function getDietaryPreference() {
        return $this->dietary_preference;
    }

    public function getRole() {
        return $this->role;
    }

    public function getConnection() {
        return $this->conn;
    }

    public function getTable() {
        return $this->table;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setFullName($full_name) {
        $this->full_name = $full_name;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function setAge($age) {
        $this->age = $age;
    }

    public function setWeight($weight) {
        $this->weight = $weight;
    }

    public function setHeight($height) {
        $this->height = $height;
    }

    public function setDietaryPreference($dietary_preference) {
        $this->dietary_preference = $dietary_preference;
    }

    public function setRole($role) {
        $this->role = $role;
    }
}
?>
