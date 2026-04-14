<?php
// config/database_back.php
// Version avec fonction - Plus professionnelle

function getBackConnection() {
    static $pdo_back = null;
    
    if ($pdo_back === null) {
        $host = 'localhost';
        $dbname = 'nutriflow_back';
        $username = 'root';
        $password = '';
        
        try {
            $pdo_back = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch(PDOException $e) {
            die("❌ Erreur connexion BACK : " . $e->getMessage());
        }
    }
    return $pdo_back;
}
?>