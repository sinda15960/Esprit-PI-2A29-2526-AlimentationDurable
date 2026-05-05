<?php
class VoiceSettings {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getSettings($userId = null) {
        $query = "SELECT * FROM voice_settings WHERE user_id IS NULL";
        if($userId) {
            $query = "SELECT * FROM voice_settings WHERE user_id = :user_id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $userId);
        } else {
            $stmt = $this->conn->prepare($query);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$result) {
            return [
                'voice_gender' => 'female',
                'voice_rate' => 1.0,
                'voice_pitch' => 1.0,
                'voice_volume' => 1.0,
                'enabled' => 1
            ];
        }
        return $result;
    }
    
    public function updateSettings($userId, $data) {
        $query = "INSERT INTO voice_settings (user_id, voice_gender, voice_rate, voice_pitch, voice_volume, enabled)
                  VALUES (:user_id, :voice_gender, :voice_rate, :voice_pitch, :voice_volume, :enabled)
                  ON DUPLICATE KEY UPDATE
                  voice_gender = VALUES(voice_gender),
                  voice_rate = VALUES(voice_rate),
                  voice_pitch = VALUES(voice_pitch),
                  voice_volume = VALUES(voice_volume),
                  enabled = VALUES(enabled)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":voice_gender", $data['voice_gender']);
        $stmt->bindParam(":voice_rate", $data['voice_rate']);
        $stmt->bindParam(":voice_pitch", $data['voice_pitch']);
        $stmt->bindParam(":voice_volume", $data['voice_volume']);
        $stmt->bindParam(":enabled", $data['enabled']);
        
        return $stmt->execute();
    }
}
?>