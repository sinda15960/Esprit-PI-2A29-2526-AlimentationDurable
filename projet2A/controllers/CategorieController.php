<?php
require_once dirname(__DIR__) . '/models/Categorie.php';
require_once dirname(__DIR__) . '/models/Recipe.php';

class CategorieController {
    private $categorie;
    private $recipe;

    public function __construct() {
        $this->categorie = new Categorie();
        $this->recipe = new Recipe();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ==================== VALIDATION PERSONNALISÉE (sans HTML5) ====================
    
    private function validateCategorieData($data) {
        $errors = [];
        
        // Validation du nom
        if(empty($data['nom']) || strlen(trim($data['nom'])) < 2) {
            $errors['nom'] = "Le nom doit contenir au moins 2 caractères";
        }
        
        if(strlen(trim($data['nom'])) > 50) {
            $errors['nom'] = "Le nom ne peut pas dépasser 50 caractères";
        }
        
        // Validation des caractères autorisés pour le nom
        if(!empty($data['nom']) && !preg_match('/^[a-zA-Z0-9À-ÿ\s\-éèêëàâäôöûüç]+$/', $data['nom'])) {
            $errors['nom'] = "Le nom contient des caractères non autorisés";
        }
        
        // Validation de la description (optionnelle)
        if(!empty($data['description']) && strlen(trim($data['description'])) > 255) {
            $errors['description'] = "La description ne peut pas dépasser 255 caractères";
        }
        
        // Validation de l'icône
        if(empty($data['icon'])) {
            $errors['icon'] = "Veuillez sélectionner une icône";
        }
        
        // Validation de la couleur
        if(empty($data['couleur'])) {
            $errors['couleur'] = "Veuillez sélectionner une couleur";
        }
        
        return $errors;
    }

    // ==================== FRONTOFFICE ====================
    
    // Afficher le formulaire de recherche par catégorie
    public function frontSearchByCategorie() {
        try {
            $stmt = $this->categorie->readAll();
            $categories = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $categories[] = $row;
            }
            
            $recettes = [];
            
            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idCategorie'])) {
                $idCategorie = (int)$_POST['idCategorie'];
                $stmtRecettes = $this->recipe->readByCategorie($idCategorie);
                while($row = $stmtRecettes->fetch(PDO::FETCH_ASSOC)) {
                    $recettes[] = $row;
                }
            }
            
            require_once dirname(__DIR__) . '/views/frontoffice/categories/search.php';
        } catch(Exception $e) {
            $_SESSION['error'] = "Erreur: " . $e->getMessage();
            require_once dirname(__DIR__) . '/views/frontoffice/categories/search.php';
        }
    }
    
    // Afficher les recettes d'une catégorie spécifique
    public function frontRecettesByCategorie($idCategorie) {
        try {
            $this->categorie->idCategorie = $idCategorie;
            $categorie = $this->categorie->readOne();
            
            if($categorie) {
                $stmt = $this->recipe->readByCategorie($idCategorie);
                $recettes = [];
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $recettes[] = $row;
                }
                require_once dirname(__DIR__) . '/views/frontoffice/categories/show.php';
            } else {
                $_SESSION['error'] = "Catégorie non trouvée";
                header("Location: index.php?action=searchByCategorie");
                exit();
            }
        } catch(Exception $e) {
            $_SESSION['error'] = "Erreur: " . $e->getMessage();
            header("Location: index.php?action=searchByCategorie");
            exit();
        }
    }

    // ==================== BACKOFFICE ====================
    
    // Afficher toutes les catégories
    public function backIndex() {
    try {
        // Récupérer toutes les catégories avec le nombre de recettes
        $stmt = $this->categorie->readAllWithRecettesCount();
        $categories = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = $row;
        }
        
        // Debug: voir si des catégories sont trouvées
        // var_dump($categories); // Décommente pour tester
        
        require_once dirname(__DIR__) . '/views/backoffice/categories/index.php';
    } catch(Exception $e) {
        $_SESSION['error'] = "Erreur: " . $e->getMessage();
        $categories = [];
        require_once dirname(__DIR__) . '/views/backoffice/categories/index.php';
    }
}
    
    // Créer une catégorie (avec validation)
    public function backCreate() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation des données
            $errors = $this->validateCategorieData($_POST);
            
            if(empty($errors)) {
                try {
                    $this->categorie->nom = trim($_POST['nom']);
                    $this->categorie->description = !empty($_POST['description']) ? trim($_POST['description']) : null;
                    $this->categorie->icon = !empty($_POST['icon']) ? $_POST['icon'] : 'fas fa-tag';
                    $this->categorie->couleur = !empty($_POST['couleur']) ? $_POST['couleur'] : '#2ecc71';
                    
                    $id = $this->categorie->create();
                    
                    if($id) {
                        $_SESSION['success'] = "Catégorie \"" . htmlspecialchars($this->categorie->nom) . "\" créée avec succès !";
                    } else {
                        $_SESSION['error'] = "Erreur lors de la création de la catégorie";
                    }
                } catch(Exception $e) {
                    $_SESSION['error'] = "Erreur: " . $e->getMessage();
                }
            } else {
                $_SESSION['errors'] = $errors;
            }
        }
        
        header("Location: index.php?action=backCategories");
        exit();
    }
    
    // Modifier une catégorie (avec validation)
    public function backEdit($id) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation des données
            $errors = $this->validateCategorieData($_POST);
            
            if(empty($errors)) {
                try {
                    $this->categorie->idCategorie = $id;
                    $this->categorie->nom = trim($_POST['nom']);
                    $this->categorie->description = !empty($_POST['description']) ? trim($_POST['description']) : null;
                    $this->categorie->icon = !empty($_POST['icon']) ? $_POST['icon'] : 'fas fa-tag';
                    $this->categorie->couleur = !empty($_POST['couleur']) ? $_POST['couleur'] : '#2ecc71';
                    
                    if($this->categorie->update()) {
                        $_SESSION['success'] = "Catégorie modifiée avec succès !";
                    } else {
                        $_SESSION['error'] = "Erreur lors de la modification";
                    }
                } catch(Exception $e) {
                    $_SESSION['error'] = "Erreur: " . $e->getMessage();
                }
            } else {
                $_SESSION['errors'] = $errors;
            }
        }
        
        header("Location: index.php?action=backCategories");
        exit();
    }
    
    // Supprimer une catégorie
    public function backDelete($id) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->categorie->idCategorie = $id;
                
                // Vérifier si la catégorie a des recettes
                $stmt = $this->recipe->readByCategorie($id);
                $recetteCount = $stmt->rowCount();
                
                if($recetteCount > 0) {
                    $_SESSION['warning'] = "Cette catégorie contient $recetteCount recette(s). Les recettes ne seront pas supprimées mais n'auront plus de catégorie.";
                }
                
                if($this->categorie->delete()) {
                    $_SESSION['success'] = "Catégorie supprimée avec succès !";
                } else {
                    $_SESSION['error'] = "Erreur lors de la suppression";
                }
            } catch(Exception $e) {
                $_SESSION['error'] = "Erreur: " . $e->getMessage();
            }
        }
        
        header("Location: index.php?action=backCategories");
        exit();
    }
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
}
?>