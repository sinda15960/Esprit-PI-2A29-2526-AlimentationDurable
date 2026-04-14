<?php
require_once dirname(__DIR__) . '/models/Instruction.php';
require_once dirname(__DIR__) . '/models/Recipe.php';
require_once dirname(__DIR__) . '/config/database.php';

class InstructionController {
    private $instruction;
    private $recipe;

    public function __construct() {
        $this->instruction = new Instruction();
        $this->recipe = new Recipe();
        
        // Vérifier si la session n'est pas déjà active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function validateInstructionData($data) {
        $errors = [];
        
        if(empty($data['description']) || strlen($data['description']) < 5) {
            $errors['description'] = "La description de l'étape doit contenir au moins 5 caractères";
        }
        
        if(empty($data['step_number']) || !is_numeric($data['step_number']) || $data['step_number'] <= 0) {
            $errors['step_number'] = "Le numéro de l'étape doit être un nombre positif";
        }
        
        return $errors;
    }

    public function backIndex($recipe_id) {
        $this->recipe->id = $recipe_id;
        $recipe = $this->recipe->readOne();
        
        if(!$recipe) {
            $_SESSION['error'] = "La recette n'existe pas";
            header("Location: index.php?action=backRecipes");
            exit();
        }
        
        $stmt = $this->instruction->readByRecipe($recipe_id);
        $instructions = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $instructions[] = $row;
        }
        
        require_once dirname(__DIR__) . '/views/backoffice/instructions/index.php';
    }

    public function backCreate($recipe_id) {
        $this->recipe->id = $recipe_id;
        $recipe = $this->recipe->readOne();
        
        if(!$recipe) {
            $_SESSION['error'] = "La recette n'existe pas";
            header("Location: index.php?action=backRecipes");
            exit();
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateInstructionData($_POST);
            
            if(empty($errors)) {
                $this->instruction->recipe_id = $recipe_id;
                $this->instruction->step_number = $_POST['step_number'];
                $this->instruction->description = $_POST['description'];
                $this->instruction->tip = !empty($_POST['tip']) ? $_POST['tip'] : null;
                
                if($this->instruction->create()) {
                    $_SESSION['success'] = "Instruction ajoutée avec succès !";
                    header("Location: index.php?action=backInstructions&id=" . $recipe_id);
                    exit();
                } else {
                    $_SESSION['error'] = "Erreur lors de l'ajout de l'instruction";
                }
            } else {
                $_SESSION['errors'] = $errors;
            }
        }
        
        require_once dirname(__DIR__) . '/views/backoffice/instructions/create.php';
    }

    public function backEdit($id) {
        $instruction = $this->getInstructionById($id);
        
        if(!$instruction) {
            $_SESSION['error'] = "L'instruction n'existe pas";
            header("Location: index.php?action=backRecipes");
            exit();
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateInstructionData($_POST);
            
            if(empty($errors)) {
                $this->instruction->id = $id;
                $this->instruction->step_number = $_POST['step_number'];
                $this->instruction->description = $_POST['description'];
                $this->instruction->tip = !empty($_POST['tip']) ? $_POST['tip'] : null;
                
                if($this->instruction->update()) {
                    $_SESSION['success'] = "Instruction modifiée avec succès !";
                    header("Location: index.php?action=backInstructions&id=" . $instruction['recipe_id']);
                    exit();
                } else {
                    $_SESSION['error'] = "Erreur lors de la modification";
                }
            } else {
                $_SESSION['errors'] = $errors;
            }
        }
        
        require_once dirname(__DIR__) . '/views/backoffice/instructions/edit.php';
    }

    public function backDelete($id) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $instruction = $this->getInstructionById($id);
            
            if($instruction) {
                $recipe_id = $instruction['recipe_id'];
                $this->instruction->id = $id;
                
                if($this->instruction->delete()) {
                    $_SESSION['success'] = "Instruction supprimée avec succès !";
                } else {
                    $_SESSION['error'] = "Erreur lors de la suppression";
                }
                header("Location: index.php?action=backInstructions&id=" . $recipe_id);
            } else {
                $_SESSION['error'] = "Instruction non trouvée";
                header("Location: index.php?action=backRecipes");
            }
            exit();
        }
    }

    private function getInstructionById($id) {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "SELECT * FROM instructions WHERE id = :id LIMIT 0,1";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function frontShowByRecipe($recipe_id) {
        $stmt = $this->instruction->readByRecipe($recipe_id);
        $instructions = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $instructions[] = $row;
        }
        return $instructions;
    }
}
?>