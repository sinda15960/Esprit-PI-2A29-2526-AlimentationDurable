<?php
require_once dirname(__DIR__) . '/config/database.php';

class Recipe {
    private $conn;
    private $table = "recipes";

    // Propriétés de la recette
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
    public $idCategorie;
    public $created_at;
    public $updated_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // ==================== CREATE ====================
    
    public function create() {
        try {
            $query = "INSERT INTO " . $this->table . "
                      (title, description, ingredients, prep_time, cook_time, difficulty, 
                       calories, protein, carbs, fats, image_url, is_vegan, is_vegetarian, 
                       is_gluten_free, idCategorie)
                      VALUES 
                      (:title, :description, :ingredients, :prep_time, :cook_time, :difficulty,
                       :calories, :protein, :carbs, :fats, :image_url, :is_vegan, :is_vegetarian,
                       :is_gluten_free, :idCategorie)";

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
            $stmt->bindParam(":idCategorie", $this->idCategorie);
            
            if($stmt->execute()) {
                $this->id = $this->conn->lastInsertId();
                return $this->id;
            }
            return false;
        } catch(PDOException $e) {
            error_log("Erreur PDO dans create(): " . $e->getMessage());
            return false;
        }
    }

    // ==================== READ ====================
    
    public function readAll() {
        try {
            $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO dans readAll(): " . $e->getMessage());
            return false;
        }
    }

    public function readAllWithCategorie() {
        try {
            $query = "SELECT r.*, 
                             c.idCategorie, c.nom as categorie_nom, 
                             c.icon as categorie_icon, c.couleur as categorie_couleur,
                             c.description as categorie_description
                      FROM " . $this->table . " r
                      LEFT JOIN categories c ON r.idCategorie = c.idCategorie
                      ORDER BY r.created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO dans readAllWithCategorie(): " . $e->getMessage());
            return false;
        }
    }

    public function readOne() {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 0,1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erreur PDO dans readOne(): " . $e->getMessage());
            return false;
        }
    }

    public function readOneWithCategorie() {
        try {
            $query = "SELECT r.*, 
                             c.idCategorie, c.nom as categorie_nom, 
                             c.icon as categorie_icon, c.couleur as categorie_couleur
                      FROM " . $this->table . " r
                      LEFT JOIN categories c ON r.idCategorie = c.idCategorie
                      WHERE r.id = :id LIMIT 0,1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erreur PDO dans readOneWithCategorie(): " . $e->getMessage());
            return false;
        }
    }

    public function readByCategorie($idCategorie) {
        try {
            $query = "SELECT r.*, 
                             c.nom as categorie_nom, c.icon as categorie_icon, c.couleur as categorie_couleur
                      FROM " . $this->table . " r
                      LEFT JOIN categories c ON r.idCategorie = c.idCategorie
                      WHERE r.idCategorie = :idCategorie
                      ORDER BY r.created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":idCategorie", $idCategorie);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO dans readByCategorie(): " . $e->getMessage());
            return false;
        }
    }

    // ==================== UPDATE ====================
    
    public function update() {
        try {
            $query = "UPDATE " . $this->table . "
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
            $stmt->bindParam(":idCategorie", $this->idCategorie);

            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Erreur PDO dans update(): " . $e->getMessage());
            return false;
        }
    }

    // ==================== DELETE ====================
    
    public function delete() {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $this->id);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Erreur PDO dans delete(): " . $e->getMessage());
            return false;
        }
    }

    // ==================== SEARCH ====================
    
    public function search($keyword) {
        try {
            $query = "SELECT * FROM " . $this->table . " 
                      WHERE title LIKE :keyword 
                      OR description LIKE :keyword 
                      OR ingredients LIKE :keyword
                      ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
            $keyword = "%{$keyword}%";
            $stmt->bindParam(":keyword", $keyword);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO dans search(): " . $e->getMessage());
            return false;
        }
    }

    public function searchWithCategorie($keyword) {
    try {
        $query = "SELECT r.*, 
                         c.nom as categorie_nom, c.icon as categorie_icon, c.couleur as categorie_couleur
                  FROM " . $this->table . " r
                  LEFT JOIN categories c ON r.idCategorie = c.idCategorie
                  WHERE r.title LIKE :keyword 
                     OR r.description LIKE :keyword 
                     OR r.ingredients LIKE :keyword
                     OR c.nom LIKE :keyword
                  ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $keyword = "%{$keyword}%";
        $stmt->bindParam(":keyword", $keyword);
        $stmt->execute();
        return $stmt;
    } catch(PDOException $e) {
        error_log("Erreur PDO: " . $e->getMessage());
        return false;
    }
}

    // ==================== FILTRES SPÉCIAUX ====================
    
    public function getByType($type) {
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
                  FROM " . $this->table . " r
                  LEFT JOIN categories c ON r.idCategorie = c.idCategorie
                  WHERE " . $sql . " 
                  ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    } catch(PDOException $e) {
        error_log("Erreur PDO dans getByType(): " . $e->getMessage());
        return false;
    }
}

    public function getQuickRecipes() {
    try {
        $query = "SELECT r.*, c.nom as categorie_nom, c.icon as categorie_icon, c.couleur as categorie_couleur
                  FROM " . $this->table . " r
                  LEFT JOIN categories c ON r.idCategorie = c.idCategorie
                  WHERE (r.prep_time + r.cook_time) <= 30
                  ORDER BY (r.prep_time + r.cook_time) ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    } catch(PDOException $e) {
        error_log("Erreur PDO dans getQuickRecipes(): " . $e->getMessage());
        return false;
    }
}

    public function getHealthyRecipes() {
        try {
            $query = "SELECT r.*, c.nom as categorie_nom
                      FROM " . $this->table . " r
                      LEFT JOIN categories c ON r.idCategorie = c.idCategorie
                      WHERE r.calories < 500 OR r.calories IS NULL
                      ORDER BY r.calories ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO dans getHealthyRecipes(): " . $e->getMessage());
            return false;
        }
    }

    public function getLatest($limit = 6) {
        try {
            $query = "SELECT r.*, c.nom as categorie_nom, c.icon as categorie_icon
                      FROM " . $this->table . " r
                      LEFT JOIN categories c ON r.idCategorie = c.idCategorie
                      ORDER BY r.created_at DESC 
                      LIMIT :limit";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO dans getLatest(): " . $e->getMessage());
            return false;
        }
    }

    public function getByDifficulty($difficulty) {
        try {
            $query = "SELECT r.*, c.nom as categorie_nom
                      FROM " . $this->table . " r
                      LEFT JOIN categories c ON r.idCategorie = c.idCategorie
                      WHERE r.difficulty = :difficulty 
                      ORDER BY r.created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":difficulty", $difficulty);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO dans getByDifficulty(): " . $e->getMessage());
            return false;
        }
    }

    // ==================== STATISTIQUES ====================
    
    public function count() {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch(PDOException $e) {
            error_log("Erreur PDO dans count(): " . $e->getMessage());
            return 0;
        }
    }

    public function countByCategorie() {
        try {
            $query = "SELECT c.idCategorie, c.nom, COUNT(r.id) as nb_recettes
                      FROM categories c
                      LEFT JOIN " . $this->table . " r ON c.idCategorie = r.idCategorie
                      GROUP BY c.idCategorie
                      ORDER BY nb_recettes DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO dans countByCategorie(): " . $e->getMessage());
            return false;
        }
    }

    public function countByDifficulty() {
        try {
            $query = "SELECT difficulty, COUNT(*) as total 
                      FROM " . $this->table . " 
                      GROUP BY difficulty";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO dans countByDifficulty(): " . $e->getMessage());
            return false;
        }
    }

    public function countByType() {
        try {
            $query = "SELECT 
                        SUM(is_vegan) as vegan,
                        SUM(is_vegetarian) as vegetarian,
                        SUM(is_gluten_free) as gluten_free,
                        COUNT(*) as total
                      FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erreur PDO dans countByType(): " . $e->getMessage());
            return false;
        }
    }

    // ==================== VALIDATION & NETTOYAGE ====================
    
    public function validate() {
        $errors = [];
        
        if(empty($this->title) || strlen($this->title) < 3) {
            $errors[] = "Le titre doit contenir au moins 3 caractères";
        }
        
        if(empty($this->description) || strlen($this->description) < 20) {
            $errors[] = "La description doit contenir au moins 20 caractères";
        }
        
        if(empty($this->ingredients) || strlen($this->ingredients) < 10) {
            $errors[] = "La liste des ingrédients doit contenir au moins 10 caractères";
        }
        
        if(empty($this->prep_time) || $this->prep_time <= 0) {
            $errors[] = "Le temps de préparation doit être un nombre positif";
        }
        
        if(!isset($this->cook_time) || $this->cook_time < 0) {
            $errors[] = "Le temps de cuisson doit être un nombre valide";
        }
        
        return $errors;
    }
    // Recettes économiques (simulé par calories basses)
    public function getEconomiqueRecipes() {
        try {
            $query = "SELECT r.*, c.nom as categorie_nom
                    FROM " . $this->table . " r
                    LEFT JOIN categories c ON r.idCategorie = c.idCategorie
                    WHERE r.calories < 400 OR r.calories IS NULL
                    ORDER BY r.calories ASC
                    LIMIT 10";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO dans getEconomiqueRecipes(): " . $e->getMessage());
            return false;
        }
    }

    private function sanitizeInputs() {
        $this->title = htmlspecialchars(strip_tags(trim($this->title)));
        $this->description = htmlspecialchars(strip_tags(trim($this->description)));
        $this->ingredients = htmlspecialchars(strip_tags(trim($this->ingredients)));
        $this->image_url = htmlspecialchars(strip_tags(trim($this->image_url)));
        
        if(empty($this->calories)) $this->calories = null;
        if(empty($this->protein)) $this->protein = null;
        if(empty($this->carbs)) $this->carbs = null;
        if(empty($this->fats)) $this->fats = null;
        if(empty($this->image_url)) $this->image_url = null;
        if(empty($this->idCategorie)) $this->idCategorie = null;
    }
}
?>