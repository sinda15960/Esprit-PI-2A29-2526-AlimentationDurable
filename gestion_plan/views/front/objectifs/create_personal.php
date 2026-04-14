<?php 
require_once 'C:/xampp/htdocs/gestion_plan/header.php'; 
?>

<h2 class="section-title">➕ Ajouter mon objectif personnel</h2>

<div class="card">
    <div class="card-body">
        <form method="POST" action="index.php?module=objectif&action=storePersonal&office=front" 
              id="formObjectifPerso" onsubmit="return validerForm()">
            
            <div class="mb-3">
                <label class="fw-bold">Titre personnalisé *</label>
                <input type="text" name="titre" id="titre" class="form-control" 
                       placeholder="Ex: Mon défi perte de poids, Objectif été...">
                <div class="text-danger small" id="err_titre"></div>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Description</label>
                <textarea name="description" id="description" class="form-control" rows="2"
                          placeholder="Décrivez votre objectif..."></textarea>
                <div class="text-danger small" id="err_description"></div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label>Poids actuel (kg)</label>
                    <input type="number" name="poids_actuel" id="poids_actuel" step="0.1" class="form-control">
                    <div class="text-danger small" id="err_poids_actuel"></div>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Poids cible (kg)</label>
                    <input type="number" name="poids_cible" id="poids_cible" step="0.1" class="form-control">
                    <div class="text-danger small" id="err_poids_cible"></div>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Taille (cm)</label>
                    <input type="number" name="taille" id="taille" class="form-control">
                    <div class="text-danger small" id="err_taille"></div>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Âge</label>
                    <input type="number" name="age" id="age" class="form-control">
                    <div class="text-danger small" id="err_age"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Date de début</label>
                    <input type="date" name="date_debut" id="date_debut" class="form-control">
                    <div class="text-danger small" id="err_date_debut"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Date fin prévue</label>
                    <input type="date" name="date_fin_prevue" id="date_fin_prevue" class="form-control">
                    <div class="text-danger small" id="err_date_fin"></div>
                </div>
            </div>

            <div class="mb-3">
                <label>État de santé / Cas exceptionnel</label>
                <textarea name="etat_sante" id="etat_sante" class="form-control" rows="2"
                          placeholder="Diabète, hypertension, allergie, blessure..."></textarea>
                <div class="text-danger small" id="err_etat_sante"></div>
            </div>

            <button type="submit" class="btn btn-green">💾 Enregistrer mon objectif</button>
            <a href="index.php?module=objectif&action=index&office=front" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>

<script>
function validerForm() {
    let valide = true;
    
    document.querySelectorAll('.text-danger').forEach(e => e.textContent = '');
    document.querySelectorAll('.form-control').forEach(e => e.classList.remove('is-invalid'));
    
    const titre = document.getElementById('titre').value.trim();
    if (titre === '') {
        document.getElementById('err_titre').textContent = 'Le titre est obligatoire.';
        document.getElementById('titre').classList.add('is-invalid');
        valide = false;
    } else if (titre.length < 3) {
        document.getElementById('err_titre').textContent = 'Le titre doit avoir au moins 3 caractères.';
        document.getElementById('titre').classList.add('is-invalid');
        valide = false;
    }
    
    const poidsActuel = document.getElementById('poids_actuel').value;
    if (poidsActuel !== '') {
        const pA = parseFloat(poidsActuel);
        if (isNaN(pA) || pA < 20 || pA > 300) {
            document.getElementById('err_poids_actuel').textContent = 'Le poids doit être entre 20 et 300 kg.';
            document.getElementById('poids_actuel').classList.add('is-invalid');
            valide = false;
        }
    }
    
    const poidsCible = document.getElementById('poids_cible').value;
    if (poidsCible !== '') {
        const pC = parseFloat(poidsCible);
        if (isNaN(pC) || pC < 20 || pC > 300) {
            document.getElementById('err_poids_cible').textContent = 'Le poids cible doit être entre 20 et 300 kg.';
            document.getElementById('poids_cible').classList.add('is-invalid');
            valide = false;
        }
    }
    
    const age = document.getElementById('age').value;
    if (age !== '') {
        const a = parseInt(age);
        if (isNaN(a) || a < 10 || a > 120) {
            document.getElementById('err_age').textContent = 'L\'âge doit être entre 10 et 120 ans.';
            document.getElementById('age').classList.add('is-invalid');
            valide = false;
        }
    }
    
    return valide;
}
</script>

<?php 
require_once 'C:/xampp/htdocs/gestion_plan/footer.php'; 
?>