<?php
require_once dirname(__DIR__) . '/config/database.php';

class Recipe {
    private $conn;
    private $table = "recipes";

    public $id;
    public $title;
    public $description;
    public $ingredients;
    public $prep_time;
    public $cook_time;
    public $difficulty;
    public $calories;
    public $protein;
    public $carbs;
    public $fats;
    public $image_url;
    public $is_vegan;
    public $is_vegetarian;
    public $is_gluten_free;
    public $created_at;
    public $updated_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . "
                  SET title=:title, description=:description, ingredients=:ingredients,
                      prep_time=:prep_time, cook_time=:cook_time, difficulty=:difficulty,
                      calories=:calories, protein=:protein, carbs=:carbs, fats=:fats,
                      image_url=:image_url, is_vegan=:is_vegan, is_vegetarian=:is_vegetarian,
                      is_gluten_free=:is_gluten_free";

        $stmt = $this->conn->prepare($query);

        $this->sanitizeInputs();

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":ingredients", $this->ingredients);
        $stmt->bindParam(":prep_time", $this->prep_time);
        $stmt->bindParam(":cook_time", $this->cook_time);
        $stmt->bindParam(":difficulty", $this->difficulty);
        $stmt->bindParam(":calories", $this->calories);
        $stmt->bindParam(":protein", $this->protein);
        $stmt->bindParam(":carbs", $this->carbs);
        $stmt->bindParam(":fats", $this->fats);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":is_vegan", $this->is_vegan);
        $stmt->bindParam(":is_vegetarian", $this->is_vegetarian);
        $stmt->bindParam(":is_gluten_free", $this->is_gluten_free);

        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        $query = "UPDATE " . $this->table . "
                  SET title=:title, description=:description, ingredients=:ingredients,
                      prep_time=:prep_time, cook_time=:cook_time, difficulty=:difficulty,
                      calories=:calories, protein=:protein, carbs=:carbs, fats=:fats,
                      image_url=:image_url, is_vegan=:is_vegan, is_vegetarian=:is_vegetarian,
                      is_gluten_free=:is_gluten_free
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $this->sanitizeInputs();
        
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":ingredients", $this->ingredients);
        $stmt->bindParam(":prep_time", $this->prep_time);
        $stmt->bindParam(":cook_time", $this->cook_time);
        $stmt->bindParam(":difficulty", $this->difficulty);
        $stmt->bindParam(":calories", $this->calories);
        $stmt->bindParam(":protein", $this->protein);
        $stmt->bindParam(":carbs", $this->carbs);
        $stmt->bindParam(":fats", $this->fats);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":is_vegan", $this->is_vegan);
        $stmt->bindParam(":is_vegetarian", $this->is_vegetarian);
        $stmt->bindParam(":is_gluten_free", $this->is_gluten_free);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function search($keyword) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE title LIKE :keyword OR description LIKE :keyword OR ingredients LIKE :keyword
                  ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $keyword = "%{$keyword}%";
        $stmt->bindParam(":keyword", $keyword);
        $stmt->execute();
        return $stmt;
    }

    private function sanitizeInputs() {
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->ingredients = htmlspecialchars(strip_tags($this->ingredients));
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));
    }
}
?>