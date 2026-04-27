<?php 
require_once 'C:/xampp/htdocs/gestion_plan/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">📊 Tableau de bord Admin</h2>
</div>

<!-- ══════════════════════════════════════════════
     DASHBOARD TEMPS RÉEL — Aujourd'hui
══════════════════════════════════════════════ -->
<h5 class="text-muted mb-3">⚡ Aujourd'hui</h5>
<div class="row g-3 mb-5">
    <div class="col-md-3">
        <div class="card text-center p-3 border-start border-4" style="border-color:#2d5a27 !important;">
            <div style="font-size:1.8rem;">✅</div>
            <h3 class="fw-bold" style="color:#2d5a27;"><?= $validationsAujourdhui ?></h3>
            <p class="text-muted small mb-0">Validations aujourd'hui</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3 border-start border-primary border-4">
            <div style="font-size:1.8rem;">👤</div>
            <h3 class="fw-bold text-primary"><?= $nouveauxUsersAujourdhui ?></h3>
            <p class="text-muted small mb-0">Nouveaux utilisateurs</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3 border-start border-warning border-4">
            <div style="font-size:1.8rem;">📋</div>
            <h3 class="fw-bold text-warning"><?= $programmesActifsAujourdhui ?></h3>
            <p class="text-muted small mb-0">Programmes actifs</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3 border-start border-info border-4">
            <div style="font-size:1.8rem;">📝</div>
            <h3 class="fw-bold text-info"><?= $totalNotes ?></h3>
            <p class="text-muted small mb-0">Notes rédigées (total)</p>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     KPIs GLOBAUX
══════════════════════════════════════════════ -->
<h5 class="text-muted mb-3">🌍 Vue globale</h5>
<div class="row g-3 mb-5">
    <div class="col-md-4">
        <div class="card text-center p-3">
            <div style="font-size:2rem;font-weight:700;color:#2d5a27;"><?= $totalUsers ?></div>
            <div class="text-muted small">👤 Utilisateurs</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-3">
            <div style="font-size:2rem;font-weight:700;color:#2d5a27;"><?= $totalProgrammes ?></div>
            <div class="text-muted small">📋 Programmes</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-3">
            <div style="font-size:2rem;font-weight:700;color:#2d5a27;"><?= $totalExercices ?></div>
            <div class="text-muted small">🏋️ Exercices</div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     DÉTECTION UTILISATEURS INACTIFS
══════════════════════════════════════════════ -->
<h4 class="section-title mb-3">🚨 Utilisateurs inactifs</h4>

<!-- 15+ jours -->
<?php if (!empty($inactifs15j)): ?>
<div class="card mb-3">
    <div class="card-header bg-danger text-white fw-bold">
        🔴 Inactifs depuis 15+ jours (<?= count($inactifs15j) ?> utilisateur<?= count($inactifs15j) > 1 ? 's' : '' ?>)
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Utilisateur</th><th>Dernière activité</th><th>Jours inactif</th><th>Exercices terminés</th></tr></thead>
            <tbody>
                <?php foreach ($inactifs15j as $u): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($u['nom']) ?></strong><br><span class="text-muted small">@<?= htmlspecialchars($u['username']) ?></span></td>
                    <td><?= $u['derniere_activite'] ? date('d/m/Y', strtotime($u['derniere_activite'])) : 'Jamais' ?></td>
                    <td><span class="badge bg-danger"><?= $u['jours_inactif'] ?? '∞' ?> jours</span></td>
                    <td><?= (int)$u['termines'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- 7–14 jours -->
<?php if (!empty($inactifs7j)): ?>
<div class="card mb-3">
    <div class="card-header bg-warning text-dark fw-bold">
        🟡 Inactifs depuis 7–14 jours (<?= count($inactifs7j) ?> utilisateur<?= count($inactifs7j) > 1 ? 's' : '' ?>)
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Utilisateur</th><th>Dernière activité</th><th>Jours inactif</th><th>Exercices terminés</th></tr></thead>
            <tbody>
                <?php foreach ($inactifs7j as $u): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($u['nom']) ?></strong><br><span class="text-muted small">@<?= htmlspecialchars($u['username']) ?></span></td>
                    <td><?= $u['derniere_activite'] ? date('d/m/Y', strtotime($u['derniere_activite'])) : 'Jamais' ?></td>
                    <td><span class="badge bg-warning text-dark"><?= $u['jours_inactif'] ?? '∞' ?> jours</span></td>
                    <td><?= (int)$u['termines'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- 3–6 jours -->
<?php if (!empty($inactifs3j)): ?>
<div class="card mb-5">
    <div class="card-header bg-info text-white fw-bold">
        🔵 Inactifs depuis 3–6 jours (<?= count($inactifs3j) ?> utilisateur<?= count($inactifs3j) > 1 ? 's' : '' ?>)
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Utilisateur</th><th>Dernière activité</th><th>Jours inactif</th><th>Exercices terminés</th></tr></thead>
            <tbody>
                <?php foreach ($inactifs3j as $u): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($u['nom']) ?></strong><br><span class="text-muted small">@<?= htmlspecialchars($u['username']) ?></span></td>
                    <td><?= $u['derniere_activite'] ? date('d/m/Y', strtotime($u['derniere_activite'])) : 'Jamais' ?></td>
                    <td><span class="badge bg-info text-dark"><?= $u['jours_inactif'] ?? '∞' ?> jours</span></td>
                    <td><?= (int)$u['termines'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php if (empty($inactifs3j) && empty($inactifs7j) && empty($inactifs15j)): ?>
<div class="alert alert-success mb-5">✅ Tous les utilisateurs sont actifs !</div>
<?php endif; ?>

<!-- ══════════════════════════════════════════════
     CLASSEMENT MEILLEURS UTILISATEURS
══════════════════════════════════════════════ -->
<h4 class="section-title mb-3">🏆 Classement des meilleurs utilisateurs</h4>
<div class="card mb-5">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Utilisateur</th>
                    <th>Exercices terminés</th>
                    <th>Jours actifs</th>
                    <th>Temps total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($classement)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">Aucune donnée.</td></tr>
                <?php else: ?>
                    <?php foreach ($classement as $i => $u): ?>
                    <tr>
                        <td>
                            <?php if ($i === 0): ?>🥇
                            <?php elseif ($i === 1): ?>🥈
                            <?php elseif ($i === 2): ?>🥉
                            <?php else: ?><?= $i + 1 ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($u['nom']) ?></strong>
                            <br><span class="text-muted small">@<?= htmlspecialchars($u['username']) ?></span>
                        </td>
                        <td><span class="badge" style="background:#2d5a27;">✅ <?= (int)$u['termines'] ?></span></td>
                        <td><span class="badge bg-primary">📅 <?= (int)$u['jours_actifs'] ?> jours</span></td>
                        <td><span class="badge bg-info text-dark">⏱ <?= (int)$u['total_minutes'] ?> min</span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     TAUX ABANDON PAR PROGRAMME
══════════════════════════════════════════════ -->
<h4 class="section-title mb-3">📉 Taux d'avancement par programme</h4>
<div class="card mb-5">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Programme</th>
                    <th>Total exercices</th>
                    <th>Terminés</th>
                    <th>En cours</th>
                    <th>En attente</th>
                    <th>Avancement</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tauxAbandon)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Aucune donnée.</td></tr>
                <?php else: ?>
                    <?php foreach ($tauxAbandon as $p): ?>
                    <?php
                        $total = (int)$p['total_exercices'];
                        $pct   = $total > 0 ? round(((int)$p['nb_termines'] / $total) * 100) : 0;
                        $barC  = $pct >= 80 ? '#2d5a27' : ($pct >= 40 ? '#ffc107' : '#dc3545');
                    ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($p['programme_nom']) ?></strong></td>
                        <td><?= $total ?></td>
                        <td><span class="badge bg-success"><?= (int)$p['nb_termines'] ?></span></td>
                        <td><span class="badge bg-warning text-dark"><?= (int)$p['nb_en_cours'] ?></span></td>
                        <td><span class="badge bg-secondary"><?= (int)$p['nb_en_attente'] ?></span></td>
                        <td style="min-width:140px;">
                            <div class="progress" style="height:10px;">
                                <div class="progress-bar" style="width:<?= $pct ?>%;background:<?= $barC ?>;"></div>
                            </div>
                            <small class="text-muted"><?= $pct ?>%</small>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     EXERCICES LES PLUS DIFFICILES
══════════════════════════════════════════════ -->
<?php if (!empty($topDifficiles)): ?>
<h4 class="section-title mb-3">🚨 Exercices signalés "Difficile" (à améliorer)</h4>
<div class="card mb-5">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Exercice</th><th>Programme</th><th>Signalements</th><th>Action recommandée</th></tr></thead>
            <tbody>
                <?php foreach ($topDifficiles as $ex): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($ex['nom']) ?></strong></td>
                    <td><?= htmlspecialchars($ex['programme_nom'] ?? '-') ?></td>
                    <td><span class="badge bg-danger fs-6"><?= (int)$ex['nb_difficile'] ?> × 😓</span></td>
                    <td class="text-muted small">
                        <?= (int)$ex['nb_difficile'] >= 2 ? '⚠️ Réduire la difficulté ou améliorer la description' : '👁️ À surveiller' ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- ══════════════════════════════════════════════
     NOTES UTILISATEURS
══════════════════════════════════════════════ -->
<h4 class="section-title mb-3">📝 Feedbacks utilisateurs (notes)</h4>
<?php if (empty($toutesLesNotes)): ?>
    <div class="card mb-5"><div class="card-body text-center text-muted py-4">Aucune note rédigée pour le moment.</div></div>
<?php else: ?>
<div class="card mb-5">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Utilisateur</th><th>Exercice</th><th>Reps</th><th>Poids</th><th>Ressenti</th><th>Note</th></tr></thead>
            <tbody>
                <?php foreach ($toutesLesNotes as $n): ?>
                <tr>
                    <td><span class="badge bg-secondary">👤 <?= htmlspecialchars($n['user_nom'] ?? $n['username'] ?? 'Inconnu') ?></span></td>
                    <td>
                        <a href="index.php?module=exercice&action=edit&office=back&id=<?= (int)$n['exercice_id'] ?>"
                           class="text-success fw-bold text-decoration-none">
                            <?= htmlspecialchars($n['exercice_nom']) ?>
                        </a>
                    </td>
                    <td><?= $n['repetitions_realisees'] !== null ? '<span class="badge bg-primary">'.(int)$n['repetitions_realisees'].' reps</span>' : '<span class="text-muted">—</span>' ?></td>
                    <td><?= $n['poids_utilise'] !== null ? '<span class="badge" style="background:#2d5a27;">'.number_format((float)$n['poids_utilise'],1).' kg</span>' : '<span class="text-muted">—</span>' ?></td>
                    <td>
                        <?php if (!empty($n['ressenti'])): ?>
                            <?php $rb = match($n['ressenti']) { 'facile' => ['bg-success','😊 Facile'], 'moyen' => ['bg-warning text-dark','😐 Moyen'], 'difficile' => ['bg-danger','😓 Difficile'], default => ['bg-secondary',$n['ressenti']] }; ?>
                            <span class="badge <?= $rb[0] ?>"><?= $rb[1] ?></span>
                        <?php else: ?><span class="text-muted">—</span><?php endif; ?>
                    </td>
                    <td style="max-width:280px; font-style:italic;">"<?= htmlspecialchars($n['note_user']) ?>"</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- ══════════════════════════════════════════════
     PROGRESSION PAR UTILISATEUR
══════════════════════════════════════════════ -->
<h4 class="section-title mb-3">👤 Progression par utilisateur</h4>
<div class="card mb-5">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Utilisateur</th><th>Exercices terminés</th><th>Progression</th><th>Notes</th><th>Reps renseignées</th></tr></thead>
            <tbody>
                <?php if (empty($progressionUsers)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">Aucun utilisateur.</td></tr>
                <?php else: ?>
                    <?php foreach ($progressionUsers as $u): ?>
                    <?php
                        $pct = $u['total_exercices'] > 0 ? round(($u['termines'] / $u['total_exercices']) * 100) : 0;
                        $bc  = $pct >= 80 ? '#2d5a27' : ($pct >= 40 ? '#e67e22' : '#dc3545');
                    ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($u['nom']) ?></strong>
                            <br><span class="text-muted small">@<?= htmlspecialchars($u['username']) ?></span>
                        </td>
                        <td><?= (int)$u['termines'] ?> / <?= (int)$u['total_exercices'] ?></td>
                        <td style="min-width:150px;">
                            <div class="progress" style="height:10px;">
                                <div class="progress-bar" style="width:<?= $pct ?>%;background:<?= $bc ?>;"></div>
                            </div>
                            <small class="text-muted"><?= $pct ?>%</small>
                        </td>
                        <td><?= (int)$u['nb_notes'] > 0 ? '<span class="badge bg-info text-dark">📝 '.$u['nb_notes'].'</span>' : '<span class="text-muted">—</span>' ?></td>
                        <td><?= (int)$u['nb_reps'] > 0 ? '<span class="badge bg-primary">✅ '.$u['nb_reps'].'</span>' : '<span class="badge bg-secondary">Aucune</span>' ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
require_once 'C:/xampp/htdocs/gestion_plan/footer.php'; 
?>