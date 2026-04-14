<?php
class ProduitController {
    private Produit $model;
    private FrigoUtilisateur $frigoModel;

    public function __construct() {
        $this->model      = new Produit();
        $this->frigoModel = new FrigoUtilisateur();
    }

    public function frigo(): void {
        $produits   = $this->model->getAll();
        $frigoItems = $this->frigoModel->getAll();
        require 'app/view/produit/frigo_view.php';
    }

    public function ajouterFrigo(): void {
        $id  = (int)($_POST['id'] ?? 0);
        $qte = (int)($_POST['quantite'] ?? 1);
        $produit = $this->model->getById($id);
        if ($produit && $qte >= 1) {
            $this->frigoModel->ajouter([
                ':produit_id'      => $id,
                ':nom_custom'      => null,
                ':quantite'        => $qte,
                ':date_expiration' => $produit['date_expiration'] ?? null,
                ':seuil_alerte'    => 2,
            ]);
            $_SESSION['success'] = "{$produit['nom']} ajouté au frigo !";
        }
        header('Location: /frigo/index.php?controller=produit&action=frigo');
        exit;
    }

    public function ajouterManuel(): void {
        $errors = [];
        $nom  = trim($_POST['nom_custom'] ?? '');
        $qte  = (int)($_POST['quantite'] ?? 0);
        $date = trim($_POST['date_expiration'] ?? '');

        if (strlen($nom) < 2) $errors[] = "Le nom doit contenir au moins 2 caractères.";
        if ($qte < 1)         $errors[] = "La quantité doit être au moins 1.";
        if (!empty($date)) {
            $d = DateTime::createFromFormat('Y-m-d', $date);
            if (!$d) $errors[] = "Format de date invalide (YYYY-MM-DD).";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
        } else {
            $this->frigoModel->ajouter([
                ':produit_id'      => null,
                ':nom_custom'      => htmlspecialchars($nom),
                ':quantite'        => $qte,
                ':date_expiration' => $date ?: null,
                ':seuil_alerte'    => 2,
            ]);
            $_SESSION['success'] = "Produit ajouté au frigo !";
        }
        header('Location: /frigo/index.php?controller=produit&action=frigo');
        exit;
    }

    public function modifierQuantiteFrigo(): void {
        $id  = (int)($_POST['frigo_id'] ?? 0);
        $qte = (int)($_POST['quantite'] ?? 1);

        if ($qte < 0) {
            $_SESSION['errors'] = ["La quantité ne peut pas être négative."];
            header('Location: /frigo/index.php?controller=produit&action=frigo');
            exit;
        }

        $this->frigoModel->modifierQuantite($id, $qte);

        $item = $this->frigoModel->verifierSeuil($id);
        if ($item) {
            $pid = $item['produit_id'] ?? 'custom_' . $id;
            if (!isset($_SESSION['panier'][$pid])) {
                $_SESSION['panier'][$pid] = [
                    'nom'      => $item['nom'],
                    'prix'     => $item['prix'] ?? 0,
                    'quantite' => 1
                ];
                $_SESSION['success'] =
                    "Stock faible pour {$item['nom']} — ajouté au panier !";
            }
        } else {
            $_SESSION['success'] = "Quantité mise à jour.";
        }

        header('Location: /frigo/index.php?controller=produit&action=frigo');
        exit;
    }

    public function supprimerDuFrigo(): void {
        $id = (int)($_GET['id'] ?? 0);
        $this->frigoModel->supprimerDuFrigo($id);
        header('Location: /frigo/index.php?controller=produit&action=frigo');
        exit;
    }

    public function envoyerAuPanier(): void {
        $id   = (int)($_GET['id'] ?? 0);
        $item = $this->frigoModel->ajouterAuPanier($id);
        if ($item) {
            $pid = $item['produit_id'] ?? 'custom_' . $id;
            $_SESSION['panier'][$pid] = [
                'nom'      => $item['nom'],
                'prix'     => $item['prix'] ?? 0,
                'quantite' => $item['quantite']
            ];
            $_SESSION['success'] = "Produit ajouté au panier !";
        }
        header('Location: /frigo/index.php?controller=commande&action=panier');
        exit;
    }

    public function index(): void {
        $produits = $this->model->getAll();
        require 'app/view/produit/index.php';
    }

    public function create(): void {
        require 'app/view/produit/create.php';
    }

    public function store(): void {
        $errors = $this->validate($_POST);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: /frigo/index.php?controller=produit&action=create');
            exit;
        }
        $data = [
            ':nom'             => htmlspecialchars(trim($_POST['nom'])),
            ':description'     => htmlspecialchars(trim($_POST['description'] ?? '')),
            ':prix'            => (float)$_POST['prix'],
            ':quantite'        => (int)$_POST['quantite'],
            ':date_expiration' => $_POST['date_expiration'],
            ':categorie_id'    => (int)$_POST['categorie_id'],
            ':image'           => $_POST['image'] ?? null,
        ];
        $this->model->create($data);
        $_SESSION['success'] = "Produit ajouté avec succès.";
        header('Location: /frigo/index.php?controller=produit&action=index');
        exit;
    }

    public function edit(): void {
        $id = (int)($_GET['id'] ?? 0);
        $produit = $this->model->getById($id);
        if (!$produit) die("Produit introuvable.");
        require 'app/view/produit/edit.php';
    }

    public function update(): void {
        $id = (int)($_POST['id'] ?? 0);
        $errors = $this->validate($_POST);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: /frigo/index.php?controller=produit&action=edit&id=$id");
            exit;
        }
        $data = [
            ':nom'             => htmlspecialchars(trim($_POST['nom'])),
            ':description'     => htmlspecialchars(trim($_POST['description'] ?? '')),
            ':prix'            => (float)$_POST['prix'],
            ':quantite'        => (int)$_POST['quantite'],
            ':date_expiration' => $_POST['date_expiration'],
            ':categorie_id'    => (int)$_POST['categorie_id'],
            ':image'           => $_POST['image'] ?? null,
        ];
        $this->model->update($id, $data);
        $_SESSION['success'] = "Produit modifié.";
        header('Location: /frigo/index.php?controller=produit&action=index');
        exit;
    }

    public function delete(): void {
        $id = (int)($_GET['id'] ?? 0);
        $this->model->delete($id);
        header('Location: /frigo/index.php?controller=produit&action=index');
        exit;
    }

    private function validate(array $data): array {
        $errors = [];
        if (empty(trim($data['nom'] ?? ''))) {
            $errors[] = "Le nom est obligatoire.";
        } elseif (strlen(trim($data['nom'])) < 2) {
            $errors[] = "Le nom doit contenir au moins 2 caractères.";
        }
        if (!isset($data['prix']) || !is_numeric($data['prix']) || $data['prix'] < 0) {
            $errors[] = "Le prix doit être un nombre positif.";
        }
        if (!isset($data['quantite']) || !ctype_digit((string)$data['quantite'])) {
            $errors[] = "La quantité doit être un entier positif.";
        }
        if (!empty($data['date_expiration'])) {
            $d = DateTime::createFromFormat('Y-m-d', $data['date_expiration']);
            if (!$d) $errors[] = "La date d'expiration est invalide.";
        }
        return $errors;
    }
}