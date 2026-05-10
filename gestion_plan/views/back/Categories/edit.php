<?php
require_once dirname(__DIR__, 3) . '/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">Modifier la Categorie</h2>
    <a href="index.php?module=categorie&action=index&office=back" class="btn btn-secondary">← Retour</a>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="index.php?module=categorie&action=update&office=back"
              onsubmit="return validerFormulaire()">

            <!-- ✅ Utilisation des GETTERS car $categorie est un objet -->
            <input type="hidden" name="id" value="<?php echo $categorie->getId(); ?>">

            <!-- Nom -->
            <div class="mb-3">
                <label class="form-label fw-bold">Nom de la categorie *</label>
                <input type="text" name="nom" id="nom" class="form-control"
                       value="<?php echo htmlspecialchars(isset($errors) && !empty($errors) ? ($_POST['nom'] ?? '') : $categorie->getNom()); ?>">
                <div class="error-msg text-danger small" id="err-nom">
                    <?php echo isset($errors['nom']) ? $errors['nom'] : ''; ?>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label class="form-label fw-bold">Description</label>
                <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars(isset($errors) && !empty($errors) ? ($_POST['description'] ?? '') : ($categorie->getDescription() ?? '')); ?></textarea>
            </div>

            <button type="submit" class="btn btn-green w-100">Enregistrer les modifications</button>
        </form>
    </div>
</div>

<script>
function validerFormulaire() {
    let valide = true;
    document.getElementById('err-nom').textContent = '';

    const nom = document.getElementById('nom').value.trim();
    if (nom === '') {
        document.getElementById('err-nom').textContent = 'Le nom est obligatoire.';
        valide = false;
    } else if (nom.length < 2) {
        document.getElementById('err-nom').textContent = 'Le nom doit avoir au moins 2 caracteres.';
        valide = false;
    }

    return valide;
}
</script>

<?php
require_once dirname(__DIR__, 3) . '/footer.php';
?>