<?php
require_once __DIR__ . '/../models/Programme.php';
require_once __DIR__ . '/../models/Objectif.php';

class ProgrammeController {
    private $model;

    public function __construct() {
        $this->model = new Programme();
    }

    public function index($office = 'front') {
        $programmes = $this->model->getAll();
        $officeDir = ($office === 'back') ? 'back' : 'front';
        require __DIR__ . "/../views/$officeDir/programmes/index.php";
    }

    // NOUVELLE METHODE : Filtrer les programmes par objectif
    public function indexByObjectif($office = 'front') {
        $objectif_id = intval($_GET['objectif_id'] ?? 0);
        if ($objectif_id > 0) {
            $programmes = $this->model->getByObjectif($objectif_id);
        } else {
            $programmes = $this->model->getAll();
        }
        $officeDir = ($office === 'back') ? 'back' : 'front';
        require __DIR__ . "/../views/$officeDir/programmes/index.php";
    }

    public function create($office = 'back') {
        $errors = [];
        $objectifs = (new Objectif())->getAll();
        require __DIR__ . "/../views/back/programmes/create.php";
    }

    public function store($office = 'back') {
        $errors = $this->valider($_POST);
        $objectifs = (new Objectif())->getAll();
        if (!empty($errors)) {
            require __DIR__ . "/../views/back/programmes/create.php";
            return;
        }
        $this->model->create([
            'nom'            => trim($_POST['nom']),
            'description'    => trim($_POST['description']),
            'duree_semaines' => intval($_POST['duree_semaines']),
            'niveau'         => $_POST['niveau'],
            'objectif_id'    => !empty($_POST['objectif_id']) ? intval($_POST['objectif_id']) : null,
        ]);
        header("Location: index.php?module=programme&action=index&office=back");
        exit;
    }

    public function edit($office = 'back') {
        $errors = [];
        $programme = $this->model->getById(intval($_GET['id']));
        $objectifs = (new Objectif())->getAll();
        require __DIR__ . "/../views/back/programmes/edit.php";
    }

    public function update($office = 'back') {
        $id = intval($_POST['id']);
        $errors = $this->valider($_POST);
        $objectifs = (new Objectif())->getAll();
        if (!empty($errors)) {
            $programme = $this->model->getById($id);
            require __DIR__ . "/../views/back/programmes/edit.php";
            return;
        }
        $this->model->update($id, [
            'nom'            => trim($_POST['nom']),
            'description'    => trim($_POST['description']),
            'duree_semaines' => intval($_POST['duree_semaines']),
            'niveau'         => $_POST['niveau'],
            'objectif_id'    => !empty($_POST['objectif_id']) ? intval($_POST['objectif_id']) : null,
        ]);
        header("Location: index.php?module=programme&action=index&office=back");
        exit;
    }

    public function delete($office = 'back') {
        $this->model->delete(intval($_GET['id']));
        header("Location: index.php?module=programme&action=index&office=back");
        exit;
    }

    private function valider($data) {
        $errors = [];
        if (empty(trim($data['nom'] ?? '')))
            $errors['nom'] = "Le nom est obligatoire.";

        if (empty($data['duree_semaines']) || !is_numeric($data['duree_semaines']) || $data['duree_semaines'] < 1)
            $errors['duree_semaines'] = "La durée doit être au moins 1 semaine.";

        $niveaux = ['debutant','intermediaire','avance'];
        if (empty($data['niveau']) || !in_array($data['niveau'], $niveaux))
            $errors['niveau'] = "Veuillez choisir un niveau.";

        return $errors;
    }
}