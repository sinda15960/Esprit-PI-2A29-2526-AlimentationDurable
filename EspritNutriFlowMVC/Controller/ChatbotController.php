<?php
require_once __DIR__ . '/../Config/Database.php';

class ChatbotController {
    private $db;
    private $apiKey;
    private $useAI;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
  
        $this->apiKey = 'AIzaSyD1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
        $this->useAI = !empty($this->apiKey) && $this->apiKey != 'VOTRE_CLE_API_GEMINI';
    }
    
    // Traiter un message utilisateur avec IA
    public function processMessage($message, $session_id) {
        $message = trim($message);
        
        // Récupérer les données de la base
        $allergies = $this->getAllergies();
        $traitements = $this->getAllTraitements();
        
        // Essayer d'abord l'IA si disponible
        if ($this->useAI) {
            $response = $this->callGeminiAPI($message, $allergies, $traitements);
            if ($response) {
                $this->saveConversation($session_id, $message, $response);
                return ['success' => true, 'response' => $response];
            }
        }
        
        // Fallback: analyse intelligente sans API
        $response = $this->analyzeWithLocalAI($message, $allergies, $traitements);
        $this->saveConversation($session_id, $message, $response);
        return ['success' => true, 'response' => $response];
    }
    
    // Appel à l'API Gemini de Google
    private function callGeminiAPI($message, $allergies, $traitements) {
        if (!$this->apiKey) return null;
        
        // Construire le contexte avec les données de la base
        $allergiesText = "";
        foreach ($allergies as $a) {
            $allergiesText .= "- " . $a['nom'] . " (gravité: " . $a['gravite'] . "): symptômes: " . substr($a['symptomes'], 0, 150) . "\n";
        }
        
        $traitementsText = "";
        foreach ($traitements as $t) {
            $stmt = $this->db->prepare("SELECT nom FROM allergies WHERE id = ?");
            $stmt->execute([$t['allergie_id']]);
            $allergieNom = $stmt->fetchColumn();
            $traitementsText .= "- " . $allergieNom . ": " . substr($t['conseil'], 0, 100) . "\n";
        }
        
        $prompt = "Tu es un assistant médical spécialisé en allergies. Tu connais cette base de données d'allergies :
        
        ALLERGIES:
        $allergiesText
        
        TRAITEMENTS:
        $traitementsText
        
        Règles importantes:
        1. Si l'utilisateur demande des SYMPTÔMES d'une allergie spécifique, donne UNIQUEMENT les symptômes
        2. Si l'utilisateur décrit des SYMPTÔMES (ex: 'je respire mal', 'j'ai des boutons'), identifie quelle(s) allergie(s) pourraient causer ces symptômes
        3. Sois concis et précis
        4. En cas d'urgence (difficultés respiratoires, gonflement du visage), recommande d'appeler le 15
        5. Réponds en français
        
        Message de l'utilisateur: \"$message\"
        
        Réponse:";
        
        // Appel à l'API Gemini
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=" . $this->apiKey;
        
        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 500
            ]
        ];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode == 200) {
            $result = json_decode($response, true);
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                return $result['candidates'][0]['content']['parts'][0]['text'];
            }
        }
        
        return null;
    }
    
    // Analyse locale intelligente (fallback)
    private function analyzeWithLocalAI($message, $allergies, $traitements) {
        $message = strtolower(trim($message));
        
        // Détection de la demande de SYMPTÔMES d'une allergie spécifique
        foreach ($allergies as $a) {
            if (strpos($message, strtolower($a['nom'])) !== false) {
                // Si l'utilisateur demande les symptômes
                if (strpos($message, 'symptome') !== false || strpos($message, 'symptômes') !== false) {
                    return "📋 **Symptômes de " . $a['nom'] . " :**\n" . $a['symptomes'];
                }
                // Sinon, donner les infos complètes
                return $this->formatAllergieResponse($a, $this->getTraitementForAllergie($a['id'], $traitements));
            }
        }
        
        // Analyse des SYMPTÔMES décrits par l'utilisateur
        $symptomesMapping = [
            'respire' => ['difficultés respiratoires', 'asthme'],
            'respir' => ['difficultés respiratoires', 'asthme'],
            'tousse' => ['toux', 'asthme'],
            'toux' => ['toux', 'asthme'],
            'œil' => ['yeux rouges', 'yeux qui piquent'],
            'yeux' => ['yeux rouges', 'yeux qui piquent'],
            'gratte' => ['démangeaisons', 'urticaire'],
            'urticaire' => ['urticaire', 'éruptions'],
            'bouton' => ['urticaire', 'éruptions cutanées'],
            'ventre' => ['douleurs abdominales', 'ballonnements'],
            'diarrhee' => ['diarrhée'],
            'gonfle' => ['gonflement du visage', 'œdème'],
            'malaise' => ['fatigue', 'malaise']
        ];
        
        $matchingAllergies = [];
        foreach ($symptomesMapping as $mot => $symptomes) {
            if (strpos($message, $mot) !== false) {
                foreach ($allergies as $a) {
                    foreach ($symptomes as $symptome) {
                        if (stripos($a['symptomes'], $symptome) !== false) {
                            $matchingAllergies[$a['id']] = $a;
                        }
                    }
                }
            }
        }
        
        if (!empty($matchingAllergies)) {
            $response = "🔍 **Allergies possibles selon vos symptômes :**\n\n";
            foreach (array_slice($matchingAllergies, 0, 3) as $a) {
                $emoji = $a['gravite'] == 'severe' ? '🔴' : ($a['gravite'] == 'moderate' ? '🟠' : '🟢');
                $response .= "{$emoji} **" . $a['nom'] . "**\n";
                $response .= "   Symptômes : " . substr($a['symptomes'], 0, 80) . "...\n\n";
            }
            $response .= "⚠️ **Important** : Consultez un médecin pour un diagnostic précis.";
            return $response;
        }
        
        // Urgence respiratoire
        if (strpos($message, 'respire') !== false && (strpos($message, 'pas') !== false || strpos($message, 'mal') !== false)) {
            return "🚨 **URGENCE** 🚨\n\nDifficultés respiratoires = signe de gravité !\n\n🔴 Allergies pouvant causer des problèmes respiratoires :\n• Arachides (sévère)\n• Fruits de mer (sévère)\n• Poisson (sévère)\n• Acariens (asthme)\n• Pollen (asthme)\n\n📞 **Appelez immédiatement le 15 (SAMU)** si la personne a du mal à respirer !";
        }
        
        // Réponse par défaut
        return "💬 **Quelques exemples de questions :**\n\n• \"Donne-moi les symptômes du gluten\"\n• \"Je tousse et j'ai du mal à respirer, quelle allergie ?\"\n• \"Quels sont les traitements pour l'urticaire ?\"\n• \"Urgence respiratoire\"";
    }
    
    private function getAllergies() {
        $stmt = $this->db->query("SELECT * FROM allergies");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getAllTraitements() {
        $stmt = $this->db->query("SELECT * FROM traitements");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getTraitementForAllergie($allergie_id, $traitements) {
        foreach ($traitements as $t) {
            if ($t['allergie_id'] == $allergie_id) {
                return $t;
            }
        }
        return null;
    }
    
    private function formatAllergieResponse($allergie, $traitement) {
        $gravite_emoji = $allergie['gravite'] == 'severe' ? '🔴' : ($allergie['gravite'] == 'moderate' ? '🟠' : '🟢');
        
        $response = "{$gravite_emoji} **" . ucfirst($allergie['nom']) . "**\n\n";
        $response .= "**Description :** " . $allergie['description'] . "\n\n";
        $response .= "**Symptômes :** " . $allergie['symptomes'] . "\n\n";
        
        if ($traitement) {
            $response .= "**Conseils :** " . $traitement['conseil'] . "\n";
            if ($traitement['interdits']) {
                $response .= "**À éviter :** " . $traitement['interdits'] . "\n";
            }
            if ($traitement['medicaments']) {
                $response .= "**Médicaments :** " . $traitement['medicaments'] . "\n";
            }
            $response .= "**Niveau d'urgence :** " . ucfirst($traitement['niveau_urgence']) . "\n";
        }
        
        $response .= "\n💡 Besoin de plus d'infos ? Visitez la fiche détaillée de cette allergie !";
        
        return $response;
    }
    
    private function saveConversation($session_id, $user_message, $bot_response) {
        $stmt = $this->db->prepare("
            INSERT INTO chatbot_conversations (session_id, user_message, bot_response)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$session_id, $user_message, $bot_response]);
    }
    
    public function getConversationHistory($session_id, $limit = 20) {
        $stmt = $this->db->prepare("
            SELECT * FROM chatbot_conversations 
            WHERE session_id = ? 
            ORDER BY created_at ASC 
            LIMIT ?
        ");
        $stmt->execute([$session_id, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>