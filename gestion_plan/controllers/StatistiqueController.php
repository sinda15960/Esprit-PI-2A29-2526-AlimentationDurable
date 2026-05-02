<?php
require_once __DIR__ . '/../config.php';

class StatistiqueController {
    private $pdo;

    public function __construct() {
        $this->pdo = getConnection();
    }

    // ════════════════════════════════════════════════════════
    // MÉTHODES PRIVÉES EXISTANTES — non modifiées
    // ════════════════════════════════════════════════════════

    private function dbGetObjectifPersonnel(int $user_id): array|false {
        $sql = "SELECT * FROM objectif WHERE user_id = ? AND is_personal = 1 ORDER BY date_creation DESC LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetch();
    }

    private function dbGetProgrammesUser(int $user_id): array {
        $sql = "SELECT p.*, o.titre AS objectif_titre, o.type_objectif
                FROM programme p
                JOIN objectif o ON p.objectif_id = o.id
                WHERE o.user_id = ? AND o.is_personal = 1
                ORDER BY p.date_creation DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    private function dbGetProgressionProgramme(int $programme_id): array {
        $sql = "SELECT COUNT(*) AS total,
                    SUM(CASE WHEN statut = 'termine' THEN 1 ELSE 0 END) AS termines
                FROM exercice WHERE programme_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$programme_id]);
        return $stmt->fetch();
    }

    private function dbGetProgrammesRecommandes(string $type_objectif): array {
        $niveauMap = [
            'maigrir'   => ['debutant', 'intermediaire'],
            'muscler'   => ['intermediaire', 'avance'],
            'maintenir' => ['debutant', 'intermediaire'],
            'grossir'   => ['intermediaire', 'avance'],
        ];
        $niveaux      = $niveauMap[$type_objectif] ?? ['debutant'];
        $placeholders = implode(',', array_fill(0, count($niveaux), '?'));
        $sql = "SELECT p.*, o.titre AS objectif_titre, o.type_objectif, c.nom AS categorie_nom
                FROM programme p
                LEFT JOIN objectif o ON p.objectif_id = o.id
                LEFT JOIN categorie c ON p.categorie_id = c.id_categorie
                WHERE p.niveau IN ($placeholders)
                ORDER BY p.date_creation DESC LIMIT 4";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($niveaux);
        return $stmt->fetchAll();
    }

    private function dbGetTotalProgrammes(): int {
        return (int)$this->pdo->query("SELECT COUNT(*) FROM programme")->fetchColumn();
    }

    private function dbGetTotalExercices(): int {
        return (int)$this->pdo->query("SELECT COUNT(*) FROM exercice")->fetchColumn();
    }

    private function dbGetExercicesTerminesUser(int $user_id): int {
        $sql = "SELECT COUNT(*) FROM exercice e
                JOIN programme p ON e.programme_id = p.id
                JOIN objectif o ON p.objectif_id = o.id
                WHERE o.user_id = ? AND o.is_personal = 1 AND e.statut = 'termine'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return (int)$stmt->fetchColumn();
    }

    private function dbGetTotalExercicesUser(int $user_id): int {
        $sql = "SELECT COUNT(*) FROM exercice e
                JOIN programme p ON e.programme_id = p.id
                JOIN objectif o ON p.objectif_id = o.id
                WHERE o.user_id = ? AND o.is_personal = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return (int)$stmt->fetchColumn();
    }

    private function calculerIMC(?float $poids, ?float $taille): ?float {
        if (!$poids || !$taille || $taille == 0) return null;
        return round($poids / ($taille * $taille), 1);
    }

    private function categorieIMC(?float $imc): array {
        if ($imc === null) return ['label' => 'Non calculable', 'color' => 'secondary', 'conseil' => ''];
        if ($imc < 18.5)  return ['label' => 'Insuffisance pondérale', 'color' => 'info',    'conseil' => 'Vous êtes en sous-poids. Consultez un nutritionniste pour un plan alimentaire adapté.'];
        if ($imc < 25)    return ['label' => 'Poids normal',           'color' => 'success', 'conseil' => 'Votre poids est dans la norme. Maintenez une alimentation équilibrée et une activité régulière.'];
        if ($imc < 30)    return ['label' => 'Surpoids',               'color' => 'warning', 'conseil' => 'Vous êtes en surpoids. Un programme cardio associé à une alimentation contrôlée est recommandé.'];
        return                   ['label' => 'Obésité',                'color' => 'danger',  'conseil' => 'Consultez un médecin ou nutritionniste. Un suivi personnalisé est fortement recommandé.'];
    }

    // ════════════════════════════════════════════════════════
    // NOUVELLES MÉTHODES PRIVÉES — règles métier
    // ════════════════════════════════════════════════════════

    /**
     * Streak : jours consécutifs avec au moins 1 exercice validé
     */
    private function calculerStreak(int $user_id): int {
        $sql = "SELECT DISTINCT DATE(e.date_validation) AS jour
                FROM exercice e
                JOIN programme p ON e.programme_id = p.id
                JOIN objectif o ON p.objectif_id = o.id
                WHERE o.user_id = ? AND o.is_personal = 1
                  AND e.date_validation IS NOT NULL
                ORDER BY jour DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        $jours = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (empty($jours)) return 0;

        $streak  = 0;
        $dateRef = new DateTime('today');

        foreach ($jours as $jour) {
            $dateJour = new DateTime($jour);
            $diff     = (int)$dateRef->diff($dateJour)->days;
            if ($diff === 0 || $diff === 1) {
                $streak++;
                $dateRef = $dateJour;
            } else {
                break;
            }
        }

        return $streak;
    }

    /**
     * Score de régularité sur 30 jours
     */
    private function calculerScoreRegularite(int $user_id): array {
        $totalPrevus = $this->dbGetTotalExercicesUser($user_id);

        $sql = "SELECT COUNT(*) FROM exercice e
                JOIN programme p ON e.programme_id = p.id
                JOIN objectif o ON p.objectif_id = o.id
                WHERE o.user_id = ? AND o.is_personal = 1
                  AND e.date_validation >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        $valides = (int)$stmt->fetchColumn();

        $score = $totalPrevus > 0 ? min(round(($valides / $totalPrevus) * 100), 100) : 0;

        $mention = match(true) {
            $score >= 80 => ['label' => '🏆 Excellent',  'color' => 'success'],
            $score >= 60 => ['label' => '👍 Bon',         'color' => 'primary'],
            $score >= 40 => ['label' => '😐 Moyen',       'color' => 'warning'],
            default      => ['label' => '📉 Faible',      'color' => 'danger'],
        };

        return ['score' => $score, 'mention' => $mention, 'valides' => $valides, 'total' => $totalPrevus];
    }

    /**
     * Alerte retard : pas d'exercice depuis 5+ jours
     */
    private function verifierAlerte(int $user_id): array {
        $sql = "SELECT MAX(e.date_validation)
                FROM exercice e
                JOIN programme p ON e.programme_id = p.id
                JOIN objectif o ON p.objectif_id = o.id
                WHERE o.user_id = ? AND o.is_personal = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        $derniere = $stmt->fetchColumn();

        if (!$derniere) {
            return ['retard' => true, 'jours' => null, 'message' => "Vous n'avez pas encore commencé. Lancez-vous !"];
        }

        $jours = (int)(new DateTime())->diff(new DateTime($derniere))->days;

        if ($jours >= 7) return ['retard' => true,  'jours' => $jours, 'message' => "⚠️ Vous êtes en retard de {$jours} jours. Reprenez votre programme !"];
        if ($jours >= 5) return ['retard' => true,  'jours' => $jours, 'message' => "🕐 Vous êtes en retard, reprenez votre programme."];
        return               ['retard' => false, 'jours' => $jours, 'message' => ''];
    }

    /**
     * Temps total entraînement ce mois-ci (minutes)
     */
    private function calculerTempsTotalMois(int $user_id): int {
        $sql = "SELECT COALESCE(SUM(e.duree_minutes), 0)
                FROM exercice e
                JOIN programme p ON e.programme_id = p.id
                JOIN objectif o ON p.objectif_id = o.id
                WHERE o.user_id = ? AND o.is_personal = 1
                  AND e.statut = 'termine'
                  AND MONTH(e.date_validation) = MONTH(NOW())
                  AND YEAR(e.date_validation)  = YEAR(NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Niveau utilisateur selon exercices terminés
     */
    private function calculerNiveau(int $termines): array {
        return match(true) {
            $termines >= 20 => ['label' => '🔥 Avancé',        'color' => 'danger'],
            $termines >= 8  => ['label' => '💪 Intermédiaire', 'color' => 'warning'],
            default         => ['label' => '🌱 Débutant',      'color' => 'success'],
        };
    }

    // ════════════════════════════════════════════════════════
    // ACTION FRONT — statistiques utilisateur
    // ════════════════════════════════════════════════════════

    public function index($office = 'front') {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php"); exit;
        }

        $user_id = $_SESSION['user_id'];

        $objectif     = $this->dbGetObjectifPersonnel($user_id);
        $imc          = null;
        $imcCategorie = ['label' => 'Non calculable', 'color' => 'secondary', 'conseil' => ''];

        if ($objectif) {
            $imc          = $this->calculerIMC($objectif['poids_actuel'], $objectif['taille']);
            $imcCategorie = $this->categorieIMC($imc);
        }

        $exercicesTermines = $this->dbGetExercicesTerminesUser($user_id);
        $exercicesTotal    = $this->dbGetTotalExercicesUser($user_id);
        $pourcentage       = $exercicesTotal > 0 ? round(($exercicesTermines / $exercicesTotal) * 100) : 0;

        $programmes   = $this->dbGetProgrammesUser($user_id);
        $progressions = [];
        foreach ($programmes as $p) {
            $prog           = $this->dbGetProgressionProgramme($p['id']);
            $pct            = $prog['total'] > 0 ? round(($prog['termines'] / $prog['total']) * 100) : 0;
            $progressions[] = ['nom' => $p['nom'], 'total' => $prog['total'], 'termines' => $prog['termines'], 'pct' => $pct];
        }

        $recommandations = [];
        if ($objectif) {
            $sqlTypeObj = "SELECT o.type_objectif FROM objectif o 
                           JOIN programme p ON p.objectif_id = o.id
                           WHERE o.is_personal = 0 LIMIT 1";
            $typeObj = $this->pdo->query($sqlTypeObj)->fetchColumn();
            if (!$typeObj) {
                $diff = ($objectif['poids_actuel'] ?? 0) - ($objectif['poids_cible'] ?? 0);
                if ($diff > 2)      $typeObj = 'maigrir';
                elseif ($diff < -2) $typeObj = 'grossir';
                else                $typeObj = 'maintenir';
            }
            if ($typeObj) $recommandations = $this->dbGetProgrammesRecommandes($typeObj);
        }

        $totalProgrammes = $this->dbGetTotalProgrammes();
        $totalExercices  = $this->dbGetTotalExercices();

        // Nouvelles données métier
        $streak     = $this->calculerStreak($user_id);
        $regularite = $this->calculerScoreRegularite($user_id);
        $alerte     = $this->verifierAlerte($user_id);
        $tempsMois  = $this->calculerTempsTotalMois($user_id);
        $niveau     = $this->calculerNiveau($exercicesTermines);

        require __DIR__ . "/../views/front/statistiques/index.php";
    }

    // ════════════════════════════════════════════════════════
    // ACTION BACK — tableau de bord admin
    // ════════════════════════════════════════════════════════

    public function indexBack($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }

        // KPIs globaux
        $totalUsers      = (int)$this->pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
        $totalExercices  = $this->dbGetTotalExercices();
        $totalProgrammes = $this->dbGetTotalProgrammes();
        $totalNotes      = (int)$this->pdo->query("SELECT COUNT(*) FROM exercice WHERE note_user IS NOT NULL AND note_user != ''")->fetchColumn();

        // Dashboard temps réel
        $validationsAujourdhui = (int)$this->pdo->query(
            "SELECT COUNT(*) FROM exercice WHERE DATE(date_validation) = CURDATE()"
        )->fetchColumn();

        $nouveauxUsersAujourdhui = (int)$this->pdo->query(
            "SELECT COUNT(*) FROM users WHERE DATE(date_creation) = CURDATE()"
        )->fetchColumn();

        $programmesActifsAujourdhui = (int)$this->pdo->query(
            "SELECT COUNT(DISTINCT programme_id) FROM exercice WHERE DATE(date_validation) = CURDATE()"
        )->fetchColumn();

        // Détection utilisateurs inactifs
        $sql = "SELECT u.id, u.nom, u.username,
                    MAX(e.date_validation) AS derniere_activite,
                    DATEDIFF(NOW(), MAX(e.date_validation)) AS jours_inactif,
                    COUNT(CASE WHEN e.statut = 'termine' THEN 1 END) AS termines
                FROM users u
                LEFT JOIN exercice e ON e.user_id = u.id
                WHERE u.role = 'user'
                GROUP BY u.id, u.nom, u.username
                ORDER BY jours_inactif DESC";
        $tousUsersActivite = $this->pdo->query($sql)->fetchAll();

        $inactifs3j  = array_values(array_filter($tousUsersActivite, fn($u) => ($u['jours_inactif'] ?? 999) >= 3  && ($u['jours_inactif'] ?? 999) < 7));
        $inactifs7j  = array_values(array_filter($tousUsersActivite, fn($u) => ($u['jours_inactif'] ?? 999) >= 7  && ($u['jours_inactif'] ?? 999) < 15));
        $inactifs15j = array_values(array_filter($tousUsersActivite, fn($u) => ($u['jours_inactif'] ?? 999) >= 15));

        // Classement meilleurs utilisateurs
        $sql = "SELECT u.nom, u.username,
                    COUNT(CASE WHEN e.statut = 'termine' THEN 1 END) AS termines,
                    COUNT(DISTINCT DATE(e.date_validation)) AS jours_actifs,
                    COALESCE(SUM(CASE WHEN e.statut = 'termine' THEN e.duree_minutes ELSE 0 END), 0) AS total_minutes
                FROM users u
                LEFT JOIN exercice e ON e.user_id = u.id
                WHERE u.role = 'user'
                GROUP BY u.id, u.nom, u.username
                ORDER BY termines DESC, jours_actifs DESC
                LIMIT 10";
        $classement = $this->pdo->query($sql)->fetchAll();

        // Taux abandon par programme
        $sql = "SELECT 
                    p.nom AS programme_nom,
                    COUNT(e.id) AS total_exercices,
                    SUM(CASE WHEN e.statut = 'termine'   THEN 1 ELSE 0 END) AS nb_termines,
                    SUM(CASE WHEN e.statut = 'en_cours'  THEN 1 ELSE 0 END) AS nb_en_cours,
                    SUM(CASE WHEN e.statut = 'en_attente'THEN 1 ELSE 0 END) AS nb_en_attente
                FROM programme p
                LEFT JOIN exercice e ON e.programme_id = p.id
                GROUP BY p.id, p.nom
                ORDER BY nb_termines DESC";
        $tauxAbandon = $this->pdo->query($sql)->fetchAll();

        // Stats par exercice
        $sql = "SELECT e.id, e.nom, e.statut, p.nom AS programme_nom,
                    ROUND(AVG(e.repetitions_realisees), 1) AS moy_reps,
                    ROUND(AVG(e.poids_utilise), 1)         AS moy_poids,
                    SUM(CASE WHEN e.ressenti = 'facile'    THEN 1 ELSE 0 END) AS nb_facile,
                    SUM(CASE WHEN e.ressenti = 'moyen'     THEN 1 ELSE 0 END) AS nb_moyen,
                    SUM(CASE WHEN e.ressenti = 'difficile' THEN 1 ELSE 0 END) AS nb_difficile,
                    COUNT(CASE WHEN e.note_user IS NOT NULL AND e.note_user != '' THEN 1 END) AS nb_notes
                FROM exercice e
                LEFT JOIN programme p ON e.programme_id = p.id
                GROUP BY e.id, e.nom, e.statut, p.nom
                ORDER BY nb_difficile DESC, e.nom ASC";
        $statsExercices = $this->pdo->query($sql)->fetchAll();

        // Exercices vides
        $sql = "SELECT e.nom, p.nom AS programme_nom, e.statut
                FROM exercice e
                LEFT JOIN programme p ON e.programme_id = p.id
                WHERE e.repetitions_realisees IS NULL
                  AND e.poids_utilise IS NULL
                  AND e.ressenti IS NULL
                  AND e.note_user IS NULL
                ORDER BY e.nom";
        $exercicesVides = $this->pdo->query($sql)->fetchAll();

        // Toutes les notes
        $sql = "SELECT e.id AS exercice_id, e.nom AS exercice_nom,
                    e.note_user, e.ressenti, e.repetitions_realisees, e.poids_utilise,
                    u.nom AS user_nom, u.username
                FROM exercice e
                LEFT JOIN users u ON e.user_id = u.id
                WHERE e.note_user IS NOT NULL AND e.note_user != ''
                ORDER BY e.nom ASC";
        $toutesLesNotes = $this->pdo->query($sql)->fetchAll();

        // Top difficiles
        $sql = "SELECT e.nom, p.nom AS programme_nom,
                    SUM(CASE WHEN e.ressenti = 'difficile' THEN 1 ELSE 0 END) AS nb_difficile
                FROM exercice e
                LEFT JOIN programme p ON e.programme_id = p.id
                WHERE e.ressenti IS NOT NULL
                GROUP BY e.id, e.nom, p.nom
                HAVING nb_difficile > 0
                ORDER BY nb_difficile DESC LIMIT 5";
        $topDifficiles = $this->pdo->query($sql)->fetchAll();

        // Progression par utilisateur
        $sql = "SELECT u.id, u.nom, u.username,
                    COUNT(e.id) AS total_exercices,
                    SUM(CASE WHEN e.statut = 'termine' THEN 1 ELSE 0 END) AS termines,
                    COUNT(CASE WHEN e.note_user IS NOT NULL AND e.note_user != '' THEN 1 END) AS nb_notes,
                    COUNT(CASE WHEN e.repetitions_realisees IS NOT NULL THEN 1 END) AS nb_reps
                FROM users u
                LEFT JOIN exercice e ON e.user_id = u.id
                WHERE u.role = 'user'
                GROUP BY u.id, u.nom, u.username
                ORDER BY termines DESC";
        $progressionUsers = $this->pdo->query($sql)->fetchAll();

        require __DIR__ . "/../views/back/statistiques/index.php";
    }
}
?>