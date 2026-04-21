<?php
require_once dirname(__DIR__) . '/models/Recipe.php';
require_once dirname(__DIR__) . '/models/Instruction.php';
require_once dirname(__DIR__) . '/models/Categorie.php';

class RecipeController {
    private $recipe;
    private $instruction;
    private $categorie;

    public function __construct() {
        $this->recipe = new Recipe();
        $this->instruction = new Instruction();
        $this->categorie = new Categorie();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ==================== VALIDATION ====================
    
    private function validateRecipeData($data) {
        $errors = [];
        
        if(empty($data['title']) || strlen(trim($data['title'])) < 3) {
            $errors['title'] = "Le titre doit contenir au moins 3 caractères";
        }
        
        if(empty($data['description']) || strlen(trim($data['description'])) < 20) {
            $errors['description'] = "La description doit contenir au moins 20 caractères";
        }
        
        if(empty($data['ingredients']) || strlen(trim($data['ingredients'])) < 10) {
            $errors['ingredients'] = "La liste des ingrédients doit contenir au moins 10 caractères";
        }
        
        if(empty($data['prep_time']) || !is_numeric($data['prep_time']) || $data['prep_time'] <= 0) {
            $errors['prep_time'] = "Le temps de préparation doit être un nombre positif";
        }
        
        if(!isset($data['cook_time']) || !is_numeric($data['cook_time']) || $data['cook_time'] < 0) {
            $errors['cook_time'] = "Le temps de cuisson doit être un nombre valide";
        }
        
        return $errors;
    }

    // ==================== FRONTOFFICE ====================
    
    public function frontIndex() {
        try {
            $stmt = $this->recipe->readAll();
            $recipes = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $recipes[] = $row;
            }
            
            // Récupérer les catégories
            $stmtCategories = $this->categorie->readAll();
            $dbCategories = [];
            while($cat = $stmtCategories->fetch(PDO::FETCH_ASSOC)) {
                $dbCategories[] = $cat;
            }
            
            require_once dirname(__DIR__) . '/views/frontoffice/recipes/index.php';
        } catch(Exception $e) {
            $_SESSION['error'] = "Erreur: " . $e->getMessage();
            $recipes = [];
            $dbCategories = [];
            require_once dirname(__DIR__) . '/views/frontoffice/recipes/index.php';
        }
    }

    public function frontShow($id) {
        try {
            $this->recipe->id = $id;
            $recipe = $this->recipe->readOne();
            
            if($recipe) {
                $stmt = $this->instruction->readByRecipe($id);
                $instructions = [];
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $instructions[] = $row;
                }
                require_once dirname(__DIR__) . '/views/frontoffice/recipes/show.php';
            } else {
                $_SESSION['error'] = "Recette non trouvée";
                header("Location: index.php?action=frontRecipes");
                exit();
            }
        } catch(Exception $e) {
            $_SESSION['error'] = "Erreur: " . $e->getMessage();
            header("Location: index.php?action=frontRecipes");
            exit();
        }
    }

    public function frontSearch() {
        try {
            $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
            $type = isset($_GET['type']) ? $_GET['type'] : '';
            
            $recipes = [];
            
            if(!empty($keyword)) {
                $stmt = $this->recipe->searchWithCategorie($keyword);
                $searchTitle = "Résultats pour : " . htmlspecialchars($keyword);
            } 
            elseif(!empty($type)) {
                switch($type) {
                    case 'vegan':
                        $stmt = $this->recipe->getByType('vegan');
                        $searchTitle = "Recettes Vegan";
                        break;
                    case 'vegetarian':
                        $stmt = $this->recipe->getByType('vegetarian');
                        $searchTitle = "Recettes Végétariennes";
                        break;
                    case 'gluten_free':
                        $stmt = $this->recipe->getByType('gluten_free');
                        $searchTitle = "Recettes Sans Gluten";
                        break;
                    case 'quick':
                        $stmt = $this->recipe->getQuickRecipes();
                        $searchTitle = "Recettes Rapides (moins de 30 min)";
                        break;
                    default:
                        $stmt = $this->recipe->readAllWithCategorie();
                        $searchTitle = "Toutes les recettes";
                }
            } 
            else {
                $stmt = $this->recipe->readAllWithCategorie();
                $searchTitle = "Toutes nos recettes";
            }
            
            if($stmt) {
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $recipes[] = $row;
                }
            }
            
            require_once dirname(__DIR__) . '/views/frontoffice/recipes/search.php';
        } catch(Exception $e) {
            $_SESSION['error'] = "Erreur lors de la recherche: " . $e->getMessage();
            header("Location: index.php?action=frontRecipes");
            exit();
        }
    }

    // ==================== BACKOFFICE ====================
    
    public function backIndex() {
        try {
            $stmt = $this->recipe->readAll();
            $recipes = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $recipes[] = $row;
            }
            require_once dirname(__DIR__) . '/views/backoffice/recipes/index.php';
        } catch(Exception $e) {
            $_SESSION['error'] = "Erreur: " . $e->getMessage();
            $recipes = [];
            require_once dirname(__DIR__) . '/views/backoffice/recipes/index.php';
        }
    }

    public function backCreate() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateRecipeData($_POST);
            
            if(empty($errors)) {
                try {
                    $this->recipe->title = trim($_POST['title']);
                    $this->recipe->description = trim($_POST['description']);
                    $this->recipe->ingredients = trim($_POST['ingredients']);
                    $this->recipe->prep_time = (int)$_POST['prep_time'];
                    $this->recipe->cook_time = (int)$_POST['cook_time'];
                    $this->recipe->difficulty = $_POST['difficulty'];
                    $this->recipe->calories = !empty($_POST['calories']) ? (int)$_POST['calories'] : null;
                    $this->recipe->protein = !empty($_POST['protein']) ? (float)$_POST['protein'] : null;
                    $this->recipe->carbs = !empty($_POST['carbs']) ? (float)$_POST['carbs'] : null;
                    $this->recipe->fats = !empty($_POST['fats']) ? (float)$_POST['fats'] : null;
                    $this->recipe->image_url = !empty($_POST['image_url']) ? trim($_POST['image_url']) : null;
                    $this->recipe->is_vegan = isset($_POST['is_vegan']) ? 1 : 0;
                    $this->recipe->is_vegetarian = isset($_POST['is_vegetarian']) ? 1 : 0;
                    $this->recipe->is_gluten_free = isset($_POST['is_gluten_free']) ? 1 : 0;
                    $this->recipe->idCategorie = !empty($_POST['idCategorie']) ? (int)$_POST['idCategorie'] : null;
                    
                    $recipeId = $this->recipe->create();
                    
                    if($recipeId) {
                        if(isset($_POST['instructions']) && is_array($_POST['instructions'])) {
                            foreach($_POST['instructions'] as $step => $instruction) {
                                if(!empty($instruction['description'])) {
                                    $instr = new Instruction();
                                    $instr->recipe_id = $recipeId;
                                    $instr->step_number = $step + 1;
                                    $instr->description = trim($instruction['description']);
                                    $instr->tip = !empty($instruction['tip']) ? trim($instruction['tip']) : null;
                                    $instr->create();
                                }
                            }
                        }
                        
                        $_SESSION['success'] = "Recette créée avec succès !";
                        header("Location: index.php?action=backRecipes");
                        exit();
                    } else {
                        $_SESSION['error'] = "Erreur lors de la création";
                    }
                } catch(Exception $e) {
                    $_SESSION['error'] = "Erreur: " . $e->getMessage();
                }
            } else {
                $_SESSION['errors'] = $errors;
            }
        }
        
        require_once dirname(__DIR__) . '/views/backoffice/recipes/create.php';
    }

    public function backEdit($id) {
        try {
            $this->recipe->id = $id;
            $recipe = $this->recipe->readOne();
            
            if(!$recipe) {
                $_SESSION['error'] = "Recette non trouvée";
                header("Location: index.php?action=backRecipes");
                exit();
            }
            
            $stmt = $this->instruction->readByRecipe($id);
            $existingInstructions = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $existingInstructions[] = $row;
            }
            
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $errors = $this->validateRecipeData($_POST);
                
                if(empty($errors)) {
                    $this->recipe->id = $id;
                    $this->recipe->title = trim($_POST['title']);
                    $this->recipe->description = trim($_POST['description']);
                    $this->recipe->ingredients = trim($_POST['ingredients']);
                    $this->recipe->prep_time = (int)$_POST['prep_time'];
                    $this->recipe->cook_time = (int)$_POST['cook_time'];
                    $this->recipe->difficulty = $_POST['difficulty'];
                    $this->recipe->calories = !empty($_POST['calories']) ? (int)$_POST['calories'] : null;
                    $this->recipe->protein = !empty($_POST['protein']) ? (float)$_POST['protein'] : null;
                    $this->recipe->carbs = !empty($_POST['carbs']) ? (float)$_POST['carbs'] : null;
                    $this->recipe->fats = !empty($_POST['fats']) ? (float)$_POST['fats'] : null;
                    $this->recipe->image_url = !empty($_POST['image_url']) ? trim($_POST['image_url']) : null;
                    $this->recipe->is_vegan = isset($_POST['is_vegan']) ? 1 : 0;
                    $this->recipe->is_vegetarian = isset($_POST['is_vegetarian']) ? 1 : 0;
                    $this->recipe->is_gluten_free = isset($_POST['is_gluten_free']) ? 1 : 0;
                    $this->recipe->idCategorie = !empty($_POST['idCategorie']) ? (int)$_POST['idCategorie'] : null;
                    
                    if($this->recipe->update()) {
                        $this->instruction->deleteByRecipe($id);
                        
                        if(isset($_POST['instructions']) && is_array($_POST['instructions'])) {
                            foreach($_POST['instructions'] as $step => $instruction) {
                                if(!empty($instruction['description'])) {
                                    $instr = new Instruction();
                                    $instr->recipe_id = $id;
                                    $instr->step_number = $step + 1;
                                    $instr->description = trim($instruction['description']);
                                    $instr->tip = !empty($instruction['tip']) ? trim($instruction['tip']) : null;
                                    $instr->create();
                                }
                            }
                        }
                        
                        $_SESSION['success'] = "Recette modifiée avec succès !";
                        header("Location: index.php?action=backRecipes");
                        exit();
                    } else {
                        $_SESSION['error'] = "Erreur lors de la modification";
                    }
                } else {
                    $_SESSION['errors'] = $errors;
                }
            }
            
            require_once dirname(__DIR__) . '/views/backoffice/recipes/edit.php';
        } catch(Exception $e) {
            $_SESSION['error'] = "Erreur: " . $e->getMessage();
            header("Location: index.php?action=backRecipes");
            exit();
        }
    }

    public function backDelete($id) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->recipe->id = $id;
                $recipe = $this->recipe->readOne();
                
                if(!$recipe) {
                    $_SESSION['error'] = "Recette non trouvée";
                    header("Location: index.php?action=backRecipes");
                    exit();
                }
                
                $this->instruction->deleteByRecipe($id);
                
                if($this->recipe->delete()) {
                    $_SESSION['success'] = "Recette supprimée avec succès !";
                } else {
                    $_SESSION['error'] = "Erreur lors de la suppression";
                }
            } catch(Exception $e) {
                $_SESSION['error'] = "Erreur: " . $e->getMessage();
            }
        }
        header("Location: index.php?action=backRecipes");
        exit();
    }

    public function backBulkDelete() {
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids']) && !empty($_POST['ids'])) {
            $ids = explode(',', $_POST['ids']);
            $deletedCount = 0;
            
            try {
                foreach($ids as $id) {
                    $id = (int)$id;
                    $this->recipe->id = $id;
                    $recipe = $this->recipe->readOne();
                    
                    if($recipe) {
                        $this->instruction->deleteByRecipe($id);
                        if($this->recipe->delete()) {
                            $deletedCount++;
                        }
                    }
                }
                
                if($deletedCount > 0) {
                    $_SESSION['success'] = "$deletedCount recette(s) supprimée(s)";
                } else {
                    $_SESSION['error'] = "Aucune suppression";
                }
            } catch(Exception $e) {
                $_SESSION['error'] = "Erreur: " . $e->getMessage();
            }
        }
        
        header("Location: index.php?action=backRecipes");
        exit();
    }
    
    // Recherche par catégorie (par nom de catégorie)
    public function searchByCategorie() {
        try {
            $categorieNom = isset($_GET['categorie']) ? trim($_GET['categorie']) : '';
            $recipes = [];
            
            if(!empty($categorieNom)) {
                $stmt = $this->categorie->search($categorieNom);
                $categoriesTrouvees = [];
                while($cat = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $categoriesTrouvees[] = $cat;
                }
                
                $recipes = [];
                foreach($categoriesTrouvees as $cat) {
                    $stmtRecettes = $this->recipe->readByCategorie($cat['idCategorie']);
                    while($row = $stmtRecettes->fetch(PDO::FETCH_ASSOC)) {
                        if(!in_array($row, $recipes)) {
                            $recipes[] = $row;
                        }
                    }
                }
                $searchTitle = "Recettes dans la catégorie : " . htmlspecialchars($categorieNom);
            } else {
                $stmt = $this->recipe->readAllWithCategorie();
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $recipes[] = $row;
                }
                $searchTitle = "Toutes les recettes";
            }
            
            require_once dirname(__DIR__) . '/views/frontoffice/recipes/search.php';
        } catch(Exception $e) {
            $_SESSION['error'] = "Erreur: " . $e->getMessage();
            header("Location: index.php?action=frontRecipes");
            exit();
        }
    }

    public function backExportCSV() {
        try {
            $stmt = $this->recipe->readAll();
            $recipes = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $recipes[] = $row;
            }
            
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=recettes_' . date('Y-m-d') . '.csv');
            
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, ['ID', 'Titre', 'Description', 'Préparation', 'Cuisson', 'Difficulté', 'Calories']);
            
            foreach($recipes as $recipe) {
                fputcsv($output, [
                    $recipe['id'],
                    $recipe['title'],
                    $recipe['description'],
                    $recipe['prep_time'],
                    $recipe['cook_time'],
                    $recipe['difficulty'],
                    $recipe['calories']
                ]);
            }
            
            fclose($output);
            exit();
        } catch(Exception $e) {
            $_SESSION['error'] = "Erreur export: " . $e->getMessage();
            header("Location: index.php?action=backRecipes");
            exit();
        }
    }
}
?>