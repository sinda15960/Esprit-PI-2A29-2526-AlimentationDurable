// ==================== DONNÉES INITIALES ====================
let allergiesData = [
    { id: 1, nom: "Gluten", categorie: "Alimentaire", description: "Intolérance au gluten, maladie cœliaque. Le gluten est une protéine présente dans certaines céréales.", symptomes: "Ballonnements, douleurs abdominales, fatigue, diarrhée, perte de poids", declencheurs: "Blé, orge, seigle, avoine, pain, pâtes, biscuits", gravite: "severe" },
    { id: 2, nom: "Lactose", categorie: "Alimentaire", description: "Intolérance au lactose causée par un déficit en lactase, l'enzyme qui digère le lactose.", symptomes: "Diarrhées, gaz, ballonnements, douleurs abdominales, nausées", declencheurs: "Lait, fromage frais, crème, yaourt, beurre, glaces", gravite: "moderate" },
    { id: 3, nom: "Arachides", categorie: "Alimentaire", description: "Allergie aux arachides, une des allergies alimentaires les plus dangereuses.", symptomes: "Urticaire, gonflement du visage, difficultés respiratoires, choc anaphylactique", declencheurs: "Arachides, beurre d'arachide, huile d'arachide, cacahuètes", gravite: "severe" },
    { id: 4, nom: "Fruits de mer", categorie: "Alimentaire", description: "Allergie aux crustacés et mollusques, souvent à vie.", symptomes: "Démangeaisons, urticaire, nausées, vomissements, difficultés respiratoires", declencheurs: "Crevettes, crabes, homards, moules, huîtres, calmars", gravite: "severe" },
    { id: 5, nom: "Œufs", categorie: "Alimentaire", description: "Allergie aux protéines de l'œuf, fréquente chez les enfants.", symptomes: "Éruptions cutanées, urticaire, congestion nasale, troubles digestifs", declencheurs: "Œufs entiers, blanc d'œuf, mayonnaise, pâtisseries", gravite: "moderate" },
    { id: 6, nom: "Soja", categorie: "Alimentaire", description: "Allergie au soja, présent dans de nombreux aliments transformés.", symptomes: "Fourmillements dans la bouche, urticaire, démangeaisons, difficultés respiratoires", declencheurs: "Soja, tofu, tempeh, sauce soja, lait de soja, edamame", gravite: "moderate" },
    { id: 7, nom: "Poisson", categorie: "Alimentaire", description: "Allergie au poisson, distincte des fruits de mer.", symptomes: "Urticaire, gonflement, vomissements, difficultés respiratoires", declencheurs: "Saumon, thon, morue, sardine, maquereau, truite", gravite: "severe" },
    { id: 8, nom: "Pollen", categorie: "Respiratoire", description: "Allergie au pollen des arbres, graminées et herbes.", symptomes: "Éternuements, nez qui coule, yeux qui piquent, congestion nasale", declencheurs: "Pollen de bouleau, graminées, ambroisie, olivier", gravite: "legere" },
    { id: 9, nom: "Acariens", categorie: "Respiratoire", description: "Allergie aux acariens de la poussière domestique.", symptomes: "Éternuements, nez bouché, yeux rouges, asthme, toux", declencheurs: "Poussière, literie, moquettes, rideaux, peluches", gravite: "moderate" },
    { id: 10, nom: "Penicilline", categorie: "Médicamenteuse", description: "Allergie à la pénicilline et antibiotiques dérivés.", symptomes: "Urticaire, démangeaisons, gonflement, difficultés respiratoires", declencheurs: "Pénicilline, amoxicilline, ampicilline", gravite: "severe" }
];

let traitementsData = [
    { id: 1, allergie_nom: "Gluten", conseil: "Évitez tous les aliments contenant du gluten. Lisez attentivement les étiquettes.", interdits: "Pain, pâtes, biscuits, céréales, bière, pâtisseries", medicaments: "Aucun médicament spécifique, régime strict à vie", duree: "Permanente", niveau_urgence: "moyen" },
    { id: 2, allergie_nom: "Lactose", conseil: "Privilégiez les produits sans lactose ou prenez des enzymes lactase.", interdits: "Lait, fromage frais, crème, yaourt, glaces", medicaments: "Compléments de lactase (Lactaid, Lactrase)", duree: "Selon tolérance", niveau_urgence: "faible" },
    { id: 3, allergie_nom: "Arachides", conseil: "Évitez tout contact avec les arachides. Ayez toujours un auto-injecteur d'adrénaline.", interdits: "Arachides, beurre d'arachide, huile d'arachide, cacahuètes", medicaments: "Antihistaminiques, EpiPen (adrénaline)", duree: "À vie", niveau_urgence: "eleve" },
    { id: 4, allergie_nom: "Fruits de mer", conseil: "Évitez tous les crustacés et mollusques. Attention aux contaminations croisées.", interdits: "Crevettes, crabes, homards, moules, huîtres, calmars", medicaments: "Antihistaminiques, EpiPen pour réactions sévères", duree: "À vie", niveau_urgence: "eleve" },
    { id: 5, allergie_nom: "Œufs", conseil: "Évitez les œufs et produits dérivés. La cuisson ne détruit pas l'allergène.", interdits: "Œufs entiers, blanc d'œuf, mayonnaise, pâtisseries, viennoiseries", medicaments: "Antihistaminiques", duree: "Peut disparaître avec l'âge", niveau_urgence: "moyen" },
    { id: 6, allergie_nom: "Soja", conseil: "Lisez les étiquettes, le soja est présent dans de nombreux produits transformés.", interdits: "Tofu, tempeh, sauce soja, lait de soja, edamame", medicaments: "Antihistaminiques", duree: "À vie généralement", niveau_urgence: "moyen" },
    { id: 7, allergie_nom: "Poisson", conseil: "Évitez tous les poissons. Attention aux restaurants et aux aliments transformés.", interdits: "Saumon, thon, morue, sardine, maquereau, truite", medicaments: "Antihistaminiques, EpiPen", duree: "À vie", niveau_urgence: "eleve" },
    { id: 8, allergie_nom: "Pollen", conseil: "Restez à l'intérieur pendant les pics polliniques. Prenez une douche en rentrant.", interdits: "Sorties prolongées au printemps, fenêtres ouvertes", medicaments: "Antihistaminiques (Cetirizine, Loratadine)", duree: "Saisonnière", niveau_urgence: "faible" },
    { id: 9, allergie_nom: "Acariens", conseil: "Utilisez des housses anti-acariens. Lavez la literie à 60°C.", interdits: "Tapis, moquettes, rideaux épais, peluches", medicaments: "Antihistaminiques, corticoïdes inhalés", duree: "Toute l'année", niveau_urgence: "moyen" },
    { id: 10, allergie_nom: "Penicilline", conseil: "Signalez toujours votre allergie avant tout traitement antibiotique.", interdits: "Pénicilline, amoxicilline, ampicilline et dérivés", medicaments: "Antibiotiques alternatifs (macrolides, quinolones)", duree: "À vie", niveau_urgence: "eleve" }
];

let feedbacks = [
    { id: 1, type: "experience", message: "Je suis cœliaque depuis 5 ans, le régime sans gluten a changé ma vie !", email: "", date: new Date().toLocaleDateString(), status: "approuve" },
    { id: 2, type: "suggestion", message: "Ajoutez l'EPIPEN dans les traitements d'urgence pour les allergies sévères !", email: "", date: new Date().toLocaleDateString(), status: "approuve" },
    { id: 3, type: "erreur", message: "Le yaourt nature contient moins de lactose que le lait, à préciser.", email: "", date: new Date().toLocaleDateString(), status: "approuve" },
    { id: 4, type: "alternative", message: "Alternative au pain : pain à base de farine de riz ou de sarrasin.", email: "", date: new Date().toLocaleDateString(), status: "approuve" }
];

// ==================== SAUVEGARDE LOCALSTORAGE ====================
function saveToLocalStorage() {
    localStorage.setItem('nutriflow_allergies', JSON.stringify(allergiesData));
    localStorage.setItem('nutriflow_traitements', JSON.stringify(traitementsData));
    localStorage.setItem('nutriflow_feedbacks', JSON.stringify(feedbacks));
}

function loadFromLocalStorage() {
    const savedAllergies = localStorage.getItem('nutriflow_allergies');
    const savedTraitements = localStorage.getItem('nutriflow_traitements');
    const savedFeedbacks = localStorage.getItem('nutriflow_feedbacks');
    if (savedAllergies) allergiesData = JSON.parse(savedAllergies);
    if (savedTraitements) traitementsData = JSON.parse(savedTraitements);
    if (savedFeedbacks) feedbacks = JSON.parse(savedFeedbacks);
}

// ==================== AFFICHAGE TABLES ====================
function displayAllergiesTable() {
    const tbody = document.getElementById('allergies-table-body');
    if (!tbody) return;
    if (allergiesData.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" style="text-align:center">Aucune allergie trouvée</td></tr>';
        return;
    }
    tbody.innerHTML = allergiesData.map(a => `
        <tr>
            <td>${a.id}</td>
            <td><strong>${escapeHtml(a.nom)}</strong></td>
            <td>${escapeHtml(a.categorie)}</td>
            <td>${escapeHtml(a.description.substring(0, 50))}...</td>
            <td>${escapeHtml(a.symptomes.substring(0, 50))}...</td>
            <td>${escapeHtml(a.declencheurs.substring(0, 40))}...</td>
            <td><span class="badge badge-${a.gravite}">${getGraviteText(a.gravite)}</span></td>
            <td>
                <button class="btn btn-edit" onclick="editAllergie(${a.id})">✏️ Modifier</button>
                <button class="btn btn-delete" onclick="deleteAllergie(${a.id})">🗑️ Supprimer</button>
            </td>
        </tr>
    `).join('');
}

function displayTraitementsTable() {
    const tbody = document.getElementById('traitements-table-body');
    if (!tbody) return;
    if (traitementsData.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" style="text-align:center">Aucun traitement trouvé</td></tr>';
        return;
    }
    tbody.innerHTML = traitementsData.map(t => `
        <tr>
            <td>${t.id}</td>
            <td><strong>${escapeHtml(t.allergie_nom)}</strong></td>
            <td>${escapeHtml(t.conseil.substring(0, 50))}...</td>
            <td>${escapeHtml(t.interdits.substring(0, 50))}...</td>
            <td>${escapeHtml((t.medicaments || 'Aucun').substring(0, 40))}...</td>
            <td>${escapeHtml(t.duree || 'Non spécifiée')}</td>
            <td><span class="badge badge-${t.niveau_urgence}">${getUrgenceText(t.niveau_urgence)}</span></td>
            <td>
                <button class="btn btn-edit" onclick="editTraitement(${t.id})">✏️ Modifier</button>
                <button class="btn btn-delete" onclick="deleteTraitement(${t.id})">🗑️ Supprimer</button>
            </td>
        </tr>
    `).join('');
}

function updateStats() {
    document.getElementById('stat-allergies').textContent = allergiesData.length;
    document.getElementById('stat-traitements').textContent = traitementsData.length;
    document.getElementById('stat-feedbacks').textContent = feedbacks.length;
}

function loadAllergieNamesForSelect() {
    const select = document.getElementById('traitement_allergie_nom');
    if (select) {
        select.innerHTML = '<option value="">Sélectionner une allergie</option>' +
            allergiesData.map(a => `<option value="${escapeHtml(a.nom)}">${escapeHtml(a.nom)}</option>`).join('');
    }
}

function showMessage(containerId, message, type) {
    const container = document.getElementById(containerId);
    if (container) {
        container.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        setTimeout(() => { container.innerHTML = ''; }, 3000);
    }
}

// ==================== CRUD ALLERGIES ====================
function saveAllergie(event) {
    event.preventDefault();
    
    if (!validateAllergieForm()) {
        showMessage('allergie-form-message', 'Veuillez remplir tous les champs correctement', 'error');
        return;
    }
    
    const id = document.getElementById('allergie_id').value;
    const newAllergie = {
        id: id ? parseInt(id) : (allergiesData.length > 0 ? Math.max(...allergiesData.map(a => a.id)) + 1 : 1),
        nom: document.getElementById('allergie_nom').value.trim(),
        categorie: document.getElementById('allergie_categorie').value,
        description: document.getElementById('allergie_description').value.trim(),
        symptomes: document.getElementById('allergie_symptomes').value.trim(),
        declencheurs: document.getElementById('allergie_declencheurs').value.trim(),
        gravite: document.getElementById('allergie_gravite').value
    };
    
    if (id) {
        // Modification
        const index = allergiesData.findIndex(a => a.id == id);
        if (index !== -1) {
            allergiesData[index] = newAllergie;
            showMessage('allergies-message', 'Allergie modifiée avec succès !', 'success');
        }
    } else {
        // Ajout
        allergiesData.push(newAllergie);
        showMessage('allergies-message', 'Allergie ajoutée avec succès !', 'success');
    }
    
    saveToLocalStorage();
    displayAllergiesTable();
    updateStats();
    loadAllergieNamesForSelect();
    closeAllergieModal();
}

function deleteAllergie(id) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette allergie ? Cela supprimera aussi les traitements associés.')) return;
    
    allergiesData = allergiesData.filter(a => a.id !== id);
    traitementsData = traitementsData.filter(t => t.allergie_nom !== allergiesData.find(a => a.id === id)?.nom);
    
    saveToLocalStorage();
    displayAllergiesTable();
    displayTraitementsTable();
    updateStats();
    loadAllergieNamesForSelect();
    showMessage('allergies-message', 'Allergie supprimée avec succès !', 'success');
}

function editAllergie(id) {
    const allergie = allergiesData.find(a => a.id == id);
    if (allergie) {
        document.getElementById('allergie-modal-title').textContent = 'Modifier une allergie';
        document.getElementById('allergie_id').value = allergie.id;
        document.getElementById('allergie_nom').value = allergie.nom;
        document.getElementById('allergie_categorie').value = allergie.categorie;
        document.getElementById('allergie_description').value = allergie.description;
        document.getElementById('allergie_symptomes').value = allergie.symptomes;
        document.getElementById('allergie_declencheurs').value = allergie.declencheurs;
        document.getElementById('allergie_gravite').value = allergie.gravite;
        
        resetAllergieValidation();
        document.getElementById('allergie-modal').classList.add('active');
        validateAllergieForm();
    }
}

function openAllergieModal() {
    document.getElementById('allergie-modal-title').textContent = 'Ajouter une allergie';
    document.getElementById('allergie-form').reset();
    document.getElementById('allergie_id').value = '';
    document.getElementById('allergie-form-message').innerHTML = '';
    resetAllergieValidation();
    document.getElementById('allergie-modal').classList.add('active');
    validateAllergieForm();
}

function closeAllergieModal() {
    document.getElementById('allergie-modal').classList.remove('active');
}

function resetAllergieValidation() {
    const fields = ['allergie_nom', 'allergie_description', 'allergie_symptomes', 'allergie_declencheurs'];
    fields.forEach(field => {
        const input = document.getElementById(field);
        if (input) {
            input.classList.remove('error', 'valid');
        }
    });
    const selects = ['allergie_categorie', 'allergie_gravite'];
    selects.forEach(select => {
        const el = document.getElementById(select);
        if (el) {
            el.classList.remove('error', 'valid');
        }
    });
    const errors = document.querySelectorAll('#allergie-form .field-error, #allergie-form .field-success');
    errors.forEach(div => div.classList.remove('show'));
}

// ==================== CRUD TRAITEMENTS ====================
function saveTraitement(event) {
    event.preventDefault();
    
    if (!validateTraitementForm()) {
        showMessage('traitement-form-message', 'Veuillez remplir tous les champs correctement', 'error');
        return;
    }
    
    const id = document.getElementById('traitement_id').value;
    const newTraitement = {
        id: id ? parseInt(id) : (traitementsData.length > 0 ? Math.max(...traitementsData.map(t => t.id)) + 1 : 1),
        allergie_nom: document.getElementById('traitement_allergie_nom').value,
        conseil: document.getElementById('traitement_conseil').value.trim(),
        interdits: document.getElementById('traitement_interdits').value.trim(),
        medicaments: document.getElementById('traitement_medicaments').value.trim(),
        duree: document.getElementById('traitement_duree').value.trim(),
        niveau_urgence: document.getElementById('traitement_niveau_urgence').value
    };
    
    if (id) {
        const index = traitementsData.findIndex(t => t.id == id);
        if (index !== -1) {
            traitementsData[index] = newTraitement;
            showMessage('traitements-message', 'Traitement modifié avec succès !', 'success');
        }
    } else {
        traitementsData.push(newTraitement);
        showMessage('traitements-message', 'Traitement ajouté avec succès !', 'success');
    }
    
    saveToLocalStorage();
    displayTraitementsTable();
    updateStats();
    closeTraitementModal();
}

function deleteTraitement(id) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce traitement ?')) return;
    
    traitementsData = traitementsData.filter(t => t.id !== id);
    saveToLocalStorage();
    displayTraitementsTable();
    updateStats();
    showMessage('traitements-message', 'Traitement supprimé avec succès !', 'success');
}

function editTraitement(id) {
    const traitement = traitementsData.find(t => t.id == id);
    if (traitement) {
        document.getElementById('traitement-modal-title').textContent = 'Modifier un traitement';
        document.getElementById('traitement_id').value = traitement.id;
        document.getElementById('traitement_allergie_nom').value = traitement.allergie_nom;
        document.getElementById('traitement_conseil').value = traitement.conseil;
        document.getElementById('traitement_interdits').value = traitement.interdits;
        document.getElementById('traitement_medicaments').value = traitement.medicaments || '';
        document.getElementById('traitement_duree').value = traitement.duree || '';
        document.getElementById('traitement_niveau_urgence').value = traitement.niveau_urgence;
        
        resetTraitementValidation();
        document.getElementById('traitement-modal').classList.add('active');
        validateTraitementForm();
    }
}

function openTraitementModal() {
    document.getElementById('traitement-modal-title').textContent = 'Ajouter un traitement';
    document.getElementById('traitement-form').reset();
    document.getElementById('traitement_id').value = '';
    document.getElementById('traitement-form-message').innerHTML = '';
    resetTraitementValidation();
    document.getElementById('traitement-modal').classList.add('active');
    validateTraitementForm();
}

function closeTraitementModal() {
    document.getElementById('traitement-modal').classList.remove('active');
}

function resetTraitementValidation() {
    const fields = ['traitement_conseil', 'traitement_interdits'];
    fields.forEach(field => {
        const input = document.getElementById(field);
        if (input) {
            input.classList.remove('error', 'valid');
        }
    });
    const selects = ['traitement_allergie_nom', 'traitement_niveau_urgence'];
    selects.forEach(select => {
        const el = document.getElementById(select);
        if (el) {
            el.classList.remove('error', 'valid');
        }
    });
    const errors = document.querySelectorAll('#traitement-form .field-error, #traitement-form .field-success');
    errors.forEach(div => div.classList.remove('show'));
}

// ==================== VALIDATIONS ALLERGIES ====================
function validateAllergieNom() {
    const input = document.getElementById('allergie_nom');
    const errorDiv = document.getElementById('allergie_nom_error');
    const successDiv = document.getElementById('allergie_nom_success');
    const value = input.value.trim();
    if (value === '') {
        errorDiv.classList.remove('show'); successDiv.classList.remove('show');
        input.classList.remove('error', 'valid'); return false;
    }
    if (/[0-9]/.test(value)) {
        errorDiv.classList.add('show'); successDiv.classList.remove('show');
        input.classList.add('error'); input.classList.remove('valid'); return false;
    } else {
        errorDiv.classList.remove('show'); successDiv.classList.add('show');
        input.classList.remove('error'); input.classList.add('valid'); return true;
    }
}

function validateAllergieDescription() {
    const input = document.getElementById('allergie_description');
    const errorDiv = document.getElementById('allergie_description_error');
    const successDiv = document.getElementById('allergie_description_success');
    const value = input.value.trim();
    if (value === '') {
        errorDiv.classList.remove('show'); successDiv.classList.remove('show');
        input.classList.remove('error', 'valid'); return false;
    }
    if (value.length < 10) {
        errorDiv.classList.add('show'); successDiv.classList.remove('show');
        input.classList.add('error'); input.classList.remove('valid'); return false;
    } else {
        errorDiv.classList.remove('show'); successDiv.classList.add('show');
        input.classList.remove('error'); input.classList.add('valid'); return true;
    }
}

function validateAllergieSymptomes() {
    const input = document.getElementById('allergie_symptomes');
    const errorDiv = document.getElementById('allergie_symptomes_error');
    const successDiv = document.getElementById('allergie_symptomes_success');
    const value = input.value.trim();
    if (value === '') {
        errorDiv.classList.remove('show'); successDiv.classList.remove('show');
        input.classList.remove('error', 'valid'); return false;
    }
    if (value.length < 10) {
        errorDiv.classList.add('show'); successDiv.classList.remove('show');
        input.classList.add('error'); input.classList.remove('valid'); return false;
    } else {
        errorDiv.classList.remove('show'); successDiv.classList.add('show');
        input.classList.remove('error'); input.classList.add('valid'); return true;
    }
}

function validateAllergieCategorie() {
    const select = document.getElementById('allergie_categorie');
    const errorDiv = document.getElementById('allergie_categorie_error');
    const isValid = select.value !== '';
    if (!isValid) { errorDiv.classList.add('show'); select.classList.add('error'); select.classList.remove('valid'); }
    else { errorDiv.classList.remove('show'); select.classList.remove('error'); select.classList.add('valid'); }
    return isValid;
}

function validateAllergieDeclencheurs() {
    const input = document.getElementById('allergie_declencheurs');
    const errorDiv = document.getElementById('allergie_declencheurs_error');
    const isValid = input.value.trim() !== '';
    if (!isValid) { errorDiv.classList.add('show'); input.classList.add('error'); input.classList.remove('valid'); }
    else { errorDiv.classList.remove('show'); input.classList.remove('error'); input.classList.add('valid'); }
    return isValid;
}

function validateAllergieGravite() {
    const select = document.getElementById('allergie_gravite');
    const errorDiv = document.getElementById('allergie_gravite_error');
    const isValid = select.value !== '';
    if (!isValid) { errorDiv.classList.add('show'); select.classList.add('error'); select.classList.remove('valid'); }
    else { errorDiv.classList.remove('show'); select.classList.remove('error'); select.classList.add('valid'); }
    return isValid;
}

function validateAllergieForm() {
    const isValid = validateAllergieNom() && validateAllergieCategorie() && validateAllergieDescription() && 
                    validateAllergieSymptomes() && validateAllergieDeclencheurs() && validateAllergieGravite();
    const btn = document.getElementById('allergie-submit-btn');
    if (btn) { btn.disabled = !isValid; btn.style.opacity = isValid ? '1' : '0.6'; btn.style.cursor = isValid ? 'pointer' : 'not-allowed'; }
    return isValid;
}

// ==================== VALIDATIONS TRAITEMENTS ====================
function validateTraitementAllergie() {
    const select = document.getElementById('traitement_allergie_nom');
    const errorDiv = document.getElementById('traitement_allergie_nom_error');
    const isValid = select.value !== '';
    if (!isValid) { errorDiv.classList.add('show'); select.classList.add('error'); select.classList.remove('valid'); }
    else { errorDiv.classList.remove('show'); select.classList.remove('error'); select.classList.add('valid'); }
    return isValid;
}

function validateTraitementConseil() {
    const input = document.getElementById('traitement_conseil');
    const errorDiv = document.getElementById('traitement_conseil_error');
    const successDiv = document.getElementById('traitement_conseil_success');
    const value = input.value.trim();
    if (value === '') {
        errorDiv.classList.remove('show'); successDiv.classList.remove('show');
        input.classList.remove('error', 'valid'); return false;
    }
    if (value.length < 10) {
        errorDiv.classList.add('show'); successDiv.classList.remove('show');
        input.classList.add('error'); input.classList.remove('valid'); return false;
    } else {
        errorDiv.classList.remove('show'); successDiv.classList.add('show');
        input.classList.remove('error'); input.classList.add('valid'); return true;
    }
}

function validateTraitementInterdits() {
    const input = document.getElementById('traitement_interdits');
    const errorDiv = document.getElementById('traitement_interdits_error');
    const successDiv = document.getElementById('traitement_interdits_success');
    const value = input.value.trim();
    if (value === '') {
        errorDiv.classList.remove('show'); successDiv.classList.remove('show');
        input.classList.remove('error', 'valid'); return false;
    }
    if (value.length < 5) {
        errorDiv.classList.add('show'); successDiv.classList.remove('show');
        input.classList.add('error'); input.classList.remove('valid'); return false;
    } else {
        errorDiv.classList.remove('show'); successDiv.classList.add('show');
        input.classList.remove('error'); input.classList.add('valid'); return true;
    }
}

function validateTraitementUrgence() {
    const select = document.getElementById('traitement_niveau_urgence');
    const errorDiv = document.getElementById('traitement_niveau_urgence_error');
    const isValid = select.value !== '';
    if (!isValid) { errorDiv.classList.add('show'); select.classList.add('error'); select.classList.remove('valid'); }
    else { errorDiv.classList.remove('show'); select.classList.remove('error'); select.classList.add('valid'); }
    return isValid;
}

function validateTraitementForm() {
    const isValid = validateTraitementAllergie() && validateTraitementConseil() && 
                    validateTraitementInterdits() && validateTraitementUrgence();
    const btn = document.getElementById('traitement-submit-btn');
    if (btn) { btn.disabled = !isValid; btn.style.opacity = isValid ? '1' : '0.6'; btn.style.cursor = isValid ? 'pointer' : 'not-allowed'; }
    return isValid;
}

// ==================== UTILITAIRES ====================
function getGraviteText(gravite) {
    const map = { 'legere': 'Légère', 'moderate': 'Modérée', 'severe': 'Sévère' };
    return map[gravite] || gravite;
}

function getUrgenceText(urgence) {
    const map = { 'faible': 'Faible', 'moyen': 'Moyen', 'eleve': 'Élevé' };
    return map[urgence] || urgence;
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// ==================== CHARGEMENT DES ONGLETS ====================
function showTab(tabName) {
    const allergiesTab = document.getElementById('allergies-tab');
    const traitementsTab = document.getElementById('traitements-tab');
    const buttons = document.querySelectorAll('.tab-btn');
    
    if (tabName === 'allergies') {
        allergiesTab.style.display = 'block';
        traitementsTab.style.display = 'none';
        buttons[0].classList.add('active');
        buttons[1].classList.remove('active');
        displayAllergiesTable();
    } else {
        allergiesTab.style.display = 'none';
        traitementsTab.style.display = 'block';
        buttons[0].classList.remove('active');
        buttons[1].classList.add('active');
        displayTraitementsTable();
    }
}

// ==================== ÉVÉNEMENTS ET INITIALISATION ====================
function setupEventListeners() {
    const allergieForm = document.getElementById('allergie-form');
    if (allergieForm) {
        allergieForm.removeEventListener('submit', saveAllergie);
        allergieForm.addEventListener('submit', saveAllergie);
    }
    
    const traitementForm = document.getElementById('traitement-form');
    if (traitementForm) {
        traitementForm.removeEventListener('submit', saveTraitement);
        traitementForm.addEventListener('submit', saveTraitement);
    }
    
    const nomInput = document.getElementById('allergie_nom');
    if (nomInput) nomInput.addEventListener('input', validateAllergieForm);
    
    const descInput = document.getElementById('allergie_description');
    if (descInput) descInput.addEventListener('input', validateAllergieForm);
    
    const symptInput = document.getElementById('allergie_symptomes');
    if (symptInput) symptInput.addEventListener('input', validateAllergieForm);
    
    const declencheursInput = document.getElementById('allergie_declencheurs');
    if (declencheursInput) declencheursInput.addEventListener('input', validateAllergieForm);
    
    const categorieSelect = document.getElementById('allergie_categorie');
    if (categorieSelect) categorieSelect.addEventListener('change', validateAllergieForm);
    
    const graviteSelect = document.getElementById('allergie_gravite');
    if (graviteSelect) graviteSelect.addEventListener('change', validateAllergieForm);
    
    const traitementAllergieSelect = document.getElementById('traitement_allergie_nom');
    if (traitementAllergieSelect) traitementAllergieSelect.addEventListener('change', validateTraitementForm);
    
    const traitementConseilInput = document.getElementById('traitement_conseil');
    if (traitementConseilInput) traitementConseilInput.addEventListener('input', validateTraitementForm);
    
    const traitementInterditsInput = document.getElementById('traitement_interdits');
    if (traitementInterditsInput) traitementInterditsInput.addEventListener('input', validateTraitementForm);
    
    const traitementUrgenceSelect = document.getElementById('traitement_niveau_urgence');
    if (traitementUrgenceSelect) traitementUrgenceSelect.addEventListener('change', validateTraitementForm);
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    console.log('BackOffice chargé - Version complète avec CRUD et validations');
    loadFromLocalStorage();
    displayAllergiesTable();
    displayTraitementsTable();
    updateStats();
    loadAllergieNamesForSelect();
    setupEventListeners();
});