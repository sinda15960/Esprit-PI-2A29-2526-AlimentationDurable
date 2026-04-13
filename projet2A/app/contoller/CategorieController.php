<?php
class CategorieController {
    private Categorie $model;

    public function __construct() {
        $this->model = new Categorie();
    }

    // FrontOffice : supermarché virtuel
    public function index(): void {
        $categories = $this->model->getAll();
        $produits = [];
        $categorieActive = null;

        if (isset($_GET['cat_id'])) {
            $categorieActive = (int)$_GET['cat_id'];
            $produits = $this->model->getProduitsParCategorie($categorieActive);
        }

        require 'app/view/categories/index.php';
    }

    // BackOffice : liste admin
    public function admin(): void {
        $categories = $this->model->getAll();
        require 'app/view/categories/admin.php';
    }

    public function create(): void {
        require 'app/view/categories/create.php';
    }

    public function store(): void {
        $errors = $this->validate($_POST);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: index.php?controller=categorie&action=admin');
            exit;
        }
        $data = [
            ':nom'         => htmlspecialchars(trim($_POST['nom'])),
            ':description' => htmlspecialchars(trim($_POST['description'] ?? '')),
            ':image'       => $_POST['image'] ?? null,
        ];
        $this->model->create($data);
        $_SESSION['success'] = "Catégorie ajoutée avec succès.";
        header('Location: index.php?controller=categorie&action=admin');
        exit;
    }

    public function edit(): void {
        $id = (int)($_GET['id'] ?? 0);
        $categorie = $this->model->getById($id);
        if (!$categorie) die("Catégorie introuvable.");
        require 'app/view/categories/edit.php';
    }

    public function update(): void {
        $id = (int)($_POST['id'] ?? 0);
        $errors = $this->validate($_POST);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: index.php?controller=categorie&action=edit&id=$id");
            exit;
        }
        $data = [
            ':nom'         => htmlspecialchars(trim($_POST['nom'])),
            ':description' => htmlspecialchars(trim($_POST['description'] ?? '')),
            ':image'       => $_POST['image'] ?? null,
        ];
        $this->model->update($id, $data);
        $_SESSION['success'] = "Catégorie modifiée.";
        header('Location: index.php?controller=categorie&action=admin');
        exit;
    }

    public function delete(): void {
        $id = (int)($_GET['id'] ?? 0);
        $this->model->delete($id);
        header('Location: index.php?controller=categorie&action=admin');
        exit;
    }

    private function validate(array $data): array {
        $errors = [];
        if (empty(trim($data['nom'] ?? ''))) {
            $errors[] = "Le nom de la catégorie est obligatoire.";
        } elseif (strlen(trim($data['nom'])) < 2) {
            $errors[] = "Le nom doit contenir au moins 2 caractères.";
        }
        return $errors;
    }
}