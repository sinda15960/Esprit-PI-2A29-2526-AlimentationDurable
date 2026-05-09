<?php
require_once dirname(__DIR__) . '/models/RecipeVersion.php';
require_once dirname(__DIR__) . '/config/database.php';

class RecipeVersionController {
    private $db;
    private $recipeVersionModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->recipeVersionModel = new RecipeVersion($this->db);
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function showHistory($recipe_id) {
        $query = "SELECT * FROM recipes WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id", $recipe_id);
        $stmt->execute();
        $recipe = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$recipe) {
            $_SESSION['error'] = "Recette non trouvée";
            header("Location: index.php?action=backRecipes");
            exit();
        }
        
        $versions = $this->recipeVersionModel->getVersionsByRecipe($recipe_id);
        
        require_once dirname(__DIR__) . '/views/backoffice/recipes/history.php';
    }
        // Sauvegarder une nouvelle version
    public function saveVersion($recipe_id, $recipeData, $changeComment = null) {
        $versionNumber = $this->getNextVersionNumber($recipe_id);
        $modified_by = $_SESSION['admin_name'] ?? $_SESSION['admin_email'] ?? 'Administrateur';
        $modified_at = date('Y-m-d H:i:s');
        
        $query = "INSERT INTO recipe_versions 
                  (recipe_id, title, description, ingredients, prep_time, cook_time, 
                   difficulty, calories, protein, carbs, fats, image_url, is_vegan, 
                   is_vegetarian, is_gluten_free, idCategorie, change_comment, 
                   version_number, modified_by, modified_at)
                  VALUES 
                  (:recipe_id, :title, :description, :ingredients, :prep_time, :cook_time,
                   :difficulty, :calories, :protein, :carbs, :fats, :image_url, :is_vegan,
                   :is_vegetarian, :is_gluten_free, :idCategorie, :change_comment,
                   :version_number, :modified_by, :modified_at)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":recipe_id", $recipe_id);
        $stmt->bindParam(":title", $recipeData['title']);
        $stmt->bindParam(":description", $recipeData['description']);
        $stmt->bindParam(":ingredients", $recipeData['ingredients']);
        $stmt->bindParam(":prep_time", $recipeData['prep_time']);
        $stmt->bindParam(":cook_time", $recipeData['cook_time']);
        $stmt->bindParam(":difficulty", $recipeData['difficulty']);
        $stmt->bindParam(":calories", $recipeData['calories']);
        $stmt->bindParam(":protein", $recipeData['protein']);
        $stmt->bindParam(":carbs", $recipeData['carbs']);
        $stmt->bindParam(":fats", $recipeData['fats']);
        $stmt->bindParam(":image_url", $recipeData['image_url']);
        $stmt->bindParam(":is_vegan", $recipeData['is_vegan']);
        $stmt->bindParam(":is_vegetarian", $recipeData['is_vegetarian']);
        $stmt->bindParam(":is_gluten_free", $recipeData['is_gluten_free']);
        $stmt->bindParam(":idCategorie", $recipeData['idCategorie']);
        $stmt->bindParam(":change_comment", $changeComment);
        $stmt->bindParam(":version_number", $versionNumber);
        $stmt->bindParam(":modified_by", $modified_by);
        $stmt->bindParam(":modified_at", $modified_at);
        
        return $stmt->execute();
    }
    
    // Récupérer le prochain numéro de version
    public function getNextVersionNumber($recipe_id) {
        $query = "SELECT MAX(version_number) as max_version FROM recipe_versions WHERE recipe_id = :recipe_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":recipe_id", $recipe_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result['max_version'] ?? 0) + 1;
    }
    
    // Récupérer le dernier numéro de version
    public function getLatestVersionNumber($recipe_id) {
        $query = "SELECT MAX(version_number) as max_version FROM recipe_versions WHERE recipe_id = :recipe_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":recipe_id", $recipe_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['max_version'] ?? 0;
    }
    
    public function restoreVersion() {
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restore_version'])) {
            $recipe_id = (int)$_POST['recipe_id'];
            $version_number = (int)$_POST['version_number'];
            
            $version = $this->recipeVersionModel->getVersion($recipe_id, $version_number);
            
            if($version) {
                $updateQuery = "UPDATE recipes SET 
                                title = :title,
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
                
                $stmt = $this->db->prepare($updateQuery);
                $stmt->bindParam(":id", $recipe_id);
                $stmt->bindParam(":title", $version['title']);
                $stmt->bindParam(":description", $version['description']);
                $stmt->bindParam(":ingredients", $version['ingredients']);
                $stmt->bindParam(":prep_time", $version['prep_time']);
                $stmt->bindParam(":cook_time", $version['cook_time']);
                $stmt->bindParam(":difficulty", $version['difficulty']);
                $stmt->bindParam(":calories", $version['calories']);
                $stmt->bindParam(":protein", $version['protein']);
                $stmt->bindParam(":carbs", $version['carbs']);
                $stmt->bindParam(":fats", $version['fats']);
                $stmt->bindParam(":image_url", $version['image_url']);
                $stmt->bindParam(":is_vegan", $version['is_vegan']);
                $stmt->bindParam(":is_vegetarian", $version['is_vegetarian']);
                $stmt->bindParam(":is_gluten_free", $version['is_gluten_free']);
                $stmt->bindParam(":idCategorie", $version['idCategorie']);
                
                if($stmt->execute()) {
                    $_SESSION['success'] = "Recette restaurée à la version " . $version_number;
                } else {
                    $_SESSION['error'] = "Erreur lors de la restauration";
                }
            }
            
            header("Location: index.php?action=backEditRecipe&id=" . $recipe_id);
            exit();
        }
    }
}
?>