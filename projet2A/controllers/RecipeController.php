<?php
require_once dirname(__DIR__) . '/models/Recipe.php';
require_once dirname(__DIR__) . '/models/Instruction.php';
require_once dirname(__DIR__) . '/models/Categorie.php';
require_once dirname(__DIR__) . '/config/database.php';

class RecipeController {
    private $recipeModel;
    private $instructionModel;
    private $categorieModel;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->recipeModel = new Recipe($this->db);
        $this->instructionModel = new Instruction($this->db);
        $this->categorieModel = new Categorie($this->db);
        if (!isset($_SESSION['milestones_notified'])) {
        $_SESSION['milestones_notified'] = [];
    }
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

    private function sanitizeInput($input) {
        return htmlspecialchars(strip_tags(trim($input)));
    }

    // ==================== LOGIQUE MÉTIER - RECETTES ====================
    
    public function getAllRecipes() {
        try {
            $query = "SELECT * FROM " . $this->recipeModel->getTable() . " ORDER BY created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function getAllRecipesWithCategorie() {
        try {
            $query = "SELECT r.*, 
                             c.idCategorie, c.nom as categorie_nom, 
                             c.icon as categorie_icon, c.couleur as categorie_couleur,
                             c.description as categorie_description
                      FROM " . $this->recipeModel->getTable() . " r
                      LEFT JOIN categories c ON r.idCategorie = c.idCategorie
                      ORDER BY r.created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function getRecipeById($id) {
        try {
            $query = "SELECT * FROM " . $this->recipeModel->getTable() . " WHERE id = :id LIMIT 0,1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function getRecipeWithCategorie($id) {
        try {
            $query = "SELECT r.*, 
                             c.idCategorie, c.nom as categorie_nom, 
                             c.icon as categorie_icon, c.couleur as categorie_couleur
                      FROM " . $this->recipeModel->getTable() . " r
                      LEFT JOIN categories c ON r.idCategorie = c.idCategorie
                      WHERE r.id = :id LIMIT 0,1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function getRecipesByCategorie($idCategorie) {
        try {
            $query = "SELECT r.*, 
                             c.nom as categorie_nom, c.icon as categorie_icon, c.couleur as categorie_couleur
                      FROM " . $this->recipeModel->getTable() . " r
                      LEFT JOIN categories c ON r.idCategorie = c.idCategorie
                      WHERE r.idCategorie = :idCategorie
                      ORDER BY r.created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":idCategorie", $idCategorie);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function searchRecipes($keyword) {
        try {
            $query = "SELECT r.*, 
                             c.nom as categorie_nom, c.icon as categorie_icon, c.couleur as categorie_couleur
                      FROM " . $this->recipeModel->getTable() . " r
                      LEFT JOIN categories c ON r.idCategorie = c.idCategorie
                      WHERE r.title LIKE :keyword 
                         OR r.description LIKE :keyword 
                         OR r.ingredients LIKE :keyword
                         OR c.nom LIKE :keyword
                      ORDER BY r.created_at DESC";
            $stmt = $this->db->prepare($query);
            $keyword = "%{$keyword}%";
            $stmt->bindParam(":keyword", $keyword);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function getRecipesByType($type) {
        try {
            $sql = "";
            switch($type) {
                case 'vegan':
                    $sql = "is_vegan = 1";
                    break;
                case 'vegetarian':
                    $sql = "is_vegetarian = 1";
                    break;
                case 'gluten_free':
                    $sql = "is_gluten_free = 1";
                    break;
                default:
                    $sql = "1=1";
            }
            
            $query = "SELECT r.*, c.nom as categorie_nom, c.icon as categorie_icon, c.couleur as categorie_couleur
                      FROM " . $this->recipeModel->getTable() . " r
                      LEFT JOIN categories c ON r.idCategorie = c.idCategorie
                      WHERE " . $sql . " 
                      ORDER BY r.created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function getQuickRecipes() {
        try {
            $query = "SELECT r.*, c.nom as categorie_nom, c.icon as categorie_icon, c.couleur as categorie_couleur
                      FROM " . $this->recipeModel->getTable() . " r
                      LEFT JOIN categories c ON r.idCategorie = c.idCategorie
                      WHERE (r.prep_time + r.cook_time) <= 30
                      ORDER BY (r.prep_time + r.cook_time) ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function createRecipe($data) {
        try {
            $query = "INSERT INTO " . $this->recipeModel->getTable() . "
                      (title, description, ingredients, prep_time, cook_time, difficulty, 
                       calories, protein, carbs, fats, image_url, is_vegan, is_vegetarian, 
                       is_gluten_free, idCategorie)
                      VALUES 
                      (:title, :description, :ingredients, :prep_time, :cook_time, :difficulty,
                       :calories, :protein, :carbs, :fats, :image_url, :is_vegan, :is_vegetarian,
                       :is_gluten_free, :idCategorie)";

            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(":title", $data['title']);
            $stmt->bindParam(":description", $data['description']);
            $stmt->bindParam(":ingredients", $data['ingredients']);
            $stmt->bindParam(":prep_time", $data['prep_time']);
            $stmt->bindParam(":cook_time", $data['cook_time']);
            $stmt->bindParam(":difficulty", $data['difficulty']);
            $stmt->bindParam(":calories", $data['calories']);
            $stmt->bindParam(":protein", $data['protein']);
            $stmt->bindParam(":carbs", $data['carbs']);
            $stmt->bindParam(":fats", $data['fats']);
            $stmt->bindParam(":image_url", $data['image_url']);
            $stmt->bindParam(":is_vegan", $data['is_vegan']);
            $stmt->bindParam(":is_vegetarian", $data['is_vegetarian']);
            $stmt->bindParam(":is_gluten_free", $data['is_gluten_free']);
            $stmt->bindParam(":idCategorie", $data['idCategorie']);
            
            if($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function updateRecipe($id, $data) {
        try {
            $query = "UPDATE " . $this->recipeModel->getTable() . "
                      SET title = :title, 
                          description = :description, 
                          ingredients = :ingredients,
                          prep_time = :prep_time, 
                          cook_time = :cook_time, 
                          difficulty = :difficulty,
                          calories = :calories, 
                          protein = :protein, 
                          carbs = :carbs, 
                          fats = :fats,
                          image_url = :image_url, 
                          is_vegan = :is_vegan, 
                          is_vegetarian = :is_vegetarian,
                          is_gluten_free = :is_gluten_free,
                          idCategorie = :idCategorie
                      WHERE id = :id";

            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":title", $data['title']);
            $stmt->bindParam(":description", $data['description']);
            $stmt->bindParam(":ingredients", $data['ingredients']);
            $stmt->bindParam(":prep_time", $data['prep_time']);
            $stmt->bindParam(":cook_time", $data['cook_time']);
            $stmt->bindParam(":difficulty", $data['difficulty']);
            $stmt->bindParam(":calories", $data['calories']);
            $stmt->bindParam(":protein", $data['protein']);
            $stmt->bindParam(":carbs", $data['carbs']);
            $stmt->bindParam(":fats", $data['fats']);
            $stmt->bindParam(":image_url", $data['image_url']);
            $stmt->bindParam(":is_vegan", $data['is_vegan']);
            $stmt->bindParam(":is_vegetarian", $data['is_vegetarian']);
            $stmt->bindParam(":is_gluten_free", $data['is_gluten_free']);
            $stmt->bindParam(":idCategorie", $data['idCategorie']);

            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function deleteRecipe($id) {
        try {
            $query = "DELETE FROM " . $this->recipeModel->getTable() . " WHERE id = :id";
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
            $query = "DELETE FROM instructions WHERE recipe_id = :recipe_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":recipe_id", $recipe_id);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    // ==================== LOGIQUE MÉTIER - CATÉGORIES ====================
    
    public function getAllCategories() {
        try {
            $query = "SELECT * FROM categories ORDER BY nom ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    // ==================== VUES (FRONTOFFICE) ====================
    
    public function frontIndex() {
        try {
            $stmt = $this->getAllRecipesWithCategorie();
            $recipes = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $recipes[] = $row;
            }
            
            $stmtCategories = $this->getAllCategories();
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
            $recipe = $this->getRecipeWithCategorie($id);
            
            if($recipe) {
                $instructionController = new InstructionController();
                $instructions = $instructionController->frontShowByRecipe($id);
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
                $stmt = $this->searchRecipes($keyword);
                $searchTitle = "Résultats pour : " . htmlspecialchars($keyword);
            } 
            elseif(!empty($type)) {
                switch($type) {
                    case 'vegan':
                        $stmt = $this->getRecipesByType('vegan');
                        $searchTitle = "Recettes Vegan";
                        break;
                    case 'vegetarian':
                        $stmt = $this->getRecipesByType('vegetarian');
                        $searchTitle = "Recettes Végétariennes";
                        break;
                    case 'gluten_free':
                        $stmt = $this->getRecipesByType('gluten_free');
                        $searchTitle = "Recettes Sans Gluten";
                        break;
                    case 'quick':
                        $stmt = $this->getQuickRecipes();
                        $searchTitle = "Recettes Rapides (moins de 30 min)";
                        break;
                    default:
                        $stmt = $this->getAllRecipesWithCategorie();
                        $searchTitle = "Toutes les recettes";
                }
            } 
            else {
                $stmt = $this->getAllRecipesWithCategorie();
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

    public function searchByCategorie() {
        try {
            $categorieNom = isset($_GET['categorie']) ? trim($_GET['categorie']) : '';
            $recipes = [];
            
            if(!empty($categorieNom)) {
                $query = "SELECT * FROM categories WHERE nom LIKE :keyword";
                $stmt = $this->db->prepare($query);
                $keyword = "%{$categorieNom}%";
                $stmt->bindParam(":keyword", $keyword);
                $stmt->execute();
                
                $categoriesTrouvees = [];
                while($cat = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $categoriesTrouvees[] = $cat;
                }
                
                $recipes = [];
                foreach($categoriesTrouvees as $cat) {
                    $stmtRecettes = $this->getRecipesByCategorie($cat['idCategorie']);
                    while($row = $stmtRecettes->fetch(PDO::FETCH_ASSOC)) {
                        if(!in_array($row, $recipes)) {
                            $recipes[] = $row;
                        }
                    }
                }
                $searchTitle = "Recettes dans la catégorie : " . htmlspecialchars($categorieNom);
            } else {
                $stmt = $this->getAllRecipesWithCategorie();
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

    // ==================== VUES (BACKOFFICE) ====================
    
    public function backIndex() {
        try {
            $stmt = $this->getAllRecipes();
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

   private function addNotification($title, $message, $type = 'info', $icon = 'fas fa-info-circle') {
    if (!isset($_SESSION['notifications'])) {
        $_SESSION['notifications'] = [];
    }
    array_unshift($_SESSION['notifications'], [
        'id' => time() . rand(1, 1000),
        'title' => $title,
        'message' => $message,
        'type' => $type,
        'icon' => $icon,
        'time' => date('d/m/Y H:i'),
        'read' => false
    ]);
    $_SESSION['notifications'] = array_slice($_SESSION['notifications'], 0, 50);
}

    public function backEdit($id) {
        try {
            $recipe = $this->getRecipeById($id);
            
            if(!$recipe) {
                $_SESSION['error'] = "Recette non trouvée";
                header("Location: index.php?action=backRecipes");
                exit();
            }
            
            $stmt = $this->getInstructionsByRecipe($id);
            $existingInstructions = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $existingInstructions[] = $row;
            }
            
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $errors = $this->validateRecipeData($_POST);
                
                if(empty($errors)) {
                    // GESTION DE L'UPLOAD D'IMAGE
                    $image_url = $recipe['image_url']; // Garder l'image actuelle par défaut
                    
                    // Vérifier si l'utilisateur veut supprimer l'image
                    if(isset($_POST['delete_image']) && $_POST['delete_image'] == 1) {
                        $image_url = null;
                        // Supprimer le fichier physique si existant
                        if(!empty($recipe['image_url']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $recipe['image_url'])) {
                            unlink($_SERVER['DOCUMENT_ROOT'] . $recipe['image_url']);
                        }
                    }
                    
                    // Vérifier si un nouveau fichier a été uploadé
                    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                        $uploadDir = dirname(__DIR__) . '/uploads/recipes/';
                        
                        // Créer le dossier s'il n'existe pas
                        if(!file_exists($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        
                        $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                        $fileName = uniqid() . '.' . $fileExtension;
                        $uploadPath = $uploadDir . $fileName;
                        
                        // Vérifier le type de fichier
                        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                        if(!in_array($_FILES['image']['type'], $allowedTypes)) {
                            $_SESSION['error'] = "Format d'image non autorisé. Utilisez JPG, PNG, GIF ou WEBP.";
                        } 
                        // Vérifier la taille (max 2MB)
                        else if($_FILES['image']['size'] > 2 * 1024 * 1024) {
                            $_SESSION['error'] = "L'image ne doit pas dépasser 2MB.";
                        }
                        else {
                            if(move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                                // Supprimer l'ancienne image si elle existe
                                if(!empty($recipe['image_url']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $recipe['image_url'])) {
                                    unlink($_SERVER['DOCUMENT_ROOT'] . $recipe['image_url']);
                                }
                                $image_url = '/uploads/recipes/' . $fileName;
                            } else {
                                $_SESSION['error'] = "Erreur lors de l'upload de l'image.";
                            }
                        }
                    }
                    
                    $data = [
                        'title' => $this->sanitizeInput($_POST['title']),
                        'description' => $this->sanitizeInput($_POST['description']),
                        'ingredients' => $this->sanitizeInput($_POST['ingredients']),
                        'prep_time' => (int)$_POST['prep_time'],
                        'cook_time' => (int)$_POST['cook_time'],
                        'difficulty' => $_POST['difficulty'],
                        'calories' => !empty($_POST['calories']) ? (int)$_POST['calories'] : null,
                        'protein' => !empty($_POST['protein']) ? (float)$_POST['protein'] : null,
                        'carbs' => !empty($_POST['carbs']) ? (float)$_POST['carbs'] : null,
                        'fats' => !empty($_POST['fats']) ? (float)$_POST['fats'] : null,
                        'image_url' => $image_url,
                        'is_vegan' => isset($_POST['is_vegan']) ? 1 : 0,
                        'is_vegetarian' => isset($_POST['is_vegetarian']) ? 1 : 0,
                        'is_gluten_free' => isset($_POST['is_gluten_free']) ? 1 : 0,
                        'idCategorie' => !empty($_POST['idCategorie']) ? (int)$_POST['idCategorie'] : null
                    ];
                    
                    if($this->updateRecipe($id, $data)) {
                        $this->deleteInstructionsByRecipe($id);
                        
                        if(isset($_POST['instructions']) && is_array($_POST['instructions'])) {
                            foreach($_POST['instructions'] as $step => $instruction) {
                                if(!empty($instruction['description'])) {
                                    $instrData = [
                                        'recipe_id' => $id,
                                        'step_number' => $step + 1,
                                        'description' => $this->sanitizeInput($instruction['description']),
                                        'tip' => !empty($instruction['tip']) ? $this->sanitizeInput($instruction['tip']) : null
                                    ];
                                    
                                    $query = "INSERT INTO instructions (recipe_id, step_number, description, tip)
                                              VALUES (:recipe_id, :step_number, :description, :tip)";
                                    $stmt = $this->db->prepare($query);
                                    $stmt->bindParam(":recipe_id", $instrData['recipe_id']);
                                    $stmt->bindParam(":step_number", $instrData['step_number']);
                                    $stmt->bindParam(":description", $instrData['description']);
                                    $stmt->bindParam(":tip", $instrData['tip']);
                                    $stmt->execute();
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
            
            // Récupérer les catégories pour le formulaire
            $stmtCategories = $this->getAllCategories();
            $categories = [];
            while($cat = $stmtCategories->fetch(PDO::FETCH_ASSOC)) {
                $categories[] = $cat;
            }
            
            require_once dirname(__DIR__) . '/views/backoffice/recipes/edit.php';
        } catch(Exception $e) {
            $_SESSION['error'] = "Erreur: " . $e->getMessage();
            header("Location: index.php?action=backRecipes");
            exit();
        }
    }
    public function backCreate() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = $this->validateRecipeData($_POST);
        
        if(empty($errors)) {
            try {
                // Gestion de l'upload d'image
                $image_url = null;
                if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = dirname(__DIR__) . '/uploads/recipes/';
                    if(!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $fileName = uniqid() . '.' . $fileExtension;
                    $uploadPath = $uploadDir . $fileName;
                    
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    if(!in_array($_FILES['image']['type'], $allowedTypes)) {
                        $_SESSION['error'] = "Format d'image non autorisé. Utilisez JPG, PNG, GIF ou WEBP.";
                    } else if($_FILES['image']['size'] > 2 * 1024 * 1024) {
                        $_SESSION['error'] = "L'image ne doit pas dépasser 2MB.";
                    } else {
                        if(move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                            $image_url = '/uploads/recipes/' . $fileName;
                        } else {
                            $_SESSION['error'] = "Erreur lors de l'upload de l'image.";
                        }
                    }
                }
                
                $data = [
                    'title' => $this->sanitizeInput($_POST['title']),
                    'description' => $this->sanitizeInput($_POST['description']),
                    'ingredients' => $this->sanitizeInput($_POST['ingredients']),
                    'prep_time' => (int)$_POST['prep_time'],
                    'cook_time' => (int)$_POST['cook_time'],
                    'difficulty' => $_POST['difficulty'],
                    'calories' => !empty($_POST['calories']) ? (int)$_POST['calories'] : null,
                    'protein' => !empty($_POST['protein']) ? (float)$_POST['protein'] : null,
                    'carbs' => !empty($_POST['carbs']) ? (float)$_POST['carbs'] : null,
                    'fats' => !empty($_POST['fats']) ? (float)$_POST['fats'] : null,
                    'image_url' => $image_url,
                    'is_vegan' => isset($_POST['is_vegan']) ? 1 : 0,
                    'is_vegetarian' => isset($_POST['is_vegetarian']) ? 1 : 0,
                    'is_gluten_free' => isset($_POST['is_gluten_free']) ? 1 : 0,
                    'idCategorie' => !empty($_POST['idCategorie']) ? (int)$_POST['idCategorie'] : null
                ];
                
                $recipeId = $this->createRecipe($data);
                
                if($recipeId) {
                    // Notification : Nouvelle recette
                    $this->addNotification(
                        "📝 Nouvelle recette",
                        "La recette \"" . htmlspecialchars($data['title']) . "\" a été ajoutée",
                        "success",
                        "fas fa-plus-circle"
                    );
                    
                    // Vérifier les objectifs de la catégorie concernée
                    if(!empty($data['idCategorie'])) {
                        $this->checkCategoryGoals($data['idCategorie']);
                    }
                    
                    // Vérifier les niveaux (Chef)
                    $this->checkRecipeMilestones();
                    
                    // Ajout des instructions
                    if(isset($_POST['instructions']) && is_array($_POST['instructions'])) {
                        foreach($_POST['instructions'] as $step => $instruction) {
                            if(!empty($instruction['description'])) {
                                $instrData = [
                                    'recipe_id' => $recipeId,
                                    'step_number' => $step + 1,
                                    'description' => $this->sanitizeInput($instruction['description']),
                                    'tip' => !empty($instruction['tip']) ? $this->sanitizeInput($instruction['tip']) : null
                                ];
                                
                                $query = "INSERT INTO instructions (recipe_id, step_number, description, tip)
                                          VALUES (:recipe_id, :step_number, :description, :tip)";
                                $stmt = $this->db->prepare($query);
                                $stmt->bindParam(":recipe_id", $instrData['recipe_id']);
                                $stmt->bindParam(":step_number", $instrData['step_number']);
                                $stmt->bindParam(":description", $instrData['description']);
                                $stmt->bindParam(":tip", $instrData['tip']);
                                $stmt->execute();
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
    
    // Récupérer les catégories pour le formulaire
    $stmtCategories = $this->getAllCategories();
    $categories = [];
    while($cat = $stmtCategories->fetch(PDO::FETCH_ASSOC)) {
        $categories[] = $cat;
    }
    
    require_once dirname(__DIR__) . '/views/backoffice/recipes/create.php';
}

    private function getInstructionsByRecipe($recipe_id) {
        try {
            $query = "SELECT * FROM instructions WHERE recipe_id = :recipe_id ORDER BY step_number ASC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":recipe_id", $recipe_id);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function backDelete($id) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $recipe = $this->getRecipeById($id);
                
                if(!$recipe) {
                    $_SESSION['error'] = "Recette non trouvée";
                    header("Location: index.php?action=backRecipes");
                    exit();
                }
                
                // Supprimer l'image associée si elle existe
                if(!empty($recipe['image_url']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $recipe['image_url'])) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . $recipe['image_url']);
                }
                
                $this->deleteInstructionsByRecipe($id);
                if($this->deleteRecipe($id)) {
    $this->addNotification(
        "🗑️ Recette supprimée",
        "La recette \"" . htmlspecialchars($recipe['title']) . "\" a été supprimée",
        "danger",
        "fas fa-trash-alt"
    );
}
                
                if($this->deleteRecipe($id)) {
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

    public function backShow($id) {
        try {
            $recipe = $this->getRecipeWithCategorie($id);
            
            if(!$recipe) {
                $_SESSION['error'] = "Recette non trouvée";
                header("Location: index.php?action=backRecipes");
                exit();
            }
            
            $stmt = $this->getInstructionsByRecipe($id);
            $instructions = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $instructions[] = $row;
            }
            
            require_once dirname(__DIR__) . '/views/backoffice/recipes/show.php';
        } catch(Exception $e) {
            $_SESSION['error'] = "Erreur: " . $e->getMessage();
            header("Location: index.php?action=backRecipes");
            exit();
        }
    }

    public function backBulkDelete() {
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids']) && !empty($_POST['ids'])) {
            $ids = explode(',', $_POST['ids']);
            $deletedCount = 0;
            
            try {
                foreach($ids as $id) {
                    $id = (int)$id;
                    $recipe = $this->getRecipeById($id);
                    
                    if($recipe) {
                        // Supprimer l'image associée
                        if(!empty($recipe['image_url']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $recipe['image_url'])) {
                            unlink($_SERVER['DOCUMENT_ROOT'] . $recipe['image_url']);
                        }
                        $this->deleteInstructionsByRecipe($id);
                        if($this->deleteRecipe($id)) {
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
    
    public function backExportCSV() {
        try {
            $stmt = $this->getAllRecipes();
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