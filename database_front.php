<?php
// config/database_front.php
// Version avec fonction - Plus professionnelle

function getFrontConnection() {
    static $pdo_front = null;
    
    if ($pdo_front === null) {
        $host = 'localhost';
        $dbname = 'nutriflow_front';
        $username = 'root';
        $password = '';
        
        try {
            $pdo_front = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch(PDOException $e) {
            die("❌ Erreur connexion FRONT : " . $e->getMessage());
        }
    }
    return $pdo_front;
}
?>