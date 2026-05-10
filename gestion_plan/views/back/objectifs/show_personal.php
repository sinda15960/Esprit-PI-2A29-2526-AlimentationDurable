<?php
require_once dirname(__DIR__, 3) . '/header.php';
require_once __DIR__ . '/../../../config.php';

$pdo = getConnection();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">Objectif Personnel</h2>
    <div class="d-flex gap-2">
        <!-- ✅ URL corrigée : module=pdf&action=exportObjectif -->
        <a href="index.php?module=pdf&action=exportObjectif&id=<?= $objectif['id'] ?>&office=back"
           class="btn btn-danger" target="_blank">
            📄 Exporter PDF
        </a>
        <a href="index.php?module=objectif&action=index&office=back" class="btn btn-secondary">← Retour</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="text-success fw-bold mb-1"><?php echo htmlspecialchars($objectif['titre']); ?></h5>
                <span class="badge bg-secondary mb-3">Utilisateur #<?php echo $objectif['user_id']; ?></span>
                <table class="table table-borderless small">
                    <tr>
                        <td class="fw-bold">Description</td>
                        <td><?php echo htmlspecialchars($objectif['description'] ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Poids actuel</td>
                        <td><?php echo $objectif['poids_actuel'] ? $objectif['poids_actuel'] . ' kg' : '-'; ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Poids cible</td>
                        <td><?php echo $objectif['poids_cible'] ? $objectif['poids_cible'] . ' kg' : '-'; ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Taille</td>
                        <td><?php echo $objectif['taille'] ? $objectif['taille'] . ' m' : '-'; ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">IMC</td>
                        <td>
                            <?php
                            if ($objectif['poids_actuel'] && $objectif['taille'] && $objectif['taille'] > 0) {
                                $imc = round($objectif['poids_actuel'] / ($objectif['taille'] * $objectif['taille']), 1);
                                echo '<strong>' . $imc . '</strong>';
                                if ($imc < 18.5)     echo ' <span class="badge bg-info">Insuffisant</span>';
                                elseif ($imc < 25)   echo ' <span class="badge bg-success">Normal</span>';
                                elseif ($imc < 30)   echo ' <span class="badge bg-warning">Surpoids</span>';
                                else                 echo ' <span class="badge bg-danger">Obesite</span>';
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Age</td>
                        <td><?php echo $objectif['age'] ? $objectif['age'] . ' ans' : '-'; ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Etat de sante</td>
                        <td><?php echo htmlspecialchars($objectif['etat_sante'] ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Date debut</td>
                        <td><?php echo $objectif['date_debut'] ?? '-'; ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Date fin prevue</td>
                        <td><?php echo $objectif['date_fin_prevue'] ?? '-'; ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Cree le</td>
                        <td><?php echo date('d/m/Y', strtotime($objectif['date_creation'])); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Programmes assignes par l'admin</h6>
                    <a href="index.php?module=programme&action=create&objectif_id=<?php echo $objectif['id']; ?>&office=back"
                       class="btn btn-sm btn-green">+ Ajouter un programme</a>
                </div>

                <?php if (empty($programmes)): ?>
                    <div class="alert alert-info small">
                        Aucun programme assigne. Cliquez sur <strong>"+ Ajouter un programme"</strong>.
                    </div>
                <?php else: ?>
                    <?php foreach ($programmes as $p): ?>
                    <div class="card mb-3 border-start border-success border-3">
                        <div class="card-body pb-2">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <strong class="text-success"><?php echo htmlspecialchars($p['nom']); ?></strong>
                                    <span class="badge bg-info text-dark ms-2"><?php echo htmlspecialchars($p['niveau']); ?></span>
                                    <span class="text-muted small ms-2"><?php echo $p['duree_semaines']; ?> sem.</span>
                                </div>
                                <div class="d-flex gap-1">
                                    <!-- ✅ Export PDF programme -->
                                    <a href="index.php?module=pdf&action=exportProgramme&id=<?= $p['id'] ?>&office=back"
                                       target="_blank" class="btn btn-sm btn-danger">📄 PDF</a>
                                    <a href="index.php?module=exercice&action=create&programme_id=<?php echo $p['id']; ?>&office=back"
                                       class="btn btn-sm btn-green">+ Exercice</a>
                                    <a href="index.php?module=programme&action=edit&id=<?php echo $p['id']; ?>&office=back"
                                       class="btn btn-sm btn-warning">Modifier</a>
                                    <a href="index.php?module=programme&action=delete&id=<?php echo $p['id']; ?>&objectif_id=<?php echo $objectif['id']; ?>&office=back"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Supprimer ce programme ?')">Supprimer</a>
                                </div>
                            </div>

                            <?php
                            $stmtEx = $pdo->prepare("SELECT e.* FROM exercice e WHERE e.programme_id = ? ORDER BY e.ordre");
                            $stmtEx->execute([$p['id']]);
                            $exercices = $stmtEx->fetchAll();
                            ?>

                            <?php if (empty($exercices)): ?>
                                <div class="alert alert-light small py-1 mb-0">Aucun exercice.</div>
                            <?php else: ?>
                                <table class="table table-sm table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Ordre</th><th>Nom</th><th>Duree</th>
                                            <th>Statut</th><th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($exercices as $ex): ?>
                                        <?php
                                        $statut = $ex['statut'] ?? 'en_attente';
                                        $colors = ['termine' => 'success', 'en_cours' => 'warning', 'en_attente' => 'secondary'];
                                        $color  = $colors[$statut] ?? 'secondary';
                                        ?>
                                        <tr>
                                            <td><?= $ex['ordre'] ?></td>
                                            <td><?= htmlspecialchars($ex['nom']) ?></td>
                                            <td><?= $ex['duree_minutes'] ?? '-' ?> min</td>
                                            <td><span class="badge bg-<?= $color ?>"><?= $statut ?></span></td>
                                            <td class="d-flex gap-1">
                                                <a href="index.php?module=exercice&action=edit&id=<?= $ex['id'] ?>&office=back"
                                                   class="btn btn-sm btn-warning">Modifier</a>
                                                <a href="index.php?module=exercice&action=delete&id=<?= $ex['id'] ?>&programme_id=<?= $p['id'] ?>&office=back"
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Supprimer ?')">Supprimer</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__, 3) . '/footer.php'; ?>