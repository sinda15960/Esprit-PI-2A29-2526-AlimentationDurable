<?php
require_once dirname(__DIR__) . '/models/Categorie.php';
require_once dirname(__DIR__) . '/models/Recipe.php';
require_once dirname(__DIR__) . '/config/database.php';

class CategorieController {
    private $categorieModel;
    private $recipeModel;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->categorieModel = new Categorie($this->db);
        $this->recipeModel = new Recipe($this->db);
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ==================== VALIDATION ====================
    private function validateCategorieData($data) {
        $errors = [];
        if(empty($data['nom']) || strlen(trim($data['nom'])) < 2) {
            $errors['nom'] = "Le nom doit contenir au moins 2 caractères";
        }
        if(strlen(trim($data['nom'])) > 50) {
            $errors['nom'] = "Le nom ne peut pas dépasser 50 caractères";
        }
        if(!empty($data['nom']) && !preg_match('/^[a-zA-Z0-9À-ÿ\s\-éèêëàâäôöûüç]+$/', $data['nom'])) {
            $errors['nom'] = "Le nom contient des caractères non autorisés";
        }
        if(!empty($data['description']) && strlen(trim($data['description'])) > 255) {
            $errors['description'] = "La description ne peut pas dépasser 255 caractères";
        }
        if(empty($data['icon'])) {
            $errors['icon'] = "Veuillez sélectionner une icône";
        }
        if(empty($data['couleur'])) {
            $errors['couleur'] = "Veuillez sélectionner une couleur";
        }
        return $errors;
    }

    // ==================== LOGIQUE MÉTIER ====================
    
    public function readAllCategories() {
        try {
            $query = "SELECT * FROM " . $this->categorieModel->getTable() . " ORDER BY nom ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function readOneCategorie($id) {
        try {
            $query = "SELECT * FROM " . $this->categorieModel->getTable() . " WHERE idCategorie = :idCategorie LIMIT 0,1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":idCategorie", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function readAllWithRecettesCount() {
        try {
            $query = "SELECT c.*, COUNT(r.id) as nb_recettes 
                      FROM " . $this->categorieModel->getTable() . " c
                      LEFT JOIN recipes r ON c.idCategorie = r.idCategorie
                      GROUP BY c.idCategorie
                      ORDER BY c.nom ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function createCategorie($data) {
        try {
            $query = "INSERT INTO " . $this->categorieModel->getTable() . "
                      SET nom = :nom, description = :description, icon = :icon, couleur = :couleur";
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(":nom", $data['nom']);
            $stmt->bindParam(":description", $data['description']);
            $stmt->bindParam(":icon", $data['icon']);
            $stmt->bindParam(":couleur", $data['couleur']);
            
            if($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function updateCategorie($id, $data) {
        try {
            $query = "UPDATE " . $this->categorieModel->getTable() . "
                      SET nom = :nom, description = :description, icon = :icon, couleur = :couleur
                      WHERE idCategorie = :idCategorie";
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(":idCategorie", $id);
            $stmt->bindParam(":nom", $data['nom']);
            $stmt->bindParam(":description", $data['description']);
            $stmt->bindParam(":icon", $data['icon']);
            $stmt->bindParam(":couleur", $data['couleur']);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function deleteCategorie($id) {
        try {
            $query = "DELETE FROM " . $this->categorieModel->getTable() . " WHERE idCategorie = :idCategorie";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":idCategorie", $id);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Erreur PDO: " . $e->getMessage());
            return false;
        }
    }

    public function getRecettesByCategorie($idCategorie) {
        try {
            $query = "SELECT r.*, c.nom as categorie_nom, c.icon as categorie_icon, c.couleur as categorie_couleur
                      FROM recipes r
                      LEFT JOIN " . $this->categorieModel->getTable() . " c ON r.idCategorie = c.idCategorie
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

    // ==================== VUES (FRONTOFFICE) ====================
    
    public function frontSearchByCategorie() {
        try {
            $stmt = $this->readAllCategories();
            $categories = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $categories[] = $row;
            }
            
            $recettes = [];
            
            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idCategorie'])) {
                $idCategorie = (int)$_POST['idCategorie'];
                $stmtRecettes = $this->getRecettesByCategorie($idCategorie);
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
    
    public function frontRecettesByCategorie($idCategorie) {
        try {
            $categorie = $this->readOneCategorie($idCategorie);
            
            if($categorie) {
                $stmt = $this->getRecettesByCategorie($idCategorie);
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

    // ==================== VUES (BACKOFFICE) ====================
    
    public function backIndex() {
        try {
            $stmt = $this->readAllWithRecettesCount();
            $categories = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $categories[] = $row;
            }
            require_once dirname(__DIR__) . '/views/backoffice/categories/index.php';
        } catch(Exception $e) {
            $_SESSION['error'] = "Erreur: " . $e->getMessage();
            $categories = [];
            require_once dirname(__DIR__) . '/views/backoffice/categories/index.php';
        }
    }
    // Vérifier les objectifs des catégories
private function checkCategoryGoals($idCategorie, $nbRecettes) {
    // Récupérer l'objectif depuis la session
    $goal = $_SESSION['category_goals'][$idCategorie] ?? 10;
    
    // Vérifier si l'objectif est atteint
    if ($nbRecettes >= $goal) {
        // Récupérer le nom de la catégorie
        $categorie = $this->readOneCategorie($idCategorie);
        $nomCategorie = $categorie['nom'] ?? 'Catégorie';
        
        $this->addNotification(
            "🏆 Objectif atteint !",
            "La catégorie \"$nomCategorie\" a atteint son objectif de $goal recettes !",
            "success",
            "fas fa-trophy"
        );
    }
    
    // Vérifier si plus que 2 recettes pour atteindre l'objectif
    $reste = $goal - $nbRecettes;
    if ($reste == 2) {
        $categorie = $this->readOneCategorie($idCategorie);
        $nomCategorie = $categorie['nom'] ?? 'Catégorie';
        
        $this->addNotification(
            "🔥 Encore $reste recettes !",
            "Plus que $reste recettes pour que la catégorie \"$nomCategorie\" atteigne son objectif !",
            "warning",
            "fas fa-fire"
        );
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
// Vérifier les niveaux (badges)
private function checkRecipeMilestones() {
    // Récupérer le nombre total de recettes
    $stmt = $this->getAllRecipes();
    $totalRecipes = $stmt->rowCount();
    
    // Définir les paliers
    $milestones = [
        5 => ["Niveau Débutant 🥉", "Félicitations ! Vous avez créé 5 recettes. Niveau Débutant atteint !"],
        10 => ["Niveau Apprenti 🍳", "Bravo ! 10 recettes créées. Vous êtes maintenant Niveau Apprenti !"],
        20 => ["Niveau Cuisinier 👨‍🍳", "Excellent ! 20 recettes créées. Niveau Cuisinier atteint !"],
        30 => ["Niveau Chef 🧑‍🍳", "Magnifique ! 30 recettes créées. Vous êtes devenu Chef !"],
        50 => ["Niveau Master Chef 🏆", "Exceptionnel ! 50 recettes créées. Master Chef légendaire !"]
    ];
    
    // Vérifier les paliers atteints
    foreach ($milestones as $seuil => $milestone) {
        if ($totalRecipes >= $seuil) {
            // Vérifier si la notification a déjà été envoyée
            $key = "milestone_$seuil";
            if (!isset($_SESSION['milestones_notified'][$key])) {
                $_SESSION['milestones_notified'][$key] = true;
                $this->addNotification(
                    $milestone[0],
                    $milestone[1],
                    "success",
                    "fas fa-medal"
                );
            }
        }
    }
    
    // Vérifier si plus que 2 recettes pour le prochain niveau
    $nextLevels = [5, 10, 20, 30, 50];
    foreach ($nextLevels as $nextLevel) {
        $remaining = $nextLevel - $totalRecipes;
        if ($remaining == 2 && $totalRecipes < $nextLevel) {
            $key = "reminder_$nextLevel";
            if (!isset($_SESSION['milestones_notified'][$key])) {
                $_SESSION['milestones_notified'][$key] = true;
                $this->addNotification(
                    "🔥 Encore $remaining recettes !",
                    "Plus que $remaining recettes pour atteindre le niveau " . $this->getLevelName($nextLevel) . " !",
                    "warning",
                    "fas fa-fire"
                );
            }
        }
    }
}

private function getLevelName($level) {
    $levels = [
        5 => "Débutant",
        10 => "Apprenti",
        20 => "Cuisinier",
        30 => "Chef",
        50 => "Master Chef"
    ];
    return $levels[$level] ?? "Supérieur";
}
    
    public function backCreate() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateCategorieData($_POST);
            
            if(empty($errors)) {
                try {
                    $data = [
                        'nom' => trim($_POST['nom']),
                        'description' => !empty($_POST['description']) ? trim($_POST['description']) : null,
                        'icon' => !empty($_POST['icon']) ? $_POST['icon'] : 'fas fa-tag',
                        'couleur' => !empty($_POST['couleur']) ? $_POST['couleur'] : '#2ecc71'
                    ];
                    
                    $id = $this->createCategorie($data);
                    
                    if($id) {
                        $_SESSION['success'] = "Catégorie \"" . htmlspecialchars($data['nom']) . "\" créée avec succès !";
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
    
    public function backEdit($id) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateCategorieData($_POST);
            
            if(empty($errors)) {
                try {
                    $data = [
                        'nom' => trim($_POST['nom']),
                        'description' => !empty($_POST['description']) ? trim($_POST['description']) : null,
                        'icon' => !empty($_POST['icon']) ? $_POST['icon'] : 'fas fa-tag',
                        'couleur' => !empty($_POST['couleur']) ? $_POST['couleur'] : '#2ecc71'
                    ];
                    
                    if($this->updateCategorie($id, $data)) {
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
    
    public function backDelete($id) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $stmt = $this->getRecettesByCategorie($id);
                $recetteCount = $stmt->rowCount();
                
                if($recetteCount > 0) {
                    $_SESSION['warning'] = "Cette catégorie contient $recetteCount recette(s). Les recettes ne seront pas supprimées mais n'auront plus de catégorie.";
                }
                
                if($this->deleteCategorie($id)) {
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
}
?>