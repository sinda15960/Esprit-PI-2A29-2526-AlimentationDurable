<?php
require_once __DIR__ . '/model/UserPollenPrefs.php';
require_once __DIR__ . '/../Config/Database.php';

class PollenController {
    private $db;
    private $apiKey;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->apiKey = 'ce7ce0e68db3bb9d8d961d1bfde6b275'; // Votre clé OpenWeatherMap
    }
    
    public function getPreferences($session_id) {
        $stmt = $this->db->prepare("SELECT * FROM user_pollen_prefs WHERE session_id = ?");
        $stmt->execute([$session_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $prefs = new UserPollenPrefs();
            $prefs->setId($row['id']);
            $prefs->setSessionId($row['session_id']);
            $prefs->setVille($row['ville']);
            $prefs->setLatitude($row['latitude']);
            $prefs->setLongitude($row['longitude']);
            $prefs->setPollenAllergy($row['pollen_allergy']);
            $prefs->setAcarienAllergy($row['acarien_allergy']);
            $prefs->setMoisissureAllergy($row['moisissure_allergy']);
            $prefs->setAlertEmail($row['alert_email']);
            $prefs->setAlertPhone($row['alert_phone']);
            $prefs->setAlertThreshold($row['alert_threshold']);
            return $prefs;
        }
        
        return $this->createDefaultPreferences($session_id);
    }
    
    public function createDefaultPreferences($session_id) {
        $stmt = $this->db->prepare("
            INSERT INTO user_pollen_prefs (session_id, ville, pollen_allergy, alert_threshold)
            VALUES (?, 'Tunis', FALSE, 70)
        ");
        $stmt->execute([$session_id]);
        return $this->getPreferences($session_id);
    }
    
    public function savePreferences($session_id, $data) {
        $stmt = $this->db->prepare("
            UPDATE user_pollen_prefs 
            SET ville = ?, pollen_allergy = ?, acarien_allergy = ?, 
                moisissure_allergy = ?, alert_email = ?, alert_phone = ?, alert_threshold = ?
            WHERE session_id = ?
        ");
        return $stmt->execute([
            $data['ville'],
            $data['pollen_allergy'] ?? 0,
            $data['acarien_allergy'] ?? 0,
            $data['moisissure_allergy'] ?? 0,
            $data['alert_email'] ?? null,
            $data['alert_phone'] ?? null,
            $data['alert_threshold'] ?? 70,
            $session_id
        ]);
    }
    
    public function getPollenData($ville = 'Tunis') {
        $stmt = $this->db->prepare("SELECT data, last_update FROM pollen_cache WHERE ville = ?");
        $stmt->execute([$ville]);
        $cached = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($cached && strtotime($cached['last_update']) > strtotime('-1 hour')) {
            return json_decode($cached['data'], true);
        }
        
        $data = $this->fetchPollenFromAPI($ville);
        
        $stmt = $this->db->prepare("
            INSERT INTO pollen_cache (ville, data, last_update) 
            VALUES (?, ?, NOW()) 
            ON DUPLICATE KEY UPDATE data = ?, last_update = NOW()
        ");
        $stmt->execute([$ville, json_encode($data), json_encode($data)]);
        
        return $data;
    }
    
    private function fetchPollenFromAPI($ville) {
        $url = "http://api.openweathermap.org/data/2.5/weather?q=" . urlencode($ville) . "&appid=" . $this->apiKey . "&units=metric&lang=fr";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode == 200 && $response) {
            $data = json_decode($response, true);
            return $this->formatWeatherData($data, $ville);
        }
        
        return $this->getSimulatedPollenData($ville);
    }
    
    private function formatWeatherData($apiData, $ville) {
        $month = (int)date('n');
        $riskLevel = 'moyen';
        $pollenLevel = 50;
        
        if ($month >= 3 && $month <= 6) {
            $riskLevel = 'eleve';
            $pollenLevel = 75;
        } elseif ($month >= 10 && $month <= 12) {
            $riskLevel = 'faible';
            $pollenLevel = 25;
        }
        
        $pollens = [];
        if ($month >= 3 && $month <= 6) {
            $pollens[] = ['nom' => 'Graminees', 'niveau' => rand(60, 85)];
            $pollens[] = ['nom' => 'Olivier', 'niveau' => rand(40, 70)];
        } elseif ($month >= 2 && $month <= 4) {
            $pollens[] = ['nom' => 'Bouleau', 'niveau' => rand(50, 80)];
        } else {
            $pollens[] = ['nom' => 'Aucun', 'niveau' => rand(10, 30)];
        }
        
        $recommendations = $this->getRecommendations($riskLevel);
        
        return [
            'ville' => $ville,
            'date' => date('Y-m-d H:i:s'),
            'risk_level' => $riskLevel,
            'pm2_5' => $pollenLevel,
            'pm10' => $pollenLevel + 10,
            'pollens' => $pollens,
            'recommendations' => $recommendations
        ];
    }
    
    private function getSimulatedPollenData($ville) {
        $month = (int)date('n');
        $riskLevel = 'moyen';
        $pollenLevel = 50;
        
        if ($month >= 3 && $month <= 6) {
            $riskLevel = 'eleve';
            $pollenLevel = 78;
        } elseif ($month >= 10 && $month <= 12) {
            $riskLevel = 'faible';
            $pollenLevel = 25;
        }
        
        $pollens = [];
        if ($month >= 3 && $month <= 6) {
            $pollens[] = ['nom' => 'Graminees', 'niveau' => rand(60, 85)];
            $pollens[] = ['nom' => 'Olivier', 'niveau' => rand(40, 70)];
        } elseif ($month >= 2 && $month <= 4) {
            $pollens[] = ['nom' => 'Bouleau', 'niveau' => rand(50, 80)];
        } else {
            $pollens[] = ['nom' => 'Aucun', 'niveau' => rand(10, 30)];
        }
        
        return [
            'ville' => $ville,
            'date' => date('Y-m-d H:i:s'),
            'risk_level' => $riskLevel,
            'pm2_5' => $pollenLevel,
            'pm10' => $pollenLevel + 10,
            'pollens' => $pollens,
            'recommendations' => $this->getRecommendations($riskLevel)
        ];
    }
    
    private function getRecommendations($risk) {
        $recommendations = [
            'faible' => [
                '✅ Profitez des activites exterieures',
                '💊 Prenez votre traitement si necessaire',
                '🚿 Lavez-vous les mains en rentrant'
            ],
            'moyen' => [
                '⚠️ Surveillez vos symptomes',
                '💊 Prenez votre traitement preventif',
                '🚿 Douche et changez de vetements en rentrant'
            ],
            'eleve' => [
                '🚨 Niveau POLLEN ELEVE !',
                '🏠 Restez a l\'interieur si possible',
                '😷 Portez un masque FFP2 a l\'exterieur',
                '💊 Prenez votre traitement antihistaminique',
                '🚿 Douche immediate en rentrant'
            ]
        ];
        
        return $recommendations[$risk] ?? $recommendations['moyen'];
    }
    
    public function checkAndSendAlert($session_id) {
        $prefs = $this->getPreferences($session_id);
        if (!$prefs->getPollenAllergy()) {
            return ['alert' => false, 'message' => 'Pas allergique au pollen'];
        }
        
        $pollenData = $this->getPollenData($prefs->getVille());
        $threshold = $prefs->getAlertThreshold();
        
        $maxPollenLevel = 0;
        foreach ($pollenData['pollens'] as $pollen) {
            if ($pollen['niveau'] > $maxPollenLevel) {
                $maxPollenLevel = $pollen['niveau'];
            }
        }
        
        $isAlert = $maxPollenLevel >= $threshold;
        
        if ($isAlert) {
            $stmt = $this->db->prepare("
                INSERT INTO pollen_alerts_history (session_id, pollen_type, level, risk_level, message, email_sent)
                VALUES (?, 'Pollen General', ?, ?, ?, FALSE)
            ");
            $message = "Alerte pollen ! Niveau {$maxPollenLevel}% a " . $prefs->getVille();
            $stmt->execute([$session_id, $maxPollenLevel, $pollenData['risk_level'], $message]);
            
            return [
                'alert' => true,
                'level' => $maxPollenLevel,
                'risk' => $pollenData['risk_level'],
                'message' => $message,
                'recommendations' => $pollenData['recommendations']
            ];
        }
        
        return ['alert' => false, 'level' => $maxPollenLevel, 'message' => 'Niveau pollen normal'];
    }
    
    public function getAlertHistory($session_id, $limit = 10) {
        // Version corrigée : utilisation de bindParam avec PDO::PARAM_INT
        $sql = "SELECT * FROM pollen_alerts_history WHERE session_id = ? ORDER BY sent_at DESC LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $session_id, PDO::PARAM_STR);
        $stmt->bindParam(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAvailableCities() {
        return ['Tunis', 'Sfax', 'Sousse', 'Nabeul', 'Bizerte', 'Gabes', 'Monastir', 'Kairouan'];
    }
}
?>