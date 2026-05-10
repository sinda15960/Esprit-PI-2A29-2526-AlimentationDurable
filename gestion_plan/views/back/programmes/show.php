<?php
require_once dirname(__DIR__, 3) . '/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">📋 Détail du Programme</h2>
    <div class="d-flex gap-2">
        <!-- ✅ Bouton Export PDF -->
        <a href="index.php?module=programme&action=exportPdf&id=<?= $programme['id'] ?>&office=back"
           class="btn btn-danger" target="_blank">
            📄 Exporter PDF
        </a>
        <a href="index.php?module=programme&action=edit&id=<?= $programme['id'] ?>&office=back"
           class="btn btn-warning">✏️ Modifier</a>
        <?php if (!empty($programme['objectif_id'])): ?>
            <a href="index.php?module=objectif&action=show&id=<?= $programme['objectif_id'] ?>&office=back"
               class="btn btn-secondary">← Retour</a>
        <?php else: ?>
            <a href="index.php?module=objectif&action=index&office=back"
               class="btn btn-secondary">← Retour</a>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="text-success fw-bold mb-3"><?= htmlspecialchars($programme['nom']) ?></h5>
                <table class="table table-borderless small">
                    <tr>
                        <td class="fw-bold">Niveau</td>
                        <td><span class="badge bg-info text-dark"><?= htmlspecialchars($programme['niveau']) ?></span></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Durée</td>
                        <td><?= $programme['duree_semaines'] ?> semaine(s)</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Description</td>
                        <td><?= htmlspecialchars($programme['description'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Objectif lié</td>
                        <td><?= htmlspecialchars($programme['objectif_titre'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Créé le</td>
                        <td><?= date('d/m/Y', strtotime($programme['date_creation'])) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">🏋️ Exercices liés</h6>
                    <a href="index.php?module=exercice&action=create&programme_id=<?= $programme['id'] ?>&office=back"
                       class="btn btn-sm btn-green">+ Ajouter</a>
                </div>

                <?php if (empty($exercices)): ?>
                    <div class="alert alert-info small">Aucun exercice lié à ce programme.</div>
                <?php else: ?>
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Ordre</th>
                                <th>Nom</th>
                                <th>Durée (min)</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($exercices as $ex): ?>
                            <tr>
                                <td><?= $ex['ordre'] ?></td>
                                <td><?= htmlspecialchars($ex['nom']) ?></td>
                                <td><?= $ex['duree_minutes'] ?? '-' ?></td>
                                <td>
                                    <?php
                                    $statut = $ex['statut'] ?? 'en_attente';
                                    $colors = ['termine' => 'success', 'en_cours' => 'warning', 'en_attente' => 'secondary'];
                                    $color = $colors[$statut] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $color ?>"><?= htmlspecialchars($statut) ?></span>
                                </td>
                                <td class="d-flex gap-1">
                                    <a href="index.php?module=exercice&action=edit&id=<?= $ex['id'] ?>&office=back"
                                       class="btn btn-sm btn-warning">✏️</a>
                                    <a href="index.php?module=exercice&action=delete&id=<?= $ex['id'] ?>&programme_id=<?= $programme['id'] ?>&office=back"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Supprimer cet exercice ?')">🗑️</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
require_once dirname(__DIR__, 3) . '/footer.php';
?>