<?php

class Database {

    private static ?PDO $front = null;
    private static ?PDO $back = null;
    private static ?PDO $ai = null;

    public static function getFrontConnection(): PDO {
        if (self::$front === null) {
            try {
                self::$front = new PDO(
                    "mysql:host=localhost;dbname=nutriflow_db;charset=utf8mb4",
                    "root",
                    "",
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) {
                die("Erreur BD Front: " . $e->getMessage());
            }
        }
        return self::$front;
    }

    public static function getBackConnection(): PDO {
        if (self::$back === null) {
            try {
                self::$back = new PDO(
                    "mysql:host=localhost;dbname=nutriflow_db;charset=utf8mb4",
                    "root",
                    "",
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) {
                die("Erreur BD Back: " . $e->getMessage());
            }
        }
        return self::$back;
    }

    public static function getAIConnection(): PDO {
        if (self::$ai === null) {
            try {
                self::$ai = new PDO(
                    "mysql:host=localhost;dbname=nutriflow_db;charset=utf8mb4",
                    "root",
                    "",
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) {
                die("Erreur BD AI: " . $e->getMessage());
            }
        }
        return self::$ai;
    }
}