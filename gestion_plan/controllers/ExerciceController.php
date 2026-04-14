<?php
require_once __DIR__ . '/../models/Exercice.php';
require_once __DIR__ . '/../models/Programme.php';

class ExerciceController {
    private $model;

    public function __construct() {
        $this->model = new Exercice();
    }

    // BACK OFFICE : liste des exercices
    public function index($office = 'back') {
        if ($office === 'front') {
            $exercices = $this->model->getAllForAjout();
            require __DIR__ . "/../views/front/exercices/index.php";
        } else {
            if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
                header("Location: login.php");
                exit;
            }
            $exercices = $this->model->getAll();
            require __DIR__ . "/../views/back/exercices/index.php";
        }
    }

    // BACK OFFICE ONLY
    public function create($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php");
            exit;
        }
        $errors = [];
        $programmes = (new Programme())->getAll();
        require __DIR__ . "/../views/back/exercices/create.php";
    }

    // BACK OFFICE ONLY
    public function store($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php");
            exit;
        }
        
        $errors = $this->valider($_POST);
        $programmes = (new Programme())->getAll();
        
        if (!empty($errors)) {
            require __DIR__ . "/../views/back/exercices/create.php";
            return;
        }
        
        $data = [
            'nom' => trim($_POST['nom']),
            'description' => trim($_POST['description']),
            'ordre' => intval($_POST['ordre']),
            'duree_minutes' => intval($_POST['duree_minutes']),
            'video_url' => trim($_POST['video_url']),
            'programme_id' => intval($_POST['programme_id']),
        ];
        $this->model->create($data);
        
        header("Location: index.php?module=exercice&action=index&office=back");
        exit;
    }

    // BACK OFFICE ONLY
    public function edit($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php");
            exit;
        }
        $errors = [];
        $exercice = $this->model->getById(intval($_GET['id']));
        $programmes = (new Programme())->getAll();
        require __DIR__ . "/../views/back/exercices/edit.php";
    }

    // BACK OFFICE ONLY
    public function update($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php");
            exit;
        }
        
        $id = intval($_POST['id']);
        $errors = $this->valider($_POST);
        
        if (!empty($errors)) {
            $exercice = $this->model->getById($id);
            $programmes = (new Programme())->getAll();
            require __DIR__ . "/../views/back/exercices/edit.php";
            return;
        }
        
        $this->model->update($id, [
            'nom' => trim($_POST['nom']),
            'description' => trim($_POST['description']),
            'ordre' => intval($_POST['ordre']),
            'duree_minutes' => intval($_POST['duree_minutes']),
            'video_url' => trim($_POST['video_url']),
            'statut' => $_POST['statut'],
            'programme_id' => intval($_POST['programme_id']),
        ]);
        
        header("Location: index.php?module=exercice&action=index&office=back");
        exit;
    }

    // BACK OFFICE ONLY
    public function delete($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php");
            exit;
        }
        $this->model->delete(intval($_GET['id']));
        header("Location: index.php?module=exercice&action=index&office=back");
        exit;
    }

    // Front : voir les exercices d'un programme officiel
    public function indexByProgramme($office = 'front') {
        $programme_id = intval($_GET['programme_id'] ?? 0);
        if ($programme_id > 0) {
            $exercices = $this->model->getByProgramme($programme_id);
        } else {
            $exercices = $this->model->getAllForAjout();
        }
        require __DIR__ . "/../views/front/exercices/index.php";
    }

    private function valider($data) {
        $errors = [];
        
        if (empty(trim($data['nom'] ?? ''))) {
            $errors['nom'] = "Le nom est obligatoire.";
        } elseif (strlen(trim($data['nom'])) < 2) {
            $errors['nom'] = "Le nom doit avoir au moins 2 caractères.";
        }

        if (empty($data['programme_id']) || !is_numeric($data['programme_id'])) {
            $errors['programme_id'] = "Veuillez choisir un programme.";
        }

        if (empty($data['ordre']) || !is_numeric($data['ordre']) || $data['ordre'] < 1) {
            $errors['ordre'] = "L'ordre doit être un nombre positif.";
        }

        if (!empty($data['video_url']) && !filter_var($data['video_url'], FILTER_VALIDATE_URL)) {
            $errors['video_url'] = "L'URL de la vidéo est invalide.";
        }

        return $errors;
    }
}
?>