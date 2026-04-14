<?php 
require_once 'C:/xampp/htdocs/gestion_plan/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title mb-0">📋 Gestion des Programmes</h2>
    <a href="index.php?module=programme&action=create&office=back" class="btn btn-green">+ Nouveau Programme</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Durée</th>
                    <th>Niveau</th>
                    <th>Objectif lié</th>
                    <th>Date création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($programmes)): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">Aucun programme trouvé.</td></tr>
                <?php else: ?>
                <?php foreach ($programmes as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['nom']) ?></td>
                    <td><?= $p['duree_semaines'] ?> semaine(s)</td>
                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($p['niveau']) ?></span></td>
                    <td><?= htmlspecialchars($p['objectif_titre'] ?? '-') ?></td>
                    <td><?= date('d/m/Y', strtotime($p['date_creation'])) ?></td>
                    <td>
                        <a href="index.php?module=programme&action=edit&id=<?= $p['id'] ?>&office=back" class="btn btn-sm btn-warning">✏️</a>
                        <a href="index.php?module=programme&action=delete&id=<?= $p['id'] ?>&office=back"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Supprimer ce programme ?')">🗑️</a>
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