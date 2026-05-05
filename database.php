<?php
class Database {
    private static $instance = null;
    private $conn;
    
    private $host = 'localhost';
    private $dbname = 'nutriflow_db';   // Vérifiez que c'est le bon nom
    private $username = 'root';          // XAMPP: root par défaut
    private $password = '';              // XAMPP: vide par défaut
    
    private function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Erreur DB: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
}
?>