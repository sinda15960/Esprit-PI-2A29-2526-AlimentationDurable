<?php 
require_once 'C:/xampp/htdocs/gestion_plan/header.php'; 
?>

<div class="container mt-4">
    <h2 class="section-title">📋 Nos Programmes</h2>

    <?php if (empty($programmes)): ?>
        <div class="alert alert-info">Aucun programme disponible pour le moment.</div>
    <?php else: ?>
    <div class="row g-4">
        <?php foreach ($programmes as $p): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 p-3">
                <h5 class="text-success fw-bold"><?= htmlspecialchars($p['nom']) ?></h5>
                <span class="badge bg-info text-dark mb-2"><?= htmlspecialchars($p['niveau']) ?></span>
                <p class="text-muted small"><?= htmlspecialchars($p['description'] ?? '') ?></p>
                <p class="small"><strong>Durée :</strong> <?= $p['duree_semaines'] ?> semaine(s)</p>
                <?php if (!empty($p['objectif_titre'])): ?>
                    <p class="small"><strong>Objectif :</strong> <?= htmlspecialchars($p['objectif_titre']) ?></p>
                <?php endif; ?>
                <!-- LIEN MODIFIÉ : passe le programme_id pour filtrer les exercices -->
                <a href="index.php?module=exercice&action=indexByProgramme&programme_id=<?= $p['id'] ?>&office=front" 
                   class="btn btn-sm btn-green mt-auto">🏋️ Voir les exercices</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    
    <!-- BOUTON RETOUR -->
    <div class="mt-4">
        <a href="index.php?module=objectif&action=index&office=front" class="btn btn-secondary">← Retour aux objectifs</a>
    </div>
</div>

<?php 
require_once 'C:/xampp/htdocs/gestion_plan/footer.php'; 
?>