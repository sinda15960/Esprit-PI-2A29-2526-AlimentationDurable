<?php
require_once dirname(__DIR__) . '/config/database.php';

class Categorie {
    private $conn;
    private $table = "categories";

    // Propriétés de la catégorie
    public $idCategorie;
    public $nom;
    public $description;
    public $icon;
    public $couleur;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // ==================== READ ====================
    
    // Lire toutes les catégories
    public function readAll() {
        try {
            $query = "SELECT * FROM " . $this->table . " ORDER BY nom ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO dans readAll(): " . $e->getMessage());
            return false;
        }
    }

    // Lire une catégorie par ID
    public function readOne() {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE idCategorie = :idCategorie LIMIT 0,1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":idCategorie", $this->idCategorie);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erreur PDO dans readOne(): " . $e->getMessage());
            return false;
        }
    }

    // Lire les catégories avec nombre de recettes (JOINTURE)
    public function readAllWithRecettesCount() {
        try {
            $query = "SELECT c.*, COUNT(r.id) as nb_recettes 
                      FROM " . $this->table . " c
                      LEFT JOIN recipes r ON c.idCategorie = r.idCategorie
                      GROUP BY c.idCategorie
                      ORDER BY c.nom ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO dans readAllWithRecettesCount(): " . $e->getMessage());
            return false;
        }
    }

    // ==================== CREATE ====================
    
    // Créer une catégorie
    public function create() {
        try {
            $query = "INSERT INTO " . $this->table . "
                      SET nom = :nom, 
                          description = :description, 
                          icon = :icon, 
                          couleur = :couleur";
            
            $stmt = $this->conn->prepare($query);
            
            // Nettoyer les données
            $this->sanitizeInputs();
            
            // Lier les paramètres
            $stmt->bindParam(":nom", $this->nom);
            $stmt->bindParam(":description", $this->description);
            $stmt->bindParam(":icon", $this->icon);
            $stmt->bindParam(":couleur", $this->couleur);
            
            if($stmt->execute()) {
                $this->idCategorie = $this->conn->lastInsertId();
                return $this->idCategorie;
            }
            return false;
        } catch(PDOException $e) {
            error_log("Erreur PDO dans create(): " . $e->getMessage());
            return false;
        }
    }

    // ==================== UPDATE ====================
    
    // Mettre à jour une catégorie
    public function update() {
        try {
            $query = "UPDATE " . $this->table . "
                      SET nom = :nom, 
                          description = :description, 
                          icon = :icon, 
                          couleur = :couleur
                      WHERE idCategorie = :idCategorie";
            
            $stmt = $this->conn->prepare($query);
            
            // Nettoyer les données
            $this->sanitizeInputs();
            
            // Lier les paramètres
            $stmt->bindParam(":idCategorie", $this->idCategorie);
            $stmt->bindParam(":nom", $this->nom);
            $stmt->bindParam(":description", $this->description);
            $stmt->bindParam(":icon", $this->icon);
            $stmt->bindParam(":couleur", $this->couleur);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Erreur PDO dans update(): " . $e->getMessage());
            return false;
        }
    }

    // ==================== DELETE ====================
    
    // Supprimer une catégorie
    public function delete() {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE idCategorie = :idCategorie";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":idCategorie", $this->idCategorie);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Erreur PDO dans delete(): " . $e->getMessage());
            return false;
        }
    }

    // ==================== VALIDATION ====================
    
    // Valider les données de la catégorie
    public function validate() {
        $errors = [];
        
        if(empty($this->nom) || strlen(trim($this->nom)) < 2) {
            $errors['nom'] = "Le nom doit contenir au moins 2 caractères";
        }
        
        if(strlen(trim($this->nom)) > 50) {
            $errors['nom'] = "Le nom ne peut pas dépasser 50 caractères";
        }
        
        if(!empty($this->description) && strlen(trim($this->description)) > 255) {
            $errors['description'] = "La description ne peut pas dépasser 255 caractères";
        }
        
        return $errors;
    }

    // Nettoyer les entrées utilisateur
    private function sanitizeInputs() {
        $this->nom = htmlspecialchars(strip_tags(trim($this->nom)));
        $this->description = htmlspecialchars(strip_tags(trim($this->description)));
        $this->icon = htmlspecialchars(strip_tags(trim($this->icon)));
        $this->couleur = htmlspecialchars(strip_tags(trim($this->couleur)));
        
        // Valeurs par défaut si vides
        if(empty($this->icon)) {
            $this->icon = "fas fa-tag";
        }
        if(empty($this->couleur)) {
            $this->couleur = "#2ecc71";
        }
    }

    // ==================== STATISTIQUES ====================
    
    // Obtenir le nombre de recettes par catégorie
    public function getRecettesCount() {
        try {
            $query = "SELECT c.idCategorie, c.nom, COUNT(r.id) as nb_recettes 
                      FROM " . $this->table . " c
                      LEFT JOIN recipes r ON c.idCategorie = r.idCategorie
                      GROUP BY c.idCategorie
                      ORDER BY nb_recettes DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO dans getRecettesCount(): " . $e->getMessage());
            return false;
        }
    }

    // Obtenir le nombre total de catégories
    public function getTotalCount() {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch(PDOException $e) {
            error_log("Erreur PDO dans getTotalCount(): " . $e->getMessage());
            return 0;
        }
    }

    // ==================== RECHERCHE ====================
    
    // Rechercher des catégories par nom
    public function search($keyword) {
        try {
            $query = "SELECT * FROM " . $this->table . " 
                      WHERE nom LIKE :keyword 
                      OR description LIKE :keyword
                      ORDER BY nom ASC";
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

    // ==================== LISTES DÉROULANTES ====================
    
    // Obtenir les catégories pour un select (id + nom)
    public function getForSelect() {
        try {
            $query = "SELECT idCategorie, nom FROM " . $this->table . " ORDER BY nom ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO dans getForSelect(): " . $e->getMessage());
            return false;
        }
    }

    // ==================== ICÔNES DISPONIBLES ====================
    
    // Liste des icônes Font Awesome disponibles
    public static function getAvailableIcons() {
        return [
            'fas fa-utensils' => 'Ustensiles',
            'fas fa-leaf' => 'Feuille (Vegan)',
            'fas fa-carrot' => 'Carotte (Végétarien)',
            'fas fa-seedling' => 'Plantation',
            'fas fa-apple-alt' => 'Pomme',
            'fas fa-cake-candles' => 'Gâteau',
            'fas fa-mug-hot' => 'Tasse chaude',
            'fas fa-bread-slice' => 'Pain',
            'fas fa-cheese' => 'Fromage',
            'fas fa-egg' => 'Œuf',
            'fas fa-fish' => 'Poisson',
            'fas fa-drumstick-bite' => 'Poulet',
            'fas fa-hamburger' => 'Burger',
            'fas fa-pizza-slice' => 'Pizza',
            'fas fa-ice-cream' => 'Glace',
            'fas fa-cocktail' => 'Cocktail',
            'fas fa-coffee' => 'Café',
            'fas fa-wine-bottle' => 'Vin',
            'fas fa-mortar-board' => 'Chef',
            'fas fa-heart' => 'Cœur (Favoris)',
            'fas fa-star' => 'Étoile (Populaire)',
            'fas fa-clock' => 'Horloge (Rapide)',
            'fas fa-fire' => 'Feu (Épicé)',
            'fas fa-snowflake' => 'Flocon (Frais)',
            'fas fa-sun' => 'Soleil (Petit-déjeuner)',
            'fas fa-moon' => 'Lune (Dîner)',
            'fas fa-smile' => 'Sourire (Facile)',
            'fas fa-frown' => 'Triste (Difficile)'
        ];
    }

    // Liste des couleurs disponibles
    public static function getAvailableColors() {
        return [
            '#2ecc71' => 'Vert',
            '#3498db' => 'Bleu',
            '#e74c3c' => 'Rouge',
            '#f39c12' => 'Orange',
            '#9b59b6' => 'Violet',
            '#1abc9c' => 'Turquoise',
            '#e67e22' => 'Corail',
            '#27ae60' => 'Vert foncé',
            '#2980b9' => 'Bleu foncé',
            '#8e44ad' => 'Violet foncé',
            '#f1c40f' => 'Jaune',
            '#34495e' => 'Bleu nuit',
            '#7f8c8d' => 'Gris',
            '#c0392b' => 'Rouge foncé',
            '#d35400' => 'Orange foncé'
        ];
    }
}
?>