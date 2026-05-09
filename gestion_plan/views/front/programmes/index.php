<?php 
require_once 'C:/xampp/htdocs/gestion_plan/header.php'; 
?>

<h2 class="section-title">📋 Nos Programmes</h2>

<!-- ✅ Formulaire Tri + Filtrage avec validation PHP -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="index.php" id="formFiltrage" onsubmit="return validerFiltres()">
            <input type="hidden" name="module" value="programme">
            <input type="hidden" name="action" value="index">
            <input type="hidden" name="office" value="front">

            <div class="row g-3 align-items-end">
                <!-- Filtre niveau -->
                <div class="col-md-3">
                    <label class="form-label fw-bold">Niveau</label>
                    <select name="niveau" id="niveau" class="form-select">
                        <option value="">-- Tous les niveaux --</option>
                        <option value="debutant"      <?= (($_GET['niveau'] ?? '') === 'debutant')      ? 'selected' : '' ?>>Débutant</option>
                        <option value="intermediaire" <?= (($_GET['niveau'] ?? '') === 'intermediaire') ? 'selected' : '' ?>>Intermédiaire</option>
                        <option value="avance"        <?= (($_GET['niveau'] ?? '') === 'avance')        ? 'selected' : '' ?>>Avancé</option>
                    </select>
                    <?php if (!empty($errors['niveau'])): ?>
                        <div class="text-danger small"><?= htmlspecialchars($errors['niveau']) ?></div>
                    <?php endif; ?>
                </div>

                <!-- Filtre catégorie -->
                <div class="col-md-3">
                    <label class="form-label fw-bold">Catégorie</label>
                    <select name="categorie_id" id="categorie_id" class="form-select">
                        <option value="0">-- Toutes les catégories --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id_categorie'] ?>"
                                <?= (intval($_GET['categorie_id'] ?? 0) === $cat['id_categorie']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tri -->
                <div class="col-md-3">
                    <label class="form-label fw-bold">Trier par</label>
                    <select name="tri" id="tri" class="form-select">
                        <option value="">-- Aucun tri --</option>
                        <option value="niveau_asc"  <?= (($_GET['tri'] ?? '') === 'niveau_asc')  ? 'selected' : '' ?>>Niveau ↑ (débutant → avancé)</option>
                        <option value="niveau_desc" <?= (($_GET['tri'] ?? '') === 'niveau_desc') ? 'selected' : '' ?>>Niveau ↓ (avancé → débutant)</option>
                        <option value="duree_asc"   <?= (($_GET['tri'] ?? '') === 'duree_asc')   ? 'selected' : '' ?>>Durée ↑ (court → long)</option>
                        <option value="duree_desc"  <?= (($_GET['tri'] ?? '') === 'duree_desc')  ? 'selected' : '' ?>>Durée ↓ (long → court)</option>
                    </select>
                    <?php if (!empty($errors['tri'])): ?>
                        <div class="text-danger small"><?= htmlspecialchars($errors['tri']) ?></div>
                    <?php endif; ?>
                </div>

                <!-- Boutons -->
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-green flex-fill">🔍 Filtrer</button>
                    <a href="index.php?module=programme&action=index&office=front" class="btn btn-outline-secondary flex-fill">✖ Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Résultats -->
<?php if (empty($programmes)): ?>
    <div class="alert alert-info">Aucun programme trouvé pour ces critères.</div>
<?php else: ?>
    <p class="text-muted small mb-3"><?= count($programmes) ?> programme(s) trouvé(s)</p>
    <div class="row g-4">
        <?php foreach ($programmes as $p): 
            $estFavori = in_array($p['id'], $favorisUser ?? []);
        ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="text-success fw-bold mb-0"><?= htmlspecialchars($p['nom']) ?></h5>
                    <!-- ✅ Bouton favori -->
                    <a href="index.php?module=favori&action=toggle&programme_id=<?= $p['id'] ?>&redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>&office=front"
                       title="<?= $estFavori ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>"
                       class="btn btn-sm <?= $estFavori ? 'btn-danger' : 'btn-outline-danger' ?>">
                        <?= $estFavori ? '❤️' : '🤍' ?>
                    </a>
                </div>
                <span class="badge bg-info text-dark mb-2"><?= htmlspecialchars($p['niveau']) ?></span>
                <p class="text-muted small"><?= htmlspecialchars($p['description'] ?? '') ?></p>
                <p class="small"><strong>Durée :</strong> <?= $p['duree_semaines'] ?> semaine(s)</p>
                <?php if (!empty($p['objectif_titre'])): ?>
                    <p class="small"><strong>Objectif :</strong> <?= htmlspecialchars($p['objectif_titre']) ?></p>
                <?php endif; ?>
                <?php if (!empty($p['categorie_nom'])): ?>
                    <p class="small"><strong>Catégorie :</strong> <?= htmlspecialchars($p['categorie_nom']) ?></p>
                <?php endif; ?>
                <a href="index.php?module=exercice&action=indexByProgramme&programme_id=<?= $p['id'] ?>&office=front" 
                   class="btn btn-sm btn-green mt-auto">🏋️ Voir les exercices</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="mt-4">
    <a href="index.php?module=objectif&action=index&office=front" class="btn btn-secondary">← Retour aux objectifs</a>
</div>

<script>
function validerFiltres() {
    // Validation côté client (PHP fait la validation côté serveur)
    // Ici on vérifie juste que les selects ont des valeurs cohérentes
    return true; // Le serveur PHP revalide
}
</script>

<?php 
require_once 'C:/xampp/htdocs/gestion_plan/footer.php'; 
?>