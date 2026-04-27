<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/Programme.php';
require_once __DIR__ . '/../models/Objectif.php';
require_once __DIR__ . '/../models/Exercice.php';

class ProgrammeController {
    private $pdo;

    public function __construct() {
        $this->pdo = getConnection();
    }

    // ─── REQUETES SQL ─────────────────────────────────────────────

    private function dbGetAllFiltres(string $niveau = '', int $categorie_id = 0, string $tri = ''): array {
        $sql = "SELECT p.*, o.titre AS objectif_titre, c.nom AS categorie_nom
                FROM programme p 
                LEFT JOIN objectif o ON p.objectif_id = o.id
                LEFT JOIN categorie c ON p.categorie_id = c.id_categorie
                WHERE 1=1";
        $params = [];

        if (!empty($niveau)) {
            $sql .= " AND p.niveau = ?";
            $params[] = $niveau;
        }
        if ($categorie_id > 0) {
            $sql .= " AND p.categorie_id = ?";
            $params[] = $categorie_id;
        }

        switch ($tri) {
            case 'niveau_asc':  $sql .= " ORDER BY FIELD(p.niveau,'debutant','intermediaire','avance')"; break;
            case 'niveau_desc': $sql .= " ORDER BY FIELD(p.niveau,'avance','intermediaire','debutant')"; break;
            case 'duree_asc':   $sql .= " ORDER BY p.duree_semaines ASC"; break;
            case 'duree_desc':  $sql .= " ORDER BY p.duree_semaines DESC"; break;
            default:            $sql .= " ORDER BY p.date_creation DESC";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    private function dbGetByObjectif(int $objectif_id): array {
        $sql = "SELECT p.*, o.titre AS objectif_titre, c.nom AS categorie_nom
                FROM programme p 
                LEFT JOIN objectif o ON p.objectif_id = o.id
                LEFT JOIN categorie c ON p.categorie_id = c.id_categorie
                WHERE p.objectif_id = ?
                ORDER BY p.date_creation DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$objectif_id]);
        return $stmt->fetchAll();
    }

    private function dbGetById(int $id): array|false {
        $sql = "SELECT p.*, o.titre AS objectif_titre, c.nom AS categorie_nom
                FROM programme p 
                LEFT JOIN objectif o ON p.objectif_id = o.id
                LEFT JOIN categorie c ON p.categorie_id = c.id_categorie
                WHERE p.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    private function dbGetExercices(int $programme_id): array {
        $sql = "SELECT e.*, p.nom AS programme_nom 
                FROM exercice e
                LEFT JOIN programme p ON e.programme_id = p.id
                WHERE e.programme_id = ?
                ORDER BY e.ordre";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$programme_id]);
        return $stmt->fetchAll();
    }

    private function dbGetAllObjectifs(): array {
        return $this->pdo->query("SELECT * FROM objectif WHERE is_personal = 0 ORDER BY titre")->fetchAll();
    }

    private function dbGetAllCategories(): array {
        return $this->pdo->query("SELECT * FROM categorie ORDER BY nom")->fetchAll();
    }

    private function dbGetFavorisUser(int $user_id): array {
        $sql = "SELECT programme_id FROM favori WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return array_column($stmt->fetchAll(), 'programme_id');
    }

    private function dbCreate(array $data): bool {
        $sql = "INSERT INTO programme (nom, description, duree_semaines, niveau, objectif_id, categorie_id)
                VALUES (:nom, :description, :duree_semaines, :niveau, :objectif_id, :categorie_id)";
        return $this->pdo->prepare($sql)->execute([
            ':nom'            => $data['nom'],
            ':description'    => $data['description'],
            ':duree_semaines' => $data['duree_semaines'],
            ':niveau'         => $data['niveau'],
            ':objectif_id'    => $data['objectif_id'],
            ':categorie_id'   => $data['categorie_id'],
        ]);
    }

    private function dbUpdate(int $id, array $data): bool {
        $sql = "UPDATE programme SET nom=:nom, description=:description,
                duree_semaines=:duree_semaines, niveau=:niveau,
                objectif_id=:objectif_id, categorie_id=:categorie_id
                WHERE id=:id";
        return $this->pdo->prepare($sql)->execute([
            ':nom'            => $data['nom'],
            ':description'    => $data['description'],
            ':duree_semaines' => $data['duree_semaines'],
            ':niveau'         => $data['niveau'],
            ':objectif_id'    => $data['objectif_id'],
            ':categorie_id'   => $data['categorie_id'],
            ':id'             => $id,
        ]);
    }

    private function dbDelete(int $id): bool {
        return $this->pdo->prepare("DELETE FROM programme WHERE id = ?")->execute([$id]);
    }

    // ─── ACTIONS ──────────────────────────────────────────────────

    public function index($office = 'front') {
        if ($office === 'back') {
            if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
                header("Location: login.php"); exit;
            }
            $programmes = $this->dbGetAllFiltres();
            require __DIR__ . "/../views/back/programmes/index.php";
            return;
        }

        $niveau       = $_GET['niveau'] ?? '';
        $categorie_id = intval($_GET['categorie_id'] ?? 0);
        $tri          = $_GET['tri'] ?? '';

        // Validation PHP des filtres (pas HTML5)
        $errors = [];
        $niveauxValides = ['', 'debutant', 'intermediaire', 'avance'];
        if (!in_array($niveau, $niveauxValides)) {
            $errors['niveau'] = "Niveau invalide.";
            $niveau = '';
        }
        $trisValides = ['', 'niveau_asc', 'niveau_desc', 'duree_asc', 'duree_desc'];
        if (!in_array($tri, $trisValides)) {
            $errors['tri'] = "Tri invalide.";
            $tri = '';
        }

        $programmes  = $this->dbGetAllFiltres($niveau, $categorie_id, $tri);
        $categories  = $this->dbGetAllCategories();
        $favorisUser = isset($_SESSION['user_id']) ? $this->dbGetFavorisUser($_SESSION['user_id']) : [];

        require __DIR__ . "/../views/front/programmes/index.php";
    }

    public function indexByObjectif($office = 'front') {
        $objectif_id  = intval($_GET['objectif_id'] ?? 0);
        $programmes   = $objectif_id > 0 ? $this->dbGetByObjectif($objectif_id) : $this->dbGetAllFiltres();
        $categories   = $this->dbGetAllCategories();
        $favorisUser  = isset($_SESSION['user_id']) ? $this->dbGetFavorisUser($_SESSION['user_id']) : [];
        $errors       = [];
        require __DIR__ . "/../views/front/programmes/index.php";
    }

    public function show($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $programme = $this->dbGetById(intval($_GET['id']));
        if (!$programme) {
            header("Location: index.php?module=objectif&action=index&office=back"); exit;
        }
        $exercices = $this->dbGetExercices($programme['id']);
        require __DIR__ . "/../views/back/programmes/show.php";
    }

    public function create($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $errors      = [];
        $objectif_id = intval($_GET['objectif_id'] ?? 0);
        $objectifs   = $this->dbGetAllObjectifs();
        require __DIR__ . "/../views/back/programmes/create.php";
    }

    public function store($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $errors      = $this->valider($_POST);
        $objectifs   = $this->dbGetAllObjectifs();
        $objectif_id = intval($_POST['objectif_id'] ?? 0);

        if (!empty($errors)) {
            require __DIR__ . "/../views/back/programmes/create.php";
            return;
        }
        $this->dbCreate([
            'nom'            => trim($_POST['nom']),
            'description'    => trim($_POST['description'] ?? ''),
            'duree_semaines' => intval($_POST['duree_semaines']),
            'niveau'         => $_POST['niveau'],
            'objectif_id'    => $objectif_id > 0 ? $objectif_id : null,
            'categorie_id'   => !empty($_POST['categorie_id']) ? intval($_POST['categorie_id']) : null,
        ]);
        header($objectif_id > 0
            ? "Location: index.php?module=objectif&action=show&id=$objectif_id&office=back"
            : "Location: index.php?module=objectif&action=index&office=back");
        exit;
    }

    public function edit($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $errors    = [];
        $programme = $this->dbGetById(intval($_GET['id']));
        if (!$programme) {
            header("Location: index.php?module=objectif&action=index&office=back"); exit;
        }
        $objectifs = $this->dbGetAllObjectifs();
        require __DIR__ . "/../views/back/programmes/edit.php";
    }

    public function update($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $id          = intval($_POST['id']);
        $errors      = $this->valider($_POST);
        $objectifs   = $this->dbGetAllObjectifs();
        $objectif_id = intval($_POST['objectif_id'] ?? 0);

        if (!empty($errors)) {
            $programme = $this->dbGetById($id);
            require __DIR__ . "/../views/back/programmes/edit.php";
            return;
        }
        $this->dbUpdate($id, [
            'nom'            => trim($_POST['nom']),
            'description'    => trim($_POST['description'] ?? ''),
            'duree_semaines' => intval($_POST['duree_semaines']),
            'niveau'         => $_POST['niveau'],
            'objectif_id'    => $objectif_id > 0 ? $objectif_id : null,
            'categorie_id'   => !empty($_POST['categorie_id']) ? intval($_POST['categorie_id']) : null,
        ]);
        header($objectif_id > 0
            ? "Location: index.php?module=objectif&action=show&id=$objectif_id&office=back"
            : "Location: index.php?module=objectif&action=index&office=back");
        exit;
    }

    public function delete($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $objectif_id = intval($_GET['objectif_id'] ?? 0);
        $this->dbDelete(intval($_GET['id']));
        header($objectif_id > 0
            ? "Location: index.php?module=objectif&action=show&id=$objectif_id&office=back"
            : "Location: index.php?module=objectif&action=index&office=back");
        exit;
    }

    private function valider($data): array {
        $errors = [];
        if (empty(trim($data['nom'] ?? ''))) {
            $errors['nom'] = "Le nom est obligatoire.";
        } elseif (strlen(trim($data['nom'])) < 2) {
            $errors['nom'] = "Le nom doit avoir au moins 2 caracteres.";
        }
        if (empty($data['duree_semaines']) || !is_numeric($data['duree_semaines']) || $data['duree_semaines'] < 1) {
            $errors['duree_semaines'] = "La duree doit etre au moins 1 semaine.";
        }
        $niveaux = ['debutant', 'intermediaire', 'avance'];
        if (empty($data['niveau']) || !in_array($data['niveau'], $niveaux)) {
            $errors['niveau'] = "Veuillez choisir un niveau valide.";
        }
        return $errors;
    }
}
?>