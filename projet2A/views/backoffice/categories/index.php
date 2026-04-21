<?php 
$pageTitle = "Gestion des Catégories";
$activeMenu = "categories";
$breadcrumb = [
    ['label' => 'Tableau de bord', 'url' => 'index.php?action=backRecipes'],
    ['label' => 'Catégories']
];

$headerPath = dirname(__DIR__) . '/layout/header.php';
if(file_exists($headerPath)) {
    include $headerPath;
}
?>

<div class="categories-container">
    <div class="top-bar-categories">
        <h1><i class="fas fa-tags"></i> Gestion des Catégories</h1>
        <button class="btn-create" onclick="showCreateModal()">
            <i class="fas fa-plus"></i> Nouvelle catégorie
        </button>
    </div>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Icône</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Couleur</th>
                    <th>Nb Recettes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($categories)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-tags" style="font-size: 3rem; color: #ccc;"></i>
                            <h3>Aucune catégorie</h3>
                            <button onclick="showCreateModal()" class="btn-create" style="margin-top: 1rem;">
                                Créer une catégorie
                            </button>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($categories as $categorie): ?>
                        <tr>
                            <td><?php echo $categorie['idCategorie']; ?></td>
                            <td>
                                <i class="<?php echo $categorie['icon']; ?>" style="font-size: 1.5rem; color: <?php echo $categorie['couleur']; ?>"></i>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($categorie['nom']); ?></strong>
                            </td>
                            <td>
                                <?php echo htmlspecialchars(substr($categorie['description'] ?? '', 0, 50)); ?>
                                <?php echo strlen($categorie['description'] ?? '') > 50 ? '...' : ''; ?>
                            </td>
                            <td>
                                <span style="display: inline-block; width: 30px; height: 30px; background: <?php echo $categorie['couleur']; ?>; border-radius: 5px;"></span>
                                <span style="font-size: 0.7rem;"><?php echo $categorie['couleur']; ?></span>
                            </td>
                            <td>
                                <span class="badge-recettes">
                                    <i class="fas fa-utensils"></i> <?php echo $categorie['nb_recettes'] ?? 0; ?>
                                </span>
                            </td>
                            <td class="actions">
                                <div class="action-buttons">
                                    <!-- BOUTON AFFICHER -->
                                    <button onclick='showViewModal(<?php echo $categorie['idCategorie']; ?>, <?php echo json_encode($categorie['nom']); ?>, <?php echo json_encode($categorie['description'] ?? ''); ?>, <?php echo json_encode($categorie['icon']); ?>, <?php echo json_encode($categorie['couleur']); ?>, <?php echo json_encode($categorie['nb_recettes'] ?? 0); ?>)' class="btn-action view" title="Afficher">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <!-- BOUTON MODIFIER -->
                                    <button onclick='showEditModal(<?php echo $categorie['idCategorie']; ?>, <?php echo json_encode($categorie['nom']); ?>, <?php echo json_encode($categorie['description'] ?? ''); ?>, <?php echo json_encode($categorie['icon']); ?>, <?php echo json_encode($categorie['couleur']); ?>)' class="btn-action edit" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <!-- BOUTON SUPPRIMER -->
                                    <button onclick='showDeleteModal(<?php echo $categorie['idCategorie']; ?>, <?php echo json_encode($categorie['nom']); ?>)' class="btn-action delete" title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal AFFICHAGE -->
<div id="viewModal" class="modal">
    <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(135deg, #3498db, #2980b9);">
            <h3><i class="fas fa-eye"></i> Détail de la catégorie</h3>
            <span class="close" onclick="closeViewModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div style="text-align: center; margin-bottom: 1rem;">
                <i id="view_icon" class="" style="font-size: 4rem;"></i>
            </div>
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 8px; font-weight: bold;">ID :</td>
                    <td id="view_id" style="padding: 8px;"></td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Nom :</td>
                    <td id="view_nom" style="padding: 8px;"></td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Description :</td>
                    <td id="view_description" style="padding: 8px;"></td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Couleur :</td>
                    <td id="view_couleur" style="padding: 8px;"></td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Nombre de recettes :</td>
                    <td id="view_nb_recettes" style="padding: 8px;"></td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeViewModal()">Fermer</button>
        </div>
    </div>
</div>

<!-- Modal Création -->
<div id="createModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-plus"></i> Nouvelle catégorie</h3>
            <span class="close" onclick="closeCreateModal()">&times;</span>
        </div>
        <form method="POST" id="createForm" action="index.php?action=backCreateCategorie">
            <div class="modal-body">
                <div class="form-group">
                    <label>Nom <span class="required">*</span></label>
                    <input type="text" name="nom" id="create_nom" required>
                    <div class="error-message" id="create_nom_error"></div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="create_description" rows="3"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Icône</label>
                        <select name="icon" id="create_icon">
                            <option value="fas fa-tag">🏷️ Tag</option>
                            <option value="fas fa-utensils">🍽️ Ustensiles</option>
                            <option value="fas fa-seedling">🌱 Vegan</option>
                            <option value="fas fa-carrot">🥕 Carotte</option>
                            <option value="fas fa-apple-alt">🍎 Pomme</option>
                            <option value="fas fa-cake-candles">🎂 Gâteau</option>
                            <option value="fas fa-mug-hot">☕ Tasse</option>
                            <option value="fas fa-bread-slice">🍞 Pain</option>
                            <option value="fas fa-cheese">🧀 Fromage</option>
                            <option value="fas fa-egg">🥚 Œuf</option>
                            <option value="fas fa-fish">🐟 Poisson</option>
                            <option value="fas fa-leaf">🍃 Feuille</option>
                            <option value="fas fa-heart">❤️ Cœur</option>
                            <option value="fas fa-star">⭐ Étoile</option>
                            <option value="fas fa-clock">⏰ Horloge</option>
                            <option value="fas fa-fire">🔥 Feu</option>
                            <option value="fas fa-sun">☀️ Soleil</option>
                            <option value="fas fa-moon">🌙 Lune</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Couleur</label>
                        <input type="color" name="couleur" id="create_couleur" value="#2ecc71">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeCreateModal()">Annuler</button>
                <button type="submit" class="btn-confirm">Créer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Modification -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Modifier la catégorie</h3>
            <span class="close" onclick="closeEditModal()">&times;</span>
        </div>
        <form method="POST" id="editForm" action="">
            <div class="modal-body">
                <div class="form-group">
                    <label>Nom <span class="required">*</span></label>
                    <input type="text" name="nom" id="edit_nom" required>
                    <div class="error-message" id="edit_nom_error"></div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="edit_description" rows="3"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Icône</label>
                        <select name="icon" id="edit_icon">
                            <option value="fas fa-tag">🏷️ Tag</option>
                            <option value="fas fa-utensils">🍽️ Ustensiles</option>
                            <option value="fas fa-seedling">🌱 Vegan</option>
                            <option value="fas fa-carrot">🥕 Carotte</option>
                            <option value="fas fa-apple-alt">🍎 Pomme</option>
                            <option value="fas fa-cake-candles">🎂 Gâteau</option>
                            <option value="fas fa-mug-hot">☕ Tasse</option>
                            <option value="fas fa-bread-slice">🍞 Pain</option>
                            <option value="fas fa-cheese">🧀 Fromage</option>
                            <option value="fas fa-egg">🥚 Œuf</option>
                            <option value="fas fa-fish">🐟 Poisson</option>
                            <option value="fas fa-leaf">🍃 Feuille</option>
                            <option value="fas fa-heart">❤️ Cœur</option>
                            <option value="fas fa-star">⭐ Étoile</option>
                            <option value="fas fa-clock">⏰ Horloge</option>
                            <option value="fas fa-fire">🔥 Feu</option>
                            <option value="fas fa-sun">☀️ Soleil</option>
                            <option value="fas fa-moon">🌙 Lune</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Couleur</label>
                        <input type="color" name="couleur" id="edit_couleur" value="#2ecc71">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Annuler</button>
                <button type="submit" class="btn-confirm">Modifier</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Suppression -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-exclamation-triangle"></i> Confirmer la suppression</h3>
            <span class="close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir supprimer la catégorie :</p>
            <p class="modal-title"><strong id="deleteCategorieTitle"></strong> ?</p>
            <div class="warning-box">
                <i class="fas fa-trash-alt"></i>
                <span>Les recettes associées ne seront pas supprimées (idCategorie deviendra NULL).</span>
            </div>
        </div>
        <div class="modal-footer">
            <form method="POST" id="deleteForm" action="">
                <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Annuler</button>
                <button type="submit" class="btn-confirm">Supprimer</button>
            </form>
        </div>
    </div>
</div>

<style>
.categories-container {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.top-bar-categories {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.top-bar-categories h1 {
    font-size: 1.5rem;
    color: #1a2a3a;
}

.top-bar-categories h1 i {
    color: #2ecc71;
    margin-right: 0.5rem;
}

.btn-create {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.9rem;
}

.badge-recettes {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.2rem 0.5rem;
    background: #e8f5e9;
    color: #2ecc71;
    border-radius: 20px;
    font-size: 0.85rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-action.view {
    background: #3498db;
    color: white;
}

.btn-action.edit {
    background: #f39c12;
    color: white;
}

.btn-action.delete {
    background: #e74c3c;
    color: white;
}

.btn-action:hover {
    transform: scale(1.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.modal-title {
    text-align: center;
    font-size: 1.1rem;
    margin: 1rem 0;
}

.warning-box {
    background: #fff3cd;
    color: #856404;
    padding: 1rem;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
}

.modal-content {
    background: white;
    margin: 10% auto;
    width: 90%;
    max-width: 500px;
    border-radius: 15px;
}

.modal-header {
    padding: 1rem;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
    border-radius: 15px 15px 0 0;
    display: flex;
    justify-content: space-between;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    padding: 1rem;
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    border-top: 1px solid #e0e0e0;
}

.btn-confirm {
    padding: 0.5rem 1rem;
    background: #2ecc71;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.btn-cancel {
    padding: 0.5rem 1rem;
    background: #95a5a6;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.error-message {
    color: #e74c3c;
    font-size: 0.75rem;
    margin-top: 0.3rem;
    display: none;
}

.error-message.show {
    display: block;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .top-bar-categories {
        flex-direction: column;
    }
}
</style>

<script>
// Modal AFFICHAGE
function showViewModal(id, nom, description, icon, couleur, nbRecettes) {
    const modal = document.getElementById('viewModal');
    document.getElementById('view_id').textContent = id;
    document.getElementById('view_nom').textContent = nom;
    document.getElementById('view_description').textContent = description || 'Aucune description';
    document.getElementById('view_couleur').innerHTML = '<span style="display: inline-block; width: 20px; height: 20px; background: ' + couleur + '; border-radius: 3px;"></span> ' + couleur;
    document.getElementById('view_nb_recettes').textContent = nbRecettes;
    
    const iconElement = document.getElementById('view_icon');
    iconElement.className = icon;
    iconElement.style.color = couleur;
    
    modal.style.display = 'block';
}

function closeViewModal() {
    document.getElementById('viewModal').style.display = 'none';
}

// Modal CRÉATION
function showCreateModal() {
    document.getElementById('createModal').style.display = 'block';
}

function closeCreateModal() {
    document.getElementById('createModal').style.display = 'none';
    document.getElementById('createForm').reset();
}

// Modal MODIFICATION
function showEditModal(id, nom, description, icon, couleur) {
    const modal = document.getElementById('editModal');
    const form = document.getElementById('editForm');
    form.action = 'index.php?action=backEditCategorie&id=' + id;
    
    document.getElementById('edit_nom').value = nom;
    document.getElementById('edit_description').value = description || '';
    document.getElementById('edit_icon').value = icon || 'fas fa-tag';
    document.getElementById('edit_couleur').value = couleur || '#2ecc71';
    
    modal.style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Modal SUPPRESSION
function showDeleteModal(id, nom) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    const title = document.getElementById('deleteCategorieTitle');
    
    form.action = 'index.php?action=backDeleteCategorie&id=' + id;
    title.textContent = nom;
    modal.style.display = 'block';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Fermer les modals en cliquant à l'extérieur
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}

// Validation du formulaire de création
document.getElementById('createForm')?.addEventListener('submit', function(e) {
    let isValid = true;
    
    const nom = document.getElementById('create_nom');
    const nomValue = nom.value.trim();
    const nomError = document.getElementById('create_nom_error');
    
    if(nomValue === '') {
        nomError.textContent = 'Le nom est requis';
        nomError.classList.add('show');
        nom.style.borderColor = '#e74c3c';
        isValid = false;
    } else if(nomValue.length < 2) {
        nomError.textContent = 'Le nom doit contenir au moins 2 caractères';
        nomError.classList.add('show');
        nom.style.borderColor = '#e74c3c';
        isValid = false;
    } else if(nomValue.length > 50) {
        nomError.textContent = 'Le nom ne peut pas dépasser 50 caractères';
        nomError.classList.add('show');
        nom.style.borderColor = '#e74c3c';
        isValid = false;
    } else {
        nomError.classList.remove('show');
        nom.style.borderColor = '#e0e0e0';
    }
    
    if(!isValid) {
        e.preventDefault();
    }
});
</script>

<?php 
$footerPath = dirname(__DIR__) . '/layout/footer.php';
if(file_exists($footerPath)) {
    include $footerPath;
}
?>