// Variables
let allergiesData = [];
let traitementsData = [];

// ==================== CHARGEMENT DES DONNÉES ====================

async function loadAllergies() {
    try {
        const response = await fetch('api/allergies.php');
        allergiesData = await response.json();
        displayAllergiesTable();
        updateStats();
    } catch(e) {
        showMessage('Erreur de chargement des allergies', 'error', 'message-container');
    }
}

async function loadTraitements() {
    try {
        const response = await fetch('api/traitements.php');
        traitementsData = await response.json();
        displayTraitementsTable();
        updateStats();
    } catch(e) {
        showMessage('Erreur de chargement des traitements', 'error', 'message-container');
    }
}

async function loadAllergieNamesForSelect() {
    try {
        const response = await fetch('api/allergies.php');
        const allergies = await response.json();
        const select = document.getElementById('traitement_allergie_nom');
        if (select) {
            select.innerHTML = '<option value="">Sélectionner une allergie</option>' +
                allergies.map(a => `<option value="${escapeHtml(a.nom)}">${escapeHtml(a.nom)}</option>`).join('');
        }
    } catch(e) {
        console.error('Erreur chargement noms allergies', e);
    }
}

async function updateStats() {
    try {
        const allergiesRes = await fetch('api/allergies.php');
        const allergies = await allergiesRes.json();
        const traitementsRes = await fetch('api/traitements.php');
        const traitements = await traitementsRes.json();
        const feedbacksRes = await fetch('api/feedbacks.php');
        const feedbacks = await feedbacksRes.json();
        
        document.getElementById('stat-allergies').textContent = allergies.length;
        document.getElementById('stat-traitements').textContent = traitements.length;
        document.getElementById('stat-feedbacks').textContent = feedbacks.length;
    } catch(e) {
        console.error('Erreur stats', e);
    }
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

// ==================== CONTRÔLES DE SAISIE ====================

function validateAllergieForm(data) {
    let errors = [];
    if (!data.nom || data.nom.trim() === "") errors.push("Le nom est obligatoire");
    else if (data.nom.trim().length < 2) errors.push("Le nom doit contenir au moins 2 caractères");
    else if (data.nom.trim().length > 50) errors.push("Le nom ne peut pas dépasser 50 caractères");
    
    if (!data.categorie || data.categorie === "") errors.push("La catégorie est obligatoire");
    
    if (!data.description || data.description.trim() === "") errors.push("La description est obligatoire");
    else if (data.description.trim().length < 10) errors.push("La description doit contenir au moins 10 caractères");
    
    if (!data.symptomes || data.symptomes.trim() === "") errors.push("Les symptômes sont obligatoires");
    else if (data.symptomes.trim().length < 10) errors.push("Les symptômes doivent contenir au moins 10 caractères");
    
    if (!data.declencheurs || data.declencheurs.trim() === "") errors.push("Les déclencheurs sont obligatoires");
    
    const validGravites = ['legere', 'moderate', 'severe'];
    if (!data.gravite || !validGravites.includes(data.gravite)) errors.push("Veuillez sélectionner une gravité valide");
    
    return errors;
}

function validateTraitementForm(data) {
    let errors = [];
    if (!data.allergie_nom || data.allergie_nom === "") errors.push("Veuillez sélectionner une allergie");
    
    if (!data.conseil || data.conseil.trim() === "") errors.push("Les conseils sont obligatoires");
    else if (data.conseil.trim().length < 10) errors.push("Les conseils doivent contenir au moins 10 caractères");
    
    if (!data.interdits || data.interdits.trim() === "") errors.push("La liste des interdits est obligatoire");
    else if (data.interdits.trim().length < 5) errors.push("Les interdits doivent contenir au moins 5 caractères");
    
    const validUrgences = ['faible', 'moyen', 'eleve'];
    if (!data.niveau_urgence || !validUrgences.includes(data.niveau_urgence)) errors.push("Veuillez sélectionner un niveau d'urgence valide");
    
    return errors;
}

function showMessage(message, type, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
    container.innerHTML = `<div class="alert ${alertClass}">${message}</div>`;
    setTimeout(() => { container.innerHTML = ''; }, 3000);
}

// ==================== CRUD ALLERGIES ====================

function openAllergieModal() {
    document.getElementById('allergie-form').reset();
    document.getElementById('allergie_id').value = '';
    document.getElementById('allergie-modal-title').textContent = 'Ajouter une allergie';
    document.getElementById('allergie-modal').classList.add('active');
}

function closeAllergieModal() {
    document.getElementById('allergie-modal').classList.remove('active');
    document.getElementById('allergie-form').reset();
    document.getElementById('allergie-form-message').innerHTML = '';
}

async function editAllergie(id) {
    try {
        const response = await fetch(`api/allergies.php?id=${id}`);
        const allergie = await response.json();
        
        document.getElementById('allergie_id').value = allergie.id;
        document.getElementById('allergie_nom').value = allergie.nom;
        document.getElementById('allergie_categorie').value = allergie.categorie;
        document.getElementById('allergie_description').value = allergie.description;
        document.getElementById('allergie_symptomes').value = allergie.symptomes;
        document.getElementById('allergie_declencheurs').value = allergie.declencheurs;
        document.getElementById('allergie_gravite').value = allergie.gravite;
        document.getElementById('allergie-modal-title').textContent = 'Modifier une allergie';
        document.getElementById('allergie-modal').classList.add('active');
    } catch(e) {
        showMessage('Erreur lors du chargement', 'error', 'message-container');
    }
}

async function deleteAllergie(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette allergie ?')) {
        try {
            const response = await fetch(`api/allergies.php?id=${id}`, { method: 'DELETE' });
            const result = await response.json();
            if (result.success) {
                showMessage(result.message, 'success', 'message-container');
                loadAllergies();
                loadTraitements();
                loadAllergieNamesForSelect();
            } else {
                showMessage(result.message, 'error', 'message-container');
            }
        } catch(e) {
            showMessage('Erreur lors de la suppression', 'error', 'message-container');
        }
    }
}

async function saveAllergie(event) {
    event.preventDefault();
    
    const formData = {
        nom: document.getElementById('allergie_nom').value,
        categorie: document.getElementById('allergie_categorie').value,
        description: document.getElementById('allergie_description').value,
        symptomes: document.getElementById('allergie_symptomes').value,
        declencheurs: document.getElementById('allergie_declencheurs').value,
        gravite: document.getElementById('allergie_gravite').value
    };
    
    const errors = validateAllergieForm(formData);
    if (errors.length > 0) {
        showMessage(errors.join('<br>'), 'error', 'allergie-form-message');
        return;
    }
    
    const id = document.getElementById('allergie_id').value;
    const method = id ? 'PUT' : 'POST';
    const url = id ? `api/allergies.php?id=${id}` : 'api/allergies.php';
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });
        const result = await response.json();
        
        if (result.success) {
            showMessage(result.message, 'success', 'message-container');
            closeAllergieModal();
            loadAllergies();
            loadAllergieNamesForSelect();
        } else {
            showMessage(result.message, 'error', 'allergie-form-message');
        }
    } catch(e) {
        showMessage('Erreur lors de l\'enregistrement', 'error', 'allergie-form-message');
    }
}

// ==================== CRUD TRAITEMENTS ====================

function openTraitementModal() {
    loadAllergieNamesForSelect();
    document.getElementById('traitement-form').reset();
    document.getElementById('traitement_id').value = '';
    document.getElementById('traitement-modal-title').textContent = 'Ajouter un traitement';
    document.getElementById('traitement-modal').classList.add('active');
}

function closeTraitementModal() {
    document.getElementById('traitement-modal').classList.remove('active');
    document.getElementById('traitement-form').reset();
    document.getElementById('traitement-form-message').innerHTML = '';
}

async function editTraitement(id) {
    try {
        await loadAllergieNamesForSelect();
        const response = await fetch(`api/traitements.php?id=${id}`);
        const traitement = await response.json();
        
        document.getElementById('traitement_id').value = traitement.id;
        document.getElementById('traitement_allergie_nom').value = traitement.allergie_nom;
        document.getElementById('traitement_conseil').value = traitement.conseil;
        document.getElementById('traitement_interdits').value = traitement.interdits;
        document.getElementById('traitement_medicaments').value = traitement.medicaments || '';
        document.getElementById('traitement_duree').value = traitement.duree || '';
        document.getElementById('traitement_niveau_urgence').value = traitement.niveau_urgence;
        document.getElementById('traitement-modal-title').textContent = 'Modifier un traitement';
        document.getElementById('traitement-modal').classList.add('active');
    } catch(e) {
        showMessage('Erreur lors du chargement', 'error', 'message-container');
    }
}

async function deleteTraitement(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce traitement ?')) {
        try {
            const response = await fetch(`api/traitements.php?id=${id}`, { method: 'DELETE' });
            const result = await response.json();
            if (result.success) {
                showMessage(result.message, 'success', 'message-container');
                loadTraitements();
            } else {
                showMessage(result.message, 'error', 'message-container');
            }
        } catch(e) {
            showMessage('Erreur lors de la suppression', 'error', 'message-container');
        }
    }
}

async function saveTraitement(event) {
    event.preventDefault();
    
    const formData = {
        allergie_nom: document.getElementById('traitement_allergie_nom').value,
        conseil: document.getElementById('traitement_conseil').value,
        interdits: document.getElementById('traitement_interdits').value,
        medicaments: document.getElementById('traitement_medicaments').value,
        duree: document.getElementById('traitement_duree').value,
        niveau_urgence: document.getElementById('traitement_niveau_urgence').value
    };
    
    const errors = validateTraitementForm(formData);
    if (errors.length > 0) {
        showMessage(errors.join('<br>'), 'error', 'traitement-form-message');
        return;
    }
    
    const id = document.getElementById('traitement_id').value;
    const method = id ? 'PUT' : 'POST';
    const url = id ? `api/traitements.php?id=${id}` : 'api/traitements.php';
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });
        const result = await response.json();
        
        if (result.success) {
            showMessage(result.message, 'success', 'message-container');
            closeTraitementModal();
            loadTraitements();
        } else {
            showMessage(result.message, 'error', 'traitement-form-message');
        }
    } catch(e) {
        showMessage('Erreur lors de l\'enregistrement', 'error', 'traitement-form-message');
    }
}

// ==================== UTILITAIRES ====================

function showTab(tabName) {
    document.getElementById('allergies-tab').style.display = 'none';
    document.getElementById('traitements-tab').style.display = 'none';
    
    if (tabName === 'allergies') {
        document.getElementById('allergies-tab').style.display = 'block';
        loadAllergies();
    } else if (tabName === 'traitements') {
        document.getElementById('traitements-tab').style.display = 'block';
        loadTraitements();
    }
    
    const btns = document.querySelectorAll('.tab-btn');
    btns.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
}

function getGraviteText(g) {
    const map = { 'legere': 'Légère', 'moderate': 'Modérée', 'severe': 'Sévère' };
    return map[g] || g;
}

function getUrgenceText(u) {
    const map = { 'faible': 'Faible', 'moyen': 'Moyen', 'eleve': 'Élevé' };
    return map[u] || u;
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

// ==================== INITIALISATION ====================

document.addEventListener('DOMContentLoaded', () => {
    loadAllergies();
    loadTraitements();
    loadAllergieNamesForSelect();
    updateStats();
    
    document.getElementById('allergie-form').addEventListener('submit', saveAllergie);
    document.getElementById('traitement-form').addEventListener('submit', saveTraitement);
});