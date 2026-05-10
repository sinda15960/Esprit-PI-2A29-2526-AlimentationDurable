<?php 
require_once dirname(__DIR__, 3) . '/header.php'; 
?>

<!-- ===== OBJECTIFS OFFICIELS ===== -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">🎯 Gestion des Objectifs</h2>
    <a href="index.php?module=objectif&action=create&office=back" class="btn btn-green">+ Ajouter un objectif</a>
</div>

<div class="card mb-5">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Titre</th>
                    <th>Type</th>
                    <th>Calories (min - max)</th>
                    <th>Maladies</th>
                    <th>Préférences</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($objectifs)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        Aucun objectif trouvé.
                        <a href="index.php?module=objectif&action=create&office=back">Ajouter le premier</a>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($objectifs as $o): ?>
                <tr>
                    <td><?= $o['id'] ?></td>
                    <td>
                        <a href="index.php?module=objectif&action=show&office=back&id=<?= $o['id'] ?>" 
                           class="text-success fw-bold text-decoration-none">
                            <?= htmlspecialchars($o['titre']) ?>
                        </a>
                    </td>
                    <td>
                        <span class="badge" style="background:#2d5a27">
                            <?= htmlspecialchars($o['type_objectif']) ?>
                        </span>
                    </td>
                    <td><?= $o['calories_min'] ?? '-' ?> — <?= $o['calories_max'] ?? '-' ?> kcal</td>
                    <td><?= htmlspecialchars($o['maladies'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($o['preferences'] ?? '-') ?></td>
                    <td>
                        <a href="index.php?module=objectif&action=edit&office=back&id=<?= $o['id'] ?>" 
                           class="btn btn-sm btn-warning">✏️ Modifier</a>
                        <a href="index.php?module=objectif&action=delete&office=back&id=<?= $o['id'] ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Supprimer cet objectif ?')">🗑️ Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ===== OBJECTIFS PERSONNELS DES USERS ===== -->
<h2 class="section-title">⭐ Objectifs Personnels des Utilisateurs</h2>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Titre</th>
                    <th>Poids actuel</th>
                    <th>Poids cible</th>
                    <th>Date début</th>
                    <th>Date fin prévue</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($objectifsPersonnels)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        Aucun objectif personnel pour le moment.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($objectifsPersonnels as $o): ?>
                <tr>
                    <td>
                        <span class="badge bg-secondary">
                            👤 User #<?= $o['user_id'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="index.php?module=objectif&action=show&office=back&id=<?= $o['id'] ?>"
                           class="text-success fw-bold text-decoration-none">
                            <?= htmlspecialchars($o['titre']) ?>
                        </a>
                    </td>
                    <td><?= $o['poids_actuel'] ? $o['poids_actuel'] . ' kg' : '-' ?></td>
                    <td><?= $o['poids_cible'] ? $o['poids_cible'] . ' kg' : '-' ?></td>
                    <td><?= $o['date_debut'] ?? '-' ?></td>
                    <td><?= $o['date_fin_prevue'] ?? '-' ?></td>
                    <td>
                        <a href="index.php?module=objectif&action=show&office=back&id=<?= $o['id'] ?>"
                           class="btn btn-sm btn-green">👁️ Voir / Aider</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
require_once dirname(__DIR__, 3) . '/footer.php'; 
?>