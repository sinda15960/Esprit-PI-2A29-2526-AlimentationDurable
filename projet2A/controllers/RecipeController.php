<?php
require_once dirname(__DIR__) . '/models/Recipe.php';
require_once dirname(__DIR__) . '/models/Instruction.php';

class RecipeController {
    private $recipe;
    private $instruction;

    public function __construct() {
        $this->recipe = new Recipe();
        $this->instruction = new Instruction();
        
        // Vérifier si la session n'est pas déjà active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Validation personnalisée pour les recettes (sans HTML5)
    private function validateRecipeData($data) {
        $errors = [];
        
        // Validation du titre
        if(empty($data['title']) || strlen(trim($data['title'])) < 3) {
            $errors['title'] = "Le titre doit contenir au moins 3 caractères";
        }
        
        // Validation de la description
        if(empty($data['description']) || strlen(trim($data['description'])) < 20) {
            $errors['description'] = "La description doit contenir au moins 20 caractères";
        }
        
        // Validation des ingrédients
        if(empty($data['ingredients']) || strlen(trim($data['ingredients'])) < 10) {
            $errors['ingredients'] = "La liste des ingrédients doit contenir au moins 10 caractères";
        }
        
        // Validation du temps de préparation
        if(empty($data['prep_time']) || !is_numeric($data['prep_time']) || $data['prep_time'] <= 0) {
            $errors['prep_time'] = "Le temps de préparation doit être un nombre positif";
        }
        
        // Validation du temps de cuisson
        if(!isset($data['cook_time']) || !is_numeric($data['cook_time']) || $data['cook_time'] < 0) {
            $errors['cook_time'] = "Le temps de cuisson doit être un nombre valide";
        }
        
        // Validation des calories (optionnel)
        if(!empty($data['calories']) && (!is_numeric($data['calories']) || $data['calories'] < 0)) {
            $errors['calories'] = "Les calories doivent être un nombre positif";
        }
        
        return $errors;
    }

    // ==================== FRONTOFFICE ====================
    
    // FrontOffice - Afficher toutes les recettes
    public function frontIndex() {
        try {
            $stmt = $this->recipe->readAll();
            $recipes = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $recipes[] = $row;
            }
            
            $headerPath = dirname(__DIR__) . '/views/frontoffice/layout/header.php';
            if(!file_exists($headerPath)) {
                // Créer le dossier si nécessaire
                if(!is_dir(dirname($headerPath))) {
                    mkdir(dirname($headerPath), 0777, true);
                }
            }
            
            require_once dirname(__DIR__) . '/views/frontoffice/recipes/index.php';
        } catch(Exception $e) {
            $_SESSION['error'] = "Erreur lors du chargement des recettes: " . $e->getMessage();
            require_once dirname(__DIR__) . '/views/frontoffice/recipes/index.php';
        }
    }

    // FrontOffice - Afficher une recette
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

    // FrontOffice - Rechercher des recettes
    public function frontSearch() {
        try {
            $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
            if(!empty($keyword)) {
                $stmt = $this->recipe->search($keyword);
                $recipes = [];
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $recipes[] = $row;
                }
            } else {
                $stmt = $this->recipe->readAll();
                $recipes = [];
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

    // ==================== BACKOFFICE - RECETTES ====================
    
    // BackOffice - Liste des recettes
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

    // BackOffice - Créer une recette
    public function backCreate() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateRecipeData($_POST);
            
            if(empty($errors)) {
                try {
                    // Remplir l'objet recette
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
                    
                    $recipeId = $this->recipe->create();
                    
                    if($recipeId) {
                        // Ajouter les instructions
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
                        $_SESSION['error'] = "Erreur lors de la création de la recette";
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

    // BackOffice - Modifier une recette
    public function backEdit($id) {
        try {
            $this->recipe->id = $id;
            $recipe = $this->recipe->readOne();
            
            if(!$recipe) {
                $_SESSION['error'] = "Recette non trouvée";
                header("Location: index.php?action=backRecipes");
                exit();
            }
            
            // Récupérer les instructions existantes
            $stmt = $this->instruction->readByRecipe($id);
            $existingInstructions = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $existingInstructions[] = $row;
            }
            
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $errors = $this->validateRecipeData($_POST);
                
                if(empty($errors)) {
                    // Mettre à jour la recette
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
                    
                    if($this->recipe->update()) {
                        // Supprimer les anciennes instructions
                        $this->instruction->deleteByRecipe($id);
                        
                        // Ajouter les nouvelles instructions
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

    // BackOffice - Supprimer une recette
    public function backDelete($id) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->recipe->id = $id;
                
                // Vérifier si la recette existe
                $recipe = $this->recipe->readOne();
                if(!$recipe) {
                    $_SESSION['error'] = "Recette non trouvée";
                    header("Location: index.php?action=backRecipes");
                    exit();
                }
                
                // Supprimer d'abord les instructions
                $this->instruction->deleteByRecipe($id);
                
                // Supprimer la recette
                if($this->recipe->delete()) {
                    $_SESSION['success'] = "Recette \"" . htmlspecialchars($recipe['title']) . "\" supprimée avec succès !";
                } else {
                    $_SESSION['error'] = "Erreur lors de la suppression de la recette";
                }
            } catch(Exception $e) {
                $_SESSION['error'] = "Erreur: " . $e->getMessage();
            }
        }
        header("Location: index.php?action=backRecipes");
        exit();
    }

    // BackOffice - Suppression groupée de recettes
    public function backBulkDelete() {
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids']) && is_array($_POST['ids'])) {
            $ids = $_POST['ids'];
            $deletedCount = 0;
            $failedCount = 0;
            
            try {
                foreach($ids as $id) {
                    $id = (int)$id;
                    $this->recipe->id = $id;
                    
                    // Vérifier si la recette existe
                    $recipe = $this->recipe->readOne();
                    if($recipe) {
                        // Supprimer les instructions
                        $this->instruction->deleteByRecipe($id);
                        // Supprimer la recette
                        if($this->recipe->delete()) {
                            $deletedCount++;
                        } else {
                            $failedCount++;
                        }
                    } else {
                        $failedCount++;
                    }
                }
                
                if($deletedCount > 0) {
                    $_SESSION['success'] = "$deletedCount recette(s) ont été supprimées avec succès !";
                    if($failedCount > 0) {
                        $_SESSION['warning'] = "$failedCount recette(s) n'ont pas pu être supprimées.";
                    }
                } else {
                    $_SESSION['error'] = "Aucune recette n'a été supprimée";
                }
            } catch(Exception $e) {
                $_SESSION['error'] = "Erreur lors de la suppression groupée: " . $e->getMessage();
            }
        }
        
        header("Location: index.php?action=backRecipes");
        exit();
    }

    // BackOffice - Exporter les recettes en CSV
    public function backExportCSV() {
        try {
            $stmt = $this->recipe->readAll();
            $recipes = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $recipes[] = $row;
            }
            
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=recettes_export_' . date('Y-m-d') . '.csv');
            
            $output = fopen('php://output', 'w');
            fputcsv($output, ['ID', 'Titre', 'Description', 'Ingrédients', 'Temps Préparation', 'Temps Cuisson', 'Difficulté', 'Calories', 'Protéines', 'Glucides', 'Lipides', 'Vegan', 'Végétarien', 'Sans Gluten', 'Date Création']);
            
            foreach($recipes as $recipe) {
                fputcsv($output, [
                    $recipe['id'],
                    $recipe['title'],
                    $recipe['description'],
                    $recipe['ingredients'],
                    $recipe['prep_time'],
                    $recipe['cook_time'],
                    $recipe['difficulty'],
                    $recipe['calories'],
                    $recipe['protein'],
                    $recipe['carbs'],
                    $recipe['fats'],
                    $recipe['is_vegan'] ? 'Oui' : 'Non',
                    $recipe['is_vegetarian'] ? 'Oui' : 'Non',
                    $recipe['is_gluten_free'] ? 'Oui' : 'Non',
                    $recipe['created_at']
                ]);
            }
            
            fclose($output);
            exit();
        } catch(Exception $e) {
            $_SESSION['error'] = "Erreur lors de l'export: " . $e->getMessage();
            header("Location: index.php?action=backRecipes");
            exit();
        }
    }

    // BackOffice - Statistiques pour le dashboard
    public function backStats() {
        try {
            $stmt = $this->recipe->readAll();
            $recipes = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $recipes[] = $row;
            }
            
            $stats = [
                'total' => count($recipes),
                'vegan' => count(array_filter($recipes, function($r) { return $r['is_vegan']; })),
                'vegetarian' => count(array_filter($recipes, function($r) { return $r['is_vegetarian']; })),
                'gluten_free' => count(array_filter($recipes, function($r) { return $r['is_gluten_free']; })),
                'facile' => count(array_filter($recipes, function($r) { return $r['difficulty'] == 'facile'; })),
                'moyen' => count(array_filter($recipes, function($r) { return $r['difficulty'] == 'moyen'; })),
                'difficile' => count(array_filter($recipes, function($r) { return $r['difficulty'] == 'difficile'; })),
                'calories_moyennes' => count($recipes) > 0 ? round(array_sum(array_column($recipes, 'calories')) / count($recipes)) : 0,
                'temps_moyen' => count($recipes) > 0 ? round(array_sum(array_map(function($r) { return $r['prep_time'] + $r['cook_time']; }, $recipes)) / count($recipes)) : 0
            ];
            
            header('Content-Type: application/json');
            echo json_encode($stats);
            exit();
        } catch(Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
            exit();
        }
    }
}
?>