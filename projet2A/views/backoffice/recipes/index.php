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
        <div class="header-buttons">
            <button class="btn-stats" onclick="showStatsModal()">
                <i class="fas fa-chart-pie"></i> Statistiques
            </button>
            <a href="index.php?action=backCreateRecipe" class="btn-create">
                <i class="fas fa-plus"></i> Nouvelle recette
            </a>
        </div>
    </div>
</div>
<!-- LIGNE DES BOUTONS SUPPLEMENTAIRES -->
<div class="extra-buttons">
    <button class="btn-surprise" onclick="showSurpriseModal()">
        🎲 Surprise ! Une recette au hasard
    </button>
    <button class="btn-compare" onclick="showCompareModal()">
        📊 Comparer deux recettes
    </button>
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
                </tr>
                    <td colspan="8" style="text-align: center; padding: 3rem;">
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
<!-- MODAL SURPRISE (RECETTE ALEATOIRE) -->
<div id="surpriseModal" class="modal">
    <div class="modal-content" style="max-width: 450px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
            <h3><i class="fas fa-dice-d6"></i> Recette surprise 🎲</h3>
            <span class="close" onclick="closeSurpriseModal()">&times;</span>
        </div>
        <div class="modal-body" style="text-align: center;">
            <div id="surpriseContent">
                <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #9b59b6;"></i>
                <p>Chargement d'une recette surprise...</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-surprise" onclick="generateRandomRecipe()" style="background: #9b59b6;">
                🎲 Une autre surprise
            </button>
            <button class="btn-cancel" onclick="closeSurpriseModal()">Fermer</button>
        </div>
    </div>
</div>

<!-- MODAL COMPARAISON -->
<div id="compareModal" class="modal">
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #1abc9c, #16a085);">
            <h3><i class="fas fa-chart-simple"></i> Comparer deux recettes</h3>
            <span class="close" onclick="closeCompareModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="compare-selectors">
                <div class="compare-recipe">
                    <label>🍽️ Recette 1</label>
                    <select id="recipe1_select" class="compare-select" onchange="updateCompare()">
                        <option value="">-- Sélectionnez --</option>
                        <?php foreach($recipes as $recipe): ?>
                            <option value="<?php echo $recipe['id']; ?>" 
                                    data-title="<?php echo htmlspecialchars($recipe['title']); ?>"
                                    data-calories="<?php echo $recipe['calories'] ?? 'N/A'; ?>"
                                    data-time="<?php echo $recipe['prep_time'] + $recipe['cook_time']; ?>"
                                    data-difficulty="<?php echo $recipe['difficulty']; ?>"
                                    data-vegan="<?php echo $recipe['is_vegan'] ? '✅ Oui' : '❌ Non'; ?>"
                                    data-veg="<?php echo $recipe['is_vegetarian'] ? '✅ Oui' : '❌ Non'; ?>">
                                <?php echo htmlspecialchars($recipe['title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="compare-recipe">
                    <label>🍽️ Recette 2</label>
                    <select id="recipe2_select" class="compare-select" onchange="updateCompare()">
                        <option value="">-- Sélectionnez --</option>
                        <?php foreach($recipes as $recipe): ?>
                            <option value="<?php echo $recipe['id']; ?>"
                                    data-title="<?php echo htmlspecialchars($recipe['title']); ?>"
                                    data-calories="<?php echo $recipe['calories'] ?? 'N/A'; ?>"
                                    data-time="<?php echo $recipe['prep_time'] + $recipe['cook_time']; ?>"
                                    data-difficulty="<?php echo $recipe['difficulty']; ?>"
                                    data-vegan="<?php echo $recipe['is_vegan'] ? '✅ Oui' : '❌ Non'; ?>"
                                    data-veg="<?php echo $recipe['is_vegetarian'] ? '✅ Oui' : '❌ Non'; ?>">
                                <?php echo htmlspecialchars($recipe['title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div id="compareResult" style="margin-top: 1.5rem; display: none;">
                <table class="compare-table">
                    <thead>
                        <tr><th>Critère</th><th id="compareTitle1">Recette 1</th><th id="compareTitle2">Recette 2</th><th>Vainqueur 🏆</th></tr>
                    </thead>
                    <tbody>
                        <tr><td><i class="fas fa-fire"></i> Calories</td><td id="compareCalories1">-</td><td id="compareCalories2">-</td><td id="winnerCalories">-</td></tr>
                        <tr><td><i class="far fa-clock"></i> Temps total</td><td id="compareTime1">-</td><td id="compareTime2">-</td><td id="winnerTime">-</td></tr>
                        <tr><td><i class="fas fa-chart-line"></i> Difficulté</td><td id="compareDifficulty1">-</td><td id="compareDifficulty2">-</td><td id="winnerDifficulty">-</td></tr>
                        <tr><td><i class="fas fa-seedling"></i> Vegan</td><td id="compareVegan1">-</td><td id="compareVegan2">-</td><td id="winnerVegan">-</td></tr>
                        <tr><td><i class="fas fa-carrot"></i> Végétarien</td><td id="compareVegetarian1">-</td><td id="compareVegetarian2">-</td><td id="winnerVegetarian">-</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeCompareModal()">Fermer</button>
        </div>
    </div>
</div>
<!-- MODAL STATISTIQUES RECETTES -->
<div id="statsModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #3498db, #2980b9);">
            <h3><i class="fas fa-chart-pie"></i> Statistiques des recettes</h3>
            <span class="close" onclick="closeStatsModal()">&times;</span>
        </div>
        <div class="modal-body" style="text-align: center; padding-top: 0.5rem;">
            <!-- Résumé compact -->
            <div style="background:#f8f9fa; border-radius:10px; padding:0.6rem; margin-bottom:0.8rem; text-align:left; display: flex; justify-content: space-around; flex-wrap: wrap;">
                <div><span style="font-weight:600;">📊 Total :</span> <span style="color:#2ecc71; font-weight:700;" id="statTotalRecettes"><?php echo $totalRecipes; ?></span></div>
                <div><span style="font-weight:600;">🌱 Vegan :</span> <span style="color:#2ecc71; font-weight:700;" id="statVegan"><?php echo $veganCount; ?></span></div>
                <div><span style="font-weight:600;">🥕 Végé :</span> <span style="color:#f39c12; font-weight:700;" id="statVegetarian"><?php echo $vegetarianCount; ?></span></div>
                <div><span style="font-weight:600;">⚡ Rapide :</span> <span style="color:#e74c3c; font-weight:700;" id="statQuick"><?php echo $quickRecipes; ?></span></div>
            </div>
            
            <!-- Roue -->
            <div style="max-width: 240px; margin: 0 auto;">
                <canvas id="recipesChart" width="240" height="240"></canvas>
            </div>
            
            <!-- Légende -->
            <div id="chartLegend" style="display:flex; flex-wrap:wrap; justify-content:center; gap:0.5rem; margin-top:0.8rem; padding-top:0.5rem; border-top:1px solid #e0e0e0;"></div>
        </div>
        <div class="modal-footer" style="padding: 0.8rem 1rem;">
            <button type="button" class="btn-cancel" onclick="closeStatsModal()">Fermer</button>
        </div>
    </div>
</div>

<style>
/* Style pour la comparaison */
.compare-selectors {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1rem;
}

.compare-recipe label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #1a2a3a;
}

.compare-select {
    width: 100%;
    padding: 0.8rem;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 0.9rem;
    background: white;
    cursor: pointer;
}

.compare-select:focus {
    outline: none;
    border-color: #1abc9c;
}

.compare-table {
    width: 100%;
    border-collapse: collapse;
    background: #f8f9fa;
    border-radius: 10px;
    overflow: hidden;
}

.compare-table th {
    background: #1abc9c;
    color: white;
    padding: 12px;
    text-align: center;
}

.compare-table td {
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #e0e0e0;
}

.compare-table tr:last-child td {
    border-bottom: none;
}

.winner-highlight {
    background: #d4edda;
    color: #155724;
    font-weight: 600;
}
/* Boutons supplémentaires */
.extra-buttons {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    justify-content: flex-end;
}

.btn-surprise {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.2rem;
    background: linear-gradient(135deg, #9b59b6, #8e44ad);
    color: white;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-surprise:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(155,89,182,0.3);
}

.btn-compare {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.2rem;
    background: linear-gradient(135deg, #1abc9c, #16a085);
    color: white;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-compare:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(26,188,156,0.3);
}
.header-buttons {
    display: flex;
    gap: 1rem;
}

.btn-stats {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.9rem;
}

.btn-stats:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(52,152,219,0.3);
}
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
/* Responsive comparaison */
@media (max-width: 768px) {
    .extra-buttons {
        flex-direction: column;
    }
    
    .btn-surprise, .btn-compare {
        justify-content: center;
    }
    
    .compare-selectors {
        grid-template-columns: 1fr;
    }
    
    .compare-table {
        font-size: 0.75rem;
    }
    
    .compare-table th, .compare-table td {
        padding: 6px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
let selectedRecipes = new Set();
let recipesChart = null;
// ==================== SURPRISE (RECETTE ALEATOIRE) ====================
function showSurpriseModal() {
    document.getElementById('surpriseModal').style.display = 'block';
    generateRandomRecipe();
}

function closeSurpriseModal() {
    document.getElementById('surpriseModal').style.display = 'none';
}

function generateRandomRecipe() {
    const rows = document.querySelectorAll('#recipesTableBody tr[data-id]');
    const recipesList = [];
    
    rows.forEach(row => {
        const id = row.getAttribute('data-id');
        const title = row.querySelector('.recipe-title-cell span')?.innerText || '';
        const difficulty = row.querySelector('.badge-difficulty')?.innerText.trim() || '';
        const time = row.querySelector('.time-badge')?.innerText.replace(/min/g, '').trim() || '0';
        const calories = row.querySelector('.calories-badge')?.innerText.replace('N/A', '0').trim() || '0';
        const type = row.getAttribute('data-type');
        
        if(title) {
            recipesList.push({ id, title, difficulty, time, calories, type });
        }
    });
    
    if(recipesList.length === 0) {
        document.getElementById('surpriseContent').innerHTML = `
            <i class="fas fa-sad-tear" style="font-size: 3rem; color: #ccc;"></i>
            <p>Aucune recette disponible pour la surprise !</p>
            <a href="index.php?action=backCreateRecipe" class="btn-create">Créer une recette</a>
        `;
        return;
    }
    
    const randomIndex = Math.floor(Math.random() * recipesList.length);
    const recipe = recipesList[randomIndex];
    
    // Icône selon le type
    let typeIcon = '';
    if(recipe.type === 'vegan') typeIcon = '🌱';
    else if(recipe.type === 'vegetarian') typeIcon = '🥕';
    else typeIcon = '🍽️';
    
    document.getElementById('surpriseContent').innerHTML = `
        <div style="text-align: center;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">🎲🍀</div>
            <h2 style="color: #9b59b6; margin-bottom: 1rem;">${typeIcon} ${recipe.title}</h2>
            <div style="background: #f8f9fa; border-radius: 10px; padding: 1rem; margin: 1rem 0;">
                <div style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap;">
                    <span>⏱️ ${recipe.time} min</span>
                    <span>🔥 ${recipe.calories} cal</span>
                    <span>📊 ${recipe.difficulty}</span>
                </div>
            </div>
            <a href="index.php?action=backEditRecipe&id=${recipe.id}" class="btn-action edit" style="padding: 0.5rem 1rem; width: auto;">Voir la recette</a>
        </div>
    `;
}

// ==================== COMPARAISON ====================
function showCompareModal() {
    document.getElementById('compareModal').style.display = 'block';
    // Réinitialiser les selects
    document.getElementById('recipe1_select').value = '';
    document.getElementById('recipe2_select').value = '';
    document.getElementById('compareResult').style.display = 'none';
}

function closeCompareModal() {
    document.getElementById('compareModal').style.display = 'none';
}

function updateCompare() {
    const select1 = document.getElementById('recipe1_select');
    const select2 = document.getElementById('recipe2_select');
    
    const option1 = select1.options[select1.selectedIndex];
    const option2 = select2.options[select2.selectedIndex];
    
    if(!option1.value || !option2.value) {
        document.getElementById('compareResult').style.display = 'none';
        return;
    }
    
    // Récupérer les données
    const title1 = option1.getAttribute('data-title');
    const calories1 = parseFloat(option1.getAttribute('data-calories')) || 0;
    const time1 = parseInt(option1.getAttribute('data-time')) || 0;
    const difficulty1 = option1.getAttribute('data-difficulty');
    const vegan1 = option1.getAttribute('data-vegan');
    const veg1 = option1.getAttribute('data-veg');
    
    const title2 = option2.getAttribute('data-title');
    const calories2 = parseFloat(option2.getAttribute('data-calories')) || 0;
    const time2 = parseInt(option2.getAttribute('data-time')) || 0;
    const difficulty2 = option2.getAttribute('data-difficulty');
    const vegan2 = option2.getAttribute('data-vegan');
    const veg2 = option2.getAttribute('data-veg');
    
    // Mettre à jour les titres
    document.getElementById('compareTitle1').innerHTML = title1;
    document.getElementById('compareTitle2').innerHTML = title2;
    
    // Mettre à jour les valeurs
    document.getElementById('compareCalories1').innerHTML = calories1 + ' kcal';
    document.getElementById('compareCalories2').innerHTML = calories2 + ' kcal';
    document.getElementById('compareTime1').innerHTML = time1 + ' min';
    document.getElementById('compareTime2').innerHTML = time2 + ' min';
    
    // Difficulté avec icône
    const getDifficultyIcon = (d) => {
        if(d === 'facile') return '😊 Facile';
        if(d === 'moyen') return '😐 Moyen';
        return '😤 Difficile';
    };
    document.getElementById('compareDifficulty1').innerHTML = getDifficultyIcon(difficulty1);
    document.getElementById('compareDifficulty2').innerHTML = getDifficultyIcon(difficulty2);
    
    document.getElementById('compareVegan1').innerHTML = vegan1;
    document.getElementById('compareVegan2').innerHTML = vegan2;
    document.getElementById('compareVegetarian1').innerHTML = veg1;
    document.getElementById('compareVegetarian2').innerHTML = veg2;
    
    // Calculer les vainqueurs
    // Calories : le plus bas gagne
    if(calories1 && calories2) {
        const winner = calories1 < calories2 ? title1 : (calories2 < calories1 ? title2 : 'Égalité');
        document.getElementById('winnerCalories').innerHTML = winner;
        document.getElementById('winnerCalories').className = calories1 !== calories2 ? 'winner-highlight' : '';
    } else {
        document.getElementById('winnerCalories').innerHTML = '-';
    }
    
    // Temps : le plus bas gagne
    if(time1 && time2) {
        const winner = time1 < time2 ? title1 : (time2 < time1 ? title2 : 'Égalité');
        document.getElementById('winnerTime').innerHTML = winner;
        document.getElementById('winnerTime').className = time1 !== time2 ? 'winner-highlight' : '';
    } else {
        document.getElementById('winnerTime').innerHTML = '-';
    }
    
    // Difficulté : facile > moyen > difficile
    const diffScore = { 'facile': 3, 'moyen': 2, 'difficile': 1 };
    const score1 = diffScore[difficulty1] || 0;
    const score2 = diffScore[difficulty2] || 0;
    if(score1 && score2) {
        const winner = score1 > score2 ? title1 : (score2 > score1 ? title2 : 'Égalité');
        document.getElementById('winnerDifficulty').innerHTML = winner;
        document.getElementById('winnerDifficulty').className = score1 !== score2 ? 'winner-highlight' : '';
    } else {
        document.getElementById('winnerDifficulty').innerHTML = '-';
    }
    
    // Vegan : celui qui est vegan gagne
    if(vegan1 !== vegan2) {
        const winner = vegan1 === '✅ Oui' ? title1 : title2;
        document.getElementById('winnerVegan').innerHTML = winner;
        document.getElementById('winnerVegan').className = 'winner-highlight';
    } else {
        document.getElementById('winnerVegan').innerHTML = vegan1 === '✅ Oui' ? 'Les deux sont vegan 🎉' : 'Aucun vegan';
        document.getElementById('winnerVegan').className = '';
    }
    
    // Végétarien
    if(veg1 !== veg2) {
        const winner = veg1 === '✅ Oui' ? title1 : title2;
        document.getElementById('winnerVegetarian').innerHTML = winner;
        document.getElementById('winnerVegetarian').className = 'winner-highlight';
    } else {
        document.getElementById('winnerVegetarian').innerHTML = veg1 === '✅ Oui' ? 'Les deux sont végétariens 🎉' : 'Aucun végétarien';
        document.getElementById('winnerVegetarian').className = '';
    }
    
    document.getElementById('compareResult').style.display = 'block';
}
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

// STATISTIQUES MODAL
function showStatsModal() {
    document.getElementById('statsModal').style.display = 'block';
    generateRecipesChart();
}

function closeStatsModal() {
    document.getElementById('statsModal').style.display = 'none';
}

function generateRecipesChart() {
    const ctx = document.getElementById('recipesChart').getContext('2d');
    
    // Récupérer les données des recettes depuis le tableau
    const rows = document.querySelectorAll('#recipesTableBody tr[data-id]');
    let vegan = 0, vegetarian = 0, standard = 0;
    
    rows.forEach(row => {
        const type = row.getAttribute('data-type');
        if(type === 'vegan') vegan++;
        else if(type === 'vegetarian') vegetarian++;
        else standard++;
    });
    
    // Préparer les données pour le graphique
    const labels = [];
    const data = [];
    const colors = [];
    
    if(vegan > 0) { labels.push('Vegan'); data.push(vegan); colors.push('#2ecc71'); }
    if(vegetarian > 0) { labels.push('Végétarien'); data.push(vegetarian); colors.push('#f39c12'); }
    if(standard > 0) { labels.push('Standard'); data.push(standard); colors.push('#3498db'); }
    
    if(data.length === 0) {
        ctx.canvas.parentElement.innerHTML = '<div style="text-align:center; padding:1rem;"><i class="fas fa-chart-pie" style="font-size:2rem; color:#ccc;"></i><p style="font-size:0.8rem;">Aucune donnée disponible</p></div>';
        document.getElementById('chartLegend').innerHTML = '';
        return;
    }
    
    if(recipesChart) recipesChart.destroy();
    
    recipesChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderColor: 'white',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(c) {
                            const total = c.dataset.data.reduce((a,b)=>a+b,0);
                            return `${c.label}: ${c.raw} recette(s) (${Math.round((c.raw/total)*100)}%)`;
                        }
                    }
                }
            }
        }
    });
    
    // Générer la légende
    const total = data.reduce((a,b)=>a+b,0);
    document.getElementById('chartLegend').innerHTML = labels.map((l,i) => `
        <div style="display:flex; align-items:center; gap:0.4rem; font-size:0.75rem;">
            <div style="width:10px; height:10px; background:${colors[i]}; border-radius:3px;"></div>
            <span>${l}</span>
            <span style="font-weight:600;">${data[i]} (${Math.round((data[i]/total)*100)}%)</span>
        </div>
    `).join('');
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
        const statsModal = document.getElementById('statsModal');
        if(statsModal) statsModal.style.display = 'none';
    }
});
</script>

<?php 
$footerPath = dirname(__DIR__) . '/layout/footer.php';
if(file_exists($footerPath)) {
    include $footerPath;
}
?>