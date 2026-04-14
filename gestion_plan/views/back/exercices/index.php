<?php 
require_once 'C:/xampp/htdocs/gestion_plan/header.php'; 
?>

<div class="container mt-4">
    <h2 class="section-title">🎯 Nos Objectifs Santé</h2>

    <?php if (empty($objectifs)): ?>
        <div class="alert alert-info">Aucun objectif disponible pour le moment.</div>
    <?php else: ?>
    <div class="row g-4">
        <?php foreach ($objectifs as $o): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 p-3">
                <h5 class="text-success fw-bold"><?= htmlspecialchars($o['titre']) ?></h5>
                <span class="badge bg-success mb-2"><?= htmlspecialchars($o['type_objectif']) ?></span>
                <p class="text-muted small"><?= htmlspecialchars($o['description'] ?? '') ?></p>
                <?php if (!empty($o['maladies'])): ?>
                    <p class="small"><strong>Maladies :</strong> <?= htmlspecialchars($o['maladies']) ?></p>
                <?php endif; ?>
                <?php if (!empty($o['preferences'])): ?>
                    <p class="small"><strong>Préférences :</strong> <?= htmlspecialchars($o['preferences']) ?></p>
                <?php endif; ?>
                <?php if (!empty($o['calories_cible'])): ?>
                    <p class="small"><strong>Calories cible :</strong> <?= $o['calories_cible'] ?> kcal</p>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php 
require_once 'C:/xampp/htdocs/gestion_plan/footer.php'; 
?>