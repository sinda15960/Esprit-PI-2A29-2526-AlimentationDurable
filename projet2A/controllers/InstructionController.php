<?php
require_once dirname(__DIR__) . '/models/Instruction.php';
require_once dirname(__DIR__) . '/models/Recipe.php';
require_once dirname(__DIR__) . '/config/database.php';

class InstructionController {
    private $instructionModel;
    private $recipeModel;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->instructionModel = new Instruction($this->db);
        $this->recipeModel = new Recipe($this->db);
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ==================== VALIDATION ====================
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

    // ==================== LOGIQUE MÉTIER ====================
    
    public function getRecipeById($recipe_id) {
        try {
            $query = "SELECT * FROM recipes WHERE id = :id LIMIT 0,1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $recipe_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function getInstructionById($id) {
        try {
            $query = "SELECT * FROM " . $this->instructionModel->getTable() . " WHERE id = :id LIMIT 0,1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function getInstructionsByRecipe($recipe_id) {
        try {
            $query = "SELECT * FROM " . $this->instructionModel->getTable() . " 
                      WHERE recipe_id = :recipe_id 
                      ORDER BY step_number ASC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":recipe_id", $recipe_id);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function createInstruction($data) {
        try {
            $query = "INSERT INTO " . $this->instructionModel->getTable() . "
                      SET recipe_id = :recipe_id, step_number = :step_number, 
                          description = :description, tip = :tip";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(":recipe_id", $data['recipe_id']);
            $stmt->bindParam(":step_number", $data['step_number']);
            $stmt->bindParam(":description", $data['description']);
            $stmt->bindParam(":tip", $data['tip']);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function updateInstruction($id, $data) {
        try {
            $query = "UPDATE " . $this->instructionModel->getTable() . "
                      SET step_number = :step_number, description = :description, tip = :tip
                      WHERE id = :id";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":step_number", $data['step_number']);
            $stmt->bindParam(":description", $data['description']);
            $stmt->bindParam(":tip", $data['tip']);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function deleteInstruction($id) {
        try {
            $query = "DELETE FROM " . $this->instructionModel->getTable() . " WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function deleteInstructionsByRecipe($recipe_id) {
        try {
            $query = "DELETE FROM " . $this->instructionModel->getTable() . " WHERE recipe_id = :recipe_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":recipe_id", $recipe_id);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    // ==================== VUES (BACKOFFICE) ====================

    public function backIndex($recipe_id) {
        $recipe = $this->getRecipeById($recipe_id);
        
        if(!$recipe) {
            $_SESSION['error'] = "La recette n'existe pas";
            header("Location: index.php?action=backRecipes");
            exit();
        }
        
        $stmt = $this->getInstructionsByRecipe($recipe_id);
        $instructions = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $instructions[] = $row;
        }
        
        require_once dirname(__DIR__) . '/views/backoffice/instructions/index.php';
    }

    public function backCreate($recipe_id) {
        $recipe = $this->getRecipeById($recipe_id);
        
        if(!$recipe) {
            $_SESSION['error'] = "La recette n'existe pas";
            header("Location: index.php?action=backRecipes");
            exit();
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateInstructionData($_POST);
            
            if(empty($errors)) {
                $data = [
                    'recipe_id' => $recipe_id,
                    'step_number' => $_POST['step_number'],
                    'description' => htmlspecialchars(strip_tags($_POST['description'])),
                    'tip' => !empty($_POST['tip']) ? htmlspecialchars(strip_tags($_POST['tip'])) : null
                ];
                
                if($this->createInstruction($data)) {
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
                $data = [
                    'step_number' => $_POST['step_number'],
                    'description' => htmlspecialchars(strip_tags($_POST['description'])),
                    'tip' => !empty($_POST['tip']) ? htmlspecialchars(strip_tags($_POST['tip'])) : null
                ];
                
                if($this->updateInstruction($id, $data)) {
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
                
                if($this->deleteInstruction($id)) {
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

    // ==================== VUES (FRONTOFFICE) ====================

    public function frontShowByRecipe($recipe_id) {
        $stmt = $this->getInstructionsByRecipe($recipe_id);
        $instructions = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $instructions[] = $row;
        }
        return $instructions;
    }
}
?>