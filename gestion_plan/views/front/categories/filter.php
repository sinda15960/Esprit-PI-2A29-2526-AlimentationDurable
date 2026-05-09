<?php
require_once 'C:/xampp/htdocs/gestion_plan/header.php';
?>

<h2 class="section-title">Recherche de programmes par categorie</h2>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="index.php" onsubmit="return validerFormulaire()">
            <input type="hidden" name="module" value="categorie">
            <input type="hidden" name="action" value="filterByCategorie">
            <input type="hidden" name="office" value="front">

            <div class="row">
                <div class="col-md-8">
                    <label class="form-label fw-bold">Selectionnez une categorie :</label>
                    <select name="categorie_id" id="categorie_id" class="form-select">
                        <option value="">-- Choisir une categorie --</option>
                        <?php foreach ($categories as $cat): ?>
                            <!-- ✅ Utilisation des GETTERS car $cat est un objet Categorie -->
                            <option value="<?php echo $cat->getId(); ?>"
                                <?php echo (isset($_GET['categorie_id']) && $_GET['categorie_id'] == $cat->getId()) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat->getNom()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="text-danger small" id="err-categorie"></div>
                </div>
                <div class="col-md-4 d-flex align-items-end mt-2">
                    <button type="submit" name="search" class="btn btn-green w-100">Rechercher</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if (isset($_GET['categorie_id']) && intval($_GET['categorie_id']) > 0): ?>
    <h4 class="section-title mt-4">Programmes correspondants</h4>

    <?php if (empty($programmesFiltres)): ?>
        <div class="alert alert-info">Aucun programme trouve pour cette categorie.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($programmesFiltres as $p): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 p-3">
                    <h5 class="text-success fw-bold"><?php echo htmlspecialchars($p['nom']); ?></h5>
                    <span class="badge bg-info text-dark mb-2"><?php echo htmlspecialchars($p['niveau']); ?></span>
                    <p class="text-muted small"><?php echo htmlspecialchars($p['description'] ?? ''); ?></p>
                    <p class="small"><strong>Duree :</strong> <?php echo $p['duree_semaines']; ?> semaine(s)</p>
                    <p class="small"><strong>Categorie :</strong> <?php echo htmlspecialchars($p['categorie_nom']); ?></p>
                    <a href="index.php?module=exercice&action=indexByProgramme&programme_id=<?php echo $p['id']; ?>&office=front"
                       class="btn btn-sm btn-green mt-auto">Voir les exercices</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="mt-4">
    <a href="index.php?module=objectif&action=index&office=front" class="btn btn-secondary">← Retour aux objectifs</a>
</div>

<script>
function validerFormulaire() {
    let valide = true;
    document.getElementById('err-categorie').textContent = '';
    document.getElementById('categorie_id').classList.remove('is-invalid');

    const categorie = document.getElementById('categorie_id').value;
    if (categorie === '') {
        document.getElementById('err-categorie').textContent = 'Veuillez selectionner une categorie.';
        document.getElementById('categorie_id').classList.add('is-invalid');
        valide = false;
    }

    return valide;
}
</script>

<?php
require_once 'C:/xampp/htdocs/gestion_plan/footer.php';
?>