<?php
require_once 'C:/xampp/htdocs/gestion_plan/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">🏋️ Modifier l'Exercice</h2>
    <?php if (!empty($exercice['programme_id'])): ?>
        <a href="index.php?module=programme&action=show&id=<?= $exercice['programme_id'] ?>&office=back" class="btn btn-secondary">← Retour</a>
    <?php else: ?>
        <a href="index.php?module=objectif&action=index&office=back" class="btn btn-secondary">← Retour</a>
    <?php endif; ?>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="index.php?module=exercice&action=update&office=back"
              onsubmit="return validerFormulaire()">

            <input type="hidden" name="id" value="<?= $exercice['id'] ?>">
            <!-- ✅ Conserve le programme_id pour la redirection -->
            <input type="hidden" name="programme_id" value="<?= $exercice['programme_id'] ?>">

            <!-- Nom -->
            <div class="mb-3">
                <label class="form-label fw-bold">Nom de l'exercice</label>
                <input type="text" name="nom" id="nom" class="form-control"
                       value="<?= htmlspecialchars($errors ? ($_POST['nom'] ?? '') : $exercice['nom']) ?>">
                <div class="error-msg" id="err-nom"><?= $errors['nom'] ?? '' ?></div>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label class="form-label fw-bold">Description</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($errors ? ($_POST['description'] ?? '') : ($exercice['description'] ?? '')) ?></textarea>
            </div>

            <!-- Ordre -->
            <div class="mb-3">
                <label class="form-label fw-bold">Ordre</label>
                <input type="text" name="ordre" id="ordre" class="form-control"
                       value="<?= htmlspecialchars($errors ? ($_POST['ordre'] ?? '') : $exercice['ordre']) ?>">
                <div class="error-msg" id="err-ordre"><?= $errors['ordre'] ?? '' ?></div>
            </div>

            <!-- Durée -->
            <div class="mb-3">
                <label class="form-label fw-bold">Durée (minutes)</label>
                <input type="text" name="duree_minutes" id="duree_minutes" class="form-control"
                       value="<?= htmlspecialchars($errors ? ($_POST['duree_minutes'] ?? '') : ($exercice['duree_minutes'] ?? '')) ?>">
                <div class="error-msg" id="err-duree"><?= $errors['duree_minutes'] ?? '' ?></div>
            </div>

            <!-- URL vidéo -->
            <div class="mb-3">
                <label class="form-label fw-bold">URL Vidéo <span class="text-muted">(optionnel)</span></label>
                <input type="text" name="video_url" id="video_url" class="form-control"
                       value="<?= htmlspecialchars($errors ? ($_POST['video_url'] ?? '') : ($exercice['video_url'] ?? '')) ?>">
                <div class="error-msg" id="err-video"><?= $errors['video_url'] ?? '' ?></div>
            </div>

            <!-- Statut -->
            <div class="mb-3">
                <label class="form-label fw-bold">Statut</label>
                <select name="statut" class="form-select">
                    <?php
                    $statutActuel = $errors ? ($_POST['statut'] ?? 'en_attente') : ($exercice['statut'] ?? 'en_attente');
                    foreach (['en_attente' => 'En attente', 'en_cours' => 'En cours', 'termine' => 'Terminé'] as $val => $label):
                    ?>
                        <option value="<?= $val ?>" <?= $statutActuel === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-green w-100">💾 Enregistrer les modifications</button>
        </form>
    </div>
</div>

<script>
function validerFormulaire() {
    let valide = true;
    ['err-nom', 'err-ordre', 'err-duree', 'err-video'].forEach(id => {
        document.getElementById(id).textContent = '';
    });

    const nom = document.getElementById('nom').value.trim();
    if (nom === '') {
        document.getElementById('err-nom').textContent = 'Le nom est obligatoire.';
        valide = false;
    } else if (nom.length < 2) {
        document.getElementById('err-nom').textContent = 'Le nom doit avoir au moins 2 caractères.';
        valide = false;
    }

    const ordre = document.getElementById('ordre').value.trim();
    if (ordre === '' || isNaN(ordre) || parseInt(ordre) < 1) {
        document.getElementById('err-ordre').textContent = "L'ordre doit être un entier positif.";
        valide = false;
    }

    const duree = document.getElementById('duree_minutes').value.trim();
    if (duree !== '' && (isNaN(duree) || parseInt(duree) < 1)) {
        document.getElementById('err-duree').textContent = 'La durée doit être un nombre de minutes positif.';
        valide = false;
    }

    const url = document.getElementById('video_url').value.trim();
    if (url !== '' && !url.startsWith('http://') && !url.startsWith('https://')) {
        document.getElementById('err-video').textContent = "L'URL doit commencer par http:// ou https://";
        valide = false;
    }

    return valide;
}
</script>

<?php
require_once 'C:/xampp/htdocs/gestion_plan/footer.php';
?>