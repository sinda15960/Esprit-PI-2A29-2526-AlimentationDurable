<?php 
require_once 'C:/xampp/htdocs/gestion_plan/header.php'; 
?>

<div class="container mt-4">
    <h2 class="section-title">🏋️ Exercices étape par étape</h2>

    <?php if (empty($exercices)): ?>
        <div class="alert alert-info">Aucun exercice disponible pour ce programme.</div>
    <?php else: ?>
        <?php
            $programme_nom = $exercices[0]['programme_nom'] ?? 'Programme';
            $programme_id  = $exercices[0]['programme_id'] ?? 0;
        ?>
        <h4 class="mt-4 mb-3 text-success">📋 Programme : <?= htmlspecialchars($programme_nom) ?></h4>

        <?php foreach ($exercices as $index => $e): ?>
            <?php
                $statut     = $e['statut'] ?? 'en_attente';
                $estTermine = ($statut === 'termine');
                $estActif   = ($statut === 'en_cours');
                $estBloque  = ($statut === 'en_attente');
            ?>

            <div class="card mb-3 <?= $estTermine ? 'border-success' : '' ?>"
                 style="<?= $estBloque ? 'opacity:0.45;filter:grayscale(60%);pointer-events:none;' : '' ?>">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="mb-1">
                                <?php if ($estTermine): ?>✅
                                <?php elseif ($estActif): ?>▶️
                                <?php else: ?>🔒
                                <?php endif; ?>
                                Étape <?= $e['ordre'] ?> — <?= htmlspecialchars($e['nom']) ?>
                            </h5>
                            <p class="text-muted small mb-1"><?= htmlspecialchars($e['description'] ?? '') ?></p>

                            <?php if (!empty($e['duree_minutes'])): ?>
                                <span class="badge bg-light text-dark">⏱ <?= $e['duree_minutes'] ?> min</span>
                            <?php endif; ?>

                            <?php if ($estBloque): ?>
                                <span class="badge bg-secondary ms-2">🔒 Terminez l'étape précédente</span>
                            <?php endif; ?>
                        </div>

                        <div class="ms-3">
                            <?php if ($estTermine): ?>
                                <span class="badge bg-success fs-6">✅ Terminé</span>

                            <?php elseif ($estActif): ?>
                                <a href="index.php?module=exercice&action=validerEtape&id=<?= $e['id'] ?>&programme_id=<?= $programme_id ?>&office=front"
                                   class="btn btn-success"
                                   onclick="return confirm('Marquer cet exercice comme terminé ?')">
                                    ✔ Valider
                                </a>

                            <?php else: ?>
                                <span class="badge bg-secondary fs-6">🔒 Verrouillé</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($e['video_url']) && !$estBloque): ?>
                        <div class="mt-3">
                            <iframe width="320" height="180"
                                src="<?= htmlspecialchars($e['video_url']) ?>"
                                frameborder="0"
                                allowfullscreen
                                style="border-radius:8px;">
                            </iframe>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        <?php endforeach; ?>

        <?php
            $tousTermines = !in_array(false, array_map(
                fn($ex) => $ex['statut'] === 'termine', $exercices
            ));
        ?>

        <?php if ($tousTermines): ?>
            <div class="alert alert-success mt-3">
                🎉 Félicitations ! Vous avez terminé tous les exercices de ce programme !
            </div>
            <a href="index.php?module=exercice&action=resetProgramme&programme_id=<?= $programme_id ?>&office=front"
               class="btn btn-warning mt-2"
               onclick="return confirm('Recommencer ce programme depuis le début ?')">
                🔄 Recommencer le programme
            </a>
        <?php endif; ?>

    <?php endif; ?>

    <div class="mt-4">
        <a href="index.php?module=programme&action=index&office=front"
           class="btn btn-secondary">← Retour aux programmes</a>
    </div>
</div>

<?php 
require_once 'C:/xampp/htdocs/gestion_plan/footer.php'; 
?>