<?php
class Recipe {
    private $conn;
    private $table = "recipes";

    // Propriétés
    private $id;
    private $title;
    private $description;
    private $ingredients;
    private $prep_time;
    private $cook_time;
    private $difficulty;
    private $calories;
    private $protein;
    private $carbs;
    private $fats;
    private $image_url;
    private $is_vegan;
    private $is_vegetarian;
    private $is_gluten_free;
    private $idCategorie;
    private $created_at;
    private $updated_at;

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
    public function getTitle() { return $this->title; }
    public function getDescription() { return $this->description; }
    public function getIngredients() { return $this->ingredients; }
    public function getPrepTime() { return $this->prep_time; }
    public function getCookTime() { return $this->cook_time; }
    public function getDifficulty() { return $this->difficulty; }
    public function getCalories() { return $this->calories; }
    public function getProtein() { return $this->protein; }
    public function getCarbs() { return $this->carbs; }
    public function getFats() { return $this->fats; }
    public function getImageUrl() { return $this->image_url; }
    public function getIsVegan() { return $this->is_vegan; }
    public function getIsVegetarian() { return $this->is_vegetarian; }
    public function getIsGlutenFree() { return $this->is_gluten_free; }
    public function getIdCategorie() { return $this->idCategorie; }
    public function getCreatedAt() { return $this->created_at; }
    public function getUpdatedAt() { return $this->updated_at; }
    public function getTable() { return $this->table; }
    public function getConnection() { return $this->conn; }

    // ==================== SETTERS ====================
    public function setId($id) { $this->id = $id; }
    public function setTitle($title) { $this->title = $title; }
    public function setDescription($description) { $this->description = $description; }
    public function setIngredients($ingredients) { $this->ingredients = $ingredients; }
    public function setPrepTime($prep_time) { $this->prep_time = $prep_time; }
    public function setCookTime($cook_time) { $this->cook_time = $cook_time; }
    public function setDifficulty($difficulty) { $this->difficulty = $difficulty; }
    public function setCalories($calories) { $this->calories = $calories; }
    public function setProtein($protein) { $this->protein = $protein; }
    public function setCarbs($carbs) { $this->carbs = $carbs; }
    public function setFats($fats) { $this->fats = $fats; }
    public function setImageUrl($image_url) { $this->image_url = $image_url; }
    public function setIsVegan($is_vegan) { $this->is_vegan = $is_vegan; }
    public function setIsVegetarian($is_vegetarian) { $this->is_vegetarian = $is_vegetarian; }
    public function setIsGlutenFree($is_gluten_free) { $this->is_gluten_free = $is_gluten_free; }
    public function setIdCategorie($idCategorie) { $this->idCategorie = $idCategorie; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }
}
?>