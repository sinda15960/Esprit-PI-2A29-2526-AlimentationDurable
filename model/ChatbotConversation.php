<?php
// Model/ChatbotConversation.php - Historique chat
class ChatbotConversation {
    private $id;
    private $session_id;
    private $user_message;
    private $bot_response;
    private $created_at;
    
    public function __construct($session_id = null, $user_message = null, $bot_response = null) {
        $this->session_id = $session_id;
        $this->user_message = $user_message;
        $this->bot_response = $bot_response;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getSessionId() { return $this->session_id; }
    public function getUserMessage() { return $this->user_message; }
    public function getBotResponse() { return $this->bot_response; }
    public function getCreatedAt() { return $this->created_at; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setSessionId($session_id) { $this->session_id = $session_id; }
    public function setUserMessage($user_message) { $this->user_message = $user_message; }
    public function setBotResponse($bot_response) { $this->bot_response = $bot_response; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
    
    public function toArray() {
        return [
            'id' => $this->id,
            'session_id' => $this->session_id,
            'user_message' => $this->user_message,
            'bot_response' => $this->bot_response,
            'created_at' => $this->created_at
        ];
    }
}
?>