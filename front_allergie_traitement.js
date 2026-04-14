// // Choisis selon ton nom de dossier :
const API_URL = '/EspritNutriFlowMVC/api/';
// OU
// const API_URL = '/EspritNutriFlowMVC/api/';  // ← Si tu renommes en EspritNutriFlowMVC10 Allergies avec leurs données complètes
let allergiesData = [
    { id: 1, nom: "Gluten", description: "Intolérance au gluten, maladie cœliaque. Le gluten est une protéine présente dans certaines céréales.", symptomes: "Ballonnements, douleurs abdominales, fatigue, diarrhée, perte de poids", declencheurs: "Blé, orge, seigle, avoine, pain, pâtes, biscuits", gravite: "severe", categorie: "Alimentaire" },
    { id: 2, nom: "Lactose", description: "Intolérance au lactose causée par un déficit en lactase, l'enzyme qui digère le lactose.", symptomes: "Diarrhées, gaz, ballonnements, douleurs abdominales, nausées", declencheurs: "Lait, fromage frais, crème, yaourt, beurre, glaces", gravite: "moderate", categorie: "Alimentaire" },
    { id: 3, nom: "Arachides", description: "Allergie aux arachides, une des allergies alimentaires les plus dangereuses.", symptomes: "Urticaire, gonflement du visage, difficultés respiratoires, choc anaphylactique", declencheurs: "Arachides, beurre d'arachide, huile d'arachide, cacahuètes", gravite: "severe", categorie: "Alimentaire" },
    { id: 4, nom: "Fruits de mer", description: "Allergie aux crustacés et mollusques, souvent à vie.", symptomes: "Démangeaisons, urticaire, nausées, vomissements, difficultés respiratoires", declencheurs: "Crevettes, crabes, homards, moules, huîtres, calmars", gravite: "severe", categorie: "Alimentaire" },
    { id: 5, nom: "Œufs", description: "Allergie aux protéines de l'œuf, fréquente chez les enfants.", symptomes: "Éruptions cutanées, urticaire, congestion nasale, troubles digestifs", declencheurs: "Œufs entiers, blanc d'œuf, jaune d'œuf, mayonnaise, pâtisseries", gravite: "moderate", categorie: "Alimentaire" },
    { id: 6, nom: "Soja", description: "Allergie au soja, présent dans de nombreux aliments transformés.", symptomes: "Fourmillements dans la bouche, urticaire, démangeaisons, difficultés respiratoires", declencheurs: "Soja, tofu, tempeh, sauce soja, lait de soja, edamame", gravite: "moderate", categorie: "Alimentaire" },
    { id: 7, nom: "Poisson", description: "Allergie au poisson, distincte des fruits de mer.", symptomes: "Urticaire, gonflement, vomissements, difficultés respiratoires", declencheurs: "Saumon, thon, morue, sardine, maquereau, truite", gravite: "severe", categorie: "Alimentaire" },
    { id: 8, nom: "Pollen", description: "Allergie au pollen des arbres, graminées et herbes.", symptomes: "Éternuements, nez qui coule, yeux qui piquent, congestion nasale", declencheurs: "Pollen de bouleau, graminées, ambroisie, olivier", gravite: "legere", categorie: "Respiratoire" },
    { id: 9, nom: "Acariens", description: "Allergie aux acariens de la poussière domestique.", symptomes: "Éternuements, nez bouché, yeux rouges, asthme, toux", declencheurs: "Poussière, literie, moquettes, rideaux, peluches", gravite: "moderate", categorie: "Respiratoire" },
    { id: 10, nom: "Penicilline", description: "Allergie à la pénicilline et antibiotiques dérivés.", symptomes: "Urticaire, démangeaisons, gonflement, difficultés respiratoires", declencheurs: "Pénicilline, amoxicilline, ampicilline", gravite: "severe", categorie: "Médicamenteuse" }
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

// ==================== SYSTÈME DE FEEDBACK ====================
let feedbacks = JSON.parse(localStorage.getItem('nutriflow_feedbacks')) || [];

if (feedbacks.length === 0) {
    feedbacks = [
        { id: 1, type: "experience", message: "Je suis cœliaque depuis 5 ans, le régime sans gluten a changé ma vie !", email: "", date: new Date(Date.now() - 86400000).toLocaleDateString(), status: "approuve" },
        { id: 2, type: "suggestion", message: "Ajoutez l'EPIPEN dans les traitements d'urgence pour les allergies sévères !", email: "", date: new Date(Date.now() - 172800000).toLocaleDateString(), status: "approuve" },
        { id: 3, type: "erreur", message: "Le yaourt nature contient moins de lactose que le lait, à préciser.", email: "", date: new Date(Date.now() - 259200000).toLocaleDateString(), status: "approuve" },
        { id: 4, type: "alternative", message: "Alternative au pain : pain à base de farine de riz ou de sarrasin.", email: "", date: new Date(Date.now() - 345600000).toLocaleDateString(), status: "approuve" }
    ];
    localStorage.setItem('nutriflow_feedbacks', JSON.stringify(feedbacks));
}

// ==================== FONCTIONS PRINCIPALES ====================

function searchAllergie() {
    const searchTerm = document.getElementById('searchAllergie').value.toLowerCase().trim();
    const resultDiv = document.getElementById('allergie-result');
    
    let filtered = [...allergiesData];
    
    if (searchTerm !== '') {
        filtered = allergiesData.filter(a => 
            a.nom.toLowerCase().includes(searchTerm) || 
            a.description.toLowerCase().includes(searchTerm) ||
            a.symptomes.toLowerCase().includes(searchTerm)
        );
    }
    
    if (filtered.length === 0) {
        resultDiv.innerHTML = '<div class="no-result">❌ Aucune allergie trouvée</div>';
        return;
    }
    
    resultDiv.innerHTML = '<div class="allergies-grid">' + filtered.map(allergie => {
        const traitement = traitementsData.find(t => t.allergie_nom === allergie.nom);
        return generateAllergieCard(allergie, traitement);
    }).join('') + '</div>';
    
    attachCardEvents();
}

function generateAllergieCard(allergie, traitement) {
    const graviteClass = allergie.gravite;
    const graviteText = getGraviteText(allergie.gravite);
    
    return `
        <div class="allergie-card" data-allergie-id="${allergie.id}" data-allergie-nom="${allergie.nom}">
            <div class="card-header" data-toggle="card-body-${allergie.id}">
                <h3>
                    🥜 ${escapeHtml(allergie.nom)}
                    <span class="category-badge">${escapeHtml(allergie.categorie)}</span>
                </h3>
                <div class="toggle-icon" data-toggle="card-body-${allergie.id}">▼</div>
            </div>
            
            <div class="card-body hidden" id="card-body-${allergie.id}">
                <div class="allergie-detail">
                    <div class="label">📝 Description</div>
                    <div class="value">${escapeHtml(allergie.description)}</div>
                </div>
                <div class="allergie-detail">
                    <div class="label">🤒 Symptômes</div>
                    <div class="value">${escapeHtml(allergie.symptomes)}</div>
                </div>
                <div class="allergie-detail">
                    <div class="label">⚠️ Déclencheurs</div>
                    <div class="value">${escapeHtml(allergie.declencheurs)}</div>
                </div>
                <div class="allergie-detail">
                    <div class="label">⚡ Gravité</div>
                    <div class="value"><span class="gravite-badge badge-${graviteClass}">${graviteText}</span></div>
                </div>
                <button class="btn-traitement" data-traitement-btn="${allergie.id}">
                    💊 Voir le traitement associé
                </button>
            </div>
            
            <div class="traitement-section hidden" id="traitement-section-${allergie.id}">
                <div class="traitement-header" data-toggle-traitement="traitement-body-${allergie.id}">
                    <span>💊 Traitement pour ${escapeHtml(allergie.nom)}</span>
                    <span class="traitement-icon" data-toggle-traitement="traitement-body-${allergie.id}">▲</span>
                </div>
                <div class="traitement-body hidden" id="traitement-body-${allergie.id}">
                    ${traitement ? generateTraitementContent(traitement) : '<p>Aucun traitement spécifique trouvé.</p>'}
                </div>
            </div>
        </div>
    `;
}

function generateTraitementContent(traitement) {
    const urgenceClass = traitement.niveau_urgence;
    const urgenceText = getUrgenceText(traitement.niveau_urgence);
    
    return `
        <div class="traitement-grid">
            <div class="traitement-item">
                <div class="item-label">💡 Conseils</div>
                <div class="item-value">${escapeHtml(traitement.conseil)}</div>
            </div>
            <div class="traitement-item">
                <div class="item-label">🚫 Interdits</div>
                <div class="item-value">${escapeHtml(traitement.interdits)}</div>
            </div>
            <div class="traitement-item">
                <div class="item-label">💊 Médicaments</div>
                <div class="item-value">${escapeHtml(traitement.medicaments)}</div>
            </div>
            <div class="traitement-item">
                <div class="item-label">⏱️ Durée</div>
                <div class="item-value">${escapeHtml(traitement.duree)}</div>
            </div>
            <div class="traitement-item">
                <div class="item-label">🚨 Niveau d'urgence</div>
                <div class="item-value"><span class="badge badge-${urgenceClass}">${urgenceText}</span></div>
            </div>
        </div>
    `;
}

function attachCardEvents() {
    document.querySelectorAll('[data-toggle]').forEach(element => {
        element.removeEventListener('click', toggleCardBody);
        element.addEventListener('click', toggleCardBody);
    });
    
    document.querySelectorAll('[data-traitement-btn]').forEach(btn => {
        btn.removeEventListener('click', showTraitement);
        btn.addEventListener('click', showTraitement);
    });
    
    document.querySelectorAll('[data-toggle-traitement]').forEach(element => {
        element.removeEventListener('click', toggleTraitementBody);
        element.addEventListener('click', toggleTraitementBody);
    });
}

function toggleCardBody(event) {
    event.stopPropagation();
    const targetId = event.currentTarget.getAttribute('data-toggle');
    const bodyElement = document.getElementById(targetId);
    const icon = event.currentTarget.querySelector('.toggle-icon') || event.currentTarget;
    
    if (bodyElement) {
        bodyElement.classList.toggle('hidden');
        if (icon.classList) {
            icon.classList.toggle('rotated');
        }
    }
}

function showTraitement(event) {
    event.stopPropagation();
    const allergieId = event.currentTarget.getAttribute('data-traitement-btn');
    const traitementSection = document.getElementById(`traitement-section-${allergieId}`);
    const traitementBody = document.getElementById(`traitement-body-${allergieId}`);
    const icon = traitementSection?.querySelector('.traitement-icon');
    
    if (traitementSection) {
        const cardBody = document.getElementById(`card-body-${allergieId}`);
        if (cardBody && !cardBody.classList.contains('hidden')) {
            cardBody.classList.add('hidden');
            const headerIcon = document.querySelector(`.allergie-card[data-allergie-id="${allergieId}"] .toggle-icon`);
            if (headerIcon) headerIcon.classList.remove('rotated');
        }
        
        traitementSection.classList.remove('hidden');
        if (traitementBody && traitementBody.classList.contains('hidden')) {
            traitementBody.classList.remove('hidden');
            if (icon) icon.classList.add('rotated');
        }
    }
}

function toggleTraitementBody(event) {
    event.stopPropagation();
    const targetId = event.currentTarget.getAttribute('data-toggle-traitement');
    const bodyElement = document.getElementById(targetId);
    const icon = event.currentTarget.querySelector('.traitement-icon') || event.currentTarget;
    
    if (bodyElement) {
        bodyElement.classList.toggle('hidden');
        if (icon.classList) {
            icon.classList.toggle('rotated');
        }
    }
}

// ==================== SYSTÈME DE FEEDBACK ====================

function displayRecentFeedback() {
    const container = document.getElementById('recent-feedback');
    if (!container) return;
    
    const approvedFeedbacks = feedbacks.filter(f => f.status === 'approuve').slice(0, 3);
    
    if (approvedFeedbacks.length === 0) {
        container.innerHTML = '<p style="color: #999; font-size: 0.85rem;">Aucun avis pour le moment. Soyez le premier !</p>';
        return;
    }
    
    container.innerHTML = approvedFeedbacks.map(f => {
        let icon = "💬";
        let typeText = "";
        if (f.type === "erreur") { icon = "❌"; typeText = "Erreur"; }
        else if (f.type === "suggestion") { icon = "💡"; typeText = "Suggestion"; }
        else if (f.type === "experience") { icon = "📝"; typeText = "Expérience"; }
        else if (f.type === "alternative") { icon = "🍽️"; typeText = "Alternative"; }
        
        return `
            <div class="feedback-card">
                <div class="feedback-header">
                    <span class="feedback-type">${icon} ${typeText}</span>
                    <span class="feedback-date">${f.date}</span>
                </div>
                <div class="feedback-message">"${escapeHtml(f.message.substring(0, 100))}${f.message.length > 100 ? '...' : ''}"</div>
            </div>
        `;
    }).join('');
}

function saveFeedback(event) {
    event.preventDefault();
    
    const type = document.getElementById('feedback-type').value;
    const message = document.getElementById('feedback-message-text').value.trim();
    const email = document.getElementById('feedback-email').value.trim();
    
    let errors = [];
    
    if (!message) {
        errors.push("Veuillez écrire votre message");
    } else if (message.length < 5) {
        errors.push("Votre message doit contenir au moins 5 caractères");
    } else if (message.length > 300) {
        errors.push("Votre message ne peut pas dépasser 300 caractères");
    }
    
    if (email !== "") {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errors.push("Veuillez entrer une adresse email valide (ex: nom@domaine.com)");
        }
    }
    
    const msgDiv = document.getElementById('feedback-message');
    
    if (errors.length > 0) {
        msgDiv.innerHTML = `<div class="alert alert-error">${errors.join('<br>')}</div>`;
        setTimeout(() => { msgDiv.innerHTML = ''; }, 4000);
        return;
    }
    
    const newFeedback = {
        id: feedbacks.length + 1,
        type: type,
        message: message,
        email: email,
        date: new Date().toLocaleDateString(),
        status: "approuve"
    };
    
    feedbacks.unshift(newFeedback);
    localStorage.setItem('nutriflow_feedbacks', JSON.stringify(feedbacks));
    
    msgDiv.innerHTML = `<div class="alert alert-success">✅ Merci ! Votre avis a été envoyé avec succès.</div>`;
    
    document.getElementById('feedback-form').reset();
    
    setTimeout(() => { msgDiv.innerHTML = ''; }, 3000);
    
    displayRecentFeedback();
}

function setupRealtimeValidation() {
    const messageInput = document.getElementById('feedback-message-text');
    const emailInput = document.getElementById('feedback-email');
    const messageError = document.getElementById('message-error');
    const messageSuccess = document.getElementById('message-success');
    const emailError = document.getElementById('email-error');
    const emailSuccess = document.getElementById('email-success');
    
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            const value = this.value.trim();
            if (value.length >= 5 && value.length <= 300) {
                this.classList.add('valid');
                this.classList.remove('error');
                messageError.classList.remove('show');
                messageSuccess.classList.add('show');
            } else {
                this.classList.add('error');
                this.classList.remove('valid');
                messageError.classList.add('show');
                messageSuccess.classList.remove('show');
            }
        });
    }
    
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            const value = this.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (value === "" || emailRegex.test(value)) {
                this.classList.add('valid');
                this.classList.remove('error');
                emailError.classList.remove('show');
                if (value !== "") {
                    emailSuccess.classList.add('show');
                } else {
                    emailSuccess.classList.remove('show');
                }
            } else {
                this.classList.add('error');
                this.classList.remove('valid');
                emailError.classList.add('show');
                emailSuccess.classList.remove('show');
            }
        });
    }
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

// ==================== INITIALISATION ====================

document.addEventListener('DOMContentLoaded', () => {
    console.log('FrontOffice chargé - Version avec cartes modernes (Allergies & Traitements intégrés)');
    
    searchAllergie();
    displayRecentFeedback();
    setupRealtimeValidation();
    
    const feedbackForm = document.getElementById('feedback-form');
    if (feedbackForm) {
        feedbackForm.addEventListener('submit', saveFeedback);
    }
});