<?php
require_once dirname(__DIR__, 3) . '/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">Categories</h2>
    <a href="index.php?module=categorie&action=create&office=back" class="btn btn-green">+ Ajouter une categorie</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($categories)): ?>
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        Aucune categorie. 
                        <a href="index.php?module=categorie&action=create&office=back">Ajouter la premiere</a>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($categories as $c): ?>
                <tr>
                    <!-- ✅ Utilisation des GETTERS car $c est un objet Categorie -->
                    <td><?php echo $c->getId(); ?></td>
                    <td><strong><?php echo htmlspecialchars($c->getNom()); ?></strong></td>
                    <td><?php echo htmlspecialchars($c->getDescription() ?? '-'); ?></td>
                    <td>
                        <a href="index.php?module=categorie&action=edit&id=<?php echo $c->getId(); ?>&office=back"
                           class="btn btn-sm btn-warning">Modifier</a>
                        <a href="index.php?module=categorie&action=delete&id=<?php echo $c->getId(); ?>&office=back"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Supprimer cette categorie ?')">Supprimer</a>
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