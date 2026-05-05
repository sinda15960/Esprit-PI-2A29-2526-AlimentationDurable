<?php
class RecipeVersion {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getVersionsByRecipe($recipe_id) {
        $query = "SELECT * FROM recipe_versions WHERE recipe_id = :recipe_id ORDER BY version_number DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":recipe_id", $recipe_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVersion($recipe_id, $version_number) {
        $query = "SELECT * FROM recipe_versions WHERE recipe_id = :recipe_id AND version_number = :version_number";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":recipe_id", $recipe_id);
        $stmt->bindParam(":version_number", $version_number);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ============ AJOUTE CES MÉTHODES ============
    
    public function saveVersion($recipe_id, $recipeData, $comment = '') {
        try {
            // Récupérer le prochain numéro de version
            $versionNumber = $this->getNextVersionNumber($recipe_id);
            
            // Récupérer le nom de l'utilisateur
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
            $stmt->bindParam(":change_comment", $comment);
            $stmt->bindParam(":version_number", $versionNumber);
            $stmt->bindParam(":modified_by", $modified_by);
            $stmt->bindParam(":modified_at", $modified_at);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Erreur saveVersion: " . $e->getMessage());
            return false;
        }
    }
    
    public function getNextVersionNumber($recipe_id) {
        $query = "SELECT MAX(version_number) as max_version FROM recipe_versions WHERE recipe_id = :recipe_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":recipe_id", $recipe_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result['max_version'] ?? 0) + 1;
    }
    
    public function getLatestVersionNumber($recipe_id) {
        $query = "SELECT MAX(version_number) as max_version FROM recipe_versions WHERE recipe_id = :recipe_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":recipe_id", $recipe_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['max_version'] ?? 0;
    }
}
?>