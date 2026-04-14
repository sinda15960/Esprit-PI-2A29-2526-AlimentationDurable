<?php
require_once __DIR__ . '/../models/Objectif.php';
require_once __DIR__ . '/../models/Programme.php';

class ObjectifController {
    private $model;

    public function __construct() {
        $this->model = new Objectif();
    }

    // ─── UTILITAIRE ───────────────────────────────────────────────
    private function convertirDate($dateStr) {
        if (empty(trim($dateStr))) return null;
        $parts = explode('/', trim($dateStr));
        if (count($parts) === 3) {
            return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }
        return null;
    }

    // ─── BACK OFFICE ──────────────────────────────────────────────
    public function index($office = 'front') {
        if ($office === 'front') {
            $objectifsOfficiels = $this->model->getAll();
            $objectifsPerso = isset($_SESSION['user_id'])
                ? $this->model->getPersonalByUser($_SESSION['user_id'])
                : [];
            require __DIR__ . "/../views/front/objectifs/index.php";
        } else {
            if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
                header("Location: login.php");
                exit;
            }
            $objectifs = $this->model->getAll();
            require __DIR__ . "/../views/back/objectifs/index.php";
        }
    }

    public function create($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php");
            exit;
        }
        $errors = [];
        require __DIR__ . "/../views/back/objectifs/create.php";
    }

    public function store($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php");
            exit;
        }

        $errors = $this->validerOfficiel($_POST);
        if (!empty($errors)) {
            require __DIR__ . "/../views/back/objectifs/create.php";
            return;
        }

        $this->model->create([
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
            header("Location: login.php");
            exit;
        }
        $errors = [];
        $objectif = $this->model->getById(intval($_GET['id']));
        if (!$objectif) {
            header("Location: index.php?module=objectif&action=index&office=back");
            exit;
        }
        require __DIR__ . "/../views/back/objectifs/edit.php";
    }

    public function update($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php");
            exit;
        }

        $id = intval($_POST['id']);
        $errors = $this->validerOfficiel($_POST);
        if (!empty($errors)) {
            $objectif = $this->model->getById($id);
            require __DIR__ . "/../views/back/objectifs/edit.php";
            return;
        }

        $this->model->update($id, [
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
            header("Location: login.php");
            exit;
        }
        $this->model->delete(intval($_GET['id']));
        header("Location: index.php?module=objectif&action=index&office=back");
        exit;
    }

    public function show($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php");
            exit;
        }
        $objectif = $this->model->getById(intval($_GET['id']));
        if (!$objectif) {
            header("Location: index.php?module=objectif&action=index&office=back");
            exit;
        }
        $programmes = (new Programme())->getByObjectif($objectif['id']);
        require __DIR__ . "/../views/back/objectifs/show.php";
    }

    // ─── FRONT OFFICE ─────────────────────────────────────────────
    public function createPersonal() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?module=auth&action=login");
            exit;
        }
        $errors = [];
        require __DIR__ . "/../views/front/objectifs/create_personal.php";
    }

    public function storePersonal() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?module=auth&action=login");
            exit;
        }

        $errors = $this->validerPersonnel($_POST);

        if (!empty($errors)) {
            $objectifsOfficiels = $this->model->getAll();
            $objectifsPerso = $this->model->getPersonalByUser($_SESSION['user_id']);
            require __DIR__ . "/../views/front/objectifs/index.php";
            return;
        }

        $this->model->createPersonal([
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
            header("Location: index.php?module=auth&action=login");
            exit;
        }

        $id = intval($_GET['id']);
        $objectif = $this->model->getById($id);

        if (!$objectif || $objectif['is_personal'] != 1 || $objectif['user_id'] != $_SESSION['user_id']) {
            header("Location: index.php?module=objectif&action=index&office=front");
            exit;
        }

        $errors = [];
        require __DIR__ . "/../views/front/objectifs/edit_personal.php";
    }

    public function updatePersonal() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?module=auth&action=login");
            exit;
        }

        $id = intval($_POST['id']);
        $objectif = $this->model->getById($id);

        if (!$objectif || $objectif['is_personal'] != 1 || $objectif['user_id'] != $_SESSION['user_id']) {
            header("Location: index.php?module=objectif&action=index&office=front");
            exit;
        }

        $errors = $this->validerPersonnel($_POST);

        if (!empty($errors)) {
            require __DIR__ . "/../views/front/objectifs/edit_personal.php";
            return;
        }

        $this->model->updatePersonal($id, [
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

    public function deletePersonal() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?module=auth&action=login");
            exit;
        }
        $id = intval($_GET['id']);
        $this->model->deletePersonal($id, $_SESSION['user_id']);
        header("Location: index.php?module=objectif&action=index&office=front");
        exit;
    }

    // ─── VALIDATIONS ──────────────────────────────────────────────
    private function validerOfficiel($data) {
        $errors = [];

        if (empty(trim($data['titre'] ?? ''))) {
            $errors['titre'] = "Le titre est obligatoire.";
        } elseif (strlen(trim($data['titre'])) < 3) {
            $errors['titre'] = "Le titre doit avoir au moins 3 caractères.";
        }

        $types = ['grossir', 'maigrir', 'maintenir', 'muscler'];
        if (empty($data['type_objectif']) || !in_array($data['type_objectif'], $types)) {
            $errors['type_objectif'] = "Veuillez choisir un type d'objectif.";
        }

        if (empty($data['calories_min']) || !is_numeric($data['calories_min']) || $data['calories_min'] < 500) {
            $errors['calories_min'] = "Calories min doit être au moins 500.";
        }

        if (empty($data['calories_max']) || !is_numeric($data['calories_max']) || $data['calories_max'] < 500) {
            $errors['calories_max'] = "Calories max doit être au moins 500.";
        }

        if (!empty($data['calories_min']) && !empty($data['calories_max'])
            && intval($data['calories_max']) <= intval($data['calories_min'])) {
            $errors['calories_max'] = "Calories max doit être supérieur à calories min.";
        }

        return $errors;
    }

    private function validerPersonnel($data) {
        $errors = [];

        // Titre
        if (empty(trim($data['titre'] ?? ''))) {
            $errors['titre'] = "Le titre est obligatoire.";
        } elseif (strlen(trim($data['titre'])) < 3) {
            $errors['titre'] = "Le titre doit avoir au moins 3 caractères.";
        }

        // Poids actuel
        if (!empty($data['poids_actuel'])) {
            if (!is_numeric($data['poids_actuel']) || $data['poids_actuel'] < 20 || $data['poids_actuel'] > 300) {
                $errors['poids_actuel'] = "Le poids doit être entre 20 et 300 kg.";
            }
        }

        // Poids cible
        if (!empty($data['poids_cible'])) {
            if (!is_numeric($data['poids_cible']) || $data['poids_cible'] < 20 || $data['poids_cible'] > 300) {
                $errors['poids_cible'] = "Le poids cible doit être entre 20 et 300 kg.";
            }
        }

        // Taille en mètres format 1.72
        if (!empty($data['taille'])) {
            if (!preg_match('/^\d+\.\d{2}$/', $data['taille']) ||
                floatval($data['taille']) < 0.50 || floatval($data['taille']) > 2.50) {
                $errors['taille'] = "La taille doit être au format 1.72 (entre 0.50 et 2.50 m).";
            }
        }

        // Age
        if (!empty($data['age'])) {
            if (!ctype_digit((string)$data['age']) || intval($data['age']) < 10 || intval($data['age']) > 120) {
                $errors['age'] = "L'âge doit être entre 10 et 120 ans.";
            }
        }

        // Dates : comparaison après conversion
        if (!empty($data['date_debut']) && !empty($data['date_fin_prevue'])) {
            $debut = $this->convertirDate($data['date_debut']);
            $fin   = $this->convertirDate($data['date_fin_prevue']);
            if ($debut && $fin && $fin <= $debut) {
                $errors['date_fin_prevue'] = "La date de fin doit être supérieure à la date de début.";
            }
        }

        return $errors;
    }
}
?>