<?php
require_once dirname(__DIR__) . '/controllers/RecipeController.php';
require_once dirname(__DIR__) . '/controllers/InstructionController.php';

$controller = new RecipeController();
$instructionController = new InstructionController();

$action = isset($_GET['action']) ? $_GET['action'] : 'frontRecipes';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch($action) {
    // FrontOffice routes
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
    
    // BackOffice routes for Recipes
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
    case 'backBulkDeleteRecipes':
        $controller->backBulkDelete();
        break;
    
    // BackOffice routes for Instructions
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
    
    default:
        $controller->frontIndex();
        break;
}
?>