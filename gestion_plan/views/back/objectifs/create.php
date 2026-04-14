<?php 
require_once 'C:/xampp/htdocs/gestion_plan/header.php'; 
?>

<h2 class="section-title">🎯 Ajouter un Objectif</h2>

<div class="card">
    <div class="card-body">
        <form method="POST" action="index.php?module=objectif&action=store&office=back" id="formObjectif">

            <div class="mb-3">
                <label class="form-label fw-bold">Titre *</label>
                <input type="text" name="titre" id="titre" 
                       class="form-control <?= isset($errors['titre']) ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($_POST['titre'] ?? '') ?>">
                <div class="error-msg text-danger small" id="err_titre">
                    <?= $errors['titre'] ?? '' ?>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Type d'objectif *</label>
                <select name="type_objectif" id="type_objectif" 
                        class="form-select <?= isset($errors['type_objectif']) ? 'is-invalid' : '' ?>">
                    <option value="">-- Choisir --</option>
                    <?php foreach (['grossir','maigrir','maintenir','muscler'] as $t): ?>
                    <option value="<?= $t ?>" <?= (($_POST['type_objectif'] ?? '') === $t) ? 'selected' : '' ?>>
                        <?= ucfirst($t) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <div class="error-msg text-danger small" id="err_type">
                    <?= $errors['type_objectif'] ?? '' ?>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Description</label>
                <textarea name="description" id="description" class="form-control" rows="3"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                <div class="error-msg text-danger small" id="err_description"></div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Maladies</label>
                <input type="text" name="maladies" id="maladies" class="form-control" 
                       placeholder="Ex: diabète, hypertension"
                       value="<?= htmlspecialchars($_POST['maladies'] ?? '') ?>">
                <div class="error-msg text-danger small" id="err_maladies"></div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Préférences alimentaires</label>
                <input type="text" name="preferences" id="preferences" class="form-control"
                       placeholder="Ex: végétarien, sans gluten"
                       value="<?= htmlspecialchars($_POST['preferences'] ?? '') ?>">
                <div class="error-msg text-danger small" id="err_preferences"></div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Calories min (kcal) *</label>
                    <input type="number" name="calories_min" id="calories_min" class="form-control"
                           value="<?= htmlspecialchars($_POST['calories_min'] ?? '1500') ?>" min="500">
                    <div class="error-msg text-danger small" id="err_cal_min"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Calories max (kcal) *</label>
                    <input type="number" name="calories_max" id="calories_max" class="form-control"
                           value="<?= htmlspecialchars($_POST['calories_max'] ?? '3000') ?>" min="500">
                    <div class="error-msg text-danger small" id="err_cal_max"></div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-green" onclick="return validerForm()">💾 Enregistrer</button>
                <a href="index.php?module=objectif&action=index&office=back" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
// Regex réutilisables
const regexLettres     = /^[a-zA-ZÀ-ÿ\s\-']+$/;   // Lettres, espaces, tirets, apostrophes
const regexTexteLibre  = /^[a-zA-ZÀ-ÿ0-9\s.,;:()\-'!?\n]+$/; // Description

function afficherErreur(idChamp, idErreur, message) {
    document.getElementById(idChamp).classList.add('is-invalid');
    document.getElementById(idErreur).textContent = message;
}

function validerForm() {
    let valide = true;

    // Reset erreurs
    document.querySelectorAll('.error-msg').forEach(e => e.textContent = '');
    document.querySelectorAll('.form-control, .form-select').forEach(e => e.classList.remove('is-invalid'));

    // --- TITRE ---
    const titre = document.getElementById('titre').value.trim();
    if (!titre) {
        afficherErreur('titre', 'err_titre', 'Le titre est obligatoire.');
        valide = false;
    } else if (titre.length < 3) {
        afficherErreur('titre', 'err_titre', 'Le titre doit avoir au moins 3 caractères.');
        valide = false;
    } else if (!regexLettres.test(titre)) {
        afficherErreur('titre', 'err_titre', 'Le titre ne doit contenir que des lettres.');
        valide = false;
    }

    // --- TYPE OBJECTIF ---
    const type = document.getElementById('type_objectif').value;
    if (!type) {
        afficherErreur('type_objectif', 'err_type', "Veuillez choisir un type d'objectif.");
        valide = false;
    }

    // --- DESCRIPTION (optionnelle mais vérifiée si remplie) ---
    const desc = document.getElementById('description').value.trim();
    if (desc && !regexTexteLibre.test(desc)) {
        afficherErreur('description', 'err_description', 'La description contient des caractères invalides.');
        valide = false;
    }

    // --- MALADIES (optionnel) ---
    const maladies = document.getElementById('maladies').value.trim();
    if (maladies && !regexLettres.test(maladies)) {
        afficherErreur('maladies', 'err_maladies', 'Maladies : lettres et espaces uniquement.');
        valide = false;
    }

    // --- PREFERENCES (optionnel) ---
    const preferences = document.getElementById('preferences').value.trim();
    if (preferences && !regexLettres.test(preferences)) {
        afficherErreur('preferences', 'err_preferences', 'Préférences : lettres et espaces uniquement.');
        valide = false;
    }

    // --- CALORIES ---
    const calMinVal = document.getElementById('calories_min').value.trim();
    const calMaxVal = document.getElementById('calories_max').value.trim();
    const calMin = parseInt(calMinVal);
    const calMax = parseInt(calMaxVal);

    if (!calMinVal) {
        afficherErreur('calories_min', 'err_cal_min', 'Calories min est obligatoire.');
        valide = false;
    } else if (!/^\d+$/.test(calMinVal)) {
        afficherErreur('calories_min', 'err_cal_min', 'Calories min doit être un nombre entier positif.');
        valide = false;
    } else if (calMin < 500) {
        afficherErreur('calories_min', 'err_cal_min', 'Calories min doit être au moins 500.');
        valide = false;
    }

    if (!calMaxVal) {
        afficherErreur('calories_max', 'err_cal_max', 'Calories max est obligatoire.');
        valide = false;
    } else if (!/^\d+$/.test(calMaxVal)) {
        afficherErreur('calories_max', 'err_cal_max', 'Calories max doit être un nombre entier positif.');
        valide = false;
    } else if (calMax < 500) {
        afficherErreur('calories_max', 'err_cal_max', 'Calories max doit être au moins 500.');
        valide = false;
    }

    if (calMinVal && calMaxVal && !isNaN(calMin) && !isNaN(calMax) && calMax <= calMin) {
        afficherErreur('calories_max', 'err_cal_max', 'Calories max doit être supérieur à calories min.');
        valide = false;
    }

    return valide;
}
</script>

<?php 
require_once 'C:/xampp/htdocs/gestion_plan/footer.php'; 
?>