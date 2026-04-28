<?php
require_once __DIR__ . '/../Model/Evaluation.php';
require_once __DIR__ . '/../Config/Database.php';

class EvaluationController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Ajouter ou mettre à jour une évaluation
    public function addOrUpdateEvaluation($traitement_id, $note) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        
        // Vérifier si l'utilisateur a déjà noté
        $stmt = $this->db->prepare("SELECT id FROM evaluations WHERE traitement_id = ? AND ip_address = ?");
        $stmt->execute([$traitement_id, $ip]);
        $existant = $stmt->fetch();
        
        if ($existant) {
            $stmt = $this->db->prepare("UPDATE evaluations SET note = ? WHERE id = ?");
            $stmt->execute([$note, $existant['id']]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO evaluations (traitement_id, note, ip_address) VALUES (?, ?, ?)");
            $stmt->execute([$traitement_id, $note, $ip]);
        }
        
        // Recalculer la moyenne
        $this->updateMoyenneTraitement($traitement_id);
        
        return true;
    }
    
    // Calculer et mettre à jour la moyenne d'un traitement
    private function updateMoyenneTraitement($traitement_id) {
        $stmt = $this->db->prepare("SELECT AVG(note) as moyenne, COUNT(*) as total FROM evaluations WHERE traitement_id = ?");
        $stmt->execute([$traitement_id]);
        $result = $stmt->fetch();
        
        $moyenne = round($result['moyenne'], 1);
        $total = $result['total'];
        
        $stmt = $this->db->prepare("UPDATE traitements SET note_moyenne = ?, nb_notes = ? WHERE id = ?");
        $stmt->execute([$moyenne, $total, $traitement_id]);
    }
    
    // Récupérer la note d'un traitement pour l'IP actuelle
    public function getUserNote($traitement_id) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $stmt = $this->db->prepare("SELECT note FROM evaluations WHERE traitement_id = ? AND ip_address = ?");
        $stmt->execute([$traitement_id, $ip]);
        $result = $stmt->fetch();
        return $result ? $result['note'] : null;
    }
}
?>