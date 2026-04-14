<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'gestion_plan');
define('DB_USER', 'root');
define('DB_PASS', '');

function getConnection() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            die("Erreur connexion : " . $e->getMessage());
        }
    }
    return $pdo;
}
?>