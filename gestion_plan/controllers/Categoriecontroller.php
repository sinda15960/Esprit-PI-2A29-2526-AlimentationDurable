<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/Categorie.php';

class CategorieController {
    private $pdo;

    public function __construct() {
        $this->pdo = getConnection();
    }

    // BACK : liste des categories
    public function index($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        
        $sql = "SELECT * FROM categorie ORDER BY nom";
        $stmt = $this->pdo->query($sql);
        $categories = [];
        while ($row = $stmt->fetch()) {
            $categorie = new Categorie();
            $categorie->setId($row['id_categorie']);
            $categorie->setNom($row['nom']);
            $categorie->setDescription($row['description']);
            $categories[] = $categorie;
        }
        
        require __DIR__ . "/../views/back/categories/index.php";
    }

    // BACK : formulaire creation
    public function create($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $errors = [];
        require __DIR__ . "/../views/back/categories/create.php";
    }

    // BACK : enregistrer
    public function store($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $errors = $this->valider($_POST);
        if (!empty($errors)) {
            require __DIR__ . "/../views/back/categories/create.php";
            return;
        }
        
        $sql = "INSERT INTO categorie (nom, description) VALUES (:nom, :description)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom'         => trim($_POST['nom']),
            ':description' => trim($_POST['description'] ?? ''),
        ]);
        
        header("Location: index.php?module=categorie&action=index&office=back");
        exit;
    }

    // BACK : formulaire modification
    public function edit($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $errors = [];
        
        $sql = "SELECT * FROM categorie WHERE id_categorie = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([intval($_GET['id'])]);
        $row = $stmt->fetch();
        
        if (!$row) {
            header("Location: index.php?module=categorie&action=index&office=back"); exit;
        }
        
        $categorie = new Categorie();
        $categorie->setId($row['id_categorie']);
        $categorie->setNom($row['nom']);
        $categorie->setDescription($row['description']);
        
        require __DIR__ . "/../views/back/categories/edit.php";
    }

    // BACK : mettre a jour
    public function update($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $id     = intval($_POST['id']);
        $errors = $this->valider($_POST);
        
        if (!empty($errors)) {
            $sql = "SELECT * FROM categorie WHERE id_categorie = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            $row = $stmt->fetch();
            $categorie = new Categorie();
            $categorie->setId($row['id_categorie']);
            $categorie->setNom($row['nom']);
            $categorie->setDescription($row['description']);
            require __DIR__ . "/../views/back/categories/edit.php";
            return;
        }
        
        $sql = "UPDATE categorie SET nom = :nom, description = :description WHERE id_categorie = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom'         => trim($_POST['nom']),
            ':description' => trim($_POST['description'] ?? ''),
            ':id'          => $id,
        ]);
        
        header("Location: index.php?module=categorie&action=index&office=back");
        exit;
    }

    // BACK : supprimer
    public function delete($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $stmt = $this->pdo->prepare("DELETE FROM categorie WHERE id_categorie = ?");
        $stmt->execute([intval($_GET['id'])]);
        header("Location: index.php?module=categorie&action=index&office=back");
        exit;
    }

    // FRONT : filtrer programmes par categorie
    public function filterByCategorie($office = 'front') {
        $sql  = "SELECT * FROM categorie ORDER BY nom";
        $stmt = $this->pdo->query($sql);
        $categories = [];
        while ($row = $stmt->fetch()) {
            $categorie = new Categorie();
            $categorie->setId($row['id_categorie']);
            $categorie->setNom($row['nom']);
            $categorie->setDescription($row['description']);
            $categories[] = $categorie;
        }
        
        $programmesFiltres = [];
        
        if (isset($_GET['categorie_id']) && intval($_GET['categorie_id']) > 0) {
            $categorie_id = intval($_GET['categorie_id']);
            $sql = "SELECT p.*, c.nom AS categorie_nom 
                    FROM programme p 
                    INNER JOIN categorie c ON p.categorie_id = c.id_categorie 
                    WHERE p.categorie_id = ?
                    ORDER BY p.date_creation DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$categorie_id]);
            $programmesFiltres = $stmt->fetchAll();
        }
        
        require __DIR__ . "/../views/front/categories/filter.php";
    }

    // VALIDATION PHP
    private function valider($data) {
        $errors = [];
        if (empty(trim($data['nom'] ?? ''))) {
            $errors['nom'] = "Le nom est obligatoire.";
        } elseif (strlen(trim($data['nom'])) < 2) {
            $errors['nom'] = "Le nom doit avoir au moins 2 caracteres.";
        }
        return $errors;
    }
}
?>