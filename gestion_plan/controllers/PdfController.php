<?php
require_once __DIR__ . '/../config.php';

class PdfController {
    private $pdo;

    public function __construct() {
        $this->pdo = getConnection();
    }

    // ─── REQUETES SQL ─────────────────────────────────────────────

    private function dbGetObjectif(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM objectif WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    private function dbGetObjectifsOfficiels(): array {
        return $this->pdo->query("SELECT * FROM objectif WHERE is_personal = 0 ORDER BY date_creation DESC")->fetchAll();
    }

    private function dbGetObjectifPersonnelUser(int $user_id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM objectif WHERE user_id = ? AND is_personal = 1 ORDER BY date_creation DESC LIMIT 1");
        $stmt->execute([$user_id]);
        return $stmt->fetch();
    }

    private function dbGetProgrammesByObjectif(int $objectif_id): array {
        $sql = "SELECT p.*, c.nom AS categorie_nom
                FROM programme p
                LEFT JOIN categorie c ON p.categorie_id = c.id_categorie
                WHERE p.objectif_id = ?
                ORDER BY p.date_creation DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$objectif_id]);
        return $stmt->fetchAll();
    }

    private function dbGetExercicesByProgramme(int $programme_id): array {
        $stmt = $this->pdo->prepare("SELECT * FROM exercice WHERE programme_id = ? ORDER BY ordre ASC");
        $stmt->execute([$programme_id]);
        return $stmt->fetchAll();
    }

    private function dbGetProgramme(int $id): array|false {
        $sql = "SELECT p.*, o.titre AS objectif_titre, c.nom AS categorie_nom
                FROM programme p
                LEFT JOIN objectif o ON p.objectif_id = o.id
                LEFT JOIN categorie c ON p.categorie_id = c.id_categorie
                WHERE p.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    private function dbGetUserInfo(int $user_id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch();
    }

    // ─── CALCUL IMC ───────────────────────────────────────────────

    private function calculerIMC(?float $poids, ?float $taille): ?float {
        if (!$poids || !$taille || $taille == 0) return null;
        return round($poids / ($taille * $taille), 1);
    }

    private function categorieIMC(?float $imc): array {
        if ($imc === null) return ['label' => 'Non calculable', 'color' => '#888', 'conseil' => 'Données insuffisantes pour calculer l\'IMC.'];
        if ($imc < 18.5) return ['label' => 'Insuffisance pondérale', 'color' => '#17a2b8', 'conseil' => 'Vous êtes en sous-poids. Consultez un nutritionniste pour un plan alimentaire adapté.'];
        if ($imc < 25)   return ['label' => 'Poids normal ✓',         'color' => '#2d5a27', 'conseil' => 'Votre poids est dans la norme. Maintenez une alimentation équilibrée et une activité régulière.'];
        if ($imc < 30)   return ['label' => 'Surpoids',               'color' => '#ffc107', 'conseil' => 'Vous êtes en surpoids. Un programme cardio associé à une alimentation contrôlée est recommandé.'];
        return             ['label' => 'Obésité',                      'color' => '#dc3545', 'conseil' => 'Consultez un médecin ou nutritionniste. Un suivi personnalisé est fortement recommandé.'];
    }

    // ─── ACTIONS ──────────────────────────────────────────────────

    // Export complet d'un objectif personnel (depuis show_personal ou index)
    public function exportObjectif($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $id = intval($_GET['id'] ?? 0);
        $objectif = $this->dbGetObjectif($id);
        if (!$objectif) { echo "Objectif introuvable."; exit; }

        $programmes = $this->dbGetProgrammesByObjectif($id);
        $imc        = $this->calculerIMC($objectif['poids_actuel'], $objectif['taille']);
        $imcInfo    = $this->categorieIMC($imc);
        $user       = $this->dbGetUserInfo($objectif['user_id'] ?? 0);

        // Récupérer aussi les objectifs officiels associés
        $objectifsOfficiels = $this->dbGetObjectifsOfficiels();

        $html = $this->buildHtmlComplet($objectif, $programmes, $imc, $imcInfo, $user, $objectifsOfficiels);
        $this->renderHtml($html);
    }

    // Export d'un programme seul
    public function exportProgramme($office = 'back') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php"); exit;
        }
        $id        = intval($_GET['id'] ?? 0);
        $programme = $this->dbGetProgramme($id);
        if (!$programme) { echo "Programme introuvable."; exit; }

        $exercices = $this->dbGetExercicesByProgramme($id);
        $html      = $this->buildHtmlProgramme($programme, $exercices);
        $this->renderHtml($html);
    }

    // ─── BUILD HTML EXPORT COMPLET ────────────────────────────────

    private function buildHtmlComplet(array $obj, array $programmes, ?float $imc, array $imcInfo, $user, array $objectifsOfficiels): string {
        $dateExport  = date('d/m/Y à H:i');
        $titre       = htmlspecialchars($obj['titre']);
        $userId      = $obj['user_id'] ?? '-';
        $userNom     = $user ? htmlspecialchars($user['nom'] ?? 'User #' . $userId) : 'User #' . $userId;
        $poidsActuel = $obj['poids_actuel'] ? $obj['poids_actuel'] . ' kg' : '-';
        $poidsCible  = $obj['poids_cible']  ? $obj['poids_cible']  . ' kg' : '-';
        $taille      = $obj['taille']        ? $obj['taille']        . ' m'  : '-';
        $age         = $obj['age']           ? $obj['age']           . ' ans': '-';
        $etatSante   = htmlspecialchars($obj['etat_sante'] ?? '-');
        $description = htmlspecialchars($obj['description'] ?? '-');
        $dateDebut   = $obj['date_debut']      ?? '-';
        $dateFin     = $obj['date_fin_prevue'] ?? '-';
        $dateCreation = isset($obj['date_creation']) ? date('d/m/Y', strtotime($obj['date_creation'])) : '-';
        $imcVal      = $imc ?? 'N/A';
        $imcLabel    = htmlspecialchars($imcInfo['label']);
        $imcColor    = $imcInfo['color'];
        $imcConseil  = htmlspecialchars($imcInfo['conseil']);

        // Barre IMC
        $imcPct = $imc ? min(max(($imc / 40) * 100, 0), 100) : 0;

        // Section programmes
        $programmesHtml = '';
        $totalExercices = 0;
        $totalTermines  = 0;

        foreach ($programmes as $p) {
            $exercices = $this->dbGetExercicesByProgramme($p['id']);
            $nbTotal   = count($exercices);
            $nbTermine = count(array_filter($exercices, fn($e) => $e['statut'] === 'termine'));
            $pct       = $nbTotal > 0 ? round(($nbTermine / $nbTotal) * 100) : 0;
            $totalExercices += $nbTotal;
            $totalTermines  += $nbTermine;

            $exRows = '';
            foreach ($exercices as $ex) {
                $statut = $ex['statut'] ?? 'en_attente';
                $icon   = $statut === 'termine' ? '✔' : ($statut === 'en_cours' ? '▶' : '○');
                $bgRow  = $statut === 'termine' ? '#f0fff0' : '#fff';
                $exRows .= "
                <tr style='background:$bgRow;'>
                    <td style='padding:7px 10px;border:1px solid #ddd;text-align:center;font-weight:bold;'>{$ex['ordre']}</td>
                    <td style='padding:7px 10px;border:1px solid #ddd;'>" . htmlspecialchars($ex['nom']) . "</td>
                    <td style='padding:7px 10px;border:1px solid #ddd;color:#666;font-size:12px;'>" . htmlspecialchars($ex['description'] ?? '-') . "</td>
                    <td style='padding:7px 10px;border:1px solid #ddd;text-align:center;'>" . ($ex['duree_minutes'] ?? '-') . " min</td>
                    <td style='padding:7px 10px;border:1px solid #ddd;text-align:center;color:$imcColor;'>$icon " . htmlspecialchars($statut) . "</td>
                </tr>";
            }

            $catNom  = htmlspecialchars($p['categorie_nom'] ?? 'Non classé');
            $niveauBadgeColor = $p['niveau'] === 'debutant' ? '#28a745' : ($p['niveau'] === 'intermediaire' ? '#ffc107' : '#dc3545');

            $programmesHtml .= "
            <div style='border:1px solid #c8e6c4;border-radius:10px;margin-bottom:20px;overflow:hidden;'>
                <div style='background:#2d5a27;color:#fff;padding:12px 16px;display:flex;justify-content:space-between;align-items:center;'>
                    <div>
                        <strong style='font-size:15px;'>" . htmlspecialchars($p['nom']) . "</strong>
                        <span style='background:$niveauBadgeColor;color:#fff;padding:2px 10px;border-radius:10px;font-size:11px;margin-left:10px;'>{$p['niveau']}</span>
                        <span style='font-size:12px;margin-left:10px;opacity:0.85;'>{$p['duree_semaines']} semaine(s) | Catégorie : $catNom</span>
                    </div>
                    <div style='font-size:13px;'>$nbTermine/$nbTotal exercices — <strong>$pct%</strong></div>
                </div>
                <div style='padding:12px 16px;'>
                    <!-- Barre progression -->
                    <div style='background:#e9ecef;border-radius:8px;height:10px;margin-bottom:12px;'>
                        <div style='width:{$pct}%;background:#2d5a27;height:10px;border-radius:8px;'></div>
                    </div>
                    " . ($nbTotal > 0 ? "
                    <table style='width:100%;border-collapse:collapse;font-size:13px;'>
                        <thead>
                            <tr style='background:#e8f5e9;'>
                                <th style='padding:7px 10px;border:1px solid #ddd;width:60px;'>Ordre</th>
                                <th style='padding:7px 10px;border:1px solid #ddd;'>Nom</th>
                                <th style='padding:7px 10px;border:1px solid #ddd;'>Description</th>
                                <th style='padding:7px 10px;border:1px solid #ddd;width:80px;'>Durée</th>
                                <th style='padding:7px 10px;border:1px solid #ddd;width:100px;'>Statut</th>
                            </tr>
                        </thead>
                        <tbody>$exRows</tbody>
                    </table>" : "<p style='color:#888;font-size:13px;margin:0;'>Aucun exercice dans ce programme.</p>") . "
                </div>
            </div>";
        }

        if (!$programmesHtml) {
            $programmesHtml = "<div style='background:#fff3cd;padding:12px;border-radius:8px;color:#856404;'>Aucun programme assigné pour le moment.</div>";
        }

        // Progression globale
        $pctGlobal = $totalExercices > 0 ? round(($totalTermines / $totalExercices) * 100) : 0;

        // Objectifs officiels section
        $objOffHtml = '';
        foreach ($objectifsOfficiels as $oo) {
            $objOffHtml .= "
            <tr>
                <td style='padding:7px 10px;border:1px solid #ddd;'>" . htmlspecialchars($oo['titre']) . "</td>
                <td style='padding:7px 10px;border:1px solid #ddd;'><span style='background:#2d5a27;color:#fff;padding:2px 8px;border-radius:8px;font-size:11px;'>" . htmlspecialchars($oo['type_objectif']) . "</span></td>
                <td style='padding:7px 10px;border:1px solid #ddd;text-align:center;'>" . ($oo['calories_min'] ?? '-') . " — " . ($oo['calories_max'] ?? '-') . " kcal</td>
                <td style='padding:7px 10px;border:1px solid #ddd;font-size:12px;'>" . htmlspecialchars($oo['maladies'] ?? '-') . "</td>
            </tr>";
        }
        $objOfficielsSection = $objOffHtml ? "
        <div style='margin-bottom:30px;'>
            <div style='background:#2d5a27;color:#fff;padding:10px 16px;border-radius:8px 8px 0 0;'>
                <strong>🎯 Objectifs Officiels Disponibles</strong>
            </div>
            <table style='width:100%;border-collapse:collapse;font-size:13px;'>
                <thead>
                    <tr style='background:#e8f5e9;'>
                        <th style='padding:7px 10px;border:1px solid #ddd;'>Titre</th>
                        <th style='padding:7px 10px;border:1px solid #ddd;'>Type</th>
                        <th style='padding:7px 10px;border:1px solid #ddd;'>Calories</th>
                        <th style='padding:7px 10px;border:1px solid #ddd;'>Maladies</th>
                    </tr>
                </thead>
                <tbody>$objOffHtml</tbody>
            </table>
        </div>" : '';

        return "<!DOCTYPE html>
<html lang='fr'>
<head>
<meta charset='UTF-8'>
<title>Fiche Complète — $userNom</title>
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: 'Segoe UI', Arial, sans-serif; color: #333; background: #fff; }
    .page { max-width: 900px; margin: 0 auto; padding: 30px; }
    h2 { color: #2d5a27; font-size: 16px; margin: 24px 0 10px; border-left: 4px solid #2d5a27; padding-left: 10px; }
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px; }
    .info-item { background: #f8f9fa; padding: 10px 14px; border-radius: 8px; }
    .info-label { color: #888; font-size: 11px; text-transform: uppercase; margin-bottom: 3px; }
    .info-value { font-weight: bold; font-size: 14px; }
    .imc-section { background: #f8f9fa; border-left: 5px solid $imcColor; padding: 16px; border-radius: 8px; margin-bottom: 16px; }
    .stat-box { background: #f8f9fa; border-radius: 8px; padding: 12px; text-align: center; }
    @media print {
        body { margin: 0; }
        .page { padding: 15px; }
        .no-print { display: none; }
    }
</style>
</head>
<body>
<div class='page'>

    <!-- EN-TÊTE -->
    <div style='text-align:center;background:linear-gradient(135deg,#2d5a27,#4a8f3f);color:#fff;padding:30px;border-radius:12px;margin-bottom:24px;'>
        <div style='font-size:40px;margin-bottom:8px;'>🥗</div>
        <h1 style='font-size:24px;font-weight:900;margin-bottom:4px;'>EAT HEALTHY</h1>
        <p style='font-size:14px;opacity:0.85;'>Fiche Complète Utilisateur</p>
        <p style='font-size:12px;opacity:0.7;margin-top:4px;'>Générée le $dateExport</p>
    </div>

    <!-- INFOS UTILISATEUR -->
    <h2>👤 Informations Utilisateur</h2>
    <div class='info-grid'>
        <div class='info-item'><div class='info-label'>Nom</div><div class='info-value'>$userNom</div></div>
        <div class='info-item'><div class='info-label'>ID Utilisateur</div><div class='info-value'>#$userId</div></div>
    </div>

    <!-- OBJECTIF PERSONNEL -->
    <h2>🎯 Objectif Personnel</h2>
    <div class='info-grid'>
        <div class='info-item'><div class='info-label'>Titre</div><div class='info-value'>$titre</div></div>
        <div class='info-item'><div class='info-label'>Description</div><div class='info-value'>$description</div></div>
        <div class='info-item'><div class='info-label'>Poids actuel</div><div class='info-value'>$poidsActuel</div></div>
        <div class='info-item'><div class='info-label'>Poids cible</div><div class='info-value'>$poidsCible</div></div>
        <div class='info-item'><div class='info-label'>Taille</div><div class='info-value'>$taille</div></div>
        <div class='info-item'><div class='info-label'>Âge</div><div class='info-value'>$age</div></div>
        <div class='info-item'><div class='info-label'>Date de début</div><div class='info-value'>$dateDebut</div></div>
        <div class='info-item'><div class='info-label'>Date de fin prévue</div><div class='info-value'>$dateFin</div></div>
        <div class='info-item' style='grid-column:1/-1;'><div class='info-label'>État de santé</div><div class='info-value'>$etatSante</div></div>
        <div class='info-item'><div class='info-label'>Créé le</div><div class='info-value'>$dateCreation</div></div>
    </div>

    <!-- IMC -->
    <h2>⚖️ Indice de Masse Corporelle (IMC)</h2>
    <div class='imc-section'>
        <div style='display:flex;align-items:center;gap:20px;flex-wrap:wrap;'>
            <div style='text-align:center;'>
                <div style='font-size:48px;font-weight:900;color:$imcColor;'>$imcVal</div>
                <div style='background:$imcColor;color:#fff;padding:4px 14px;border-radius:20px;font-size:13px;margin-top:4px;'>$imcLabel</div>
            </div>
            <div style='flex:1;min-width:200px;'>
                <p style='font-size:13px;color:#555;margin-bottom:10px;'>$imcConseil</p>
                <div style='display:flex;justify-content:space-between;font-size:11px;color:#888;margin-bottom:4px;'>
                    <span>&lt;18.5<br>Insuffisant</span>
                    <span>18.5–25<br>Normal</span>
                    <span>25–30<br>Surpoids</span>
                    <span>&gt;30<br>Obèse</span>
                </div>
                <div style='background:#e9ecef;border-radius:10px;height:14px;'>
                    <div style='width:{$imcPct}%;background:$imcColor;height:14px;border-radius:10px;'></div>
                </div>
            </div>
        </div>
    </div>

    <!-- PROGRESSION GLOBALE -->
    <h2>📈 Progression Globale</h2>
    <div style='display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:20px;'>
        <div class='stat-box'>
            <div style='font-size:28px;font-weight:900;color:#2d5a27;'>" . count($programmes) . "</div>
            <div style='font-size:12px;color:#888;'>Programmes assignés</div>
        </div>
        <div class='stat-box'>
            <div style='font-size:28px;font-weight:900;color:#2d5a27;'>$totalTermines/$totalExercices</div>
            <div style='font-size:12px;color:#888;'>Exercices terminés</div>
        </div>
        <div class='stat-box'>
            <div style='font-size:28px;font-weight:900;color:#2d5a27;'>$pctGlobal%</div>
            <div style='font-size:12px;color:#888;'>Complétion globale</div>
        </div>
    </div>
    <div style='background:#e9ecef;border-radius:10px;height:18px;margin-bottom:24px;'>
        <div style='width:{$pctGlobal}%;background:#2d5a27;height:18px;border-radius:10px;'></div>
    </div>

    <!-- PROGRAMMES & EXERCICES -->
    <h2>🏋️ Programmes & Exercices Assignés</h2>
    $programmesHtml

    <!-- OBJECTIFS OFFICIELS -->
    $objOfficielsSection

    <!-- PIED DE PAGE -->
    <div style='margin-top:40px;border-top:1px solid #eee;padding-top:14px;text-align:center;font-size:11px;color:#aaa;'>
        EAT HEALTHY — Document généré automatiquement le $dateExport
    </div>
</div>

<script>window.onload = function() { window.print(); }</script>
</body>
</html>";
    }

    // ─── BUILD HTML PROGRAMME SEUL ────────────────────────────────

    private function buildHtmlProgramme(array $programme, array $exercices): string {
        $nom         = htmlspecialchars($programme['nom']);
        $niveau      = htmlspecialchars($programme['niveau']);
        $duree       = $programme['duree_semaines'];
        $description = htmlspecialchars($programme['description'] ?? '-');
        $objectif    = htmlspecialchars($programme['objectif_titre'] ?? '-');
        $categorie   = htmlspecialchars($programme['categorie_nom'] ?? '-');
        $dateCreation = isset($programme['date_creation']) ? date('d/m/Y', strtotime($programme['date_creation'])) : '-';
        $dateExport  = date('d/m/Y H:i');

        $total    = count($exercices);
        $termines = count(array_filter($exercices, fn($e) => $e['statut'] === 'termine'));
        $pct      = $total > 0 ? round(($termines / $total) * 100) : 0;

        $exRows = '';
        foreach ($exercices as $ex) {
            $statut = $ex['statut'] ?? 'en_attente';
            $icon   = $statut === 'termine' ? '✔' : ($statut === 'en_cours' ? '▶' : '○');
            $bgRow  = $statut === 'termine' ? '#f0fff0' : '#fff';
            $exRows .= "
            <tr style='background:$bgRow;'>
                <td style='padding:8px 10px;border:1px solid #ddd;text-align:center;font-weight:bold;'>{$ex['ordre']}</td>
                <td style='padding:8px 10px;border:1px solid #ddd;'>" . htmlspecialchars($ex['nom']) . "</td>
                <td style='padding:8px 10px;border:1px solid #ddd;font-size:12px;color:#666;'>" . htmlspecialchars($ex['description'] ?? '-') . "</td>
                <td style='padding:8px 10px;border:1px solid #ddd;text-align:center;'>" . ($ex['duree_minutes'] ?? '-') . " min</td>
                <td style='padding:8px 10px;border:1px solid #ddd;text-align:center;'>$icon " . htmlspecialchars($statut) . "</td>
            </tr>";
        }

        return "<!DOCTYPE html>
<html lang='fr'>
<head>
<meta charset='UTF-8'>
<title>Fiche Programme — $nom</title>
<style>
    body { font-family:'Segoe UI',Arial,sans-serif; color:#333; margin:30px; }
    h2 { color:#2d5a27; border-left:4px solid #2d5a27; padding-left:10px; font-size:16px; margin:20px 0 10px; }
    .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:16px; }
    .info-item { background:#f8f9fa; padding:10px 14px; border-radius:8px; }
    .info-label { color:#888; font-size:11px; text-transform:uppercase; }
    .info-value { font-weight:bold; font-size:14px; }
    @media print { body { margin:15px; } }
</style>
</head>
<body>

<div style='text-align:center;background:linear-gradient(135deg,#2d5a27,#4a8f3f);color:#fff;padding:24px;border-radius:10px;margin-bottom:24px;'>
    <div style='font-size:32px;'>🥗</div>
    <h1 style='font-size:20px;margin:6px 0;'>EAT HEALTHY — Fiche Programme</h1>
    <p style='font-size:12px;opacity:0.7;'>Généré le $dateExport</p>
</div>

<h2>📋 Informations du Programme</h2>
<div class='info-grid'>
    <div class='info-item'><div class='info-label'>Nom</div><div class='info-value'>$nom</div></div>
    <div class='info-item'><div class='info-label'>Niveau</div><div class='info-value'>$niveau</div></div>
    <div class='info-item'><div class='info-label'>Durée</div><div class='info-value'>$duree semaine(s)</div></div>
    <div class='info-item'><div class='info-label'>Catégorie</div><div class='info-value'>$categorie</div></div>
    <div class='info-item'><div class='info-label'>Objectif lié</div><div class='info-value'>$objectif</div></div>
    <div class='info-item'><div class='info-label'>Créé le</div><div class='info-value'>$dateCreation</div></div>
    <div class='info-item' style='grid-column:1/-1;'><div class='info-label'>Description</div><div class='info-value'>$description</div></div>
</div>

<h2>📈 Progression</h2>
<div style='display:flex;align-items:center;gap:16px;margin-bottom:16px;'>
    <div style='font-size:32px;font-weight:900;color:#2d5a27;'>$pct%</div>
    <div style='flex:1;'>
        <div style='font-size:13px;color:#555;margin-bottom:6px;'>$termines exercice(s) terminé(s) sur $total</div>
        <div style='background:#e9ecef;border-radius:10px;height:14px;'>
            <div style='width:{$pct}%;background:#2d5a27;height:14px;border-radius:10px;'></div>
        </div>
    </div>
</div>

<h2>🏋️ Liste des Exercices</h2>
" . ($exRows ? "
<table style='width:100%;border-collapse:collapse;font-size:13px;'>
    <thead style='background:#2d5a27;color:#fff;'>
        <tr>
            <th style='padding:8px 10px;border:1px solid #1a4a18;width:60px;'>Ordre</th>
            <th style='padding:8px 10px;border:1px solid #1a4a18;'>Nom</th>
            <th style='padding:8px 10px;border:1px solid #1a4a18;'>Description</th>
            <th style='padding:8px 10px;border:1px solid #1a4a18;width:80px;'>Durée</th>
            <th style='padding:8px 10px;border:1px solid #1a4a18;width:100px;'>Statut</th>
        </tr>
    </thead>
    <tbody>$exRows</tbody>
</table>" : "<p style='color:#888;'>Aucun exercice dans ce programme.</p>") . "

<div style='margin-top:40px;border-top:1px solid #eee;padding-top:12px;text-align:center;font-size:11px;color:#aaa;'>
    EAT HEALTHY — Document généré le $dateExport
</div>

<script>window.onload = function() { window.print(); }</script>
</body>
</html>";
    }

    // ─── RENDER ───────────────────────────────────────────────────

    private function renderHtml(string $html): void {
        header('Content-Type: text/html; charset=UTF-8');
        echo $html;
        exit;
    }
}
?>