<?php
class Instruction {
    private $conn;
    private $table = "instructions";

    // Propriétés
    private $id;
    private $recipe_id;
    private $step_number;
    private $description;
    private $tip;

    // Constructeur
    public function __construct($db) {
        $this->conn = $db;
    }

    // Destructeur
    public function __destruct() {
        $this->conn = null;
    }

    // ==================== GETTERS ====================
    public function getId() { return $this->id; }
    public function getRecipeId() { return $this->recipe_id; }
    public function getStepNumber() { return $this->step_number; }
    public function getDescription() { return $this->description; }
    public function getTip() { return $this->tip; }
    public function getTable() { return $this->table; }
    public function getConnection() { return $this->conn; }

    // ==================== SETTERS ====================
    public function setId($id) { $this->id = $id; }
    public function setRecipeId($recipe_id) { $this->recipe_id = $recipe_id; }
    public function setStepNumber($step_number) { $this->step_number = $step_number; }
    public function setDescription($description) { $this->description = $description; }
    public function setTip($tip) { $this->tip = $tip; }
}
?>