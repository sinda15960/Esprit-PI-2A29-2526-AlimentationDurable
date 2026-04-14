<?php 
require_once 'C:/xampp/htdocs/gestion_plan/header.php'; 
?>

<h2 class="section-title">✏️ Modifier le Programme</h2>

<div class="card">
    <div class="card-body">
        <form method="POST" action="index.php?module=programme&action=update&office=back" id="formProgramme">
            <input type="hidden" name="id" value="<?= $programme['id'] ?>">

            <div class="mb-3">
                <label class="form-label fw-bold">Nom *</label>
                <input type="text" name="nom" id="nom" class="form-control <?= isset($errors['nom']) ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($_POST['nom'] ?? $programme['nom']) ?>">
                <div class="text-danger small" id="err_nom"><?= $errors['nom'] ?? '' ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Description</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($_POST['description'] ?? $programme['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Durée (semaines) *</label>
                <input type="number" name="duree_semaines" id="duree_semaines" min="1"
                       class="form-control <?= isset($errors['duree_semaines']) ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($_POST['duree_semaines'] ?? $programme['duree_semaines']) ?>">
                <div class="text-danger small" id="err_duree"><?= $errors['duree_semaines'] ?? '' ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Niveau *</label>
                <select name="niveau" id="niveau" class="form-select <?= isset($errors['niveau']) ? 'is-invalid' : '' ?>">
                    <option value="">-- Choisir --</option>
                    <?php 
                    $currentNiveau = $_POST['niveau'] ?? $programme['niveau'];
                    foreach (['debutant','intermediaire','avance'] as $n): ?>
                    <option value="<?= $n ?>" <?= ($currentNiveau === $n) ? 'selected' : '' ?>><?= ucfirst($n) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="text-danger small" id="err_niveau"><?= $errors['niveau'] ?? '' ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Objectif lié *</label>
                <select name="objectif_id" id="objectif_id" class="form-select">
                    <option value="">-- Choisir un objectif --</option>
                    <?php 
                    $currentObj = $_POST['objectif_id'] ?? $programme['objectif_id'];
                    foreach ($objectifs as $o): ?>
                    <option value="<?= $o['id'] ?>" <?= ($currentObj == $o['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($o['titre']) ?> (<?= $o['type_objectif'] ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
                <div class="text-danger small" id="err_objectif"></div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-green" onclick="return validerForm()">💾 Mettre à jour</button>
                <a href="index.php?module=programme&action=index&office=back" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
function validerForm() {
    let valide = true;
    document.querySelectorAll('.text-danger').forEach(e => e.textContent = '');
    document.querySelectorAll('.form-control, .form-select').forEach(e => e.classList.remove('is-invalid'));

    const nom = document.getElementById('nom').value.trim();
    if (nom.length < 3) {
        document.getElementById('err_nom').textContent = 'Le nom doit avoir au moins 3 caractères.';
        document.getElementById('nom').classList.add('is-invalid');
        valide = false;
    }

    const duree = parseInt(document.getElementById('duree_semaines').value);
    if (!duree || duree < 1) {
        document.getElementById('err_duree').textContent = 'La durée doit être au moins 1 semaine.';
        document.getElementById('duree_semaines').classList.add('is-invalid');
        valide = false;
    }

    const niveau = document.getElementById('niveau').value;
    if (!niveau) {
        document.getElementById('err_niveau').textContent = 'Veuillez choisir un niveau.';
        document.getElementById('niveau').classList.add('is-invalid');
        valide = false;
    }

    const objectif = document.getElementById('objectif_id').value;
    if (!objectif) {
        document.getElementById('err_objectif').textContent = 'Veuillez choisir un objectif.';
        document.getElementById('objectif_id').classList.add('is-invalid');
        valide = false;
    }

    return valide;
}
</script>

<?php 
require_once 'C:/xampp/htdocs/gestion_plan/footer.php'; 
?>