<?php 
require_once 'C:/xampp/htdocs/gestion_plan/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">🎯 Détail de l'Objectif</h2>
    <div class="d-flex gap-2">
        <a href="index.php?module=objectif&action=edit&office=back&id=<?= $objectif['id'] ?>" 
           class="btn btn-warning">✏️ Modifier</a>
        <a href="index.php?module=objectif&action=index&office=back" 
           class="btn btn-secondary">← Retour</a>
    </div>
</div>

<div class="row g-4">
    <!-- Infos objectif -->
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="text-success fw-bold mb-3"><?= htmlspecialchars($objectif['titre']) ?></h5>
                <table class="table table-borderless small">
                    <tr>
                        <td class="fw-bold">Type</td>
                        <td><span class="badge" style="background:#2d5a27"><?= htmlspecialchars($objectif['type_objectif']) ?></span></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Description</td>
                        <td><?= htmlspecialchars($objectif['description'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Maladies</td>
                        <td><?= htmlspecialchars($objectif['maladies'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Préférences</td>
                        <td><?= htmlspecialchars($objectif['preferences'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Calories</td>
                        <td><?= $objectif['calories_min'] ?> — <?= $objectif['calories_max'] ?> kcal</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Créé le</td>
                        <td><?= date('d/m/Y', strtotime($objectif['date_creation'])) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Programmes liés -->
    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">📋 Programmes liés</h6>
                    <a href="index.php?module=programme&action=create&office=back" 
                       class="btn btn-sm btn-green">+ Ajouter</a>
                </div>
                <?php if (empty($programmes)): ?>
                    <div class="alert alert-info small">Aucun programme lié à cet objectif.</div>
                <?php else: ?>
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Niveau</th>
                                <th>Durée</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($programmes as $p): ?>
                            <tr>
                                <td><?= htmlspecialchars($p['nom']) ?></td>
                                <td><span class="badge bg-info text-dark"><?= $p['niveau'] ?></span></td>
                                <td><?= $p['duree_semaines'] ?> sem.</td>
                                <td>
                                    <a href="index.php?module=programme&action=edit&id=<?= $p['id'] ?>&office=back" 
                                       class="btn btn-sm btn-warning">✏️</a>
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
require_once 'C:/xampp/htdocs/gestion_plan/footer.php'; 
?>