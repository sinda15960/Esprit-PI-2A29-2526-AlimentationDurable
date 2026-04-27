<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/Exercice.php';
require_once __DIR__ . '/../models/Programme.php';

class ExerciceController {
    private $pdo;

    public function __construct() {
        $this->pdo = getConnection();
    }

    public function index($office = 'back') {
        if ($office === 'front') {
            $sql = "SELECT e.*, p.nom AS programme_nom 
                    FROM exercice e
                    LEFT JOIN programme p ON e.programme_id = p.id
                    ORDER BY p.nom, e.ordre";
            $stmt = $this->pdo->query($sql);
            $exercices = $stmt->fetchAll();
            require __DIR__ . "/../views/front/exercices/index.php";
        } else {
            if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
                header("Location: login.php"); exit;
            }
            $sql = "SELECT e.*, p.nom AS programme_nom 
                    FROM exercice e 
                    LEFT JOIN programme p ON e.programme_id = p.id
                    ORDER BY e.programme_id, e.ordre";
            $stmt = $this->pdo->query($sql);
            $exercices = $stmt->fetchAll();
            require __DIR__ . "/../views/back/exercices/index.php";
        }
    }

    public function create($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $errors = [];
        $programme_id = intval($_GET['programme_id'] ?? 0);
        $sql = "SELECT * FROM programme ORDER BY nom";
        $programmes = $this->pdo->query($sql)->fetchAll();
        require __DIR__ . "/../views/back/exercices/create.php";
    }

    public function store($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $errors = $this->valider($_POST);
        $sql = "SELECT * FROM programme ORDER BY nom";
        $programmes = $this->pdo->query($sql)->fetchAll();
        $programme_id = intval($_POST['programme_id'] ?? 0);

        if (!empty($errors)) {
            require __DIR__ . "/../views/back/exercices/create.php";
            return;
        }

        $sql = "SELECT COUNT(*) FROM exercice WHERE programme_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$programme_id]);
        $total  = $stmt->fetchColumn();
        $statut = ($total == 0) ? 'en_cours' : 'en_attente';

        $sql = "INSERT INTO exercice (nom, description, ordre, duree_minutes, video_url, programme_id, statut)
                VALUES (:nom, :description, :ordre, :duree_minutes, :video_url, :programme_id, :statut)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom'           => trim($_POST['nom']),
            ':description'   => trim($_POST['description'] ?? ''),
            ':ordre'         => intval($_POST['ordre']),
            ':duree_minutes' => intval($_POST['duree_minutes'] ?? 0),
            ':video_url'     => trim($_POST['video_url'] ?? ''),
            ':programme_id'  => $programme_id,
            ':statut'        => $statut,
        ]);

        if ($programme_id > 0) {
            header("Location: index.php?module=programme&action=show&id=$programme_id&office=back");
        } else {
            header("Location: index.php?module=objectif&action=index&office=back");
        }
        exit;
    }

    public function edit($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $errors = [];
        $sql = "SELECT e.*, p.nom AS programme_nom 
                FROM exercice e 
                LEFT JOIN programme p ON e.programme_id = p.id 
                WHERE e.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([intval($_GET['id'])]);
        $exercice = $stmt->fetch();

        if (!$exercice) {
            header("Location: index.php?module=objectif&action=index&office=back"); exit;
        }
        $sql = "SELECT * FROM programme ORDER BY nom";
        $programmes = $this->pdo->query($sql)->fetchAll();
        require __DIR__ . "/../views/back/exercices/edit.php";
    }

    public function update($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $id           = intval($_POST['id']);
        $errors       = $this->valider($_POST);
        $programme_id = intval($_POST['programme_id'] ?? 0);

        if (!empty($errors)) {
            $sql = "SELECT e.*, p.nom AS programme_nom 
                    FROM exercice e 
                    LEFT JOIN programme p ON e.programme_id = p.id 
                    WHERE e.id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            $exercice   = $stmt->fetch();
            $programmes = $this->pdo->query("SELECT * FROM programme ORDER BY nom")->fetchAll();
            require __DIR__ . "/../views/back/exercices/edit.php";
            return;
        }

        $sql = "UPDATE exercice SET nom=:nom, description=:description,
                ordre=:ordre, duree_minutes=:duree_minutes,
                video_url=:video_url, statut=:statut, programme_id=:programme_id
                WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom'           => trim($_POST['nom']),
            ':description'   => trim($_POST['description'] ?? ''),
            ':ordre'         => intval($_POST['ordre']),
            ':duree_minutes' => intval($_POST['duree_minutes'] ?? 0),
            ':video_url'     => trim($_POST['video_url'] ?? ''),
            ':statut'        => $_POST['statut'] ?? 'en_attente',
            ':programme_id'  => $programme_id,
            ':id'            => $id,
        ]);

        if ($programme_id > 0) {
            header("Location: index.php?module=programme&action=show&id=$programme_id&office=back");
        } else {
            header("Location: index.php?module=objectif&action=index&office=back");
        }
        exit;
    }

    public function delete($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $programme_id = intval($_GET['programme_id'] ?? 0);
        $stmt = $this->pdo->prepare("DELETE FROM exercice WHERE id = ?");
        $stmt->execute([intval($_GET['id'])]);

        if ($programme_id > 0) {
            header("Location: index.php?module=programme&action=show&id=$programme_id&office=back");
        } else {
            header("Location: index.php?module=objectif&action=index&office=back");
        }
        exit;
    }

    public function indexByProgramme($office = 'front') {
        $programme_id = intval($_GET['programme_id'] ?? 0);

        if ($programme_id > 0) {
            $sql = "SELECT e.*, p.nom AS programme_nom 
                    FROM exercice e
                    LEFT JOIN programme p ON e.programme_id = p.id
                    WHERE e.programme_id = ?
                    ORDER BY e.ordre";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$programme_id]);
            $exercices = $stmt->fetchAll();
        } else {
            $sql = "SELECT e.*, p.nom AS programme_nom 
                    FROM exercice e
                    LEFT JOIN programme p ON e.programme_id = p.id
                    ORDER BY p.nom, e.ordre";
            $stmt      = $this->pdo->query($sql);
            $exercices = $stmt->fetchAll();
        }
        require __DIR__ . "/../views/front/exercices/index.php";
    }

    /**
     * ✅ MODIFIÉ — enregistre date_validation = NOW() quand l'exercice est validé
     */
    public function validerEtape($office = 'front') {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php"); exit;
        }

        $exercice_id  = intval($_GET['id']);
        $programme_id = intval($_GET['programme_id']);

        $sql  = "SELECT * FROM exercice WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$exercice_id]);
        $exercice = $stmt->fetch();

        if ($exercice && $exercice['statut'] === 'en_cours') {
            // ← date_validation ajoutée ici
            $sql  = "UPDATE exercice SET statut = 'termine', date_validation = NOW() WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$exercice_id]);
            $this->activerExerciceSuivant($programme_id, $exercice['ordre']);
        }

        header("Location: index.php?module=exercice&action=indexByProgramme&programme_id=$programme_id&office=front");
        exit;
    }

    public function resetProgramme($office = 'front') {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php"); exit;
        }

        $programme_id = intval($_GET['programme_id']);
        $sql  = "SELECT * FROM exercice WHERE programme_id = ? ORDER BY ordre ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$programme_id]);
        $exercices = $stmt->fetchAll();

        $premier = true;
        foreach ($exercices as $ex) {
            $nouveauStatut = $premier ? 'en_cours' : 'en_attente';
            // Reset aussi date_validation
            $sql  = "UPDATE exercice SET statut = :statut, date_validation = NULL WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':statut' => $nouveauStatut, ':id' => $ex['id']]);
            $premier = false;
        }

        header("Location: index.php?module=exercice&action=indexByProgramme&programme_id=$programme_id&office=front");
        exit;
    }

    // ════════════════════════════════════════════════════════
    // CRUD utilisateur sur note_user / performance
    // ════════════════════════════════════════════════════════

    public function showExercice($office = 'front') {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php"); exit;
        }

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: index.php?module=exercice&action=index&office=front"); exit;
        }

        $sql = "SELECT e.*, p.nom AS programme_nom
                FROM exercice e
                LEFT JOIN programme p ON e.programme_id = p.id
                WHERE e.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $exercice = $stmt->fetch();

        if (!$exercice) {
            header("Location: index.php?module=exercice&action=index&office=front"); exit;
        }

        $success = $_SESSION['success'] ?? null;
        $error   = $_SESSION['error']   ?? null;
        unset($_SESSION['success'], $_SESSION['error']);

        require __DIR__ . "/../views/front/exercices/show.php";
    }

    public function savePerformance($office = 'front') {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php"); exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?module=exercice&action=index&office=front"); exit;
        }

        $id       = intval($_POST['exercice_id'] ?? 0);
        $reps     = trim($_POST['repetitions_realisees'] ?? '');
        $poids    = trim($_POST['poids_utilise'] ?? '');
        $ressenti = trim($_POST['ressenti'] ?? '');
        $note     = trim($_POST['note_user'] ?? '');

        $errors = [];
        if ($id <= 0) $errors[] = "Identifiant d'exercice invalide.";
        if ($reps === '' && $poids === '' && $ressenti === '' && $note === '') $errors[] = "Veuillez remplir au moins un champ.";

        if ($reps !== '') {
            if (!ctype_digit($reps) || intval($reps) < 1) $errors[] = "Les répétitions doivent être un nombre entier positif.";
            elseif (intval($reps) > 9999)                 $errors[] = "Les répétitions ne peuvent pas dépasser 9999.";
        }
        if ($poids !== '') {
            if (!is_numeric($poids) || floatval($poids) <= 0) $errors[] = "Le poids doit être un nombre positif.";
            elseif (floatval($poids) > 999)                   $errors[] = "Le poids ne peut pas dépasser 999 kg.";
        }
        if (!in_array($ressenti, ['', 'facile', 'moyen', 'difficile'], true)) $errors[] = "Ressenti invalide.";
        if ($note !== '' && mb_strlen($note) > 1000) $errors[] = "La note ne doit pas dépasser 1000 caractères.";
        if ($note !== '' && $note !== strip_tags($note)) $errors[] = "La note ne doit pas contenir de balises HTML.";

        if (!empty($errors)) {
            $_SESSION['error'] = implode(' ', $errors);
            header("Location: index.php?module=exercice&action=showExercice&id={$id}&office=front"); exit;
        }

        $stmt = $this->pdo->prepare("SELECT id FROM exercice WHERE id = ?");
        $stmt->execute([$id]);
        if (!$stmt->fetch()) {
            $_SESSION['error'] = "Exercice introuvable.";
            header("Location: index.php?module=exercice&action=index&office=front"); exit;
        }

        $sql = "UPDATE exercice
                SET repetitions_realisees = :reps,
                    poids_utilise         = :poids,
                    ressenti              = :ressenti,
                    note_user             = :note
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $ok   = $stmt->execute([
            ':reps'     => $reps     !== '' ? intval($reps)                                : null,
            ':poids'    => $poids    !== '' ? floatval($poids)                             : null,
            ':ressenti' => $ressenti !== '' ? $ressenti                                    : null,
            ':note'     => $note     !== '' ? htmlspecialchars($note, ENT_QUOTES, 'UTF-8') : null,
            ':id'       => $id,
        ]);

        $_SESSION[$ok ? 'success' : 'error'] = $ok ? "Performance enregistrée." : "Erreur lors de l'enregistrement.";
        header("Location: index.php?module=exercice&action=showExercice&id={$id}&office=front");
        exit;
    }

    public function resetPerformance($office = 'front') {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php"); exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?module=exercice&action=index&office=front"); exit;
        }

        $id = intval($_POST['exercice_id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error'] = "Identifiant invalide.";
            header("Location: index.php?module=exercice&action=index&office=front"); exit;
        }

        $stmt = $this->pdo->prepare("SELECT repetitions_realisees, poids_utilise, ressenti, note_user FROM exercice WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if (!$row) {
            $_SESSION['error'] = "Exercice introuvable.";
            header("Location: index.php?module=exercice&action=index&office=front"); exit;
        }

        if ($row['repetitions_realisees'] === null && $row['poids_utilise'] === null && $row['ressenti'] === null && $row['note_user'] === null) {
            $_SESSION['error'] = "Aucune donnée à réinitialiser.";
            header("Location: index.php?module=exercice&action=showExercice&id={$id}&office=front"); exit;
        }

        $sql  = "UPDATE exercice SET repetitions_realisees=NULL, poids_utilise=NULL, ressenti=NULL, note_user=NULL WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        $ok   = $stmt->execute([':id' => $id]);

        $_SESSION[$ok ? 'success' : 'error'] = $ok ? "Données réinitialisées." : "Erreur lors de la réinitialisation.";
        header("Location: index.php?module=exercice&action=showExercice&id={$id}&office=front");
        exit;
    }

    // ════════════════════════════════════════════════════════
    // MÉTHODES PRIVÉES
    // ════════════════════════════════════════════════════════

    private function activerExerciceSuivant($programme_id, $ordre_actuel) {
        $sql  = "SELECT * FROM exercice WHERE programme_id = ? AND ordre = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$programme_id, $ordre_actuel + 1]);
        $suivant = $stmt->fetch();

        if ($suivant) {
            $sql  = "UPDATE exercice SET statut = 'en_cours' WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$suivant['id']]);
        }
    }

    private function valider($data) {
        $errors = [];
        if (empty(trim($data['nom'] ?? '')))                                                                            $errors['nom']           = "Le nom est obligatoire.";
        elseif (strlen(trim($data['nom'])) < 2)                                                                         $errors['nom']           = "Le nom doit avoir au moins 2 caracteres.";
        if (empty($data['programme_id']) || !is_numeric($data['programme_id']) || $data['programme_id'] < 1)           $errors['programme_id']  = "Veuillez choisir un programme.";
        if (empty($data['ordre']) || !is_numeric($data['ordre']) || intval($data['ordre']) < 1)                        $errors['ordre']         = "L'ordre doit etre un nombre entier positif.";
        if (!empty($data['duree_minutes']) && (!is_numeric($data['duree_minutes']) || intval($data['duree_minutes']) < 1)) $errors['duree_minutes'] = "La duree doit etre un nombre de minutes positif.";
        if (!empty($data['video_url']) && !filter_var($data['video_url'], FILTER_VALIDATE_URL))                        $errors['video_url']     = "L'URL de la video est invalide.";
        return $errors;
    }
}
?>