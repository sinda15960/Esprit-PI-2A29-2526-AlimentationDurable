<?php
require_once dirname(__DIR__, 3) . '/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">Nouvelle Categorie</h2>
    <a href="index.php?module=categorie&action=index&office=back" class="btn btn-secondary">← Retour</a>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="index.php?module=categorie&action=store&office=back"
              onsubmit="return validerFormulaire()">

            <!-- Nom -->
            <div class="mb-3">
                <label class="form-label fw-bold">Nom de la categorie *</label>
                <input type="text" name="nom" id="nom" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>"
                       placeholder="Ex: Perte de poids, Musculation...">
                <div class="error-msg text-danger small" id="err-nom">
                    <?php echo $errors['nom'] ?? ''; ?>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label class="form-label fw-bold">Description</label>
                <textarea name="description" id="description" class="form-control" rows="3"
                          placeholder="Decrivez cette categorie..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                <div class="error-msg text-danger small" id="err-description"></div>
            </div>

            <button type="submit" class="btn btn-green w-100">Enregistrer</button>
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