<?php
class ProduitController {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    // ========== METHODES PRIVEES SQL ==========

    private function getProduitById(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM frigo_produit WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    private function ajouterFrigoDb(array $data): bool {
        $stmt = $this->pdo->prepare("
            INSERT INTO frigo_utilisateur
            (produit_id, nom_custom, quantite, date_expiration, seuil_alerte, image)
            VALUES (:produit_id, :nom_custom, :quantite, :date_expiration, :seuil_alerte, :image)
        ");
        return $stmt->execute($data);
    }

    // ========== ACTIONS FRIGO ==========

    public function frigo(): void {
        $stmt = $this->pdo->query("
            SELECT p.*, c.nom AS categorie_nom
            FROM frigo_produit p
            LEFT JOIN frigo_categorie c ON p.categorie_id = c.id
            ORDER BY p.nom ASC
        ");
        $produits = $stmt->fetchAll();

        $stmt = $this->pdo->query("
            SELECT 
                f.id, f.quantite, f.date_expiration,
                f.seuil_alerte, f.produit_id, f.image,
                COALESCE(p.nom, f.nom_custom) AS nom,
                p.prix, c.nom AS categorie_nom, c.id AS categorie_id,
                CASE
                    WHEN f.date_expiration < CURDATE() THEN 'perime'
                    WHEN f.date_expiration <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)
                         THEN 'bientot_perime'
                    ELSE 'frais'
                END AS etat
            FROM frigo_utilisateur f
            LEFT JOIN frigo_produit p ON f.produit_id = p.id
            LEFT JOIN frigo_categorie c ON p.categorie_id = c.id
            ORDER BY f.date_expiration ASC
        ");
        $frigoItems = $stmt->fetchAll();

        $stmt = $this->pdo->query("SELECT * FROM frigo_categorie ORDER BY nom ASC");
        $categories = $stmt->fetchAll();

        $categorieActive = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categorie_id'])) {
            $categorieActive = (int)$_POST['categorie_id'];
            $stmt = $this->pdo->prepare("
                SELECT 
                    f.id, f.quantite, f.date_expiration,
                    f.image,
                    COALESCE(p.nom, f.nom_custom) AS nom,
                    p.prix, c.nom AS categorie_nom,
                    CASE
                        WHEN f.date_expiration < CURDATE() THEN 'perime'
                        WHEN f.date_expiration <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)
                             THEN 'bientot_perime'
                        ELSE 'frais'
                    END AS etat
                FROM frigo_utilisateur f
                LEFT JOIN frigo_produit p ON f.produit_id = p.id
                LEFT JOIN frigo_categorie c ON p.categorie_id = c.id
                WHERE c.id = :categorie_id
                ORDER BY f.date_expiration ASC
            ");
            $stmt->execute([':categorie_id' => $categorieActive]);
            $frigoItems = $stmt->fetchAll();
        }

        require 'app/view/produit/frigo_view.php';
    }

    public function ajouterFrigo(): void {
        $id      = (int)($_POST['id'] ?? 0);
        $qte     = (int)($_POST['quantite'] ?? 1);
        $produit = $this->getProduitById($id);

        if ($produit && $qte >= 1) {
            $this->ajouterFrigoDb([
                ':produit_id'      => $id,
                ':nom_custom'      => null,
                ':quantite'        => $qte,
                ':date_expiration' => $produit['date_expiration'] ?? null,
                ':seuil_alerte'    => 2,
                ':image'           => null,
            ]);
            $_SESSION['success'] = "{$produit['nom']} ajouté au frigo !";
        }
        header('Location: ' . FRIGO_INDEX . '?controller=produit&action=frigo');
        exit;
    }

    public function ajouterManuel(): void {
        $errors = [];
        $nom    = trim($_POST['nom_custom'] ?? '');
        $qte    = (int)($_POST['quantite'] ?? 0);
        $date   = trim($_POST['date_expiration'] ?? '');
        $image  = null;

        if (strlen($nom) < 2) $errors[] = "Le nom doit contenir au moins 2 caractères.";
        if ($qte < 1)         $errors[] = "La quantité doit être au moins 1.";
        if (!empty($date)) {
            $d = DateTime::createFromFormat('Y-m-d', $date);
            if (!$d) $errors[] = "Format de date invalide (YYYY-MM-DD).";
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($ext, $allowed)) {
                $errors[] = "Format image invalide (jpg, png, gif, webp).";
            } else {
                $nomFichier  = uniqid('aliment_') . '.' . $ext;
                $destination = 'public/images/uploads/' . $nomFichier;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    $image = $nomFichier;
                }
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
        } else {
            $this->ajouterFrigoDb([
                ':produit_id'      => null,
                ':nom_custom'      => htmlspecialchars($nom),
                ':quantite'        => $qte,
                ':date_expiration' => $date ?: null,
                ':seuil_alerte'    => 2,
                ':image'           => $image,
            ]);
            $_SESSION['success'] = "Produit ajouté au frigo !";
        }
        header('Location: ' . FRIGO_INDEX . '?controller=produit&action=frigo');
        exit;
    }

    public function modifierQuantiteFrigo(): void {
        $id  = (int)($_POST['frigo_id'] ?? 0);
        $qte = (int)($_POST['quantite'] ?? 1);

        if ($qte < 0) {
            $_SESSION['errors'] = ["La quantité ne peut pas être négative."];
            header('Location: ' . FRIGO_INDEX . '?controller=produit&action=frigo');
            exit;
        }

        $stmt = $this->pdo->prepare(
            "UPDATE frigo_utilisateur SET quantite = :qte WHERE id = :id"
        );
        $stmt->execute([':qte' => $qte, ':id' => $id]);

        $stmt = $this->pdo->prepare("
            SELECT f.*, COALESCE(p.nom, f.nom_custom) AS nom, p.prix
            FROM frigo_utilisateur f
            LEFT JOIN frigo_produit p ON f.produit_id = p.id
            WHERE f.id = :id AND f.quantite <= f.seuil_alerte
        ");
        $stmt->execute([':id' => $id]);
        $item = $stmt->fetch();

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

        header('Location: ' . FRIGO_INDEX . '?controller=produit&action=frigo');
        exit;
    }

    public function supprimerDuFrigo(): void {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->pdo->prepare("DELETE FROM frigo_utilisateur WHERE id = :id");
        $stmt->execute([':id' => $id]);
        header('Location: ' . FRIGO_INDEX . '?controller=produit&action=frigo');
        exit;
    }

    public function envoyerAuPanier(): void {
        $id   = (int)($_GET['id'] ?? 0);
        $stmt = $this->pdo->prepare("
            SELECT f.*, COALESCE(p.nom, f.nom_custom) AS nom, p.prix
            FROM frigo_utilisateur f
            LEFT JOIN frigo_produit p ON f.produit_id = p.id
            WHERE f.id = :id
        ");
        $stmt->execute([':id' => $id]);
        $item = $stmt->fetch();

        if ($item) {
            $pid = $item['produit_id'] ?? 'custom_' . $id;
            $_SESSION['panier'][$pid] = [
                'nom'      => $item['nom'],
                'prix'     => $item['prix'] ?? 0,
                'quantite' => $item['quantite']
            ];
            $_SESSION['success'] = "Produit ajouté au panier !";
        }
        header('Location: ' . FRIGO_INDEX . '?controller=commande&action=panier');
        exit;
    }

    public function ajouterFrigoQR(): void {
        $id      = (int)($_GET['id'] ?? 0);
        $produit = $this->getProduitById($id);

        if ($produit) {
            $this->ajouterFrigoDb([
                ':produit_id'      => $id,
                ':nom_custom'      => null,
                ':quantite'        => 1,
                ':date_expiration' => $produit['date_expiration'] ?? null,
                ':seuil_alerte'    => 2,
                ':image'           => null,
            ]);
            $_SESSION['success'] = "{$produit['nom']} ajouté via QR code !";
        } else {
            $_SESSION['errors'] = ["Produit non reconnu."];
        }
        header('Location: ' . FRIGO_INDEX . '?controller=produit&action=frigo');
        exit;
    }

    // ========== BACKOFFICE PRODUIT ==========

    public function index(): void {
        $stmt = $this->pdo->query("
            SELECT p.*, c.nom AS categorie_nom,
            CASE
                WHEN p.date_expiration < CURDATE() THEN 'perime'
                WHEN p.date_expiration <= DATE_ADD(CURDATE(), INTERVAL 3 DAY) THEN 'bientot_perime'
                ELSE 'frais'
            END AS etat
            FROM frigo_produit p
            LEFT JOIN frigo_categorie c ON p.categorie_id = c.id
            ORDER BY p.date_expiration ASC
        ");
        $produits = $stmt->fetchAll();
        require 'app/view/produit/index.php';
    }

    public function create(): void {
        $stmt = $this->pdo->query("SELECT * FROM frigo_categorie ORDER BY nom ASC");
        $categories = $stmt->fetchAll();
        require 'app/view/produit/create.php';
    }

    public function store(): void {
        $errors = $this->validate($_POST);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: ' . FRIGO_INDEX . '?controller=produit&action=create');
            exit;
        }
        $stmt = $this->pdo->prepare("
            INSERT INTO frigo_produit (nom, description, prix, quantite, date_expiration, categorie_id, image)
            VALUES (:nom, :description, :prix, :quantite, :date_expiration, :categorie_id, :image)
        ");
        $stmt->execute([
            ':nom'             => htmlspecialchars(trim($_POST['nom'])),
            ':description'     => htmlspecialchars(trim($_POST['description'] ?? '')),
            ':prix'            => (float)$_POST['prix'],
            ':quantite'        => (int)$_POST['quantite'],
            ':date_expiration' => $_POST['date_expiration'],
            ':categorie_id'    => (int)$_POST['categorie_id'],
            ':image'           => $_POST['image'] ?? null
        ]);
        $_SESSION['success'] = "Produit ajouté avec succès.";
        header('Location: ' . FRIGO_INDEX . '?controller=produit&action=index');
        exit;
    }

    public function edit(): void {
        $id      = (int)($_GET['id'] ?? 0);
        $produit = $this->getProduitById($id);
        if (!$produit) die("Produit introuvable.");
        $stmt = $this->pdo->query("SELECT * FROM frigo_categorie ORDER BY nom ASC");
        $categories = $stmt->fetchAll();
        require 'app/view/produit/edit.php';
    }

    public function update(): void {
        $id     = (int)($_POST['id'] ?? 0);
        $errors = $this->validate($_POST);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: " . FRIGO_INDEX . "?controller=produit&action=edit&id=$id");
            exit;
        }
        $stmt = $this->pdo->prepare("
            UPDATE produit SET nom=:nom, description=:description, prix=:prix,
            quantite=:quantite, date_expiration=:date_expiration,
            categorie_id=:categorie_id, image=:image WHERE id=:id
        ");
        $stmt->execute([
            ':id'              => $id,
            ':nom'             => htmlspecialchars(trim($_POST['nom'])),
            ':description'     => htmlspecialchars(trim($_POST['description'] ?? '')),
            ':prix'            => (float)$_POST['prix'],
            ':quantite'        => (int)$_POST['quantite'],
            ':date_expiration' => $_POST['date_expiration'],
            ':categorie_id'    => (int)$_POST['categorie_id'],
            ':image'           => $_POST['image'] ?? null
        ]);
        $_SESSION['success'] = "Produit modifié.";
        header('Location: ' . FRIGO_INDEX . '?controller=produit&action=index');
        exit;
    }

    public function delete(): void {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->pdo->prepare("DELETE FROM frigo_produit WHERE id = :id");
        $stmt->execute([':id' => $id]);
        header('Location: ' . FRIGO_INDEX . '?controller=produit&action=index');
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