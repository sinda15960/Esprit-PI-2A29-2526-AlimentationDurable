<?php
class ProduitController {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    // ========== METHODES PRIVEES SQL ==========

    private function getProduitById(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM produit WHERE id = :id");
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

    // ========== IA - SUGGESTIONS INTELLIGENTES (IDÉE 3a) ==========

    /**
     * Génère des suggestions basées sur le contenu du frigo d'un utilisateur
     * Identifie le client par son numéro de téléphone
     */
    private function genererSuggestionsFrigo(string $telephone): array {
        $suggestions = [];
        
        // 1. Détection des produits à stock faible
        $stmt = $this->pdo->prepare("
            SELECT f.*, COALESCE(p.nom, f.nom_custom) as nom, p.id as produit_id_ref
            FROM frigo_utilisateur f
            LEFT JOIN produit p ON f.produit_id = p.id
            WHERE f.quantite <= f.seuil_alerte
            AND f.quantite > 0
            AND (f.produit_id IS NOT NULL)
            ORDER BY f.quantite ASC
            LIMIT 3
        ");
        $stmt->execute();
        $stockFaible = $stmt->fetchAll();
        
        foreach ($stockFaible as $item) {
            $suggestions[] = [
                'type' => 'stock_faible',
                'produit_id' => $item['produit_id_ref'],
                'message' => "Votre frigo n'a plus que {$item['quantite']}x {$item['nom']}. Voulez-vous en racheter ?"
            ];
        }
        
        // 2. Détection des produits qui expirent bientôt (moins de 3 jours)
        $stmt = $this->pdo->prepare("
            SELECT f.*, COALESCE(p.nom, f.nom_custom) as nom,
                   DATEDIFF(f.date_expiration, CURDATE()) as jours_restants
            FROM frigo_utilisateur f
            LEFT JOIN produit p ON f.produit_id = p.id
            WHERE f.date_expiration IS NOT NULL
            AND f.date_expiration <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)
            AND f.date_expiration >= CURDATE()
            ORDER BY f.date_expiration ASC
            LIMIT 3
        ");
        $stmt->execute();
        $expirationProche = $stmt->fetchAll();
        
        foreach ($expirationProche as $item) {
            $suggestions[] = [
                'type' => 'expiration_proche',
                'produit_id' => null,
                'message' => "⚠️ {$item['nom']} expire dans {$item['jours_restants']} jour(s) ! Consommez-le rapidement."
            ];
        }
        
        // 3. Suggestions de produits complémentaires basées sur l'historique
        if (!empty($telephone)) {
            $stmt = $this->pdo->prepare("
                SELECT p.id, p.nom, COUNT(cp.id) as frequence
                FROM commande c
                JOIN commande_produit cp ON c.id = cp.commande_id
                JOIN produit p ON cp.produit_id = p.id
                WHERE c.telephone = :telephone
                AND c.statut = 'confirmee'
                GROUP BY p.id
                ORDER BY frequence DESC
                LIMIT 3
            ");
            $stmt->execute([':telephone' => $telephone]);
            $historiqueAchats = $stmt->fetchAll();
            
            foreach ($historiqueAchats as $item) {
                $check = $this->pdo->prepare("
                    SELECT COUNT(*) FROM frigo_utilisateur 
                    WHERE produit_id = :id AND quantite > 0
                ");
                $check->execute([':id' => $item['id']]);
                $dejaPresent = $check->fetchColumn() > 0;
                
                if (!$dejaPresent) {
                    $suggestions[] = [
                        'type' => 'recommandation',
                        'produit_id' => $item['id'],
                        'message' => "Vous achetez souvent {$item['nom']}. Voulez-vous le commander ?"
                    ];
                }
            }
        }
        
        return $suggestions;
    }

    // ========== ACTIONS FRIGO ==========

    public function frigo(): void {
        $stmt = $this->pdo->query("
            SELECT p.*, c.nom AS categorie_nom
            FROM produit p
            LEFT JOIN categorie c ON p.categorie_id = c.id
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
            LEFT JOIN produit p ON f.produit_id = p.id
            LEFT JOIN categorie c ON p.categorie_id = c.id
            ORDER BY f.date_expiration ASC
        ");
        $frigoItems = $stmt->fetchAll();

        $stmt = $this->pdo->query("SELECT * FROM categorie ORDER BY nom ASC");
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
                LEFT JOIN produit p ON f.produit_id = p.id
                LEFT JOIN categorie c ON p.categorie_id = c.id
                WHERE c.id = :categorie_id
                ORDER BY f.date_expiration ASC
            ");
            $stmt->execute([':categorie_id' => $categorieActive]);
            $frigoItems = $stmt->fetchAll();
        }

        // Récupérer le téléphone depuis la session ou les commandes récentes
        $telephone = $_SESSION['telephone_client'] ?? '';
        if (empty($telephone)) {
            $stmt = $this->pdo->query("SELECT telephone FROM commande ORDER BY id DESC LIMIT 1");
            $last = $stmt->fetch();
            if ($last) $telephone = $last['telephone'];
        }
        
        $suggestions = $this->genererSuggestionsFrigo($telephone);

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
        header('Location: /frigo/index.php?controller=produit&action=frigo');
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

        $stmt = $this->pdo->prepare(
            "UPDATE frigo_utilisateur SET quantite = :qte WHERE id = :id"
        );
        $stmt->execute([':qte' => $qte, ':id' => $id]);

        $stmt = $this->pdo->prepare("
            SELECT f.*, COALESCE(p.nom, f.nom_custom) AS nom, p.prix
            FROM frigo_utilisateur f
            LEFT JOIN produit p ON f.produit_id = p.id
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

        header('Location: /frigo/index.php?controller=produit&action=frigo');
        exit;
    }

    public function supprimerDuFrigo(): void {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->pdo->prepare("DELETE FROM frigo_utilisateur WHERE id = :id");
        $stmt->execute([':id' => $id]);
        header('Location: /frigo/index.php?controller=produit&action=frigo');
        exit;
    }

    public function envoyerAuPanier(): void {
        $id   = (int)($_GET['id'] ?? 0);
        $stmt = $this->pdo->prepare("
            SELECT f.*, COALESCE(p.nom, f.nom_custom) AS nom, p.prix
            FROM frigo_utilisateur f
            LEFT JOIN produit p ON f.produit_id = p.id
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
        header('Location: /frigo/index.php?controller=commande&action=panier');
        exit;
    }

    // ========== SCAN CODE-BARRES / QR CODE (IDÉE 8) ==========

    /**
     * Ajoute un produit par scan de code-barres ou QR code
     * Remplace l'ancienne méthode ajouterFrigoQR
     */
    public function ajouterParScan(): void {
        $produitId = (int)($_GET['produit_id'] ?? 0);
        $codeBarres = $_GET['code_barres'] ?? '';
        $redirect = $_GET['redirect'] ?? 'frigo';
        
        if ($produitId > 0) {
            $produit = $this->getProduitById($produitId);
            if ($produit) {
                $this->ajouterFrigoDb([
                    ':produit_id'      => $produitId,
                    ':nom_custom'      => null,
                    ':quantite'        => 1,
                    ':date_expiration' => $produit['date_expiration'] ?? null,
                    ':seuil_alerte'    => 2,
                    ':image'           => null,
                ]);
                $_SESSION['success'] = "{$produit['nom']} ajouté via scan !";
            }
        } 
        elseif (!empty($codeBarres)) {
            // Recherche du produit par code-barres dans la base
            $stmt = $this->pdo->prepare("SELECT * FROM produit WHERE code_barres = :code");
            $stmt->execute([':code' => $codeBarres]);
            $produit = $stmt->fetch();
            
            if ($produit) {
                $this->ajouterFrigoDb([
                    ':produit_id'      => $produit['id'],
                    ':nom_custom'      => null,
                    ':quantite'        => 1,
                    ':date_expiration' => $produit['date_expiration'] ?? null,
                    ':seuil_alerte'    => 2,
                    ':image'           => null,
                ]);
                $_SESSION['success'] = "{$produit['nom']} ajouté via scan code-barres !";
            } else {
                // Produit non trouvé : proposition d'ajout manuel
                $_SESSION['pending_scan'] = $codeBarres;
                $_SESSION['errors'] = ["Produit non trouvé. Ajoutez-le manuellement."];
                header('Location: /frigo/index.php?mode=front&controller=produit&action=ajouterManuel');
                exit;
            }
        }
        
        if ($redirect === 'panier') {
            header('Location: /frigo/index.php?mode=front&controller=commande&action=panier');
        } else {
            header('Location: /frigo/index.php?mode=front&controller=produit&action=frigo');
        }
        exit;
    }

    /**
     * Ancienne méthode ajouterFrigoQR conservée pour compatibilité
     */
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
        header('Location: /frigo/index.php?controller=produit&action=frigo');
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
            FROM produit p
            LEFT JOIN categorie c ON p.categorie_id = c.id
            ORDER BY p.date_expiration ASC
        ");
        $produits = $stmt->fetchAll();
        require 'app/view/produit/index.php';
    }

    public function create(): void {
        $stmt = $this->pdo->query("SELECT * FROM categorie ORDER BY nom ASC");
        $categories = $stmt->fetchAll();
        require 'app/view/produit/create.php';
    }

    public function store(): void {
        $errors = $this->validate($_POST);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: /frigo/index.php?controller=produit&action=create');
            exit;
        }
        $stmt = $this->pdo->prepare("
            INSERT INTO produit (nom, description, prix, quantite, date_expiration, categorie_id, image, code_barres)
            VALUES (:nom, :description, :prix, :quantite, :date_expiration, :categorie_id, :image, :code_barres)
        ");
        $stmt->execute([
            ':nom'             => htmlspecialchars(trim($_POST['nom'])),
            ':description'     => htmlspecialchars(trim($_POST['description'] ?? '')),
            ':prix'            => (float)$_POST['prix'],
            ':quantite'        => (int)$_POST['quantite'],
            ':date_expiration' => $_POST['date_expiration'] ?: null,
            ':categorie_id'    => (int)$_POST['categorie_id'],
            ':image'           => $_POST['image'] ?? null,
            ':code_barres'     => $_POST['code_barres'] ?? null
        ]);
        $_SESSION['success'] = "Produit ajouté avec succès.";
        header('Location: /frigo/index.php?controller=produit&action=index');
        exit;
    }

    public function edit(): void {
        $id      = (int)($_GET['id'] ?? 0);
        $produit = $this->getProduitById($id);
        if (!$produit) die("Produit introuvable.");
        $stmt = $this->pdo->query("SELECT * FROM categorie ORDER BY nom ASC");
        $categories = $stmt->fetchAll();
        require 'app/view/produit/edit.php';
    }

    public function update(): void {
        $id     = (int)($_POST['id'] ?? 0);
        $errors = $this->validate($_POST);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: /frigo/index.php?controller=produit&action=edit&id=$id");
            exit;
        }
        $stmt = $this->pdo->prepare("
            UPDATE produit SET nom=:nom, description=:description, prix=:prix,
            quantite=:quantite, date_expiration=:date_expiration,
            categorie_id=:categorie_id, image=:image, code_barres=:code_barres
            WHERE id=:id
        ");
        $stmt->execute([
            ':id'              => $id,
            ':nom'             => htmlspecialchars(trim($_POST['nom'])),
            ':description'     => htmlspecialchars(trim($_POST['description'] ?? '')),
            ':prix'            => (float)$_POST['prix'],
            ':quantite'        => (int)$_POST['quantite'],
            ':date_expiration' => $_POST['date_expiration'] ?: null,
            ':categorie_id'    => (int)$_POST['categorie_id'],
            ':image'           => $_POST['image'] ?? null,
            ':code_barres'     => $_POST['code_barres'] ?? null
        ]);
        $_SESSION['success'] = "Produit modifié.";
        header('Location: /frigo/index.php?controller=produit&action=index');
        exit;
    }

    public function delete(): void {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->pdo->prepare("DELETE FROM produit WHERE id = :id");
        $stmt->execute([':id' => $id]);
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