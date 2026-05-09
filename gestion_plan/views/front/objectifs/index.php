<?php 
require_once 'C:/xampp/htdocs/gestion_plan/header.php'; 
?>

<!-- Objectifs officiels -->
<div class="mb-5">
    <h2 class="section-title">Nos Objectifs Sante</h2>
    <?php if (empty($objectifsOfficiels)): ?>
        <div class="alert alert-info">Aucun objectif disponible pour le moment.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($objectifsOfficiels as $o): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 p-3">
                    <h5 class="text-success fw-bold"><?php echo htmlspecialchars($o['titre']); ?></h5>
                    <span class="badge bg-success mb-2"><?php echo htmlspecialchars($o['type_objectif']); ?></span>
                    <p class="text-muted small"><?php echo htmlspecialchars($o['description'] ?? ''); ?></p>
                    <?php if (!empty($o['maladies'])): ?>
                        <p class="small"><strong>Maladies :</strong> <?php echo htmlspecialchars($o['maladies']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($o['preferences'])): ?>
                        <p class="small"><strong>Preferences :</strong> <?php echo htmlspecialchars($o['preferences']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($o['calories_min']) && !empty($o['calories_max'])): ?>
                        <p class="small"><strong>Calories :</strong> <?php echo $o['calories_min']; ?> - <?php echo $o['calories_max']; ?> kcal</p>
                    <?php endif; ?>
                    <a href="index.php?module=programme&action=indexByObjectif&objectif_id=<?php echo $o['id']; ?>&office=front" 
                       class="btn btn-sm btn-green mt-auto">Voir les programmes</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Mes objectifs personnels en CARTES -->
<?php if (!empty($objectifsPerso)): ?>
<div class="mb-5">
    <h4 class="section-title">Mes objectifs personnalises</h4>
    <div class="row g-3">
        <?php foreach ($objectifsPerso as $o): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 p-3 border-start border-warning border-3">
                <h5 class="text-success fw-bold"><?php echo htmlspecialchars($o['titre']); ?></h5>
                <?php if (!empty($o['description'])): ?>
                    <p class="text-muted small mb-2"><?php echo htmlspecialchars($o['description']); ?></p>
                <?php endif; ?>
                <div class="row g-1 small mb-2">
                    <div class="col-6">
                        <span class="text-muted">Poids actuel :</span><br>
                        <strong><?php echo $o['poids_actuel'] ? $o['poids_actuel'] . ' kg' : '-'; ?></strong>
                    </div>
                    <div class="col-6">
                        <span class="text-muted">Poids cible :</span><br>
                        <strong><?php echo $o['poids_cible'] ? $o['poids_cible'] . ' kg' : '-'; ?></strong>
                    </div>
                    <!-- ✅ Taille affichee -->
                    <div class="col-6 mt-2">
                        <span class="text-muted">Taille :</span><br>
                        <strong><?php echo $o['taille'] ? $o['taille'] . ' m' : '-'; ?></strong>
                    </div>
                    <div class="col-6 mt-2">
                        <span class="text-muted">Age :</span><br>
                        <strong><?php echo $o['age'] ? $o['age'] . ' ans' : '-'; ?></strong>
                    </div>
                    <div class="col-6 mt-2">
                        <span class="text-muted">Debut :</span><br>
                        <strong><?php echo $o['date_debut'] ?? '-'; ?></strong>
                    </div>
                    <div class="col-6 mt-2">
                        <span class="text-muted">Fin prevue :</span><br>
                        <strong><?php echo $o['date_fin_prevue'] ?? '-'; ?></strong>
                    </div>
                </div>
                <?php if (!empty($o['etat_sante'])): ?>
                    <p class="small mb-2"><span class="text-muted">Sante :</span> <?php echo htmlspecialchars($o['etat_sante']); ?></p>
                <?php endif; ?>
                <div class="d-flex gap-2 mt-auto pt-2 flex-wrap">
                    <a href="index.php?module=objectif&action=showPersonal&id=<?php echo $o['id']; ?>&office=front"
                       class="btn btn-sm btn-green flex-fill">Mon programme</a>
                    <a href="index.php?module=objectif&action=editPersonal&id=<?php echo $o['id']; ?>&office=front"
                       class="btn btn-sm btn-warning">Modifier</a>
                    <a href="index.php?module=objectif&action=deletePersonal&id=<?php echo $o['id']; ?>&office=front"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Supprimer cet objectif ?')">Supprimer</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Bouton Ajouter -->
<div class="text-center my-4">
    <button type="button" class="btn btn-success btn-lg" onclick="toggleForm()" id="btnToggle">
        Ajouter mon objectif personnel
    </button>
</div>

<!-- Formulaire ajout -->
<div id="personalForm" style="display: none;">
    <div class="card mb-5">
        <div class="card-header bg-light">
            <h5 class="mb-0">Mon objectif personnel</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="index.php?module=objectif&action=storePersonal&office=front" 
                  id="formObjectifPerso" onsubmit="return validerFormPerso()">
                
                <div class="mb-3">
                    <label class="fw-bold">Titre *</label>
                    <input type="text" name="titre" id="titre" class="form-control" placeholder="Ex: Je veux perdre 10 kg...">
                    <div class="text-danger small" id="err_titre"></div>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="2" placeholder="Decrivez votre objectif..."></textarea>
                    <div class="text-danger small" id="err_description"></div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Poids actuel (kg) *</label>
                        <input type="text" name="poids_actuel" id="poids_actuel" class="form-control" placeholder="Ex: 75.5">
                        <div class="text-danger small" id="err_poids_actuel"></div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Poids cible (kg) *</label>
                        <input type="text" name="poids_cible" id="poids_cible" class="form-control" placeholder="Ex: 65.0">
                        <div class="text-danger small" id="err_poids_cible"></div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Taille (m) *</label>
                        <input type="text" name="taille" id="taille" class="form-control" placeholder="Ex: 1.72">
                        <div class="text-danger small" id="err_taille"></div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Age *</label>
                        <input type="text" name="age" id="age" class="form-control" placeholder="Ex: 25">
                        <div class="text-danger small" id="err_age"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Date de debut * <small class="text-muted">(aujourd'hui ou futur)</small></label>
                        <!-- ✅ Pre-rempli avec aujourd'hui, date passee interdite -->
                        <input type="text" name="date_debut" id="date_debut" class="form-control" placeholder="jj/mm/aaaa">
                        <div class="text-danger small" id="err_date_debut"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Date fin prevue * <small class="text-muted">(superieure a la date debut)</small></label>
                        <input type="text" name="date_fin_prevue" id="date_fin_prevue" class="form-control" placeholder="jj/mm/aaaa">
                        <div class="text-danger small" id="err_date_fin"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Etat de sante / Cas exceptionnel</label>
                    <textarea name="etat_sante" id="etat_sante" class="form-control" rows="2" placeholder="Diabete, hypertension, allergie..."></textarea>
                    <div class="text-danger small" id="err_etat_sante"></div>
                </div>

                <button type="submit" class="btn btn-green">Enregistrer mon objectif</button>
                <button type="button" class="btn btn-secondary" onclick="toggleForm()">Annuler</button>
            </form>
        </div>
    </div>
</div>

<script>
// ✅ Pre-remplir date debut avec aujourd'hui
window.onload = function() {
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var yyyy = today.getFullYear();
    document.getElementById('date_debut').value = dd + '/' + mm + '/' + yyyy;
};

function toggleForm() {
    var form = document.getElementById('personalForm');
    var btn = document.getElementById('btnToggle');
    if (form.style.display === 'none') {
        form.style.display = 'block';
        btn.textContent = 'Annuler l\'ajout';
        btn.classList.replace('btn-success', 'btn-secondary');
    } else {
        form.style.display = 'none';
        btn.textContent = 'Ajouter mon objectif personnel';
        btn.classList.replace('btn-secondary', 'btn-success');
    }
}

function parseDate(str) {
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

function validerFormPerso() {
    let valide = true;
    document.querySelectorAll('.text-danger').forEach(e => e.textContent = '');
    document.querySelectorAll('.form-control').forEach(e => e.classList.remove('is-invalid'));

    // Titre
    const titre = document.getElementById('titre').value.trim();
    if (!titre) {
        afficherErreur('titre', 'err_titre', 'Le titre est obligatoire.'); valide = false;
    } else if (titre.length < 3) {
        afficherErreur('titre', 'err_titre', 'Le titre doit avoir au moins 3 caracteres.'); valide = false;
    }

    // Poids actuel
    const poidsActuelVal = document.getElementById('poids_actuel').value.trim();
    if (!poidsActuelVal) {
        afficherErreur('poids_actuel', 'err_poids_actuel', 'Le poids actuel est obligatoire.'); valide = false;
    } else {
        const pA = parseFloat(poidsActuelVal);
        if (isNaN(pA) || pA < 20 || pA > 300) {
            afficherErreur('poids_actuel', 'err_poids_actuel', 'Le poids doit etre entre 20 et 300 kg.'); valide = false;
        }
    }

    // Poids cible
    const poidsCibleVal = document.getElementById('poids_cible').value.trim();
    if (!poidsCibleVal) {
        afficherErreur('poids_cible', 'err_poids_cible', 'Le poids cible est obligatoire.'); valide = false;
    } else {
        const pC = parseFloat(poidsCibleVal);
        if (isNaN(pC) || pC < 20 || pC > 300) {
            afficherErreur('poids_cible', 'err_poids_cible', 'Le poids cible doit etre entre 20 et 300 kg.'); valide = false;
        }
    }

    // Taille
    const tailleVal = document.getElementById('taille').value.trim();
    if (!tailleVal) {
        afficherErreur('taille', 'err_taille', 'La taille est obligatoire.'); valide = false;
    } else {
        const t = parseFloat(tailleVal);
        if (isNaN(t) || t < 0.5 || t > 2.5) {
            afficherErreur('taille', 'err_taille', 'La taille doit etre entre 0.5 et 2.5 m. Ex: 1.72'); valide = false;
        }
    }

    // Age
    const ageVal = document.getElementById('age').value.trim();
    if (!ageVal) {
        afficherErreur('age', 'err_age', "L'age est obligatoire."); valide = false;
    } else {
        const a = parseInt(ageVal);
        if (isNaN(a) || a < 10 || a > 120) {
            afficherErreur('age', 'err_age', "L'age doit etre entre 10 et 120 ans."); valide = false;
        }
    }

    // ✅ Date debut : doit etre >= aujourd'hui pour un NOUVEAU objectif
    const dateDebutVal = document.getElementById('date_debut').value.trim();
    let dateDebut = null;
    if (!dateDebutVal) {
        afficherErreur('date_debut', 'err_date_debut', 'La date de debut est obligatoire.'); valide = false;
    } else {
        dateDebut = parseDate(dateDebutVal);
        if (!dateDebut) {
            afficherErreur('date_debut', 'err_date_debut', 'Format invalide. Ex: 19/04/2026'); valide = false;
        } else {
            // Verifier que la date est aujourd'hui ou dans le futur
            var today = new Date();
            today.setHours(0, 0, 0, 0);
            if (dateDebut < today) {
                afficherErreur('date_debut', 'err_date_debut', 'La date de debut doit etre aujourd\'hui ou dans le futur.');
                valide = false;
            }
        }
    }

    // Date fin : doit etre superieure a date debut
    const dateFinVal = document.getElementById('date_fin_prevue').value.trim();
    if (!dateFinVal) {
        afficherErreur('date_fin_prevue', 'err_date_fin', 'La date de fin est obligatoire.'); valide = false;
    } else {
        const dateFin = parseDate(dateFinVal);
        if (!dateFin) {
            afficherErreur('date_fin_prevue', 'err_date_fin', 'Format invalide. Ex: 19/10/2026'); valide = false;
        } else if (dateDebut && dateFin <= dateDebut) {
            afficherErreur('date_fin_prevue', 'err_date_fin', 'La date de fin doit etre superieure a la date de debut.'); valide = false;
        }
    }

    return valide;
}
</script>

<?php 
require_once 'C:/xampp/htdocs/gestion_plan/footer.php'; 
?>