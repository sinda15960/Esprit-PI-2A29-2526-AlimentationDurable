<?php 
require_once 'C:/xampp/htdocs/gestion_plan/header.php'; 
?>

<h2 class="section-title">✏️ Modifier l'Exercice</h2>

<div class="card">
    <div class="card-body">
        <form method="POST" action="index.php?module=exercice&action=update&office=back" id="formExercice">
            <input type="hidden" name="id" value="<?= $exercice['id'] ?>">

            <div class="mb-3">
                <label class="form-label fw-bold">Nom *</label>
                <input type="text" name="nom" id="nom" class="form-control"
                       value="<?= htmlspecialchars($_POST['nom'] ?? $exercice['nom']) ?>">
                <div class="text-danger small" id="err_nom"><?= $errors['nom'] ?? '' ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Description</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($_POST['description'] ?? $exercice['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Programme *</label>
                <select name="programme_id" id="programme_id" class="form-select">
                    <option value="">-- Choisir --</option>
                    <?php 
                    $currentProg = $_POST['programme_id'] ?? $exercice['programme_id'];
                    foreach ($programmes as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= ($currentProg == $p['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['nom']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <div class="text-danger small" id="err_programme"><?= $errors['programme_id'] ?? '' ?></div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Ordre *</label>
                    <input type="number" name="ordre" id="ordre" min="1" class="form-control"
                           value="<?= htmlspecialchars($_POST['ordre'] ?? $exercice['ordre']) ?>">
                    <div class="text-danger small" id="err_ordre"><?= $errors['ordre'] ?? '' ?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Durée (minutes)</label>
                    <input type="number" name="duree_minutes" id="duree_minutes" min="0" class="form-control"
                           value="<?= htmlspecialchars($_POST['duree_minutes'] ?? $exercice['duree_minutes']) ?>">
                    <div class="text-danger small" id="err_duree"></div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">URL Vidéo</label>
                <input type="text" name="video_url" id="video_url" class="form-control"
                       value="<?= htmlspecialchars($_POST['video_url'] ?? $exercice['video_url']) ?>"
                       placeholder="https://www.youtube.com/embed/...">
                <div class="text-danger small" id="err_video"><?= $errors['video_url'] ?? '' ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Statut</label>
                <select name="statut" class="form-select">
                    <?php $currentStatut = $_POST['statut'] ?? $exercice['statut']; ?>
                    <option value="en_attente" <?= ($currentStatut === 'en_attente') ? 'selected' : '' ?>>En attente</option>
                    <option value="en_cours" <?= ($currentStatut === 'en_cours') ? 'selected' : '' ?>>En cours</option>
                    <option value="termine" <?= ($currentStatut === 'termine') ? 'selected' : '' ?>>Terminé</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-green" onclick="return validerForm()">💾 Mettre à jour</button>
                <a href="index.php?module=exercice&action=index&office=back" class="btn btn-secondary">Annuler</a>
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
    if (nom.length < 2) {
        document.getElementById('err_nom').textContent = 'Le nom doit avoir au moins 2 caractères.';
        document.getElementById('nom').classList.add('is-invalid');
        valide = false;
    }

    const programme = document.getElementById('programme_id').value;
    if (!programme) {
        document.getElementById('err_programme').textContent = 'Veuillez choisir un programme.';
        document.getElementById('programme_id').classList.add('is-invalid');
        valide = false;
    }

    const ordre = parseInt(document.getElementById('ordre').value);
    if (!ordre || ordre < 1) {
        document.getElementById('err_ordre').textContent = 'L\'ordre doit être au moins 1.';
        document.getElementById('ordre').classList.add('is-invalid');
        valide = false;
    }

    const video = document.getElementById('video_url').value.trim();
    if (video && !video.startsWith('http')) {
        document.getElementById('err_video').textContent = 'L\'URL doit commencer par http.';
        document.getElementById('video_url').classList.add('is-invalid');
        valide = false;
    }

    return valide;
}
</script>

<?php 
require_once 'C:/xampp/htdocs/gestion_plan/footer.php'; 
?>