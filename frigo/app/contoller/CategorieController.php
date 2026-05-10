<?php
class CategorieController {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    public function index(): void {
        $stmt = $this->pdo->query("SELECT * FROM frigo_categorie ORDER BY nom ASC");
        $categories = $stmt->fetchAll();

        $produits        = [];
        $categorieActive = null;
        $favorisIds      = [];
        $favorisListe    = []; // Liste des favoris à afficher

        // Récupérer les favoris existants (IDs)
        $stmt = $this->pdo->query("SELECT produit_id FROM frigo_favori");
        $favorisTemp = $stmt->fetchAll();
        $favorisIds = array_column($favorisTemp, 'produit_id');

        // Récupérer la liste complète des favoris avec détails
        if (!empty($favorisIds)) {
            $placeholders = implode(',', array_fill(0, count($favorisIds), '?'));
            $stmt = $this->pdo->prepare("
                SELECT p.*, c.nom AS categorie_nom
                FROM frigo_produit p
                LEFT JOIN frigo_categorie c ON p.categorie_id = c.id
                WHERE p.id IN ($placeholders)
                ORDER BY p.nom ASC
            ");
            $stmt->execute($favorisIds);
            $favorisListe = $stmt->fetchAll();
        }

        if (isset($_GET['cat_id'])) {
            $categorieActive = (int)$_GET['cat_id'];
            $stmt = $this->pdo->prepare("
                SELECT * FROM frigo_produit
                WHERE categorie_id = :id ORDER BY nom ASC
            ");
            $stmt->execute([':id' => $categorieActive]);
            $produits = $stmt->fetchAll();
        }

        require 'app/view/categories/index.php';
    }

    public function admin(): void {
        $stmt = $this->pdo->query("SELECT * FROM frigo_categorie ORDER BY nom ASC");
        $categories = $stmt->fetchAll();
        require 'app/view/categories/admin.php';
    }

    public function store(): void {
        $errors = [];
        $nom = trim($_POST['nom'] ?? '');
        if (strlen($nom) < 2) {
            $errors[] = "Le nom doit contenir au moins 2 caractères.";
        }
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: ' . FRIGO_INDEX . '?mode=back&controller=categorie&action=admin');
            exit;
        }
        $stmt = $this->pdo->prepare("
            INSERT INTO frigo_categorie (nom, description, image)
            VALUES (:nom, :description, :image)
        ");
        $stmt->execute([
            ':nom'         => htmlspecialchars($nom),
            ':description' => htmlspecialchars(trim($_POST['description'] ?? '')),
            ':image'       => $_POST['image'] ?? null,
        ]);
        $_SESSION['success'] = "Catégorie ajoutée.";
        header('Location: ' . FRIGO_INDEX . '?mode=back&controller=categorie&action=admin');
        exit;
    }

    public function edit(): void {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->pdo->prepare("SELECT * FROM frigo_categorie WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $categorie = $stmt->fetch();
        if (!$categorie) die("Catégorie introuvable.");
        require 'app/view/categories/edit.php';
    }

    public function update(): void {
        $id  = (int)($_POST['id'] ?? 0);
        $nom = trim($_POST['nom'] ?? '');
        if (strlen($nom) < 2) {
            $_SESSION['errors'] = ["Le nom doit contenir au moins 2 caractères."];
            header("Location: " . FRIGO_INDEX . "?mode=back&controller=categorie&action=edit&id=$id");
            exit;
        }
        $stmt = $this->pdo->prepare("
            UPDATE frigo_categorie SET nom=:nom, description=:description WHERE id=:id
        ");
        $stmt->execute([
            ':nom'         => htmlspecialchars($nom),
            ':description' => htmlspecialchars(trim($_POST['description'] ?? '')),
            ':id'          => $id,
        ]);
        $_SESSION['success'] = "Catégorie modifiée.";
        header('Location: ' . FRIGO_INDEX . '?mode=back&controller=categorie&action=admin');
        exit;
    }

    public function delete(): void {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->pdo->prepare("DELETE FROM frigo_categorie WHERE id = :id");
        $stmt->execute([':id' => $id]);
        header('Location: ' . FRIGO_INDEX . '?mode=back&controller=categorie&action=admin');
        exit;
    }
}