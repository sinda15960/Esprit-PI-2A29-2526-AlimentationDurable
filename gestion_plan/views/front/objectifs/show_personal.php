<?php
require_once dirname(__DIR__, 3) . '/header.php';
require_once __DIR__ . '/../../../config.php';

$pdo = getConnection();
?>

<!-- MODALE PERSONNALISEE -->
<div class="modal fade" id="modalConfirm" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background:#2d5a27; color:#fff;">
                <h5 class="modal-title" id="modalTitre">Confirmation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div id="modalIcone" class="mb-3" style="font-size:3rem;">⚠️</div>
                <p id="modalMessage" class="fs-5 fw-bold mb-1"></p>
                <p id="modalSousmessage" class="text-muted small"></p>
            </div>
            <div class="modal-footer justify-content-center gap-3">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Annuler</button>
                <a id="modalBtnConfirm" href="#" class="btn btn-green px-4">Oui, valider</a>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">Mon Objectif : <?php echo htmlspecialchars($objectif['titre']); ?></h2>
    <a href="index.php?module=objectif&action=index&office=front" class="btn btn-secondary">← Retour</a>
</div>

<!-- Infos objectif -->
<div class="card mb-4 border-start border-warning border-3">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <span class="text-muted small">Poids actuel</span><br>
                <strong><?php echo $objectif['poids_actuel'] ? $objectif['poids_actuel'] . ' kg' : '-'; ?></strong>
            </div>
            <div class="col-md-3">
                <span class="text-muted small">Poids cible</span><br>
                <strong><?php echo $objectif['poids_cible'] ? $objectif['poids_cible'] . ' kg' : '-'; ?></strong>
            </div>
            <div class="col-md-3">
                <span class="text-muted small">Date debut</span><br>
                <strong><?php echo $objectif['date_debut'] ?? '-'; ?></strong>
            </div>
            <div class="col-md-3">
                <span class="text-muted small">Date fin prevue</span><br>
                <strong><?php echo $objectif['date_fin_prevue'] ?? '-'; ?></strong>
            </div>
            <?php if (!empty($objectif['etat_sante'])): ?>
            <div class="col-12">
                <span class="text-muted small">Etat de sante</span><br>
                <strong><?php echo htmlspecialchars($objectif['etat_sante']); ?></strong>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Programmes assignes par l'admin -->
<h4 class="section-title mb-3">Programmes assignes par l'admin</h4>

<?php if (empty($programmes)): ?>
    <div class="alert alert-info">
        Aucun programme n'a encore ete assigne par l'administrateur. Revenez plus tard.
    </div>
<?php else: ?>
    <?php foreach ($programmes as $p):
        // Récupérer les exercices du programme directement avec PDO
        $sqlExercices = "SELECT e.*, p.nom AS programme_nom 
                        FROM exercice e
                        LEFT JOIN programme p ON e.programme_id = p.id
                        WHERE e.programme_id = ?
                        ORDER BY e.ordre";
        $stmtExercices = $pdo->prepare($sqlExercices);
        $stmtExercices->execute([$p['id']]);
        $exercices = $stmtExercices->fetchAll();
    ?>
    <div class="card mb-4">
        <div class="card-header" style="background:#2d5a27; color:#fff;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong><?php echo htmlspecialchars($p['nom']); ?></strong>
                    <span class="badge bg-light text-dark ms-2"><?php echo htmlspecialchars($p['niveau']); ?></span>
                    <span class="ms-2 small"><?php echo $p['duree_semaines']; ?> semaine(s)</span>
                </div>

                <!-- Bouton Recommencer ajouté -->
                <a href="index.php?module=exercice&action=resetProgramme&programme_id=<?php echo $p['id']; ?>&objectif_id=<?php echo $objectif['id']; ?>&office=front"
                   class="btn btn-sm btn-warning">🔄 Recommencer</a>
            </div>
            <?php if (!empty($p['description'])): ?>
                <small><?php echo htmlspecialchars($p['description']); ?></small>
            <?php endif; ?>
        </div>

        <div class="card-body p-0">
            <?php if (empty($exercices)): ?>
                <div class="p-3 text-muted small">Aucun exercice dans ce programme pour le moment.</div>
            <?php else: ?>
                <?php foreach ($exercices as $ex): ?>
                    <?php
                    $statut = $ex['statut'] ?? 'en_attente';
                    $isTermine   = $statut === 'termine';
                    $isEnCours   = $statut === 'en_cours';
                    $isVerrouille = $statut === 'en_attente';
                    ?>
                    <div class="p-3 border-bottom <?php echo $isTermine ? 'bg-light' : ''; ?>">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1 <?php echo $isVerrouille ? 'text-muted' : 'text-dark'; ?>">
                                    <?php if ($isTermine): ?>✅
                                    <?php elseif ($isVerrouille): ?>🔒
                                    <?php else: ?>🔵<?php endif; ?>
                                    Etape <?php echo $ex['ordre']; ?> — <?php echo htmlspecialchars($ex['nom']); ?>
                                </h6>

                                <?php if (!empty($ex['description'])): ?>
                                    <p class="text-muted small mb-1"><?php echo htmlspecialchars($ex['description']); ?></p>
                                <?php endif; ?>

                                <?php if (!empty($ex['duree_minutes'])): ?>
                                    <span class="text-muted small">⏱ <?php echo $ex['duree_minutes']; ?> min</span>
                                <?php endif; ?>

                                <?php if ($isVerrouille): ?>
                                    <span class="badge bg-secondary ms-2 small">Terminez l'etape precedente</span>
                                <?php endif; ?>

                                <?php if (!empty($ex['video_url']) && ($isEnCours || $isTermine)): ?>
                                    <div class="mt-2">
                                        <?php
                                        $videoUrl = $ex['video_url'];
                                        $embedUrl = '';
                                        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $videoUrl, $matches)) {
                                            $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                                        }
                                        ?>
                                        <?php if ($embedUrl): ?>
                                            <iframe width="300" height="170" src="<?php echo $embedUrl; ?>"
                                                    frameborder="0" allowfullscreen class="rounded"></iframe>
                                        <?php else: ?>
                                            <a href="<?php echo htmlspecialchars($videoUrl); ?>" target="_blank"
                                               class="btn btn-sm btn-outline-success">Voir la video</a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Actions -->
                            <?php if ($isEnCours): ?>
                                <button type="button" class="btn btn-sm btn-green ms-3"
                                    onclick="ouvrirModale(
                                        '<?php echo addslashes($ex['nom']); ?>',
                                        'index.php?module=exercice&action=validerEtape&id=<?php echo $ex['id']; ?>&programme_id=<?php echo $p['id']; ?>&objectif_id=<?php echo $objectif['id']; ?>&office=front'
                                    )">✔ Valider</button>

                            <?php elseif ($isTermine): ?>
                                <span class="badge bg-success ms-3 p-2">✔ Termine</span>
                            <?php else: ?>
                                <span class="badge bg-secondary ms-3 p-2">🔒 Verrouille</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<script>
function ouvrirModale(nomExercice, url) {
    document.getElementById('modalTitre').textContent = 'Valider l\'exercice';
    document.getElementById('modalIcone').textContent = '✅';
    document.getElementById('modalMessage').textContent = 'Avez-vous termine l\'exercice "' + nomExercice + '" ?';
    document.getElementById('modalSousmessage').textContent = 'Cela marquera cet exercice comme termine et debloquera l\'etape suivante.';
    document.getElementById('modalBtnConfirm').href = url;
    var modal = new bootstrap.Modal(document.getElementById('modalConfirm'));
    modal.show();
}
</script>

<?php
require_once dirname(__DIR__, 3) . '/footer.php';
?>