<?php
// Model/Log.php - Audit log
class Log {
    private $id;
    private $action_type;
    private $table_name;
    private $record_id;
    private $record_name;
    private $details;
    private $admin_user;
    private $ip_address;
    private $created_at;
    
    public function __construct($action_type = null, $table_name = null, $record_id = null, 
                                $record_name = null, $details = null) {
        $this->action_type = $action_type;
        $this->table_name = $table_name;
        $this->record_id = $record_id;
        $this->record_name = $record_name;
        $this->details = $details;
        $this->admin_user = $_SESSION['username'] ?? 'admin';
        $this->ip_address = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getActionType() { return $this->action_type; }
    public function getTableName() { return $this->table_name; }
    public function getRecordId() { return $this->record_id; }
    public function getRecordName() { return $this->record_name; }
    public function getDetails() { return $this->details; }
    public function getAdminUser() { return $this->admin_user; }
    public function getIpAddress() { return $this->ip_address; }
    public function getCreatedAt() { return $this->created_at; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setActionType($action_type) { $this->action_type = $action_type; }
    public function setTableName($table_name) { $this->table_name = $table_name; }
    public function setRecordId($record_id) { $this->record_id = $record_id; }
    public function setRecordName($record_name) { $this->record_name = $record_name; }
    public function setDetails($details) { $this->details = $details; }
    public function setAdminUser($admin_user) { $this->admin_user = $admin_user; }
    public function setIpAddress($ip_address) { $this->ip_address = $ip_address; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
    
    public function toArray() {
        return [
            'id' => $this->id,
            'action_type' => $this->action_type,
            'table_name' => $this->table_name,
            'record_id' => $this->record_id,
            'record_name' => $this->record_name,
            'details' => $this->details,
            'admin_user' => $this->admin_user,
            'ip_address' => $this->ip_address,
            'created_at' => $this->created_at
        ];
    }
    
    public function show() {
        echo "<table border='1' cellpadding='8'>";
        echo "<tr><th>Propriété</th><th>Valeur</th></tr>";
        echo "<tr><td>ID</td><td>{$this->id}</td></tr>";
        echo "<tr><td>Action</td><td>{$this->action_type}</td></tr>";
        echo "<tr><td>Table</td><td>{$this->table_name}</td></tr>";
        echo "<tr><td>Enregistrement</td><td>{$this->record_name} (ID: {$this->record_id})</td></tr>";
        echo "<tr><td>Détails</td><td>" . htmlspecialchars($this->details) . "</td></tr>";
        echo "<tr><td>Admin</td><td>{$this->admin_user}</td></tr>";
        echo "<tr><td>IP</td><td>{$this->ip_address}</td></tr>";
        echo "<tr><td>Date</td><td>{$this->created_at}</td></tr>";
        echo "</table>";
    }
}
?>