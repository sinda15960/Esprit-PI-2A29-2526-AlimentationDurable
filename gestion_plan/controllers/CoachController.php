<?php
require_once __DIR__ . '/../config.php';

class CoachController {
    private $pdo;

    public function __construct() {
        $this->pdo = getConnection();
    }

    public function index($office = 'front') {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php"); exit;
        }

        $user_id = intval($_SESSION['user_id']);

        // ── Profil utilisateur ───────────────────────
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        // ── Objectif personnel ───────────────────────
        $stmt = $this->pdo->prepare(
            "SELECT * FROM objectif WHERE user_id = ? AND is_personal = 1
             ORDER BY date_creation DESC LIMIT 1"
        );
        $stmt->execute([$user_id]);
        $objectif = $stmt->fetch();

        // ── IMC ──────────────────────────────────────
        $imc = null;
        if ($objectif && $objectif['poids_actuel'] && $objectif['taille']) {
            $imc = round($objectif['poids_actuel'] / ($objectif['taille'] ** 2), 1);
        }

        // ── Progression exercices ────────────────────
        $stmt = $this->pdo->prepare(
            "SELECT 
                COUNT(*) AS total,
                SUM(CASE WHEN e.statut = 'termine' THEN 1 ELSE 0 END) AS termines,
                SUM(CASE WHEN e.ressenti = 'facile'    THEN 1 ELSE 0 END) AS nb_facile,
                SUM(CASE WHEN e.ressenti = 'moyen'     THEN 1 ELSE 0 END) AS nb_moyen,
                SUM(CASE WHEN e.ressenti = 'difficile' THEN 1 ELSE 0 END) AS nb_difficile,
                ROUND(AVG(e.repetitions_realisees), 1) AS moy_reps,
                ROUND(AVG(e.poids_utilise), 1)         AS moy_poids,
                COALESCE(SUM(CASE WHEN e.statut='termine' THEN e.duree_minutes ELSE 0 END), 0) AS total_minutes
             FROM exercice e
             JOIN programme p ON e.programme_id = p.id
             JOIN objectif o  ON p.objectif_id  = o.id
             WHERE o.user_id = ? AND o.is_personal = 1"
        );
        $stmt->execute([$user_id]);
        $stats = $stmt->fetch();

        // ── Dernier programme ────────────────────────
        $stmt = $this->pdo->prepare(
            "SELECT p.nom, p.niveau, p.duree_semaines
             FROM programme p
             JOIN objectif o ON p.objectif_id = o.id
             WHERE o.user_id = ? AND o.is_personal = 1
             ORDER BY p.date_creation DESC LIMIT 1"
        );
        $stmt->execute([$user_id]);
        $programme = $stmt->fetch();

        // ── Dernière activité ────────────────────────
        $stmt = $this->pdo->prepare(
            "SELECT MAX(e.date_validation) AS derniere
             FROM exercice e
             JOIN programme p ON e.programme_id = p.id
             JOIN objectif o  ON p.objectif_id  = o.id
             WHERE o.user_id = ? AND o.is_personal = 1"
        );
        $stmt->execute([$user_id]);
        $derniere = $stmt->fetchColumn();
        $joursInactif = $derniere ? (int)(new DateTime())->diff(new DateTime($derniere))->days : null;

        // ── Streak ───────────────────────────────────
        $stmt = $this->pdo->prepare(
            "SELECT DISTINCT DATE(e.date_validation) AS jour
             FROM exercice e
             JOIN programme p ON e.programme_id = p.id
             JOIN objectif o  ON p.objectif_id  = o.id
             WHERE o.user_id = ? AND o.is_personal = 1
               AND e.date_validation IS NOT NULL
             ORDER BY jour DESC"
        );
        $stmt->execute([$user_id]);
        $jours   = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $streak  = 0;
        $dateRef = new DateTime('today');
        foreach ($jours as $jour) {
            $dateJour = new DateTime($jour);
            $diff     = (int)$dateRef->diff($dateJour)->days;
            if ($diff === 0 || $diff === 1) { $streak++; $dateRef = $dateJour; }
            else break;
        }

        // ── Niveau automatique ───────────────────────
        $termines = (int)($stats['termines'] ?? 0);
        $niveau   = $termines >= 20 ? 'Avancé' : ($termines >= 8 ? 'Intermédiaire' : 'Débutant');

        // ── Construction du contexte pour l'IA ───────
        $contexte = $this->construireContexte(
            $user, $objectif, $imc, $stats, $programme,
            $joursInactif, $streak, $niveau
        );

        require __DIR__ . "/../views/front/coach/index.php";
    }

    /**
     * Construit le prompt système avec toutes les données de l'utilisateur
     */
    private function construireContexte($user, $objectif, $imc, $stats, $programme, $joursInactif, $streak, $niveau): string {
        $nom = htmlspecialchars($user['nom'] ?? 'l\'utilisateur');

        $ctx = "Tu es un coach sportif et nutritionniste personnel expert, bienveillant et motivant. ";
        $ctx .= "Tu t'appelles CoachAI. Tu parles en français. Tu es précis, professionnel et encourageant. ";
        $ctx .= "Tu connais parfaitement le profil de ton client. Voici toutes ses données :\n\n";

        $ctx .= "=== PROFIL ===\n";
        $ctx .= "Nom : {$nom}\n";

        if ($objectif) {
            $ctx .= "Objectif : " . ($objectif['titre'] ?? 'Non défini') . "\n";
            $ctx .= "Type d'objectif : " . ($objectif['type_objectif'] ?? 'Non défini') . "\n";
            if ($objectif['poids_actuel']) $ctx .= "Poids actuel : {$objectif['poids_actuel']} kg\n";
            if ($objectif['poids_cible'])  $ctx .= "Poids cible : {$objectif['poids_cible']} kg\n";
            if ($objectif['taille'])       $ctx .= "Taille : {$objectif['taille']} m\n";
            if ($imc)                      $ctx .= "IMC : {$imc}\n";
            if ($objectif['date_debut'])   $ctx .= "Date début : {$objectif['date_debut']}\n";
            if ($objectif['date_fin_prevue']) $ctx .= "Date fin prévue : {$objectif['date_fin_prevue']}\n";
            if ($objectif['maladies'])     $ctx .= "Maladies / conditions : {$objectif['maladies']}\n";
            if ($objectif['preferences'])  $ctx .= "Préférences alimentaires : {$objectif['preferences']}\n";
        }

        $ctx .= "\n=== NIVEAU & PROGRESSION ===\n";
        $ctx .= "Niveau : {$niveau}\n";
        $ctx .= "Exercices terminés : " . ($stats['termines'] ?? 0) . " / " . ($stats['total'] ?? 0) . "\n";
        $ctx .= "Temps total d'entraînement : " . ($stats['total_minutes'] ?? 0) . " minutes\n";
        $ctx .= "Streak actuel : {$streak} jour(s) consécutifs\n";

        if ($joursInactif !== null) {
            $ctx .= "Dernière activité : il y a {$joursInactif} jour(s)\n";
        }

        if ($programme) {
            $ctx .= "\n=== PROGRAMME ACTUEL ===\n";
            $ctx .= "Nom : " . ($programme['nom'] ?? 'Aucun') . "\n";
            $ctx .= "Niveau programme : " . ($programme['niveau'] ?? 'Non défini') . "\n";
            $ctx .= "Durée : " . ($programme['duree_semaines'] ?? '?') . " semaine(s)\n";
        }

        $ctx .= "\n=== RESSENTIS SUR LES EXERCICES ===\n";
        $ctx .= "Exercices trouvés faciles : " . ($stats['nb_facile'] ?? 0) . "\n";
        $ctx .= "Exercices trouvés moyens : " . ($stats['nb_moyen'] ?? 0) . "\n";
        $ctx .= "Exercices trouvés difficiles : " . ($stats['nb_difficile'] ?? 0) . "\n";

        if ($stats['moy_reps'])  $ctx .= "Répétitions moyennes réalisées : " . ($stats['moy_reps'] ?? 0) . "\n";
        if ($stats['moy_poids']) $ctx .= "Poids moyen utilisé : " . ($stats['moy_poids'] ?? 0) . " kg\n";

        $ctx .= "\n=== INSTRUCTIONS ===\n";
        $ctx .= "- Réponds toujours de façon personnalisée en utilisant le prénom du client.\n";
        $ctx .= "- Si le client parle d'une douleur ou blessure, conseille-lui de consulter un médecin.\n";
        $ctx .= "- Donne des conseils nutritionnels adaptés à son objectif et ses préférences.\n";
        $ctx .= "- Si inactif depuis plus de 5 jours, encourage-le à reprendre doucement.\n";
        $ctx .= "- Propose des exercices ou ajustements concrets basés sur ses données réelles.\n";
        $ctx .= "- Ne réponds jamais hors du domaine sport, nutrition et bien-être.\n";
        $ctx .= "- Garde un ton motivant, positif et professionnel.\n";

        return $ctx;
    }
}
?>