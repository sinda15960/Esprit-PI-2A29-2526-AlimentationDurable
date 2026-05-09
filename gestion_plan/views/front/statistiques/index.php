<?php
require_once 'C:/xampp/htdocs/gestion_plan/header.php';
?>

<h2 class="section-title">📊 Mes Statistiques</h2>

<!-- ══════════════════════════════════════════════
     ALERTE RETARD
══════════════════════════════════════════════ -->
<?php if (!empty($alerte['retard']) && $alerte['retard'] === true): ?>
<div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
    <span style="font-size:1.5rem; margin-right:12px;">⚠️</span>
    <div>
        <strong>Retard détecté !</strong> <?= htmlspecialchars($alerte['message']) ?>
        <a href="index.php?module=exercice&action=index&office=front" class="btn btn-sm btn-danger ms-3">
            Reprendre maintenant
        </a>
    </div>
</div>
<?php endif; ?>

<!-- ══════════════════════════════════════════════
     STATS GLOBALES (existantes)
══════════════════════════════════════════════ -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center p-3 border-start border-success border-4">
            <div style="font-size:2rem;">📋</div>
            <h3 class="text-success fw-bold"><?= $totalProgrammes ?></h3>
            <p class="text-muted small mb-0">Programmes disponibles</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3 border-start border-info border-4">
            <div style="font-size:2rem;">🏋️</div>
            <h3 class="text-info fw-bold"><?= $totalExercices ?></h3>
            <p class="text-muted small mb-0">Exercices disponibles</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3 border-start border-warning border-4">
            <div style="font-size:2rem;">✅</div>
            <h3 class="text-warning fw-bold"><?= $exercicesTermines ?></h3>
            <p class="text-muted small mb-0">Exercices terminés</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3 border-start border-primary border-4">
            <div style="font-size:2rem;">🎯</div>
            <h3 class="text-primary fw-bold"><?= $pourcentage ?>%</h3>
            <p class="text-muted small mb-0">Progression globale</p>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     NOUVEAUX BLOCS MÉTIER
══════════════════════════════════════════════ -->
<div class="row g-3 mb-4">

    <!-- Streak -->
    <div class="col-md-3">
        <div class="card text-center p-3 h-100 border-start border-4" style="border-color:#ff6b35 !important;">
            <div style="font-size:2rem;">🔥</div>
            <h3 class="fw-bold" style="color:#ff6b35;">
                <?= $streak ?> jour<?= $streak > 1 ? 's' : '' ?>
            </h3>
            <p class="text-muted small mb-1">Série active</p>
            <?php if ($streak >= 3): ?>
                <span class="badge bg-danger">🔥 En feu !</span>
            <?php elseif ($streak >= 1): ?>
                <span class="badge bg-warning text-dark">⏳ Continue !</span>
            <?php else: ?>
                <span class="badge bg-secondary">Commencez aujourd'hui</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Score régularité -->
    <div class="col-md-3">
        <div class="card text-center p-3 h-100 border-start border-4 border-<?= $regularite['mention']['color'] ?>">
            <div style="font-size:2rem;">📅</div>
            <h3 class="fw-bold text-<?= $regularite['mention']['color'] ?>">
                <?= $regularite['score'] ?>%
            </h3>
            <p class="text-muted small mb-1">Régularité (30 jours)</p>
            <span class="badge bg-<?= $regularite['mention']['color'] ?>">
                <?= $regularite['mention']['label'] ?>
            </span>
        </div>
    </div>

    <!-- Temps total ce mois -->
    <div class="col-md-3">
        <div class="card text-center p-3 h-100 border-start border-4 border-info">
            <div style="font-size:2rem;">⏱️</div>
            <h3 class="fw-bold text-info"><?= $tempsMois ?> min</h3>
            <p class="text-muted small mb-1">Ce mois-ci</p>
            <?php if ($tempsMois >= 120): ?>
                <span class="badge bg-success">💪 Très actif</span>
            <?php elseif ($tempsMois >= 30): ?>
                <span class="badge bg-info text-dark">👍 Bien</span>
            <?php else: ?>
                <span class="badge bg-secondary">🚀 Continuez</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Niveau automatique -->
    <div class="col-md-3">
        <div class="card text-center p-3 h-100 border-start border-4 border-<?= $niveau['color'] ?>">
            <div style="font-size:2rem;">🏅</div>
            <h3 class="fw-bold text-<?= $niveau['color'] ?>"><?= $niveau['label'] ?></h3>
            <p class="text-muted small mb-1">Votre niveau</p>
            <p class="text-muted" style="font-size:0.75rem;">
                <?= $exercicesTermines ?> exercice<?= $exercicesTermines > 1 ? 's' : '' ?> terminé<?= $exercicesTermines > 1 ? 's' : '' ?>
            </p>
        </div>
    </div>

</div>

<!-- Barre score régularité -->
<div class="card p-3 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="fw-bold small">📊 Score de régularité sur 30 jours</span>
        <span class="small text-muted">
            <?= $regularite['valides'] ?> / <?= $regularite['total'] ?> exercices réalisés
        </span>
    </div>
    <div style="background:#e9ecef; border-radius:10px; height:18px;">
        <?php
            $barColorReg = match($regularite['mention']['color']) {
                'success' => '#2d5a27',
                'primary' => '#0d6efd',
                'warning' => '#ffc107',
                default   => '#dc3545',
            };
        ?>
        <div style="width:<?= $regularite['score'] ?>%; background:<?= $barColorReg ?>; height:18px; border-radius:10px; transition:width 1s;"></div>
    </div>
    <div class="d-flex justify-content-between mt-1" style="font-size:0.75rem; color:#6c757d;">
        <span>📉 Faible</span>
        <span>😐 Moyen</span>
        <span>👍 Bon</span>
        <span>🏆 Excellent</span>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     IMC (existant — non modifié)
══════════════════════════════════════════════ -->
<div class="row g-4 mb-4">
    <div class="col-md-5">
        <div class="card p-4 h-100">
            <h4 class="section-title">⚖️ Mon IMC</h4>
            <?php if ($objectif && $imc !== null): ?>
                <div class="text-center my-3">
                    <div style="font-size:3.5rem; font-weight:900; color:#2d5a27;"><?= $imc ?></div>
                    <span class="badge bg-<?= $imcCategorie['color'] ?> fs-6 mt-1">
                        <?= htmlspecialchars($imcCategorie['label']) ?>
                    </span>
                </div>
                <div class="small text-muted mb-3">
                    <strong>Poids :</strong> <?= $objectif['poids_actuel'] ?> kg &nbsp;|&nbsp;
                    <strong>Taille :</strong> <?= $objectif['taille'] ?> m
                </div>
                <?php if (!empty($imcCategorie['conseil'])): ?>
                    <div class="alert alert-<?= $imcCategorie['color'] ?> small">
                        💡 <?= htmlspecialchars($imcCategorie['conseil']) ?>
                    </div>
                <?php endif; ?>
                <div class="mt-2">
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Insuffisant<br>&lt;18.5</span>
                        <span>Normal<br>18.5–25</span>
                        <span>Surpoids<br>25–30</span>
                        <span>Obèse<br>&gt;30</span>
                    </div>
                    <?php
                        $imcPct   = min(max(($imc / 40) * 100, 0), 100);
                        $barColor = $imcCategorie['color'] === 'success' ? '#2d5a27'
                                  : ($imcCategorie['color'] === 'warning' ? '#ffc107'
                                  : ($imcCategorie['color'] === 'danger'  ? '#dc3545' : '#0dcaf0'));
                    ?>
                    <div style="background:#e9ecef; border-radius:10px; height:14px;">
                        <div style="width:<?= $imcPct ?>%; background:<?= $barColor ?>; height:14px; border-radius:10px; transition:width 1s;"></div>
                    </div>
                </div>
            <?php elseif ($objectif): ?>
                <div class="alert alert-warning small">
                    Renseignez votre poids et taille dans votre objectif pour calculer votre IMC.
                </div>
            <?php else: ?>
                <div class="alert alert-info small">
                    Créez un objectif personnel pour voir votre IMC.
                    <a href="index.php?module=objectif&action=index&office=front" class="btn btn-sm btn-green mt-2 d-block">
                        Créer mon objectif
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- PROGRESSION PAR PROGRAMME (existant — non modifié) -->
    <div class="col-md-7">
        <div class="card p-4 h-100">
            <h4 class="section-title">📈 Ma Progression par Programme</h4>
            <?php if (empty($progressions)): ?>
                <div class="alert alert-info small">Aucun programme assigné pour le moment.</div>
            <?php else: ?>
                <?php foreach ($progressions as $prog): ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-bold small"><?= htmlspecialchars($prog['nom']) ?></span>
                            <span class="small text-muted">
                                <?= $prog['termines'] ?>/<?= $prog['total'] ?> — <strong><?= $prog['pct'] ?>%</strong>
                            </span>
                        </div>
                        <div style="background:#e9ecef; border-radius:10px; height:16px;">
                            <div style="width:<?= $prog['pct'] ?>%; background:#2d5a27; height:16px; border-radius:10px; transition:width 1s;"></div>
                        </div>
                        <?php if ($prog['pct'] == 100): ?>
                            <span class="badge bg-success mt-1">🎉 Terminé !</span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     RECOMMANDATIONS (existant — non modifié)
══════════════════════════════════════════════ -->
<?php if (!empty($recommandations)): ?>
<div class="card p-4 mb-4">
    <h4 class="section-title">🎯 Programmes Recommandés pour Vous</h4>
    <p class="text-muted small mb-3">Basé sur votre profil et vos objectifs :</p>
    <div class="row g-3">
        <?php foreach ($recommandations as $r): ?>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 p-3 border-start border-success border-3">
                <h6 class="text-success fw-bold"><?= htmlspecialchars($r['nom']) ?></h6>
                <span class="badge bg-info text-dark mb-1"><?= htmlspecialchars($r['niveau']) ?></span>
                <p class="text-muted small mb-1"><?= $r['duree_semaines'] ?> semaine(s)</p>
                <?php if (!empty($r['categorie_nom'])): ?>
                    <p class="text-muted small mb-2">📂 <?= htmlspecialchars($r['categorie_nom']) ?></p>
                <?php endif; ?>
                <a href="index.php?module=exercice&action=indexByProgramme&programme_id=<?= $r['id'] ?>&office=front"
                   class="btn btn-sm btn-green mt-auto">Voir les exercices</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php
require_once 'C:/xampp/htdocs/gestion_plan/footer.php';
?>