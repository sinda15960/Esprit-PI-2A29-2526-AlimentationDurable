<?php
require_once dirname(__DIR__) . '/config/database.php';

class Instruction {
    private $conn;
    private $table = "instructions";

    public $id;
    public $recipe_id;
    public $step_number;
    public $description;
    public $tip;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . "
                  SET recipe_id=:recipe_id, step_number=:step_number, 
                      description=:description, tip=:tip";
        
        $stmt = $this->conn->prepare($query);
        
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->tip = htmlspecialchars(strip_tags($this->tip));
        
        $stmt->bindParam(":recipe_id", $this->recipe_id);
        $stmt->bindParam(":step_number", $this->step_number);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":tip", $this->tip);
        
        return $stmt->execute();
    }

    public function readByRecipe($recipe_id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE recipe_id = :recipe_id 
                  ORDER BY step_number ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":recipe_id", $recipe_id);
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table . "
                  SET step_number=:step_number, description=:description, tip=:tip
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":step_number", $this->step_number);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":tip", $this->tip);
        
        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    public function deleteByRecipe($recipe_id) {
        $query = "DELETE FROM " . $this->table . " WHERE recipe_id = :recipe_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":recipe_id", $recipe_id);
        return $stmt->execute();
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>