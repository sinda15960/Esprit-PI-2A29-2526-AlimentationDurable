/* ============================
   DonSolidaire — Application Logic (Version Tunisienne)
   ============================ */

// ==================== DATA STORE ====================
let associations = [
    {
        id: 1,
        nom: "Médecins Sans Frontières",
        cause: "Sante",
        objectif: 264000,
        description: "Association humanitaire internationale d'aide medicale",
        email: "contact@msf.org",
        tel: "53211586"
    },
    {
        id: 2,
        nom: "GreenHealth Tunisia",
        cause: "Alimentation saine",
        objectif: 165000,
        description: "Sensibilisation a une nutrition equilibree",
        email: "info@greenhealth.tn",
        tel: "53211586"
    },
    {
        id: 3,
        nom: "NutriBalance",
        cause: "diabète, tension",
        objectif: 99000,
        description: "Accompagne les personnes ayant des maladies chroniques en leur proposant des conseils nutritionnels.",
        email: "hello@NutriBalance.fr",
        tel: "53211586"
    },
    {
        id: 4,
        nom: "Croissant Rouge Tunisien",
        cause: "Aide humanitaire",
        objectif: 66000,
        description: "Organisation humanitaire qui aide les personnes vulnérables à travers des actions de secours, de distribution de nourriture, de soins médicaux et de soutien social.",
        email: "hilal.ahmar@planet.tn",
        tel: "71711335"
    }
];

let dons = [
    { id: 1, donateur: "Alice Martin", email: "alice@email.com", associationId: 1, montant: 1650, date: "2026-01-10", type: "ponctuel", message: "Courage" },
    { id: 2, donateur: "Bob Dupont", email: "bob@email.com", associationId: 1, montant: 495, date: "2026-02-14", type: "mensuel", message: "" },
    { id: 3, donateur: "Claire Moreau", email: "claire@email.com", associationId: 2, montant: 6600, date: "2026-01-20", type: "annuel", message: "Pour la planete" },
    { id: 4, donateur: "David Laurent", email: "david@email.com", associationId: 3, montant: 248, date: "2026-03-05", type: "ponctuel", message: "Pour les enfants" },
    { id: 5, donateur: "Alice Martin", email: "alice@email.com", associationId: 2, montant: 990, date: "2026-03-12", type: "ponctuel", message: "" },
    { id: 6, donateur: "Emilie Roux", email: "emilie@email.com", associationId: 4, montant: 330, date: "2026-02-28", type: "mensuel", message: "Pour les animaux" },
    { id: 7, donateur: "Francois Petit", email: "francois@email.com", associationId: 1, montant: 16500, date: "2026-04-01", type: "annuel", message: "Merci" },
    { id: 8, donateur: "Bob Dupont", email: "bob@email.com", associationId: 3, montant: 825, date: "2026-04-10", type: "ponctuel", message: "" }
];

let nextAssocId = 5;
let nextDonId = 9;

// ==================== FORMAT CURRENCY (100% Tunisien) ====================
function formatCurrency(amount) {
    return amount.toLocaleString() + ' DT';
}

// ==================== UTILITIES ====================
function formatDate(dateStr) {
    const d = new Date(dateStr);
    return d.toLocaleDateString('fr-TN');
}

function getDonsForAssociation(assocId) {
    return dons.filter(d => d.associationId === assocId);
}

function getTotalDonsForAssociation(assocId) {
    return getDonsForAssociation(assocId).reduce((sum, d) => sum + d.montant, 0);
}

function getAssociationById(id) {
    return associations.find(a => a.id === id);
}

// ==================== BADGE SYSTEM ====================
function getBadge(montant) {
    if (montant >= 3300) return { label: "Mecene Diamant", class: "badge-diamant" };
    if (montant >= 1650) return { label: "Mecene Or", class: "badge-or" };
    if (montant >= 330) return { label: "Mecene Argent", class: "badge-argent" };
    return { label: "Mecene Bronze", class: "badge-bronze" };
}

// ==================== TOAST ====================
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    const icons = { success: '✓', error: '✗', info: 'i' };
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `<span class="toast-icon">${icons[type]}</span><span class="toast-message">${message}</span>`;
    container.appendChild(toast);
    setTimeout(() => {
        toast.classList.add('toast-out');
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}

// ==================== NAVIGATION ====================
function initNavigation() {
    document.querySelectorAll('.nav-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const tab = btn.dataset.tab;
            document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
            if (tab === 'associations') document.getElementById('tabAssociations').classList.add('active');
            else if (tab === 'dons') document.getElementById('tabDons').classList.add('active');
            else if (tab === 'stats') {
                document.getElementById('tabStats').classList.add('active');
                renderStats();
            }
        });
    });
}

// ==================== RENDER ASSOCIATIONS ====================
function renderAssociations(filter = 'all', search = '') {
    const grid = document.getElementById('associationsGrid');
    const emptyState = document.getElementById('emptyAssociations');
    if (!grid) return;
    grid.innerHTML = '';

    let filtered = associations;
    if (filter !== 'all') filtered = filtered.filter(a => a.cause === filter);
    if (search.trim()) {
        const q = search.toLowerCase();
        filtered = filtered.filter(a => a.nom.toLowerCase().includes(q) || a.cause.toLowerCase().includes(q));
    }

    if (filtered.length === 0) {
        if (emptyState) emptyState.classList.add('visible');
        grid.style.display = 'none';
        return;
    }

    if (emptyState) emptyState.classList.remove('visible');
    grid.style.display = 'grid';

    filtered.forEach((assoc, i) => {
        const totalDons = getTotalDonsForAssociation(assoc.id);
        const donCount = getDonsForAssociation(assoc.id).length;
        const progress = Math.min((totalDons / assoc.objectif) * 100, 100);

        const card = document.createElement('div');
        card.className = 'assoc-card';
        card.dataset.cause = assoc.cause;
        card.innerHTML = `
            <div class="card-header"><span class="card-cause">${assoc.cause}</span></div>
            <h3 class="card-name">${assoc.nom}</h3>
            <p class="card-description">${assoc.description}</p>
            <div class="card-progress">
                <div class="progress-header">
                    <span class="progress-label">Progression</span>
                    <span class="progress-value">${formatCurrency(totalDons)} / ${formatCurrency(assoc.objectif)}</span>
                </div>
                <div class="progress-bar"><div class="progress-fill" style="width: 0%"></div></div>
            </div>
            <div class="card-info">
                <span class="card-info-item">Dons: ${donCount}</span>
                <span class="card-info-item">Email: ${assoc.email}</span>
            </div>
            <div class="card-actions">
                <button class="btn btn-sm btn-secondary" onclick="viewAssociationDons(${assoc.id})">Voir dons</button>
                <button class="btn-icon edit" onclick="editAssociation(${assoc.id})">✏️</button>
                <button class="btn-icon delete" onclick="deleteAssociation(${assoc.id})">🗑️</button>
            </div>
        `;
        grid.appendChild(card);
        setTimeout(() => { card.querySelector('.progress-fill').style.width = progress + '%'; }, 100);
    });
}

// ==================== RENDER DONS ====================
function renderDons(filterAssocId = 'all', search = '') {
    const tbody = document.getElementById('donsTableBody');
    const emptyState = document.getElementById('emptyDons');
    const table = document.getElementById('donsTable');
    if (!tbody) return;
    tbody.innerHTML = '';

    let filtered = [...dons];
    if (filterAssocId !== 'all') filtered = filtered.filter(d => d.associationId === parseInt(filterAssocId));
    if (search.trim()) {
        const q = search.toLowerCase();
        filtered = filtered.filter(d => d.donateur.toLowerCase().includes(q));
    }
    filtered.sort((a, b) => new Date(b.date) - new Date(a.date));

    if (filtered.length === 0) {
        if (emptyState) emptyState.classList.add('visible');
        if (table) table.style.display = 'none';
        return;
    }

    if (emptyState) emptyState.classList.remove('visible');
    if (table) table.style.display = 'table';

    filtered.forEach(don => {
        const assoc = getAssociationById(don.associationId);
        const badge = getBadge(don.montant);
        const typeLabels = { ponctuel: "Ponctuel", mensuel: "Mensuel", annuel: "Annuel" };
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><strong>${don.donateur}</strong><br><small>${don.email}</small></td>
            <td>${assoc ? assoc.nom : 'Supprimee'}</td>
            <td><span class="don-montant">${formatCurrency(don.montant)}</span></td>
            <td>${formatDate(don.date)}</td>
            <td><span class="don-type ${don.type}">${typeLabels[don.type]}</span></td>
            <td><span class="don-badge ${badge.class}">${badge.label}</span></td>
            <td><button class="btn-icon edit" onclick="editDon(${don.id})">✏️</button>
                <button class="btn-icon delete" onclick="deleteDon(${don.id})">🗑️</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// ==================== RENDER STATS ====================
function renderStats() {
    const totalCollecte = dons.reduce((sum, d) => sum + d.montant, 0);
    const donMoyen = dons.length > 0 ? totalCollecte / dons.length : 0;
    
    document.getElementById('statTotalCollecte').textContent = formatCurrency(totalCollecte);
    document.getElementById('statDonMoyen').textContent = formatCurrency(Math.round(donMoyen));
    
    const globalProgress = Math.min((totalCollecte / 330000) * 100, 100);
    document.getElementById('progressGlobal').style.width = globalProgress + '%';

    const donorTotals = {};
    dons.forEach(d => { donorTotals[d.donateur] = (donorTotals[d.donateur] || 0) + d.montant; });
    const sortedDonors = Object.entries(donorTotals).sort((a, b) => b[1] - a[1]);
    if (sortedDonors.length > 0) {
        document.getElementById('statTopDonateur').textContent = sortedDonors[0][0];
        document.getElementById('statTopDonateurTotal').textContent = formatCurrency(sortedDonors[0][1]);
    }

    let topAssoc = null, topTotal = 0;
    associations.forEach(a => {
        const t = getTotalDonsForAssociation(a.id);
        if (t > topTotal) { topTotal = t; topAssoc = a; }
    });
    if (topAssoc) {
        document.getElementById('statTopAssoc').textContent = topAssoc.nom;
        document.getElementById('statTopAssocDons').textContent = `${getDonsForAssociation(topAssoc.id).length} dons — ${formatCurrency(topTotal)}`;
    }

    const listContainer = document.getElementById('assocStatsList');
    if (listContainer) {
        listContainer.innerHTML = '';
        associations.forEach(assoc => {
            const total = getTotalDonsForAssociation(assoc.id);
            const count = getDonsForAssociation(assoc.id).length;
            const progress = Math.min((total / assoc.objectif) * 100, 100);
            const item = document.createElement('div');
            item.className = 'assoc-stat-item';
            item.innerHTML = `
                <div class="assoc-stat-info">
                    <div class="assoc-stat-name">${assoc.nom}</div>
                    <div class="assoc-stat-bar"><div class="progress-fill" style="width: ${progress}%"></div></div>
                    <div class="assoc-stat-detail">${count} dons • ${progress.toFixed(1)}%</div>
                </div>
                <div class="assoc-stat-total">${formatCurrency(total)}</div>
            `;
            listContainer.appendChild(item);
        });
    }
}

// ==================== UPDATE STATS ====================
function updateGlobalStats() {
    document.getElementById('totalAssociations').textContent = associations.length;
    document.getElementById('totalDons').textContent = dons.length;
    const totalMontant = dons.reduce((sum, d) => sum + d.montant, 0);
    document.getElementById('totalMontant').textContent = formatCurrency(totalMontant);
    
    let topAssoc = null, topTotal = 0;
    associations.forEach(a => {
        const t = getTotalDonsForAssociation(a.id);
        if (t > topTotal) { topTotal = t; topAssoc = a; }
    });
    document.getElementById('topAssociation').textContent = topAssoc ? topAssoc.nom : '—';
    
    updateDonAssociationFilter();
}

function updateDonAssociationFilter() {
    const select = document.getElementById('filterDonsAssociation');
    const donAssocSelect = document.getElementById('donAssociation');
    if (select) {
        const currentVal = select.value;
        select.innerHTML = '<option value="all">Toutes</option>';
        associations.forEach(a => { select.innerHTML += `<option value="${a.id}">${a.nom}</option>`; });
        select.value = currentVal;
    }
    if (donAssocSelect) {
        const currentVal = donAssocSelect.value;
        donAssocSelect.innerHTML = '<option value="">Choisir</option>';
        associations.forEach(a => { donAssocSelect.innerHTML += `<option value="${a.id}">${a.nom}</option>`; });
        donAssocSelect.value = currentVal;
    }
}

// ==================== MODALS ====================
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }

// ==================== ASSOCIATION CRUD ====================
function openAddAssociationModal() {
    document.getElementById('modalAssocTitle').textContent = 'Nouvelle Association';
    document.getElementById('formAssociation').reset();
    document.getElementById('assocId').value = '';
    openModal('modalAssociation');
}

function editAssociation(id) {
    const assoc = getAssociationById(id);
    if (!assoc) return;
    document.getElementById('modalAssocTitle').textContent = 'Modifier Association';
    document.getElementById('assocId').value = assoc.id;
    document.getElementById('assocNom').value = assoc.nom;
    document.getElementById('assocCause').value = assoc.cause;
    document.getElementById('assocObjectif').value = assoc.objectif;
    document.getElementById('assocDescription').value = assoc.description;
    document.getElementById('assocEmail').value = assoc.email;
    document.getElementById('assocTel').value = assoc.tel || '';
    openModal('modalAssociation');
}

function submitAssociation(e) {
    e.preventDefault();
    const id = document.getElementById('assocId').value;
    const data = {
        nom: document.getElementById('assocNom').value.trim(),
        cause: document.getElementById('assocCause').value,
        objectif: parseFloat(document.getElementById('assocObjectif').value),
        description: document.getElementById('assocDescription').value.trim(),
        email: document.getElementById('assocEmail').value.trim(),
        tel: document.getElementById('assocTel').value.trim()
    };
    if (id) {
        const assoc = getAssociationById(parseInt(id));
        if (assoc) Object.assign(assoc, data);
        showToast(`Association modifiee`);
    } else {
        data.id = nextAssocId++;
        associations.push(data);
        showToast(`Association creee`);
    }
    closeModal('modalAssociation');
    refreshAll();
}

let pendingDeleteType = null, pendingDeleteId = null;

function deleteAssociation(id) {
    pendingDeleteType = 'association';
    pendingDeleteId = id;
    document.getElementById('confirmText').innerHTML = 'Supprimer cette association ?';
    openModal('modalConfirm');
}

function confirmDeleteAssociation(id) {
    const assoc = getAssociationById(id);
    if (assoc) {
        dons = dons.filter(d => d.associationId !== id);
        associations = associations.filter(a => a.id !== id);
        showToast(`Association supprimee`);
    }
    refreshAll();
}

// ==================== DON CRUD ====================
function openAddDonModal() {
    document.getElementById('modalDonTitle').textContent = 'Nouveau Don';
    document.getElementById('formDon').reset();
    document.getElementById('donId').value = '';
    document.getElementById('donDate').value = new Date().toISOString().split('T')[0];
    openModal('modalDon');
}

function editDon(id) {
    const don = dons.find(d => d.id === id);
    if (!don) return;
    document.getElementById('modalDonTitle').textContent = 'Modifier Don';
    document.getElementById('donId').value = don.id;
    document.getElementById('donDonateur').value = don.donateur;
    document.getElementById('donEmail').value = don.email;
    document.getElementById('donAssociation').value = don.associationId;
    document.getElementById('donMontant').value = don.montant;
    document.getElementById('donDate').value = don.date;
    document.getElementById('donType').value = don.type;
    document.getElementById('donMessage').value = don.message || '';
    openModal('modalDon');
}

function submitDon(e) {
    e.preventDefault();
    const id = document.getElementById('donId').value;
    const data = {
        donateur: document.getElementById('donDonateur').value.trim(),
        email: document.getElementById('donEmail').value.trim(),
        associationId: parseInt(document.getElementById('donAssociation').value),
        montant: parseFloat(document.getElementById('donMontant').value),
        date: document.getElementById('donDate').value,
        type: document.getElementById('donType').value,
        message: document.getElementById('donMessage').value.trim()
    };
    if (id) {
        const don = dons.find(d => d.id === parseInt(id));
        if (don) Object.assign(don, data);
        showToast(`Don modifie`);
    } else {
        data.id = nextDonId++;
        dons.push(data);
        showToast(`Don ajoute`);
    }
    closeModal('modalDon');
    refreshAll();
}

function deleteDon(id) {
    pendingDeleteType = 'don';
    pendingDeleteId = id;
    document.getElementById('confirmText').innerHTML = 'Supprimer ce don ?';
    openModal('modalConfirm');
}

function confirmDeleteDon(id) {
    const don = dons.find(d => d.id === id);
    if (don) {
        dons = dons.filter(d => d.id !== id);
        showToast(`Don supprime`);
    }
    refreshAll();
}

function viewAssociationDons(assocId) {
    const assoc = getAssociationById(assocId);
    if (!assoc) return;
    document.getElementById('modalViewDonsTitle').textContent = `Dons - ${assoc.nom}`;
    const assocDons = getDonsForAssociation(assocId);
    const total = assocDons.reduce((sum, d) => sum + d.montant, 0);
    document.getElementById('assocDonsSummary').innerHTML = `
        <div class="summary-stat"><span class="summary-stat-value">${assocDons.length}</span><span class="summary-stat-label">Dons</span></div>
        <div class="summary-stat"><span class="summary-stat-value">${formatCurrency(total)}</span><span class="summary-stat-label">Total</span></div>
    `;
    const tbody = document.getElementById('viewDonsTableBody');
    if (tbody) {
        tbody.innerHTML = '';
        assocDons.forEach(don => {
            const badge = getBadge(don.montant);
            const tr = document.createElement('tr');
            tr.innerHTML = `<td><strong>${don.donateur}</strong></td><td>${formatCurrency(don.montant)}</td><td>${formatDate(don.date)}</td><td><span class="don-badge ${badge.class}">${badge.label}</span></td>`;
            tbody.appendChild(tr);
        });
    }
    openModal('modalViewDons');
}

function refreshAll() {
    const activeFilter = document.querySelector('.chip.active');
    const filter = activeFilter ? activeFilter.dataset.filter : 'all';
    const searchAssoc = document.getElementById('searchAssociations');
    renderAssociations(filter, searchAssoc ? searchAssoc.value : '');
    const filterDons = document.getElementById('filterDonsAssociation');
    const searchDon = document.getElementById('searchDons');
    renderDons(filterDons ? filterDons.value : 'all', searchDon ? searchDon.value : '');
    updateGlobalStats();
    if (document.getElementById('tabStats').classList.contains('active')) renderStats();
}

// ==================== EVENT LISTENERS ====================
function setupEventListeners() {
    document.getElementById('btnAddAssociation').addEventListener('click', openAddAssociationModal);
    document.getElementById('btnAddDon').addEventListener('click', openAddDonModal);
    document.getElementById('formAssociation').addEventListener('submit', submitAssociation);
    document.getElementById('formDon').addEventListener('submit', submitDon);
    document.getElementById('closeModalAssoc').addEventListener('click', () => closeModal('modalAssociation'));
    document.getElementById('cancelAssoc').addEventListener('click', () => closeModal('modalAssociation'));
    document.getElementById('closeModalDon').addEventListener('click', () => closeModal('modalDon'));
    document.getElementById('cancelDon').addEventListener('click', () => closeModal('modalDon'));
    document.getElementById('closeModalViewDons').addEventListener('click', () => closeModal('modalViewDons'));
    document.getElementById('closeModalConfirm').addEventListener('click', () => closeModal('modalConfirm'));
    document.getElementById('cancelConfirm').addEventListener('click', () => closeModal('modalConfirm'));
    document.getElementById('confirmDelete').addEventListener('click', () => {
        if (pendingDeleteType === 'association') confirmDeleteAssociation(pendingDeleteId);
        else if (pendingDeleteType === 'don') confirmDeleteDon(pendingDeleteId);
        closeModal('modalConfirm');
    });
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', (e) => { if (e.target === overlay) closeModal(overlay.id); });
    });
    document.getElementById('searchAssociations').addEventListener('input', (e) => {
        const activeFilter = document.querySelector('.chip.active');
        renderAssociations(activeFilter ? activeFilter.dataset.filter : 'all', e.target.value);
    });
    document.querySelectorAll('.chip').forEach(chip => {
        chip.addEventListener('click', () => {
            document.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
            chip.classList.add('active');
            renderAssociations(chip.dataset.filter, document.getElementById('searchAssociations').value);
        });
    });
    document.getElementById('searchDons').addEventListener('input', (e) => {
        renderDons(document.getElementById('filterDonsAssociation').value, e.target.value);
    });
    document.getElementById('filterDonsAssociation').addEventListener('change', (e) => {
        renderDons(e.target.value, document.getElementById('searchDons').value);
    });
}

// ==================== INIT ====================
document.addEventListener('DOMContentLoaded', () => {
    initNavigation();
    setupEventListeners();
    refreshAll();
});