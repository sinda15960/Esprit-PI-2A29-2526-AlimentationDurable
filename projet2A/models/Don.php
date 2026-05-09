<?php
require_once __DIR__ . '/Model.php';

class Don extends Model {
    protected $table = 'dons';
    
    public function create($data) {
        $sql = "INSERT INTO dons (association_id, donor_name, donor_email, donor_phone, amount, 
                donation_type, food_type, quantity, message, payment_method) 
                VALUES (:association_id, :donor_name, :donor_email, :donor_phone, :amount, 
                :donation_type, :food_type, :quantity, :message, :payment_method)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function update($id, $data) {
        $sql = "UPDATE dons SET status = :status WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function getDonsWithAssociation() {
        $sql = "SELECT d.*, a.name as association_name 
                FROM dons d JOIN associations a ON d.association_id = a.id 
                ORDER BY d.created_at DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
    
    // ============ MÉTHODE MANQUANTE À AJOUTER ============
    public function findDonationById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(array('id' => $id));
        return $stmt->fetch();
    }
    // ====================================================
    
    // Total des dons VALIDES (exclut les annulés)
    public function getTotalDonations() {
        $stmt = $this->pdo->query("SELECT SUM(amount) as total FROM dons WHERE status != 'cancelled'");
        $result = $stmt->fetch();
        return isset($result['total']) ? $result['total'] : 0;
    }
    
    // Total des dons CONFIRMÉS uniquement
    public function getTotalConfirmedDonations() {
        $stmt = $this->pdo->query("SELECT SUM(amount) as total FROM dons WHERE status = 'confirmed'");
        $result = $stmt->fetch();
        return isset($result['total']) ? $result['total'] : 0;
    }
    
    // Total des dons EN ATTENTE
    public function getTotalPendingDonations() {
        $stmt = $this->pdo->query("SELECT SUM(amount) as total FROM dons WHERE status = 'pending'");
        $result = $stmt->fetch();
        return isset($result['total']) ? $result['total'] : 0;
    }
    
    // Total des dons LIVRÉS
    public function getTotalDeliveredDonations() {
        $stmt = $this->pdo->query("SELECT SUM(amount) as total FROM dons WHERE status = 'delivered'");
        $result = $stmt->fetch();
        return isset($result['total']) ? $result['total'] : 0;
    }
    
    // Total des dons ANNULÉS
    public function getTotalCancelledDonations() {
        $stmt = $this->pdo->query("SELECT SUM(amount) as total FROM dons WHERE status = 'cancelled'");
        $result = $stmt->fetch();
        return isset($result['total']) ? $result['total'] : 0;
    }
}
?>
