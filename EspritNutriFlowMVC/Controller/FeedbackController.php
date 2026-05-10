<?php
require_once __DIR__ . '/../model/Feedback.php';
require_once __DIR__ . '/../Config/Database.php';

class FeedbackController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Récupérer tous les feedbacks
    public function getAllFeedbacks() {
        $stmt = $this->db->query("SELECT * FROM feedbacks ORDER BY date_creation DESC");
        return $stmt->fetchAll();
    }
    
    // Récupérer les feedbacks en attente
    public function getPendingFeedbacks() {
        $stmt = $this->db->query("SELECT * FROM feedbacks WHERE status = 'en_attente' ORDER BY date_creation DESC");
        return $stmt->fetchAll();
    }
    
    // Récupérer les feedbacks approuvés
    public function getApprovedFeedbacks($limit = 5) {
        $stmt = $this->db->query("
            SELECT * FROM feedbacks 
            WHERE status = 'approuve' 
            ORDER BY date_creation DESC 
            LIMIT $limit
        ");
        return $stmt->fetchAll();
    }
    
    // Ajouter un feedback
    public function addFeedback($type, $message, $email = null) {
        $stmt = $this->db->prepare("
            INSERT INTO feedbacks (type, message, email, status, date_creation)
            VALUES (:type, :message, :email, 'approuve', NOW())
        ");
        return $stmt->execute([
            ':type' => $type,
            ':message' => $message,
            ':email' => $email
        ]);
    }
    
    // Approuver un feedback
    public function approveFeedback($id) {
        $stmt = $this->db->prepare("UPDATE feedbacks SET status = 'approuve' WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    
    // Rejeter un feedback
    public function rejectFeedback($id) {
        $stmt = $this->db->prepare("UPDATE feedbacks SET status = 'rejete' WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    
    // Supprimer un feedback
    public function deleteFeedback($id) {
        $stmt = $this->db->prepare("DELETE FROM feedbacks WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    
    // Mettre à jour le statut
    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE feedbacks SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
?>