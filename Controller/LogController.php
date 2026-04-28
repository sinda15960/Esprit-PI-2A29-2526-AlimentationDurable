<?php
require_once __DIR__ . '/../Model/Log.php';
require_once __DIR__ . '/../Config/Database.php';

class LogController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Ajouter un log d'audit
     */
    public function addLog($action_type, $table_name, $record_id, $record_name, $details = null) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $admin = $_SESSION['username'] ?? 'admin';
        
        $stmt = $this->db->prepare("
            INSERT INTO logs (action_type, table_name, record_id, record_name, details, admin_user, ip_address)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$action_type, $table_name, $record_id, $record_name, $details, $admin, $ip]);
    }
    
    /**
     * Récupérer tous les logs avec limite
     */
    public function getAllLogs($limit = 50) {
        $stmt = $this->db->prepare("
            SELECT * FROM logs 
            ORDER BY created_at DESC 
            LIMIT :limit
        ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupérer les logs par table
     */
    public function getLogsByTable($table_name, $limit = 20) {
        $stmt = $this->db->prepare("
            SELECT * FROM logs 
            WHERE table_name = :table_name 
            ORDER BY created_at DESC 
            LIMIT :limit
        ");
        $stmt->bindParam(':table_name', $table_name, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupérer les logs récents pour le dashboard (VERSION CORRIGÉE)
     */
    public function getRecentLogs($limit = 10) {
        // Version avec bindParam pour éviter l'erreur SQL
        $sql = "SELECT * FROM logs ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupérer les logs par action (ADD, EDIT, DELETE)
     */
    public function getLogsByAction($action_type, $limit = 20) {
        $stmt = $this->db->prepare("
            SELECT * FROM logs 
            WHERE action_type = :action_type 
            ORDER BY created_at DESC 
            LIMIT :limit
        ");
        $stmt->bindParam(':action_type', $action_type, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Compter le nombre total de logs
     */
    public function countLogs() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM logs");
        return $stmt->fetchColumn();
    }
    
    /**
     * Supprimer les logs plus anciens qu'une certaine date
     */
    public function cleanOldLogs($days = 30) {
        $stmt = $this->db->prepare("
            DELETE FROM logs 
            WHERE created_at < DATE_SUB(NOW(), INTERVAL :days DAY)
        ");
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Récupérer les logs pour une allergie spécifique
     */
    public function getLogsForAllergie($allergie_id, $limit = 10) {
        $stmt = $this->db->prepare("
            SELECT * FROM logs 
            WHERE table_name = 'allergies' AND record_id = :record_id
            ORDER BY created_at DESC 
            LIMIT :limit
        ");
        $stmt->bindParam(':record_id', $allergie_id, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupérer les logs avec filtres avancés
     */
    public function getLogsWithFilters($filters = [], $limit = 30) {
        $sql = "SELECT * FROM logs WHERE 1=1";
        $params = [];
        
        if (!empty($filters['action_type'])) {
            $sql .= " AND action_type = :action_type";
            $params[':action_type'] = $filters['action_type'];
        }
        
        if (!empty($filters['table_name'])) {
            $sql .= " AND table_name = :table_name";
            $params[':table_name'] = $filters['table_name'];
        }
        
        if (!empty($filters['admin_user'])) {
            $sql .= " AND admin_user LIKE :admin_user";
            $params[':admin_user'] = '%' . $filters['admin_user'] . '%';
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(created_at) >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(created_at) <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>