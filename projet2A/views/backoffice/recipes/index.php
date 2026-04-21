<?php 
$pageTitle = "Gestion des Recettes";
$activeMenu = "recipes";
$breadcrumb = [
    ['label' => 'Tableau de bord', 'url' => 'index.php?action=backRecipes'],
    ['label' => 'Recettes']
];

// Récupérer les statistiques
$totalRecipes = count($recipes);
$veganCount = count(array_filter($recipes, function($r) { return $r['is_vegan']; }));
$vegetarianCount = count(array_filter($recipes, function($r) { return $r['is_vegetarian']; }));
$glutenFreeCount = count(array_filter($recipes, function($r) { return $r['is_gluten_free']; }));
$quickRecipes = count(array_filter($recipes, function($r) { return ($r['prep_time'] + $r['cook_time']) <= 30; }));

$headerPath = dirname(__DIR__) . '/layout/header.php';
if(file_exists($headerPath)) {
    include $headerPath;
}
?>

<!-- Stats Cards -->
<div class="stats-cards">
    <div class="stat-card" data-aos="fade-up" data-aos-delay="0">
        <div class="stat-info">
            <h3>Total des recettes</h3>
            <div class="number"><?php echo $totalRecipes; ?></div>
            <small>+12% ce mois</small>
        </div>
        <div class="stat-icon">
            <i class="fas fa-utensils"></i>
        </div>
    </div>
    
    <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-info">
            <h3>Recettes Vegan</h3>
            <div class="number"><?php echo $veganCount; ?></div>
            <small><?php echo $totalRecipes > 0 ? round(($veganCount / $totalRecipes) * 100) : 0; ?>% du total</small>
        </div>
        <div class="stat-icon" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
            <i class="fas fa-seedling"></i>
        </div>
    </div>
    
    <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
        <div class="stat-info">
            <h3>Recettes Végétariennes</h3>
            <div class="number"><?php echo $vegetarianCount; ?></div>
            <small><?php echo $totalRecipes > 0 ? round(($vegetarianCount / $totalRecipes) * 100) : 0; ?>% du total</small>
        </div>
        <div class="stat-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
            <i class="fas fa-carrot"></i>
        </div>
    </div>
    
    <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
        <div class="stat-info">
            <h3>Recettes Rapides</h3>
            <div class="number"><?php echo $quickRecipes; ?></div>
            <small>Moins de 30 min</small>
        </div>
        <div class="stat-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
            <i class="fas fa-bolt"></i>
        </div>
    </div>
</div>

<!-- Search and Filter Bar + Bouton Créer -->
<div class="search-filter-bar">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" placeholder="Rechercher une recette..." onkeyup="filterRecipes()">
    </div>
    
    <div class="filter-group">
        <select id="difficultyFilter" onchange="filterRecipes()" class="filter-select">
            <option value="">Toutes difficultés</option>
            <option value="facile">Facile</option>
            <option value="moyen">Moyen</option>
            <option value="difficile">Difficile</option>
        </select>
        
        <select id="typeFilter" onchange="filterRecipes()" class="filter-select">
            <option value="">Tous types</option>
            <option value="vegan">Vegan</option>
            <option value="vegetarian">Végétarien</option>
            <option value="standard">Standard</option>
        </select>
        
        <!-- BOUTON CRÉER UNE RECETTE -->
        <a href="index.php?action=backCreateRecipe" class="btn-create">
            <i class="fas fa-plus"></i> Nouvelle recette
        </a>
    </div>
</div>

<!-- Bulk Actions Bar -->
<div class="bulk-actions-bar" id="bulkActionsBar" style="display: none;">
    <div class="bulk-info">
        <i class="fas fa-check-circle"></i>
        <span id="selectedCount">0</span> recette(s) sélectionnée(s)
    </div>
    <div class="bulk-buttons">
        <form method="POST" id="bulkDeleteForm" action="index.php?action=backBulkDeleteRecipes">
            <input type="hidden" name="ids" id="bulkIds" value="">
            <button type="button" class="btn-bulk-delete" onclick="bulkDelete()">
                <i class="fas fa-trash-alt"></i> Supprimer
            </button>
        </form>
        <button class="btn-bulk-cancel" onclick="clearSelection()">
            Annuler
        </button>
    </div>
</div>

<!-- Table Container -->
<div class="table-container">
    <table class="data-table" id="recipesTable">
        <thead>
            <tr>
                <th class="checkbox-col">
                    <input type="checkbox" id="selectAll" onclick="toggleSelectAll()">
                </th>
                <th>ID</th>
                <th>Titre</th>
                <th>Difficulté</th>
                <th>Temps total</th>
                <th>Calories</th>
                <th>Type</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="recipesTableBody">
            <?php if(empty($recipes)): ?>
                <tr>
                    <td colspan="9" style="text-align: center; padding: 3rem;">
                        <i class="fas fa-utensils" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem; display: block;"></i>
                        <h3>Aucune recette trouvée</h3>
                        <p>Commencez par créer votre première recette !</p>
                        <a href="index.php?action=backCreateRecipe" class="btn-create" style="margin-top: 1rem; display: inline-block;">
                            <i class="fas fa-plus"></i> Créer une recette
                        </a>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach($recipes as $recipe): ?>
                    <tr data-id="<?php echo $recipe['id']; ?>"
                        data-title="<?php echo htmlspecialchars(strtolower($recipe['title'])); ?>"
                        data-difficulty="<?php echo $recipe['difficulty']; ?>"
                        data-type="<?php echo $recipe['is_vegan'] ? 'vegan' : ($recipe['is_vegetarian'] ? 'vegetarian' : 'standard'); ?>"
                        data-time="<?php echo $recipe['prep_time'] + $recipe['cook_time']; ?>"
                        data-date="<?php echo $recipe['created_at']; ?>">
                        
                        <td class="checkbox-col">
                            <input type="checkbox" class="recipe-select" onchange="updateBulkActions()">
                        </td>
                        <td><?php echo $recipe['id']; ?></td>
                        <td class="recipe-title">
                            <div class="recipe-title-cell">
                                <?php if($recipe['image_url']): ?>
                                    <img src="<?php echo $recipe['image_url']; ?>" alt="" class="recipe-thumb">
                                <?php else: ?>
                                    <div class="recipe-thumb-placeholder">
                                        <i class="fas fa-utensils"></i>
                                    </div>
                                <?php endif; ?>
                                <span><?php echo htmlspecialchars($recipe['title']); ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="badge-difficulty <?php echo $recipe['difficulty']; ?>">
                                <i class="fas <?php echo $recipe['difficulty'] == 'facile' ? 'fa-smile' : ($recipe['difficulty'] == 'moyen' ? 'fa-meh' : 'fa-frown'); ?>"></i>
                                <?php echo ucfirst($recipe['difficulty']); ?>
                            </span>
                        </td>
                        <td>
                            <span class="time-badge">
                                <i class="far fa-clock"></i> <?php echo $recipe['prep_time'] + $recipe['cook_time']; ?> min
                            </span>
                        </td>
                        <td>
                            <span class="calories-badge">
                                <i class="fas fa-fire"></i> <?php echo $recipe['calories'] ?? 'N/A'; ?>
                            </span>
                        </td>
                        <td>
                            <?php if($recipe['is_vegan']): ?>
                                <span class="badge-type vegan"><i class="fas fa-seedling"></i> Vegan</span>
                            <?php elseif($recipe['is_vegetarian']): ?>
                                <span class="badge-type vegetarian"><i class="fas fa-carrot"></i> Végétarien</span>
                            <?php else: ?>
                                <span class="badge-type standard"><i class="fas fa-utensils"></i> Standard</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-badge active">
                                <i class="fas fa-circle"></i> Publié
                            </span>
                        </td>
                        <td class="actions">
                            <div class="action-buttons">
                                <a href="index.php?action=backShowRecipe&id=<?php echo $recipe['id']; ?>" class="btn-action view" title="Voir la recette">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="index.php?action=backEditRecipe&id=<?php echo $recipe['id']; ?>" class="btn-action edit" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?action=backInstructions&id=<?php echo $recipe['id']; ?>" class="btn-action instructions" title="Gérer les instructions">
                                    <i class="fas fa-list-ol"></i>
                                </a>
                                <button onclick="showDeleteModal(<?php echo $recipe['id']; ?>, '<?php echo htmlspecialchars(addslashes($recipe['title'])); ?>')" class="btn-action delete" title="Supprimer">
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

<!-- Modal de suppression -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-exclamation-triangle"></i> Confirmer la suppression</h3>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir supprimer la recette :</p>
            <p class="modal-recipe-title"><strong id="deleteRecipeTitle"></strong> ?</p>
            <div class="warning-box">
                <i class="fas fa-trash-alt"></i>
                <span>Cette action est irréversible !</span>
            </div>
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <span>Toutes les instructions associées seront également supprimées.</span>
            </div>
        </div>
        <div class="modal-footer">
            <form method="POST" id="deleteForm">
                <button type="button" class="btn-cancel">Annuler</button>
                <button type="submit" class="btn-confirm">
                    <i class="fas fa-trash-alt"></i> Oui, supprimer
                </button>
            </form>
        </div>
    </div>
</div>

<style>
/* Stats Cards */
.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-info h3 {
    font-size: 0.85rem;
    color: #999;
    margin-bottom: 5px;
}

.stat-info .number {
    font-size: 2rem;
    font-weight: 700;
    color: #1a2a3a;
}

.stat-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon i {
    font-size: 1.5rem;
    color: white;
}

/* Search and Filter Bar */
.search-filter-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.search-box {
    flex: 1;
    position: relative;
    max-width: 300px;
}

.search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
}

.search-box input {
    width: 100%;
    padding: 10px 10px 10px 35px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 0.9rem;
}

.filter-group {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    align-items: center;
}

.filter-select {
    padding: 10px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background: white;
    cursor: pointer;
}

/* Bouton Créer */
.btn-create {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 10px 20px;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-create:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(46,204,113,0.3);
}

/* Table Container */
.table-container {
    background: white;
    border-radius: 15px;
    overflow-x: auto;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 900px;
}

.data-table thead {
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
}

.data-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
}

.data-table td {
    padding: 1rem;
    border-bottom: 1px solid #e0e0e0;
}

.data-table tbody tr:hover {
    background: #f8f9fa;
}

.checkbox-col {
    width: 40px;
    text-align: center;
}

.recipe-title-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}

.recipe-thumb {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    object-fit: cover;
}

.recipe-thumb-placeholder {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

/* Badges */
.badge-difficulty {
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.badge-difficulty.facile {
    background: #d4edda;
    color: #155724;
}

.badge-difficulty.moyen {
    background: #fff3cd;
    color: #856404;
}

.badge-difficulty.difficile {
    background: #f8d7da;
    color: #721c24;
}

.badge-type {
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.badge-type.vegan {
    background: #2ecc71;
    color: white;
}

.badge-type.vegetarian {
    background: #f39c12;
    color: white;
}

.badge-type.standard {
    background: #3498db;
    color: white;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-badge.active {
    background: #d4edda;
    color: #155724;
}

.status-badge i {
    font-size: 0.5rem;
}

.time-badge, .calories-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.2rem 0.5rem;
    background: #f8f9fa;
    border-radius: 5px;
    font-size: 0.85rem;
}

/* Action Buttons */
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
    text-decoration: none;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
}

.btn-action.view {
    background: #3498db;
    color: white;
}

.btn-action.edit {
    background: #f39c12;
    color: white;
}

.btn-action.instructions {
    background: #9b59b6;
    color: white;
}

.btn-action.delete {
    background: #e74c3c;
    color: white;
}

.btn-action:hover {
    transform: scale(1.1);
}

/* Bulk Actions Bar */
.bulk-actions-bar {
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    animation: slideDown 0.3s ease;
}

.bulk-buttons {
    display: flex;
    gap: 1rem;
}

.btn-bulk-delete, .btn-bulk-cancel {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: 600;
    transition: transform 0.3s;
}

.btn-bulk-delete {
    background: #e74c3c;
    color: white;
}

.btn-bulk-cancel {
    background: rgba(255,255,255,0.2);
    color: white;
}

.btn-bulk-delete:hover, .btn-bulk-cancel:hover {
    transform: translateY(-2px);
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    animation: fadeIn 0.3s;
}

.modal-content {
    background: white;
    margin: 10% auto;
    width: 90%;
    max-width: 500px;
    border-radius: 15px;
    animation: slideDown 0.3s;
}

.modal-header {
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
    border-radius: 15px 15px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header .close {
    font-size: 1.5rem;
    cursor: pointer;
}

.modal-body {
    padding: 1.5rem;
}

.warning-box, .info-box {
    padding: 1rem;
    border-radius: 10px;
    margin-top: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.warning-box {
    background: #f8d7da;
    color: #721c24;
}

.info-box {
    background: #d1ecf1;
    color: #0c5460;
}

.modal-footer {
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    border-top: 1px solid #e0e0e0;
}

.btn-confirm {
    padding: 0.5rem 1.5rem;
    background: #e74c3c;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.btn-cancel {
    padding: 0.5rem 1.5rem;
    background: #95a5a6;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideDown {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .stats-cards {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .search-filter-bar {
        flex-direction: column;
    }
    
    .search-box {
        max-width: 100%;
        width: 100%;
    }
    
    .filter-group {
        width: 100%;
        justify-content: space-between;
    }
    
    .filter-select {
        flex: 1;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .bulk-actions-bar {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}
</style>

<script>
let selectedRecipes = new Set();

// Filtrer les recettes
function filterRecipes() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const difficultyFilter = document.getElementById('difficultyFilter').value;
    const typeFilter = document.getElementById('typeFilter').value;
    
    const rows = document.querySelectorAll('#recipesTableBody tr');
    
    rows.forEach(row => {
        if(row.getAttribute('data-id')) {
            const title = row.getAttribute('data-title');
            const difficulty = row.getAttribute('data-difficulty');
            const type = row.getAttribute('data-type');
            
            let show = true;
            
            if(searchTerm && !title.includes(searchTerm)) {
                show = false;
            }
            
            if(difficultyFilter && difficulty !== difficultyFilter) {
                show = false;
            }
            
            if(typeFilter && type !== typeFilter) {
                show = false;
            }
            
            row.style.display = show ? '' : 'none';
        }
    });
}

// Sélectionner tout
function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.recipe-select');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
        const row = checkbox.closest('tr');
        const id = row.getAttribute('data-id');
        
        if(selectAllCheckbox.checked) {
            if(id) selectedRecipes.add(id);
        } else {
            selectedRecipes.delete(id);
        }
    });
    
    updateBulkActions();
}

// Mettre à jour les actions groupées
function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.recipe-select');
    const selectedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCountSpan = document.getElementById('selectedCount');
    const bulkIdsInput = document.getElementById('bulkIds');
    
    if(selectedCount > 0) {
        bulkActionsBar.style.display = 'flex';
        selectedCountSpan.textContent = selectedCount;
        
        const selectedIds = [];
        checkboxes.forEach(cb => {
            if(cb.checked) {
                const id = cb.closest('tr').getAttribute('data-id');
                if(id) selectedIds.push(id);
            }
        });
        if(bulkIdsInput) bulkIdsInput.value = selectedIds.join(',');
    } else {
        bulkActionsBar.style.display = 'none';
        if(bulkIdsInput) bulkIdsInput.value = '';
    }
    
    const selectAllCheckbox = document.getElementById('selectAll');
    if(selectAllCheckbox) {
        selectAllCheckbox.checked = selectedCount === checkboxes.length && checkboxes.length > 0;
    }
}

// Effacer la sélection
function clearSelection() {
    const checkboxes = document.querySelectorAll('.recipe-select');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    selectedRecipes.clear();
    updateBulkActions();
    
    const selectAllCheckbox = document.getElementById('selectAll');
    if(selectAllCheckbox) {
        selectAllCheckbox.checked = false;
    }
}

// Suppression groupée
function bulkDelete() {
    const selected = Array.from(document.querySelectorAll('.recipe-select:checked'))
        .map(cb => cb.closest('tr').getAttribute('data-id'))
        .filter(id => id);
    
    if(selected.length === 0) return;
    
    if(confirm(`Êtes-vous sûr de vouloir supprimer ${selected.length} recette(s) ?`)) {
        document.getElementById('bulkDeleteForm').submit();
    }
}

// Modal suppression
function showDeleteModal(id, title) {
    const modal = document.getElementById('deleteModal');
    const deleteTitle = document.getElementById('deleteRecipeTitle');
    const deleteForm = document.getElementById('deleteForm');
    
    if(modal && deleteTitle && deleteForm) {
        deleteTitle.textContent = title;
        deleteForm.action = `index.php?action=backDeleteRecipe&id=${id}`;
        modal.style.display = 'block';
        
        const closeBtn = modal.querySelector('.close');
        const cancelBtn = modal.querySelector('.btn-cancel');
        
        if(closeBtn) {
            closeBtn.onclick = function() {
                modal.style.display = 'none';
            };
        }
        
        if(cancelBtn) {
            cancelBtn.onclick = function() {
                modal.style.display = 'none';
            };
        }
        
        window.onclick = function(event) {
            if(event.target === modal) {
                modal.style.display = 'none';
            }
        };
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    filterRecipes();
    
    // Animation des lignes
    const rows = document.querySelectorAll('#recipesTableBody tr');
    rows.forEach((row, index) => {
        if(row.getAttribute('data-id')) {
            row.style.opacity = '0';
            row.style.transform = 'translateX(-20px)';
            row.style.transition = `opacity 0.3s ease ${index * 0.05}s, transform 0.3s ease ${index * 0.05}s`;
            
            setTimeout(() => {
                row.style.opacity = '1';
                row.style.transform = 'translateX(0)';
            }, 100);
        }
    });
});

// Raccourcis clavier
document.addEventListener('keydown', function(e) {
    if(e.ctrlKey && e.key === 'f') {
        e.preventDefault();
        document.getElementById('searchInput')?.focus();
    }
    
    if(e.key === 'Escape') {
        const modal = document.getElementById('deleteModal');
        if(modal) modal.style.display = 'none';
    }
});
</script>

<?php 
$footerPath = dirname(__DIR__) . '/layout/footer.php';
if(file_exists($footerPath)) {
    include $footerPath;
}
?>