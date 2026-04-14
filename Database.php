<?php
class Database {
    private static $instance_front = null;
    private static $instance_back = null;
    
    public static function getFrontConnection() {
        if (self::$instance_front === null) {
            try {
                self::$instance_front = new PDO(
                    "mysql:host=localhost;dbname=nutriflow_front;charset=utf8mb4",
                    "front_user",
                    "front_password",
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch(PDOException $e) {
                die("Erreur BD Front: " . $e->getMessage());
            }
        }
        return self::$instance_front;
    }
    
    public static function getBackConnection() {
        if (self::$instance_back === null) {
            try {
                self::$instance_back = new PDO(
                    "mysql:host=localhost;dbname=nutriflow_back;charset=utf8mb4",
                    "back_user",
                    "back_password",
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch(PDOException $e) {
                die("Erreur BD Back: " . $e->getMessage());
            }
        }
        return self::$instance_back;
    }
}
?>