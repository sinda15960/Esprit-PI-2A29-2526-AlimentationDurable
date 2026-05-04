<?php
class ProduitController {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    // ===== REQUETES PRIVEES =====

    private function getProduitById(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM produit WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    private function getAllCategories(): array {
        return $this->pdo->query("SELECT * FROM categorie ORDER BY nom ASC")->fetchAll();
    }

    private function getEmojiByNom(string $nom): string {
        $nom = strtolower(trim($nom));
        $emojis = [
            'pomme' => '🍎', 'banane' => '🍌', 'orange' => '🍊', 'fraise' => '🍓',
            'raisin' => '🍇', 'mangue' => '🥭', 'tomate' => '🍅', 'carotte' => '🥕',
            'courgette' => '🥒', 'salade' => '🥬', 'poivron' => '🫑', 'oignon' => '🧅',
            'lait' => '🥛', 'yaourt' => '🍶', 'fromage' => '🧀', 'beurre' => '🧈',
            'creme' => '🍦', 'crème' => '🍦', 'poulet' => '🍗', 'boeuf' => '🥩',
            'merguez' => '🌭', 'thon' => '🐟', 'eau' => '💧', 'jus' => '🧃',
            'coca' => '🥤', 'cafe' => '☕', 'café' => '☕', 'the' => '🍵',
            'thé' => '🍵', 'limonade' => '🍋', 'pates' => '🍝', 'pâtes' => '🍝',
            'riz' => '🍚', 'huile' => '🫙', 'sucre' => '🍬', 'pain' => '🍞',
            'croissant' => '🥐', 'biscuit' => '🍪', 'oeuf' => '🥚', 'ail' => '🧄',
            'pizza' => '🍕', 'kiwi' => '🥝', 'ananas' => '🍍', 'cerise' => '🍒',
            'myrtille' => '🫐', 'noix' => '🥜', 'chocolat' => '🍫'
        ];
        
        foreach ($emojis as $mot => $emoji) {
            if (str_contains($nom, $mot)) return $emoji;
        }
        return '🥗';
    }

    private function getSuggestions(): array {
        $suggestions = [];

        $stmt = $this->pdo->query("
            SELECT f.id, f.quantite, f.seuil_alerte,
                   COALESCE(p.nom, f.nom_custom) AS nom,
                   p.id AS produit_id
            FROM frigo_utilisateur f
            LEFT JOIN produit p ON f.produit_id = p.id
            WHERE f.quantite <= f.seuil_alerte
            LIMIT 3
        ");
        foreach ($stmt->fetchAll() as $item) {
            $suggestions[] = [
                'type'       => 'stock_faible',
                'message'    => "Votre frigo manque de {$item['nom']} (quantité : {$item['quantite']}). Ajouter au panier ?",
                'produit_id' => $item['produit_id'],
            ];
        }

        $stmt = $this->pdo->query("
            SELECT COALESCE(p.nom, f.nom_custom) AS nom, f.date_expiration
            FROM frigo_utilisateur f
            LEFT JOIN produit p ON f.produit_id = p.id
            WHERE f.date_expiration IS NOT NULL
            AND f.date_expiration BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY)
            LIMIT 3
        ");
        foreach ($stmt->fetchAll() as $item) {
            $suggestions[] = [
                'type'       => 'expiration_proche',
                'message'    => "{$item['nom']} expire le " . date('d/m/Y', strtotime($item['date_expiration'])) . ". Consommez-le vite !",
                'produit_id' => null,
            ];
        }

        return $suggestions;
    }

    // ===== VOIX OFF =====

    public function ajouterParVoix(): void {
    // Permettre l'accès AJAX
    header('Content-Type: application/json');
    
    $nomAliment = trim($_POST['nom_aliment'] ?? '');
    if (empty($nomAliment)) {
        echo json_encode(['success' => false, 'message' => 'Nom d\'aliment vide']);
        return;
    }
    
    // Recherche dans la base de données (correspondance exacte ou LIKE)
    $stmt = $this->pdo->prepare("SELECT * FROM produit WHERE LOWER(nom) = LOWER(:nom) OR LOWER(nom) LIKE LOWER(:like)");
    $stmt->execute([
        ':nom' => $nomAliment,
        ':like' => '%' . $nomAliment . '%'
    ]);
    $produit = $stmt->fetch();
    
    if ($produit) {
        echo json_encode([
            'success' => true,
            'produit' => [
                'id' => $produit['id'],
                'nom' => $produit['nom'],
                'prix' => $produit['prix'],
                'emoji' => $this->getEmojiByNom($produit['nom'])
            ],
            'message' => "Aliment trouvé : {$produit['nom']}"
        ]);
        } else {
        echo json_encode([
            'success' => false,
            'message' => "L'aliment '$nomAliment' n'existe pas dans notre catalogue.",
            'nom_saisi' => $nomAliment
        ]);
        }
    }
    public function confirmerAjoutVoix(): void {
    $id = (int)($_POST['produit_id'] ?? 0);
    $qte = (int)($_POST['quantite'] ?? 1);
    $produit = $this->getProduitById($id);

    if ($produit && $qte >= 1) {
        $stmt = $this->pdo->prepare("
            INSERT INTO frigo_utilisateur
            (produit_id, nom_custom, quantite, date_expiration, seuil_alerte, emoji)
            VALUES (:produit_id, NULL, :quantite, :date_expiration, 2, :emoji)
        ");
        $stmt->execute([
            ':produit_id'      => $id,
            ':quantite'        => $qte,
            ':date_expiration' => $produit['date_expiration'] ?? null,
            ':emoji'           => $this->getEmojiByNom($produit['nom']),
        ]);
        $_SESSION['success'] = "🎤 {$produit['nom']} ajouté au frigo par voix !";
        }
    header('Location: /frigo/index.php?mode=front&controller=produit&action=frigo');
    exit;
    }
    // ===== FRIGO =====

    public function frigo(): void {
        $stmt = $this->pdo->query("
            SELECT p.*, c.nom AS categorie_nom
            FROM produit p
            LEFT JOIN categorie c ON p.categorie_id = c.id
            ORDER BY p.nom ASC
        ");
        $produits = $stmt->fetchAll();

        $stmt = $this->pdo->query("
            SELECT f.id, f.quantite, f.date_expiration, f.seuil_alerte,
                   f.produit_id, f.emoji,
                   COALESCE(p.nom, f.nom_custom) AS nom,
                   p.prix, c.nom AS categorie_nom, c.id AS categorie_id,
                   CASE
                     WHEN f.date_expiration < CURDATE() THEN 'perime'
                     WHEN f.date_expiration <= DATE_ADD(CURDATE(), INTERVAL 3 DAY) THEN 'bientot_perime'
                     ELSE 'frais'
                   END AS etat
            FROM frigo_utilisateur f
            LEFT JOIN produit p ON f.produit_id = p.id
            LEFT JOIN categorie c ON p.categorie_id = c.id
            ORDER BY f.date_expiration ASC
        ");
        $frigoItems = $stmt->fetchAll();

        // Mettre à jour les emojis des items du frigo
        foreach ($frigoItems as &$item) {
            if (empty($item['emoji']) || $item['emoji'] === 'auto' || $item['emoji'] === '🥗') {
                $item['emoji'] = $this->getEmojiByNom($item['nom']);
            }
        }

        $categories      = $this->getAllCategories();
        $categorieActive = null;
        $suggestions     = $this->getSuggestions();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categorie_id'])) {
            $categorieActive = (int)$_POST['categorie_id'];
            $stmt = $this->pdo->prepare("
                SELECT f.id, f.quantite, f.date_expiration, f.emoji,
                       COALESCE(p.nom, f.nom_custom) AS nom,
                       p.prix, c.nom AS categorie_nom,
                       CASE
                         WHEN f.date_expiration < CURDATE() THEN 'perime'
                         WHEN f.date_expiration <= DATE_ADD(CURDATE(), INTERVAL 3 DAY) THEN 'bientot_perime'
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
            foreach ($frigoItems as &$item) {
                if (empty($item['emoji']) || $item['emoji'] === 'auto') {
                    $item['emoji'] = $this->getEmojiByNom($item['nom']);
                }
            }
        }

        require 'app/view/produit/frigo_view.php';
    }

    public function ajouterFrigo(): void {
        $id      = (int)($_POST['id'] ?? 0);
        $qte     = (int)($_POST['quantite'] ?? 1);
        $produit = $this->getProduitById($id);

        if ($produit && $qte >= 1) {
            $stmt = $this->pdo->prepare("
                INSERT INTO frigo_utilisateur
                (produit_id, nom_custom, quantite, date_expiration, seuil_alerte, emoji)
                VALUES (:produit_id, NULL, :quantite, :date_expiration, 2, :emoji)
            ");
            $stmt->execute([
                ':produit_id'      => $id,
                ':quantite'        => $qte,
                ':date_expiration' => $produit['date_expiration'] ?? null,
                ':emoji'           => $this->getEmojiByNom($produit['nom']),
            ]);
            $_SESSION['success'] = "{$produit['nom']} ajouté au frigo !";
        }
        header('Location: /frigo/index.php?mode=front&controller=produit&action=frigo');
        exit;
    }

    public function ajouterManuel(): void {
        $errors = [];
        $nom    = trim($_POST['nom_custom'] ?? '');
        $qte    = (int)($_POST['quantite'] ?? 0);
        $date   = trim($_POST['date_expiration'] ?? '');
        $emoji  = $_POST['emoji'] ?? $this->getEmojiByNom($nom);

        if (strlen($nom) < 2) $errors[] = "Le nom doit contenir au moins 2 caractères.";
        if ($qte < 1)         $errors[] = "La quantité doit être au moins 1.";
        if (!empty($date)) {
            $d = DateTime::createFromFormat('Y-m-d', $date);
            if (!$d) $errors[] = "Format de date invalide (YYYY-MM-DD).";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
        } else {
            $stmt = $this->pdo->prepare("
                INSERT INTO frigo_utilisateur
                (produit_id, nom_custom, quantite, date_expiration, seuil_alerte, emoji)
                VALUES (NULL, :nom_custom, :quantite, :date_expiration, 2, :emoji)
            ");
            $stmt->execute([
                ':nom_custom'      => htmlspecialchars($nom),
                ':quantite'        => $qte,
                ':date_expiration' => $date ?: null,
                ':emoji'           => $emoji,
            ]);
            $_SESSION['success'] = "$emoji $nom ajouté au frigo !";
        }
        header('Location: /frigo/index.php?mode=front&controller=produit&action=frigo');
        exit;
    }

    public function ajouterParScan(): void {
        $produitId  = (int)($_GET['produit_id'] ?? 0);
        $codeBarres = trim($_GET['code_barres'] ?? '');

        $produit = null;
        if ($produitId > 0) {
            $produit = $this->getProduitById($produitId);
        }

        if ($produit) {
            $stmt = $this->pdo->prepare("
                INSERT INTO frigo_utilisateur
                (produit_id, nom_custom, quantite, date_expiration, seuil_alerte, emoji)
                VALUES (:produit_id, NULL, 1, :date_expiration, 2, :emoji)
            ");
            $stmt->execute([
                ':produit_id'      => $produit['id'],
                ':date_expiration' => $produit['date_expiration'] ?? null,
                ':emoji'           => $this->getEmojiByNom($produit['nom']),
            ]);
            $_SESSION['success'] = "✅ {$produit['nom']} scanné et ajouté au frigo !";
        } else {
            $_SESSION['errors'] = ["Produit non reconnu (code : $codeBarres). Ajoutez-le manuellement."];
        }
        header('Location: /frigo/index.php?mode=front&controller=produit&action=frigo');
        exit;
    }

    public function modifierQuantiteFrigo(): void {
        $id  = (int)($_POST['frigo_id'] ?? 0);
        $qte = (int)($_POST['quantite'] ?? 1);

        if ($qte < 0) {
            $_SESSION['errors'] = ["La quantité ne peut pas être négative."];
            header('Location: /frigo/index.php?mode=front&controller=produit&action=frigo');
            exit;
        }

        $stmt = $this->pdo->prepare("UPDATE frigo_utilisateur SET quantite = :qte WHERE id = :id");
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
                $_SESSION['success'] = "Stock faible pour {$item['nom']} — ajouté au panier !";
            }
        } else {
            $_SESSION['success'] = "Quantité mise à jour.";
        }
        header('Location: /frigo/index.php?mode=front&controller=produit&action=frigo');
        exit;
    }

    public function supprimerDuFrigo(): void {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->pdo->prepare("DELETE FROM frigo_utilisateur WHERE id = :id");
        $stmt->execute([':id' => $id]);
        header('Location: /frigo/index.php?mode=front&controller=produit&action=frigo');
        exit;
    }

    // ===== BACKOFFICE =====

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
        $categories = $this->getAllCategories();
        require 'app/view/produit/create.php';
    }

    public function store(): void {
        $errors = $this->validate($_POST);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: /frigo/index.php?mode=back&controller=produit&action=create');
            exit;
        }
        $stmt = $this->pdo->prepare("
            INSERT INTO produit (nom, description, prix, quantite, date_expiration, categorie_id, image)
            VALUES (:nom, :description, :prix, :quantite, :date_expiration, :categorie_id, :image)
        ");
        $stmt->execute([
            ':nom'             => htmlspecialchars(trim($_POST['nom'])),
            ':description'     => htmlspecialchars(trim($_POST['description'] ?? '')),
            ':prix'            => (float)$_POST['prix'],
            ':quantite'        => (int)$_POST['quantite'],
            ':date_expiration' => $_POST['date_expiration'] ?: null,
            ':categorie_id'    => (int)$_POST['categorie_id'],
            ':image'           => $_POST['image'] ?? null,
        ]);
        $_SESSION['success'] = "Produit ajouté avec succès.";
        header('Location: /frigo/index.php?mode=back&controller=produit&action=index');
        exit;
    }

    public function edit(): void {
        $id      = (int)($_GET['id'] ?? 0);
        $produit = $this->getProduitById($id);
        if (!$produit) die("Produit introuvable.");
        $categories = $this->getAllCategories();
        require 'app/view/produit/edit.php';
    }

    public function update(): void {
        $id     = (int)($_POST['id'] ?? 0);
        $errors = $this->validate($_POST);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: /frigo/index.php?mode=back&controller=produit&action=edit&id=$id");
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
            ':date_expiration' => $_POST['date_expiration'] ?: null,
            ':categorie_id'    => (int)$_POST['categorie_id'],
            ':image'           => $_POST['image'] ?? null,
        ]);
        $_SESSION['success'] = "Produit modifié.";
        header('Location: /frigo/index.php?mode=back&controller=produit&action=index');
        exit;
    }

    public function delete(): void {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->pdo->prepare("DELETE FROM produit WHERE id = :id");
        $stmt->execute([':id' => $id]);
        header('Location: /frigo/index.php?mode=back&controller=produit&action=index');
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
?>