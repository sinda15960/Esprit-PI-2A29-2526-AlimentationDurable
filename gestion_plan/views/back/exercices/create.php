<?php
require_once dirname(__DIR__, 3) . '/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">🏋️ Nouvel Exercice</h2>
    <?php if (!empty($programme_id)): ?>
        <a href="index.php?module=programme&action=show&id=<?= $programme_id ?>&office=back" class="btn btn-secondary">← Retour</a>
    <?php else: ?>
        <a href="index.php?module=objectif&action=index&office=back" class="btn btn-secondary">← Retour</a>
    <?php endif; ?>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="index.php?module=exercice&action=store&office=back"
              onsubmit="return validerFormulaire()">

            <!-- Nom -->
            <div class="mb-3">
                <label class="form-label fw-bold">Nom de l'exercice</label>
                <input type="text" name="nom" id="nom" class="form-control"
                       value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
                <div class="error-msg" id="err-nom"><?= $errors['nom'] ?? '' ?></div>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label class="form-label fw-bold">Description</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>

            <!-- Programme (pré-sélectionné et verrouillé) -->
            <div class="mb-3">
                <label class="form-label fw-bold">Programme</label>
                <select name="programme_id" id="programme_id" class="form-select">
                    <option value="">-- Choisir un programme --</option>
                    <?php foreach ($programmes as $prog): ?>
                        <option value="<?= $prog['id'] ?>"
                            <?= (intval($_POST['programme_id'] ?? $programme_id) === intval($prog['id'])) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($prog['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="error-msg" id="err-programme"><?= $errors['programme_id'] ?? '' ?></div>
            </div>

            <!-- Ordre -->
            <div class="mb-3">
                <label class="form-label fw-bold">Ordre</label>
                <input type="text" name="ordre" id="ordre" class="form-control"
                       value="<?= htmlspecialchars($_POST['ordre'] ?? '') ?>">
                <div class="error-msg" id="err-ordre"><?= $errors['ordre'] ?? '' ?></div>
            </div>

            <!-- Durée -->
            <div class="mb-3">
                <label class="form-label fw-bold">Durée (minutes)</label>
                <input type="text" name="duree_minutes" id="duree_minutes" class="form-control"
                       value="<?= htmlspecialchars($_POST['duree_minutes'] ?? '') ?>">
                <div class="error-msg" id="err-duree"><?= $errors['duree_minutes'] ?? '' ?></div>
            </div>

            <!-- URL vidéo -->
            <div class="mb-3">
                <label class="form-label fw-bold">URL Vidéo <span class="text-muted">(optionnel)</span></label>
                <input type="text" name="video_url" id="video_url" class="form-control"
                       value="<?= htmlspecialchars($_POST['video_url'] ?? '') ?>"
                       placeholder="https://...">
                <div class="error-msg" id="err-video"><?= $errors['video_url'] ?? '' ?></div>
            </div>

            <button type="submit" class="btn btn-green w-100">💾 Enregistrer</button>
        </form>
    </div>
</div>

<script>
function validerFormulaire() {
    let valide = true;
    ['err-nom', 'err-programme', 'err-ordre', 'err-duree', 'err-video'].forEach(id => {
        document.getElementById(id).textContent = '';
    });

    // Nom
    const nom = document.getElementById('nom').value.trim();
    if (nom === '') {
        document.getElementById('err-nom').textContent = 'Le nom est obligatoire.';
        valide = false;
    } else if (nom.length < 2) {
        document.getElementById('err-nom').textContent = 'Le nom doit avoir au moins 2 caractères.';
        valide = false;
    }

    // Programme
    const prog = document.getElementById('programme_id').value;
    if (prog === '' || parseInt(prog) < 1) {
        document.getElementById('err-programme').textContent = 'Veuillez choisir un programme.';
        valide = false;
    }

    // Ordre
    const ordre = document.getElementById('ordre').value.trim();
    if (ordre === '' || isNaN(ordre) || parseInt(ordre) < 1) {
        document.getElementById('err-ordre').textContent = "L'ordre doit être un entier positif.";
        valide = false;
    }

    // Durée (optionnel mais si renseigné doit être > 0)
    const duree = document.getElementById('duree_minutes').value.trim();
    if (duree !== '' && (isNaN(duree) || parseInt(duree) < 1)) {
        document.getElementById('err-duree').textContent = 'La durée doit être un nombre de minutes positif.';
        valide = false;
    }

    // URL vidéo (optionnel mais si renseigné doit commencer par http)
    const url = document.getElementById('video_url').value.trim();
    if (url !== '' && !url.startsWith('http://') && !url.startsWith('https://')) {
        document.getElementById('err-video').textContent = "L'URL doit commencer par http:// ou https://";
        valide = false;
    }

    return valide;
}
</script>

<?php
require_once dirname(__DIR__, 3) . '/footer.php';
?>