<?php
// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__DIR__) . '/error_log.txt');

require_once dirname(__DIR__) . '/controllers/RecipeController.php';
require_once dirname(__DIR__) . '/controllers/InstructionController.php';
require_once dirname(__DIR__) . '/controllers/CategorieController.php';
require_once dirname(__DIR__) . '/controllers/RecipeVersionController.php'; // Ajoute ceci si nécessaire
require_once dirname(__DIR__) . '/controllers/VoiceAssistantController.php';

$controller = new RecipeController();
$instructionController = new InstructionController();
$categorieController = new CategorieController();

$action = isset($_GET['action']) ? $_GET['action'] : 'frontRecipes';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch($action) {
    // ==================== FRONTOFFICE - RECETTES ====================
    case 'frontRecipes':
        $controller->frontIndex();
        break;
    
    case 'frontShowRecipe':
        if($id) {
            $controller->frontShow($id);
        } else {
            header("Location: index.php?action=frontRecipes");
        }
        break;
    
    case 'searchRecipes':
        $controller->frontSearch();
        break;
    
    // ==================== FRONTOFFICE - CATEGORIES ====================
    case 'searchByCategorie':
        $categorieController->frontSearchByCategorie();
        break;
    
    case 'frontRecettesByCategorie':
        if($id) {
            $categorieController->frontRecettesByCategorie($id);
        } else {
            header("Location: index.php?action=searchByCategorie");
        }
        break;
    
    // ==================== BACKOFFICE - RECETTES ====================
    case 'backRecipes':
        $controller->backIndex();
        break;
    
    case 'backCreateRecipe':
        $controller->backCreate();
        break;
    
    case 'backEditRecipe':
        if($id) {
            $controller->backEdit($id);
        } else {
            header("Location: index.php?action=backRecipes");
        }
        break;
    
    case 'backDeleteRecipe':
        if($id) {
            $controller->backDelete($id);
        } else {
            header("Location: index.php?action=backRecipes");
        }
        break;
    
    case 'backShowRecipe':
        if($id) {
            $controller->backShow($id);
        } else {
            header("Location: index.php?action=backRecipes");
        }
        break;
    
    case 'backBulkDeleteRecipes':
        $controller->backBulkDelete();
        break;
    
    case 'backExportCSV':
        $controller->backExportCSV();
        break;
    
    // ==================== BACKOFFICE - INSTRUCTIONS ====================
    case 'backInstructions':
        if($id) {
            $instructionController->backIndex($id);
        } else {
            header("Location: index.php?action=backRecipes");
        }
        break;
    
    case 'backCreateInstruction':
        if($id) {
            $instructionController->backCreate($id);
        } else {
            header("Location: index.php?action=backRecipes");
        }
        break;
    
    case 'backEditInstruction':
        if($id) {
            $instructionController->backEdit($id);
        } else {
            header("Location: index.php?action=backRecipes");
        }
        break;
    
    case 'backDeleteInstruction':
        if($id) {
            $instructionController->backDelete($id);
        } else {
            header("Location: index.php?action=backRecipes");
        }
        break;
    
    // ==================== BACKOFFICE - CATEGORIES ====================
    case 'backCategories':
        $categorieController->backIndex();
        break;
    
    case 'backCreateCategorie':
        $categorieController->backCreate();
        break;
    
    case 'backEditCategorie':
        if($id) {
            $categorieController->backEdit($id);
        } else {
            header("Location: index.php?action=backCategories");
        }
        break;
    
    case 'backDeleteCategorie':
        if($id) {
            $categorieController->backDelete($id);
        } else {
            header("Location: index.php?action=backCategories");
        }
        break;
    
    case 'searchByType':
        $controller->searchByType();
        break;
    
    case 'recipeHistory':
        $versionController = new RecipeVersionController();
        if($id) {
            $versionController->showHistory($id);
        } else {
            header("Location: index.php?action=backRecipes");
        }
        break;
    
    case 'restoreVersion':
        $versionController = new RecipeVersionController();
        $versionController->restoreVersion();
        break;
    
    // ==================== AJOUTE ICI (avant le default) ====================
    // ==================== ASSISTANT VOCAL ====================
case 'processCommand':
    $voiceController = new VoiceAssistantController();
    $voiceController->processCommand();
    break;

case 'getVoiceSettings':
    $voiceController = new VoiceAssistantController();
    $voiceController->getVoiceSettings();
    break;

case 'updateVoiceSettings':
    $voiceController = new VoiceAssistantController();
    $voiceController->updateVoiceSettings();
    break;

case 'voiceSettings':
    $voiceController = new VoiceAssistantController();
    $voiceController->showSettings();
    break;
case 'sendReport':
    header('Content-Type: application/json');
    
    // Vérifier si la session n'est pas déjà active
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $response = [
        'success' => true,
        'message' => 'Rapport hebdomadaire envoyé avec succès !'
    ];
    
    // Ajouter une notification dans la session
    if (!isset($_SESSION['notifications'])) {
        $_SESSION['notifications'] = [];
    }
    
    $_SESSION['notifications'][] = [
        'id' => time(),
        'title' => '📧 Rapport hebdomadaire',
        'message' => 'Le rapport a été envoyé avec succès',
        'type' => 'success',
        'icon' => 'fas fa-envelope',
        'time' => date('H:i:s'),
        'read' => false
    ];
    
    echo json_encode($response);
    exit;
    break;
        header('Content-Type: application/json');
        session_start();
        
        $response = [
            'success' => true,
            'message' => 'Rapport hebdomadaire envoyé avec succès !'
        ];
        
        // Ajouter une notification dans la session
        if (!isset($_SESSION['notifications'])) {
            $_SESSION['notifications'] = [];
        }
        
        $_SESSION['notifications'][] = [
            'id' => time(),
            'title' => '📧 Rapport hebdomadaire',
            'message' => 'Le rapport a été envoyé avec succès',
            'type' => 'success',
            'icon' => 'fas fa-envelope',
            'time' => date('H:i:s'),
            'read' => false
        ];
        
        echo json_encode($response);
        exit;
        break;
    
    // ==================== PAGE PAR DÉFAUT ====================
    default:
        $controller->frontIndex();
        break;
}
?>