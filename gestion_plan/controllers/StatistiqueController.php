<?php
require_once __DIR__ . '/../config.php';

class StatistiqueController {
    private $pdo;

    public function __construct() {
        $this->pdo = getConnection();
    }

    // ─── REQUETES SQL ─────────────────────────────────────────────

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
        $sql = "SELECT 
                    COUNT(*) AS total,
                    SUM(CASE WHEN statut = 'termine' THEN 1 ELSE 0 END) AS termines
                FROM exercice WHERE programme_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$programme_id]);
        return $stmt->fetch();
    }

    private function dbGetProgrammesRecommandes(string $type_objectif): array {
        // Mapping type_objectif → niveau recommandé
        $niveauMap = [
            'maigrir'   => ['debutant', 'intermediaire'],
            'muscler'   => ['intermediaire', 'avance'],
            'maintenir' => ['debutant', 'intermediaire'],
            'grossir'   => ['intermediaire', 'avance'],
        ];
        $niveaux = $niveauMap[$type_objectif] ?? ['debutant'];
        $placeholders = implode(',', array_fill(0, count($niveaux), '?'));

        $sql = "SELECT p.*, o.titre AS objectif_titre, o.type_objectif, c.nom AS categorie_nom
                FROM programme p
                LEFT JOIN objectif o ON p.objectif_id = o.id
                LEFT JOIN categorie c ON p.categorie_id = c.id_categorie
                WHERE p.niveau IN ($placeholders)
                ORDER BY p.date_creation DESC
                LIMIT 4";
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

    // ─── CALCUL IMC ───────────────────────────────────────────────

    private function calculerIMC(?float $poids, ?float $taille): ?float {
        if (!$poids || !$taille || $taille == 0) return null;
        return round($poids / ($taille * $taille), 1);
    }

    private function categorieIMC(?float $imc): array {
        if ($imc === null) return ['label' => 'Non calculable', 'color' => 'secondary', 'conseil' => ''];
        if ($imc < 18.5) return [
            'label'  => 'Insuffisance pondérale',
            'color'  => 'info',
            'conseil'=> 'Vous êtes en sous-poids. Consultez un nutritionniste pour un plan alimentaire adapté.'
        ];
        if ($imc < 25) return [
            'label'  => 'Poids normal',
            'color'  => 'success',
            'conseil'=> 'Votre poids est dans la norme. Maintenez une alimentation équilibrée et une activité régulière.'
        ];
        if ($imc < 30) return [
            'label'  => 'Surpoids',
            'color'  => 'warning',
            'conseil'=> 'Vous êtes en surpoids. Un programme cardio associé à une alimentation contrôlée est recommandé.'
        ];
        return [
            'label'  => 'Obésité',
            'color'  => 'danger',
            'conseil'=> 'Consultez un médecin ou nutritionniste. Un suivi personnalisé est fortement recommandé.'
        ];
    }

    // ─── ACTION PRINCIPALE ────────────────────────────────────────

    public function index($office = 'front') {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php"); exit;
        }

        $user_id = $_SESSION['user_id'];

        // Objectif personnel
        $objectif = $this->dbGetObjectifPersonnel($user_id);

        // IMC
        $imc = null;
        $imcCategorie = ['label' => 'Non calculable', 'color' => 'secondary', 'conseil' => ''];
        if ($objectif) {
            $imc = $this->calculerIMC($objectif['poids_actuel'], $objectif['taille']);
            $imcCategorie = $this->categorieIMC($imc);
        }

        // Progression globale
        $exercicesTermines = $this->dbGetExercicesTerminesUser($user_id);
        $exercicesTotal    = $this->dbGetTotalExercicesUser($user_id);
        $pourcentage = $exercicesTotal > 0 ? round(($exercicesTermines / $exercicesTotal) * 100) : 0;

        // Progression par programme
        $programmes = $this->dbGetProgrammesUser($user_id);
        $progressions = [];
        foreach ($programmes as $p) {
            $prog = $this->dbGetProgressionProgramme($p['id']);
            $pct  = $prog['total'] > 0 ? round(($prog['termines'] / $prog['total']) * 100) : 0;
            $progressions[] = [
                'nom'      => $p['nom'],
                'total'    => $prog['total'],
                'termines' => $prog['termines'],
                'pct'      => $pct,
            ];
        }

        // Recommandations automatiques
        $recommandations = [];
        if ($objectif) {
            // Cherche le type_objectif de l'objectif officiel lié si disponible
            $sqlTypeObj = "SELECT o.type_objectif FROM objectif o 
                           JOIN programme p ON p.objectif_id = o.id
                           WHERE o.is_personal = 0
                           LIMIT 1";
            $typeObj = $this->pdo->query($sqlTypeObj)->fetchColumn();

            // Sinon déduire depuis poids actuel/cible
            if (!$typeObj && $objectif) {
                $diff = ($objectif['poids_actuel'] ?? 0) - ($objectif['poids_cible'] ?? 0);
                if ($diff > 2)       $typeObj = 'maigrir';
                elseif ($diff < -2)  $typeObj = 'grossir';
                else                 $typeObj = 'maintenir';
            }

            if ($typeObj) {
                $recommandations = $this->dbGetProgrammesRecommandes($typeObj);
            }
        }

        // Stats globales
        $totalProgrammes = $this->dbGetTotalProgrammes();
        $totalExercices  = $this->dbGetTotalExercices();

        require __DIR__ . "/../views/front/statistiques/index.php";
    }
}
?>