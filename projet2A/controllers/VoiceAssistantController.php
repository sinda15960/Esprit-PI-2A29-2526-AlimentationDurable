<?php
require_once dirname(__DIR__) . '/models/VoiceSettings.php';

class VoiceAssistantController {
    private $db;
    private $voiceSettings;
    
    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        $this->voiceSettings = new VoiceSettings($this->db);
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function processCommand() {
        header('Content-Type: application/json');
        
        $command = isset($_POST['command']) ? strtolower(trim($_POST['command'])) : '';
        $userId = $_SESSION['user_id'] ?? null;
        
        $response = [
            'success' => true,
            'action' => null,
            'message' => '',
            'voice_type' => 'neutral'
        ];
        
        // Analyser la commande
        if (strpos($command, 'ajouter recette') !== false || strpos($command, 'nouvelle recette') !== false) {
            $response['action'] = 'redirect';
            $response['url'] = 'index.php?action=backCreateRecipe';
            $response['message'] = 'Ouverture du formulaire de création de recette';
            $response['voice_type'] = 'success';
        }
        elseif (strpos($command, 'liste des recettes') !== false || strpos($command, 'toutes les recettes') !== false) {
            $response['action'] = 'redirect';
            $response['url'] = 'index.php?action=backRecipes';
            $response['message'] = 'Affichage de la liste des recettes';
            $response['voice_type'] = 'success';
        }
        elseif (strpos($command, 'rapport') !== false || strpos($command, 'envoyer rapport') !== false) {
            $response['action'] = 'sendReport';
            $response['message'] = 'Envoi du rapport hebdomadaire';
            $response['voice_type'] = 'warning';
        }
        elseif (strpos($command, 'categorie') !== false || strpos($command, 'catégories') !== false) {
            $response['action'] = 'redirect';
            $response['url'] = 'index.php?action=backCategories';
            $response['message'] = 'Ouverture des catégories';
            $response['voice_type'] = 'success';
        }
        elseif (strpos($command, 'supprimer recette') !== false) {
            // Extraire l'ID si présent
            preg_match('/(\d+)/', $command, $matches);
            $recipeId = $matches[1] ?? null;
            $response['action'] = 'deleteRecipe';
            $response['recipe_id'] = $recipeId;
            $response['message'] = $recipeId ? 'Voulez-vous supprimer la recette ?' : 'Quelle recette voulez-vous supprimer ?';
            $response['voice_type'] = 'danger';
        }
        elseif (strpos($command, 'aide') !== false || strpos($command, 'help') !== false) {
            $response['action'] = 'help';
            $response['message'] = 'Commandes disponibles : Ajouter recette, Liste des recettes, Envoyer rapport, Ouvrir les catégories, Supprimer recette';
            $response['voice_type'] = 'neutral';
        }
        elseif (strpos($command, 'parametres voix') !== false || strpos($command, 'configuration voix') !== false) {
            $response['action'] = 'redirect';
            $response['url'] = 'index.php?action=voiceSettings';
            $response['message'] = 'Ouverture des paramètres vocaux';
            $response['voice_type'] = 'neutral';
        }
        else {
            $response['success'] = false;
            $response['message'] = 'Désolé, je n\'ai pas compris cette commande. Dites "aide" pour voir les commandes disponibles.';
            $response['voice_type'] = 'error';
        }
        
        echo json_encode($response);
        exit;
    }
    
    public function getVoiceSettings() {
        header('Content-Type: application/json');
        $userId = $_SESSION['user_id'] ?? null;
        $settings = $this->voiceSettings->getSettings($userId);
        echo json_encode($settings);
        exit;
    }
    
    public function updateVoiceSettings() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'] ?? null;
            
            $data = [
                'voice_gender' => $_POST['voice_gender'] ?? 'female',
                'voice_rate' => floatval($_POST['voice_rate'] ?? 1.0),
                'voice_pitch' => floatval($_POST['voice_pitch'] ?? 1.0),
                'voice_volume' => floatval($_POST['voice_volume'] ?? 1.0),
                'enabled' => isset($_POST['enabled']) ? 1 : 0
            ];
            
            if($this->voiceSettings->updateSettings($userId, $data)) {
                echo json_encode(['success' => true, 'message' => 'Paramètres sauvegardés']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la sauvegarde']);
            }
        }
        exit;
    }
    
    public function showSettings() {
        $userId = $_SESSION['user_id'] ?? null;
        $settings = $this->voiceSettings->getSettings($userId);
        
        $pageTitle = "Paramètres vocaux";
        $activeMenu = "settings";
        
        require_once dirname(__DIR__) . '/views/backoffice/voice-settings.php';
    }
}
?>