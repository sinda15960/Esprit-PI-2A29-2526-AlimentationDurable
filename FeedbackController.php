<?php
require_once __DIR__ . '/../Model/Feedback.php';

class FeedbackController {
    
    public function getApprovedFeedbacks() {
        return Feedback::findApproved();
    }
    
    public function addFeedback($data) {
        if (empty($data['message']) || strlen($data['message']) < 5) {
            return ['success' => false, 'message' => 'Message trop court (min 5 caractères)'];
        }
        
        $feedback = new Feedback(
            $data['type'],
            $data['message'],
            $data['email'] ?? null
        );
        
        if ($feedback->save()) {
            return ['success' => true, 'message' => 'Feedback envoyé avec succès'];
        }
        return ['success' => false, 'message' => 'Erreur lors de l\'envoi'];
    }
}
?>