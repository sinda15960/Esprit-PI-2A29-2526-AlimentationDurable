<?php
require_once dirname(__DIR__, 3) . '/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">❤️ Mes Programmes Favoris</h2>
    <a href="index.php?module=programme&action=index&office=front" class="btn btn-secondary btn-sm">
        📋 Voir tous les programmes
    </a>
</div>

<?php if (empty($favoris)): ?>
    <div class="alert alert-info">
        Vous n'avez pas encore de programme favori.
        <a href="index.php?module=programme&action=index&office=front" class="btn btn-sm btn-green ms-2">
            Parcourir les programmes
        </a>
    </div>
<?php else: ?>
    <p class="text-muted small mb-3"><?= count($favoris) ?> programme(s) en favori</p>
    <div class="row g-4">
        <?php foreach ($favoris as $f): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 p-3 border-start border-danger border-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="text-success fw-bold mb-0"><?= htmlspecialchars($f['programme_nom']) ?></h5>
                    <!-- ✅ Bouton supprimer favori -->
                    <a href="index.php?module=favori&action=supprimer&programme_id=<?= $f['programme_id'] ?>&office=front"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Retirer ce programme de vos favoris ?')">
                        ❤️ Retirer
                    </a>
                </div>
                <span class="badge bg-info text-dark mb-2"><?= htmlspecialchars($f['niveau']) ?></span>
                <p class="text-muted small"><?= htmlspecialchars($f['programme_description'] ?? '') ?></p>
                <p class="small"><strong>Durée :</strong> <?= $f['duree_semaines'] ?> semaine(s)</p>
                <?php if (!empty($f['objectif_titre'])): ?>
                    <p class="small"><strong>Objectif :</strong> <?= htmlspecialchars($f['objectif_titre']) ?></p>
                <?php endif; ?>
                <?php if (!empty($f['categorie_nom'])): ?>
                    <p class="small"><strong>Catégorie :</strong> <?= htmlspecialchars($f['categorie_nom']) ?></p>
                <?php endif; ?>
                <p class="text-muted small">❤️ Ajouté le <?= htmlspecialchars($f['date_ajout']) ?></p>
                <a href="index.php?module=exercice&action=indexByProgramme&programme_id=<?= $f['programme_id'] ?>&office=front"
                   class="btn btn-sm btn-green mt-auto">🏋️ Voir les exercices</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
require_once dirname(__DIR__, 3) . '/footer.php';
?>