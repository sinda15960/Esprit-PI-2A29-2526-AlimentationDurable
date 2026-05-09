<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/Objectif.php';
require_once __DIR__ . '/../models/Programme.php';

class ObjectifController {
    private $pdo;

    public function __construct() {
        $this->pdo = getConnection();
    }

    // ─── REQUETES SQL ─────────────────────────────────────────────

    private function dbGetAll(): array {
        $sql = "SELECT * FROM objectif WHERE is_personal = 0 ORDER BY date_creation DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    private function dbGetPersonalByUser(int $user_id): array {
        $sql = "SELECT * FROM objectif WHERE is_personal = 1 AND user_id = ? ORDER BY date_creation DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    private function dbGetAllPersonal(): array {
        $sql = "SELECT o.*, u.nom as user_nom 
                FROM objectif o 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE o.is_personal = 1 
                ORDER BY o.date_creation DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    private function dbGetById(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM objectif WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    private function dbCreate(array $data): bool {
        $sql = "INSERT INTO objectif (titre, type_objectif, description, maladies, preferences, calories_min, calories_max, is_personal)
                VALUES (:titre, :type_objectif, :description, :maladies, :preferences, :calories_min, :calories_max, 0)";
        return $this->pdo->prepare($sql)->execute([
            ':titre'         => $data['titre'],
            ':type_objectif' => $data['type_objectif'],
            ':description'   => $data['description'],
            ':maladies'      => $data['maladies'],
            ':preferences'   => $data['preferences'],
            ':calories_min'  => $data['calories_min'],
            ':calories_max'  => $data['calories_max'],
        ]);
    }

    private function dbCreatePersonal(array $data): bool {
        $sql = "INSERT INTO objectif (titre, description, poids_actuel, poids_cible, taille, age, 
                                      etat_sante, date_debut, date_fin_prevue, user_id, is_personal)
                VALUES (:titre, :description, :poids_actuel, :poids_cible, :taille, :age, 
                        :etat_sante, :date_debut, :date_fin_prevue, :user_id, 1)";
        return $this->pdo->prepare($sql)->execute([
            ':titre'           => $data['titre'],
            ':description'     => $data['description'],
            ':poids_actuel'    => $data['poids_actuel'],
            ':poids_cible'     => $data['poids_cible'],
            ':taille'          => $data['taille'],
            ':age'             => $data['age'],
            ':etat_sante'      => $data['etat_sante'],
            ':date_debut'      => $data['date_debut'],
            ':date_fin_prevue' => $data['date_fin_prevue'],
            ':user_id'         => $data['user_id'],
        ]);
    }

    private function dbUpdatePersonal(int $id, array $data): bool {
        $sql = "UPDATE objectif SET 
                    titre = :titre, description = :description,
                    poids_actuel = :poids_actuel, poids_cible = :poids_cible,
                    taille = :taille, age = :age, etat_sante = :etat_sante,
                    date_debut = :date_debut, date_fin_prevue = :date_fin_prevue
                WHERE id = :id AND user_id = :user_id AND is_personal = 1";
        return $this->pdo->prepare($sql)->execute([
            ':titre'           => $data['titre'],
            ':description'     => $data['description'],
            ':poids_actuel'    => $data['poids_actuel'],
            ':poids_cible'     => $data['poids_cible'],
            ':taille'          => $data['taille'],
            ':age'             => $data['age'],
            ':etat_sante'      => $data['etat_sante'],
            ':date_debut'      => $data['date_debut'],
            ':date_fin_prevue' => $data['date_fin_prevue'],
            ':id'              => $id,
            ':user_id'         => $data['user_id'],
        ]);
    }

    private function dbUpdate(int $id, array $data): bool {
        $sql = "UPDATE objectif SET 
                    titre = :titre, type_objectif = :type_objectif,
                    description = :description, maladies = :maladies,
                    preferences = :preferences, calories_min = :calories_min, 
                    calories_max = :calories_max
                WHERE id = :id AND is_personal = 0";
        return $this->pdo->prepare($sql)->execute([
            ':titre'         => $data['titre'],
            ':type_objectif' => $data['type_objectif'],
            ':description'   => $data['description'],
            ':maladies'      => $data['maladies'],
            ':preferences'   => $data['preferences'],
            ':calories_min'  => $data['calories_min'],
            ':calories_max'  => $data['calories_max'],
            ':id'            => $id,
        ]);
    }

    private function dbDelete(int $id): bool {
        return $this->pdo->prepare("DELETE FROM objectif WHERE id = ?")->execute([$id]);
    }

    private function dbDeletePersonal(int $id, int $user_id): bool {
        return $this->pdo->prepare("DELETE FROM objectif WHERE id = ? AND user_id = ? AND is_personal = 1")
                         ->execute([$id, $user_id]);
    }

    private function dbGetProgrammesByObjectif(int $objectif_id): array {
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

    // ─── ACTIONS ──────────────────────────────────────────────────

    public function index($office = 'front') {
        if ($office === 'front') {
            $objectifsOfficiels = $this->dbGetAll();
            $objectifsPerso = isset($_SESSION['user_id'])
                ? $this->dbGetPersonalByUser($_SESSION['user_id'])
                : [];
            require __DIR__ . "/../views/front/objectifs/index.php";
        } else {
            if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
                header("Location: login.php"); exit;
            }
            $objectifs = $this->dbGetAll();
            $objectifsPersonnels = $this->dbGetAllPersonal();
            require __DIR__ . "/../views/back/objectifs/index.php";
        }
    }

    public function create($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $errors = [];
        require __DIR__ . "/../views/back/objectifs/create.php";
    }

    public function store($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $errors = $this->validerOfficiel($_POST);
        if (!empty($errors)) {
            require __DIR__ . "/../views/back/objectifs/create.php";
            return;
        }
        $this->dbCreate([
            'titre'         => trim($_POST['titre']),
            'type_objectif' => $_POST['type_objectif'],
            'description'   => trim($_POST['description'] ?? ''),
            'maladies'      => trim($_POST['maladies'] ?? ''),
            'preferences'   => trim($_POST['preferences'] ?? ''),
            'calories_min'  => intval($_POST['calories_min']),
            'calories_max'  => intval($_POST['calories_max']),
        ]);
        header("Location: index.php?module=objectif&action=index&office=back");
        exit;
    }

    public function edit($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $errors = [];
        $objectif = $this->dbGetById(intval($_GET['id']));
        if (!$objectif) {
            header("Location: index.php?module=objectif&action=index&office=back"); exit;
        }
        require __DIR__ . "/../views/back/objectifs/edit.php";
    }

    public function update($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $id = intval($_POST['id']);
        $errors = $this->validerOfficiel($_POST);
        if (!empty($errors)) {
            $objectif = $this->dbGetById($id);
            require __DIR__ . "/../views/back/objectifs/edit.php";
            return;
        }
        $this->dbUpdate($id, [
            'titre'         => trim($_POST['titre']),
            'type_objectif' => $_POST['type_objectif'],
            'description'   => trim($_POST['description'] ?? ''),
            'maladies'      => trim($_POST['maladies'] ?? ''),
            'preferences'   => trim($_POST['preferences'] ?? ''),
            'calories_min'  => intval($_POST['calories_min']),
            'calories_max'  => intval($_POST['calories_max']),
        ]);
        header("Location: index.php?module=objectif&action=index&office=back");
        exit;
    }

    public function delete($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $this->dbDelete(intval($_GET['id']));
        header("Location: index.php?module=objectif&action=index&office=back");
        exit;
    }

    public function show($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $objectif = $this->dbGetById(intval($_GET['id']));
        if (!$objectif) {
            header("Location: index.php?module=objectif&action=index&office=back"); exit;
        }
        $programmes = $this->dbGetProgrammesByObjectif($objectif['id']);
        if ($objectif['is_personal'] == 1) {
            require __DIR__ . "/../views/back/objectifs/show_personal.php";
        } else {
            require __DIR__ . "/../views/back/objectifs/show.php";
        }
    }

    public function showPersonal($office = 'front') {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?module=auth&action=login"); exit;
        }
        $id = intval($_GET['id']);
        $objectif = $this->dbGetById($id);
        if (!$objectif || $objectif['is_personal'] != 1 || $objectif['user_id'] != $_SESSION['user_id']) {
            header("Location: index.php?module=objectif&action=index&office=front"); exit;
        }
        $programmes = $this->dbGetProgrammesByObjectif($objectif['id']);
        require __DIR__ . "/../views/front/objectifs/show_personal.php";
    }

    public function createPersonal() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?module=auth&action=login"); exit;
        }
        $errors = [];
        require __DIR__ . "/../views/front/objectifs/create_personal.php";
    }

    public function storePersonal() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?module=auth&action=login"); exit;
        }
        $errors = $this->validerPersonnel($_POST);
        if (!empty($errors)) {
            $objectifsOfficiels = $this->dbGetAll();
            $objectifsPerso = $this->dbGetPersonalByUser($_SESSION['user_id']);
            require __DIR__ . "/../views/front/objectifs/index.php";
            return;
        }
        $this->dbCreatePersonal([
            'titre'           => trim($_POST['titre']),
            'description'     => trim($_POST['description'] ?? ''),
            'poids_actuel'    => !empty($_POST['poids_actuel']) ? floatval($_POST['poids_actuel']) : null,
            'poids_cible'     => !empty($_POST['poids_cible']) ? floatval($_POST['poids_cible']) : null,
            'taille'          => !empty($_POST['taille']) ? floatval($_POST['taille']) : null,
            'age'             => !empty($_POST['age']) ? intval($_POST['age']) : null,
            'etat_sante'      => trim($_POST['etat_sante'] ?? ''),
            'date_debut'      => $this->convertirDate($_POST['date_debut'] ?? ''),
            'date_fin_prevue' => $this->convertirDate($_POST['date_fin_prevue'] ?? ''),
            'user_id'         => $_SESSION['user_id'],
        ]);
        header("Location: index.php?module=objectif&action=index&office=front");
        exit;
    }

    public function editPersonal() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?module=auth&action=login"); exit;
        }
        $id = intval($_GET['id']);
        $objectif = $this->dbGetById($id);
        if (!$objectif || $objectif['is_personal'] != 1 || $objectif['user_id'] != $_SESSION['user_id']) {
            header("Location: index.php?module=objectif&action=index&office=front"); exit;
        }
        $errors = [];
        require __DIR__ . "/../views/front/objectifs/edit_personal.php";
    }

    public function updatePersonal() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?module=auth&action=login"); exit;
        }
        $id = intval($_POST['id']);
        $objectif = $this->dbGetById($id);
        if (!$objectif || $objectif['is_personal'] != 1 || $objectif['user_id'] != $_SESSION['user_id']) {
            header("Location: index.php?module=objectif&action=index&office=front"); exit;
        }
        $errors = $this->validerPersonnel($_POST);
        if (!empty($errors)) {
            require __DIR__ . "/../views/front/objectifs/edit_personal.php";
            return;
        }
        $result = $this->dbUpdatePersonal($id, [
            'titre'           => trim($_POST['titre']),
            'description'     => trim($_POST['description'] ?? ''),
            'poids_actuel'    => !empty($_POST['poids_actuel']) ? floatval($_POST['poids_actuel']) : null,
            'poids_cible'     => !empty($_POST['poids_cible']) ? floatval($_POST['poids_cible']) : null,
            'taille'          => !empty($_POST['taille']) ? floatval($_POST['taille']) : null,
            'age'             => !empty($_POST['age']) ? intval($_POST['age']) : null,
            'etat_sante'      => trim($_POST['etat_sante'] ?? ''),
            'date_debut'      => $this->convertirDate($_POST['date_debut'] ?? ''),
            'date_fin_prevue' => $this->convertirDate($_POST['date_fin_prevue'] ?? ''),
            'user_id'         => $_SESSION['user_id'],
        ]);
        if ($result) {
            header("Location: index.php?module=objectif&action=index&office=front"); exit;
        } else {
            $errors['general'] = "Erreur lors de la mise a jour.";
            require __DIR__ . "/../views/front/objectifs/edit_personal.php";
        }
    }

    public function deletePersonal() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?module=auth&action=login"); exit;
        }
        $this->dbDeletePersonal(intval($_GET['id']), $_SESSION['user_id']);
        header("Location: index.php?module=objectif&action=index&office=front");
        exit;
    }

    // ─── VALIDATION ───────────────────────────────────────────────

    private function validerOfficiel($data): array {
        $errors = [];
        if (empty(trim($data['titre'] ?? ''))) {
            $errors['titre'] = "Le titre est obligatoire.";
        } elseif (strlen(trim($data['titre'])) < 3) {
            $errors['titre'] = "Le titre doit avoir au moins 3 caracteres.";
        }
        $types = ['grossir', 'maigrir', 'maintenir', 'muscler'];
        if (empty($data['type_objectif']) || !in_array($data['type_objectif'], $types)) {
            $errors['type_objectif'] = "Veuillez choisir un type d'objectif.";
        }
        if (empty($data['calories_min']) || !is_numeric($data['calories_min']) || $data['calories_min'] < 500) {
            $errors['calories_min'] = "Calories min doit etre au moins 500.";
        }
        if (empty($data['calories_max']) || !is_numeric($data['calories_max']) || $data['calories_max'] < 500) {
            $errors['calories_max'] = "Calories max doit etre au moins 500.";
        }
        if (!empty($data['calories_min']) && !empty($data['calories_max'])
            && intval($data['calories_max']) <= intval($data['calories_min'])) {
            $errors['calories_max'] = "Calories max doit etre superieur a calories min.";
        }
        return $errors;
    }

    private function validerPersonnel($data): array {
        $errors = [];
        if (empty(trim($data['titre'] ?? ''))) {
            $errors['titre'] = "Le titre est obligatoire.";
        } elseif (strlen(trim($data['titre'])) < 3) {
            $errors['titre'] = "Le titre doit avoir au moins 3 caracteres.";
        }
        if (!empty($data['poids_actuel']) && (!is_numeric($data['poids_actuel']) || floatval($data['poids_actuel']) < 20 || floatval($data['poids_actuel']) > 300)) {
            $errors['poids_actuel'] = "Le poids doit etre entre 20 et 300 kg.";
        }
        if (!empty($data['poids_cible']) && (!is_numeric($data['poids_cible']) || floatval($data['poids_cible']) < 20 || floatval($data['poids_cible']) > 300)) {
            $errors['poids_cible'] = "Le poids cible doit etre entre 20 et 300 kg.";
        }
        if (!empty($data['taille']) && (!is_numeric($data['taille']) || floatval($data['taille']) < 0.5 || floatval($data['taille']) > 2.5)) {
            $errors['taille'] = "La taille doit etre entre 0.5 et 2.5 m.";
        }
        if (!empty($data['age']) && (!ctype_digit((string)$data['age']) || intval($data['age']) < 10 || intval($data['age']) > 120)) {
            $errors['age'] = "L'age doit etre entre 10 et 120 ans.";
        }
        if (!empty($data['date_debut']) && !empty($data['date_fin_prevue'])) {
            $debut = $this->convertirDate($data['date_debut']);
            $fin   = $this->convertirDate($data['date_fin_prevue']);
            if ($debut && $fin && $fin <= $debut) {
                $errors['date_fin_prevue'] = "La date de fin doit etre superieure a la date de debut.";
            }
        }
        return $errors;
    }

    private function convertirDate($dateStr): ?string {
        if (empty(trim($dateStr))) return null;
        $parts = explode('/', trim($dateStr));
        if (count($parts) === 3) {
            return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }
        return null;
    }
}
?>