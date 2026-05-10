<?php 
require_once dirname(__DIR__, 3) . '/header.php'; 

function formatDateAffichage($date) {
    if (empty($date)) return '';
    $parts = explode('-', $date);
    if (count($parts) === 3) return $parts[2] . '/' . $parts[1] . '/' . $parts[0];
    return $date;
}
?>

<h2 class="section-title">Modifier mon objectif personnel</h2>

<div class="card">
    <div class="card-body">
        <?php if (isset($errors['general'])): ?>
            <div class="alert alert-danger"><?php echo $errors['general']; ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?module=objectif&action=updatePersonal&office=front" 
              id="formEdit" onsubmit="return validerForm()">
            <input type="hidden" name="id" value="<?php echo $objectif['id']; ?>">

            <div class="mb-3">
                <label class="fw-bold">Titre *</label>
                <input type="text" name="titre" id="titre" class="form-control" 
                       value="<?php echo htmlspecialchars(isset($_POST['titre']) ? $_POST['titre'] : $objectif['titre']); ?>">
                <div class="text-danger small" id="err_titre"><?php echo isset($errors['titre']) ? $errors['titre'] : ''; ?></div>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Description</label>
                <textarea name="description" class="form-control" rows="2"><?php echo htmlspecialchars(isset($_POST['description']) ? $_POST['description'] : $objectif['description']); ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label>Poids actuel (kg)</label>
                    <input type="text" name="poids_actuel" id="poids_actuel" class="form-control" placeholder="Ex: 75.5"
                           value="<?php echo htmlspecialchars(isset($_POST['poids_actuel']) ? $_POST['poids_actuel'] : $objectif['poids_actuel']); ?>">
                    <div class="text-danger small" id="err_poids_actuel"><?php echo isset($errors['poids_actuel']) ? $errors['poids_actuel'] : ''; ?></div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Poids cible (kg)</label>
                    <input type="text" name="poids_cible" id="poids_cible" class="form-control" placeholder="Ex: 65.0"
                           value="<?php echo htmlspecialchars(isset($_POST['poids_cible']) ? $_POST['poids_cible'] : $objectif['poids_cible']); ?>">
                    <div class="text-danger small" id="err_poids_cible"><?php echo isset($errors['poids_cible']) ? $errors['poids_cible'] : ''; ?></div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Taille (m)</label>
                    <input type="text" name="taille" id="taille" class="form-control" placeholder="Ex: 1.72"
                           value="<?php echo htmlspecialchars(isset($_POST['taille']) ? $_POST['taille'] : $objectif['taille']); ?>">
                    <div class="text-danger small" id="err_taille"><?php echo isset($errors['taille']) ? $errors['taille'] : ''; ?></div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Age</label>
                    <input type="text" name="age" id="age" class="form-control" placeholder="Ex: 25"
                           value="<?php echo htmlspecialchars(isset($_POST['age']) ? $_POST['age'] : $objectif['age']); ?>">
                    <div class="text-danger small" id="err_age"><?php echo isset($errors['age']) ? $errors['age'] : ''; ?></div>
                </div>
            </div>

            <div class="row">
                <!-- ✅ Date debut : affiche la date existante meme si passee, pas de restriction -->
                <div class="col-md-6 mb-3">
                    <label>Date de debut</label>
                    <input type="text" name="date_debut" id="date_debut" class="form-control" placeholder="jj/mm/aaaa"
                           value="<?php echo htmlspecialchars(isset($_POST['date_debut']) ? $_POST['date_debut'] : formatDateAffichage($objectif['date_debut'])); ?>">
                    <div class="text-danger small" id="err_date_debut"><?php echo isset($errors['date_debut']) ? $errors['date_debut'] : ''; ?></div>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Date fin prevue</label>
                    <input type="text" name="date_fin_prevue" id="date_fin_prevue" class="form-control" placeholder="jj/mm/aaaa"
                           value="<?php echo htmlspecialchars(isset($_POST['date_fin_prevue']) ? $_POST['date_fin_prevue'] : formatDateAffichage($objectif['date_fin_prevue'])); ?>">
                    <div class="text-danger small" id="err_date_fin"><?php echo isset($errors['date_fin_prevue']) ? $errors['date_fin_prevue'] : ''; ?></div>
                </div>
            </div>

            <div class="mb-3">
                <label>Etat de sante / Cas exceptionnel</label>
                <textarea name="etat_sante" class="form-control" rows="2"><?php echo htmlspecialchars(isset($_POST['etat_sante']) ? $_POST['etat_sante'] : $objectif['etat_sante']); ?></textarea>
            </div>

            <button type="submit" class="btn btn-green">Mettre a jour</button>
            <a href="index.php?module=objectif&action=index&office=front" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>

<script>
function parseDate(str) {
    if (!str || !str.trim()) return null;
    const parts = str.trim().split('/');
    if (parts.length !== 3) return null;
    const d = parseInt(parts[0]), m = parseInt(parts[1]) - 1, y = parseInt(parts[2]);
    if (isNaN(d) || isNaN(m) || isNaN(y)) return null;
    const date = new Date(y, m, d);
    if (date.getFullYear() !== y || date.getMonth() !== m || date.getDate() !== d) return null;
    return date;
}

function afficherErreur(idChamp, idErreur, msg) {
    document.getElementById(idChamp).classList.add('is-invalid');
    document.getElementById(idErreur).textContent = msg;
}

function validerForm() {
    let valide = true;
    document.querySelectorAll('.text-danger').forEach(e => e.textContent = '');
    document.querySelectorAll('.form-control').forEach(e => e.classList.remove('is-invalid'));

    // TITRE
    const titre = document.getElementById('titre').value.trim();
    if (!titre) {
        afficherErreur('titre', 'err_titre', 'Le titre est obligatoire.');
        valide = false;
    } else if (titre.length < 3) {
        afficherErreur('titre', 'err_titre', 'Le titre doit avoir au moins 3 caracteres.');
        valide = false;
    }

    // POIDS ACTUEL
    const poidsActuelVal = document.getElementById('poids_actuel').value.trim();
    if (poidsActuelVal !== '') {
        const pA = parseFloat(poidsActuelVal);
        if (isNaN(pA) || pA < 20 || pA > 300) {
            afficherErreur('poids_actuel', 'err_poids_actuel', 'Le poids doit etre entre 20 et 300 kg.');
            valide = false;
        }
    }

    // POIDS CIBLE
    const poidsCibleVal = document.getElementById('poids_cible').value.trim();
    if (poidsCibleVal !== '') {
        const pC = parseFloat(poidsCibleVal);
        if (isNaN(pC) || pC < 20 || pC > 300) {
            afficherErreur('poids_cible', 'err_poids_cible', 'Le poids cible doit etre entre 20 et 300 kg.');
            valide = false;
        }
    }

    // ✅ TAILLE : accepte 1, 1.7, 1.72
    const tailleVal = document.getElementById('taille').value.trim();
    if (tailleVal !== '') {
        const t = parseFloat(tailleVal);
        if (isNaN(t) || t < 0.5 || t > 2.5) {
            afficherErreur('taille', 'err_taille', 'La taille doit etre entre 0.5 et 2.5 m.');
            valide = false;
        }
    }

    // AGE
    const ageVal = document.getElementById('age').value.trim();
    if (ageVal !== '') {
        const a = parseInt(ageVal);
        if (isNaN(a) || a < 10 || a > 120) {
            afficherErreur('age', 'err_age', "L'age doit etre entre 10 et 120 ans.");
            valide = false;
        }
    }

    // ✅ DATE DEBUT : format valide uniquement, pas de restriction dates passees en modification
    const dateDebutVal = document.getElementById('date_debut').value.trim();
    let dateDebut = null;
    if (dateDebutVal !== '') {
        dateDebut = parseDate(dateDebutVal);
        if (!dateDebut) {
            afficherErreur('date_debut', 'err_date_debut', 'Format invalide. Ex: 20/02/2024');
            valide = false;
        }
    }

    // ✅ DATE FIN : doit etre superieure a date debut
    const dateFinVal = document.getElementById('date_fin_prevue').value.trim();
    if (dateFinVal !== '') {
        const dateFin = parseDate(dateFinVal);
        if (!dateFin) {
            afficherErreur('date_fin_prevue', 'err_date_fin', 'Format invalide. Ex: 02/02/2025');
            valide = false;
        } else if (dateDebut && dateFin <= dateDebut) {
            afficherErreur('date_fin_prevue', 'err_date_fin', 'La date de fin doit etre superieure a la date de debut.');
            valide = false;
        }
    }

    return valide;
}
</script>

<?php 
require_once dirname(__DIR__, 3) . '/footer.php'; 
?>