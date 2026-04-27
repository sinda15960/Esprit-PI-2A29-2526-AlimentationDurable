<?php 
require_once 'C:/xampp/htdocs/gestion_plan/header.php'; 
?>

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
                    <th>Preferences</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($objectifs)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        Aucun objectif trouve.
                        <a href="index.php?module=objectif&action=create&office=back">Ajouter le premier</a>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($objectifs as $o): ?>
                <tr>
                    <td><?php echo $o['id']; ?></td>
                    <td>
                        <a href="index.php?module=objectif&action=show&office=back&id=<?php echo $o['id']; ?>" 
                           class="text-success fw-bold text-decoration-none">
                            <?php echo htmlspecialchars($o['titre']); ?>
                        </a>
                    </td>
                    <td>
                        <span class="badge" style="background:#2d5a27">
                            <?php echo htmlspecialchars($o['type_objectif']); ?>
                        </span>
                    </td>
                    <td><?php echo $o['calories_min'] ?? '-'; ?> - <?php echo $o['calories_max'] ?? '-'; ?> kcal</td>
                    <td><?php echo htmlspecialchars($o['maladies'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($o['preferences'] ?? '-'); ?></td>
                    <td>
                        <a href="index.php?module=objectif&action=edit&office=back&id=<?php echo $o['id']; ?>" 
                           class="btn btn-sm btn-warning">Modifier</a>
                        <a href="index.php?module=objectif&action=delete&office=back&id=<?php echo $o['id']; ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Supprimer cet objectif ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<h2 class="section-title">Objectifs Personnels des Utilisateurs</h2>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Titre</th>
                    <th>Poids actuel</th>
                    <th>Poids cible</th>
                    <th>Date debut</th>
                    <th>Date fin prevue</th>
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
                            <?php echo htmlspecialchars($o['user_nom'] ?? 'User #' . $o['user_id']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="index.php?module=objectif&action=show&office=back&id=<?php echo $o['id']; ?>"
                           class="text-success fw-bold text-decoration-none">
                            <?php echo htmlspecialchars($o['titre']); ?>
                        </a>
                    </td>
                    <td><?php echo $o['poids_actuel'] ? $o['poids_actuel'] . ' kg' : '-'; ?></td>
                    <td><?php echo $o['poids_cible'] ? $o['poids_cible'] . ' kg' : '-'; ?></td>
                    <td><?php echo $o['date_debut'] ?? '-'; ?></td>
                    <td><?php echo $o['date_fin_prevue'] ?? '-'; ?></td>
                    <td>
                        <a href="index.php?module=objectif&action=show&office=back&id=<?php echo $o['id']; ?>"
                           class="btn btn-sm btn-green">Voir / Aider</a>
                    </td>
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