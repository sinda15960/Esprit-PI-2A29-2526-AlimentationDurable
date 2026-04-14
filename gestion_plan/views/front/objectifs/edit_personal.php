<?php 
require_once 'C:/xampp/htdocs/gestion_plan/header.php'; 

// Convertir date format aaaa-mm-jj → jj/mm/aaaa pour affichage
function formatDateAffichage($date) {
    if (empty($date)) return '';
    $parts = explode('-', $date);
    if (count($parts) === 3) return $parts[2] . '/' . $parts[1] . '/' . $parts[0];
    return $date;
}
?>

<h2 class="section-title">✏️ Modifier mon objectif personnel</h2>

<div class="card">
    <div class="card-body">
        <form method="POST" action="index.php?module=objectif&action=updatePersonal&office=front" 
              id="formObjectifPerso" onsubmit="return validerForm()">
            <input type="hidden" name="id" value="<?= $objectif['id'] ?>">

            <div class="mb-3">
                <label class="fw-bold">Titre *</label>
                <input type="text" name="titre" id="titre" class="form-control" 
                       value="<?= htmlspecialchars($objectif['titre']) ?>">
                <div class="text-danger small" id="err_titre"><?= $errors['titre'] ?? '' ?></div>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Description</label>
                <textarea name="description" id="description" class="form-control" rows="2"><?= htmlspecialchars($objectif['description'] ?? '') ?></textarea>
                <div class="text-danger small" id="err_description"></div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label>Poids actuel (kg) *</label>
                    <input type="text" name="poids_actuel" id="poids_actuel" class="form-control"
                           placeholder="Ex: 75.5"
                           value="<?= htmlspecialchars($objectif['poids_actuel'] ?? '') ?>">
                    <div class="text-danger small" id="err_poids_actuel"></div>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Poids cible (kg) *</label>
                    <input type="text" name="poids_cible" id="poids_cible" class="form-control"
                           placeholder="Ex: 65.0"
                           value="<?= htmlspecialchars($objectif['poids_cible'] ?? '') ?>">
                    <div class="text-danger small" id="err_poids_cible"></div>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Taille (m) *</label>
                    <input type="text" name="taille" id="taille" class="form-control"
                           placeholder="Ex: 1.72"
                           value="<?= htmlspecialchars($objectif['taille'] ?? '') ?>">
                    <div class="text-danger small" id="err_taille"></div>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Âge *</label>
                    <input type="text" name="age" id="age" class="form-control"
                           placeholder="Ex: 25"
                           value="<?= htmlspecialchars($objectif['age'] ?? '') ?>">
                    <div class="text-danger small" id="err_age"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Date de début *</label>
                    <input type="text" name="date_debut" id="date_debut" class="form-control"
                           placeholder="jj/mm/aaaa"
                           value="<?= formatDateAffichage($objectif['date_debut']) ?>">
                    <div class="text-danger small" id="err_date_debut"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Date fin prévue *</label>
                    <input type="text" name="date_fin_prevue" id="date_fin_prevue" class="form-control"
                           placeholder="jj/mm/aaaa"
                           value="<?= formatDateAffichage($objectif['date_fin_prevue']) ?>">
                    <div class="text-danger small" id="err_date_fin"></div>
                </div>
            </div>

            <div class="mb-3">
                <label>État de santé / Cas exceptionnel</label>
                <textarea name="etat_sante" id="etat_sante" class="form-control" rows="2"><?= htmlspecialchars($objectif['etat_sante'] ?? '') ?></textarea>
                <div class="text-danger small" id="err_etat_sante"></div>
            </div>

            <button type="submit" class="btn btn-green">💾 Mettre à jour</button>
            <a href="index.php?module=objectif&action=index&office=front" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>

<script>
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

function validerForm() {
    let valide = true;

    document.querySelectorAll('.text-danger').forEach(e => e.textContent = '');
    document.querySelectorAll('.form-control').forEach(e => e.classList.remove('is-invalid'));

    // --- TITRE ---
    const titre = document.getElementById('titre').value.trim();
    if (!titre) {
        afficherErreur('titre', 'err_titre', 'Le titre est obligatoire.');
        valide = false;
    } else if (titre.length < 3) {
        afficherErreur('titre', 'err_titre', 'Le titre doit avoir au moins 3 caractères.');
        valide = false;
    } else if (!/^[a-zA-ZÀ-ÿ0-9\s\-',.!?]+$/.test(titre)) {
        afficherErreur('titre', 'err_titre', 'Le titre contient des caractères invalides.');
        valide = false;
    }

    // --- DESCRIPTION (optionnelle) ---
    const desc = document.getElementById('description').value.trim();
    if (desc && !/^[a-zA-ZÀ-ÿ0-9\s\-',.!?()\n]+$/.test(desc)) {
        afficherErreur('description', 'err_description', 'La description contient des caractères invalides.');
        valide = false;
    }

    // --- POIDS ACTUEL ---
    const poidsActuelVal = document.getElementById('poids_actuel').value.trim();
    if (!poidsActuelVal) {
        afficherErreur('poids_actuel', 'err_poids_actuel', 'Le poids actuel est obligatoire.');
        valide = false;
    } else if (!/^\d+(\.\d{1,2})?$/.test(poidsActuelVal)) {
        afficherErreur('poids_actuel', 'err_poids_actuel', 'Format invalide. Ex: 75 ou 75.5');
        valide = false;
    } else {
        const pA = parseFloat(poidsActuelVal);
        if (pA < 20 || pA > 300) {
            afficherErreur('poids_actuel', 'err_poids_actuel', 'Le poids doit être entre 20 et 300 kg.');
            valide = false;
        }
    }

    // --- POIDS CIBLE ---
    const poidsCibleVal = document.getElementById('poids_cible').value.trim();
    if (!poidsCibleVal) {
        afficherErreur('poids_cible', 'err_poids_cible', 'Le poids cible est obligatoire.');
        valide = false;
    } else if (!/^\d+(\.\d{1,2})?$/.test(poidsCibleVal)) {
        afficherErreur('poids_cible', 'err_poids_cible', 'Format invalide. Ex: 65 ou 65.5');
        valide = false;
    } else {
        const pC = parseFloat(poidsCibleVal);
        if (pC < 20 || pC > 300) {
            afficherErreur('poids_cible', 'err_poids_cible', 'Le poids cible doit être entre 20 et 300 kg.');
            valide = false;
        }
    }

    // --- TAILLE format 1.62 ---
    const tailleVal = document.getElementById('taille').value.trim();
    if (!tailleVal) {
        afficherErreur('taille', 'err_taille', 'La taille est obligatoire.');
        valide = false;
    } else if (!/^\d+\.\d{2}$/.test(tailleVal)) {
        afficherErreur('taille', 'err_taille', 'Format invalide. Ex: 1.72 (obligatoire avec 2 décimales)');
        valide = false;
    } else {
        const t = parseFloat(tailleVal);
        if (t < 0.50 || t > 2.50) {
            afficherErreur('taille', 'err_taille', 'La taille doit être entre 0.50 et 2.50 m.');
            valide = false;
        }
    }

    // --- AGE ---
    const ageVal = document.getElementById('age').value.trim();
    if (!ageVal) {
        afficherErreur('age', 'err_age', "L'âge est obligatoire.");
        valide = false;
    } else if (!/^\d+$/.test(ageVal)) {
        afficherErreur('age', 'err_age', "L'âge doit être un nombre entier. Ex: 25");
        valide = false;
    } else {
        const a = parseInt(ageVal);
        if (a < 10 || a > 120) {
            afficherErreur('age', 'err_age', "L'âge doit être entre 10 et 120 ans.");
            valide = false;
        }
    }

    // --- DATE DEBUT ---
    const dateDebutVal = document.getElementById('date_debut').value.trim();
    let dateDebut = null;
    if (!dateDebutVal) {
        afficherErreur('date_debut', 'err_date_debut', 'La date de début est obligatoire.');
        valide = false;
    } else {
        dateDebut = parseDate(dateDebutVal);
        if (!dateDebut) {
            afficherErreur('date_debut', 'err_date_debut', 'Format invalide. Utilisez jj/mm/aaaa');
            valide = false;
        }
    }

    // --- DATE FIN ---
    const dateFinVal = document.getElementById('date_fin_prevue').value.trim();
    let dateFin = null;
    if (!dateFinVal) {
        afficherErreur('date_fin_prevue', 'err_date_fin', 'La date de fin prévue est obligatoire.');
        valide = false;
    } else {
        dateFin = parseDate(dateFinVal);
        if (!dateFin) {
            afficherErreur('date_fin_prevue', 'err_date_fin', 'Format invalide. Utilisez jj/mm/aaaa');
            valide = false;
        } else if (dateDebut && dateFin <= dateDebut) {
            afficherErreur('date_fin_prevue', 'err_date_fin', 'La date de fin doit être supérieure à la date de début.');
            valide = false;
        }
    }

    // --- ETAT SANTE (optionnel) ---
    const etatSante = document.getElementById(