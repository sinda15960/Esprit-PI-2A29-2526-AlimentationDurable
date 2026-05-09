<?php 
$pageTitle = "Gestion des Catégories";
$activeMenu = "categories";
$breadcrumb = [
    ['label' => 'Tableau de bord', 'url' => 'index.php?action=backRecipes'],
    ['label' => 'Catégories']
];

// Calcul des statistiques
$totalCategories = count($categories);
$totalRecettes = 0;
$categoriePlusRecettes = ['nom' => '', 'nb' => 0];
$categoriesAvecRecettes = 0;
$categoriesSansRecettes = 0;

// Récupérer les objectifs depuis la session ou initialiser
if (!isset($_SESSION['category_goals'])) {
    $_SESSION['category_goals'] = [];
}

foreach($categories as $cat) {
    $nb = $cat['nb_recettes'] ?? 0;
    $totalRecettes += $nb;
    
    if($nb > $categoriePlusRecettes['nb']) {
        $categoriePlusRecettes['nom'] = $cat['nom'];
        $categoriePlusRecettes['nb'] = $nb;
    }
    
    if($nb > 0) {
        $categoriesAvecRecettes++;
    } else {
        $categoriesSansRecettes++;
    }
    
    // Initialiser objectif par défaut si non existant
    $catId = $cat['idCategorie'];
    if (!isset($_SESSION['category_goals'][$catId])) {
        $_SESSION['category_goals'][$catId] = 10; // Objectif par défaut : 10 recettes
    }
}

$moyenneRecettes = $totalCategories > 0 ? round($totalRecettes / $totalCategories, 1) : 0;

// Traitement de l'export CSV
if (isset($_GET['export_csv'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=categories_' . date('Y-m-d') . '.csv');
    
    $output = fopen('php://output', 'w');
    fwrite($output, "\xEF\xBB\xBF"); // BOM pour UTF-8
    fputcsv($output, ['ID', 'Nom', 'Description', 'Couleur', 'Icône', 'Nombre de recettes', 'Objectif', 'Progression (%)']);
    
    foreach($categories as $cat) {
        $catId = $cat['idCategorie'];
        $objectif = $_SESSION['category_goals'][$catId] ?? 10;
        $nbRecettes = $cat['nb_recettes'] ?? 0;
        $progression = $objectif > 0 ? round(($nbRecettes / $objectif) * 100) : 0;
        
        fputcsv($output, [
            $cat['idCategorie'],
            $cat['nom'],
            $cat['description'] ?? '',
            $cat['couleur'],
            $cat['icon'],
            $nbRecettes,
            $objectif,
            $progression . '%'
        ]);
    }
    
    fclose($output);
    exit();
}

// Traitement de la mise à jour d'objectif
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_goal'])) {
    $catId = (int)$_POST['category_id'];
    $newGoal = (int)$_POST['goal_value'];
    if ($newGoal >= 1 && $newGoal <= 100) {
        $_SESSION['category_goals'][$catId] = $newGoal;
        $_SESSION['success'] = "Objectif mis à jour avec succès !";
    }
    header("Location: index.php?action=backCategories");
    exit();
}

$headerPath = dirname(__DIR__) . '/layout/header.php';
if(file_exists($headerPath)) {
    include $headerPath;
}
?>

<div class="categories-container">
    <!-- En-tête -->
    <div class="top-bar-categories">
        <h1><i class="fas fa-tags"></i> Gestion des Catégories</h1>
        <div class="header-buttons">
            <a href="?action=backCategories&export_csv=1" class="btn-export">
                <i class="fas fa-download"></i> 📥 Exporter CSV
            </a>
            <button class="btn-stats" onclick="showStatsModal()">
                <i class="fas fa-chart-pie"></i> Statistiques
            </button>
            <button class="btn-create" onclick="showCreateModal()">
                <i class="fas fa-plus"></i> Nouvelle catégorie
            </button>
        </div>
    </div>
    
    <!-- Cartes statistiques -->
    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-box-icon"><i class="fas fa-folder-tree"></i></div>
            <div class="stat-box-content">
                <span class="stat-box-label">TOTAL CATÉGORIES</span>
                <span class="stat-box-value"><?php echo $totalCategories; ?></span>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-box-icon" style="background:#2ecc71;"><i class="fas fa-utensils"></i></div>
            <div class="stat-box-content">
                <span class="stat-box-label">TOTAL RECETTES</span>
                <span class="stat-box-value"><?php echo $totalRecettes; ?></span>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-box-icon" style="background:#f39c12;"><i class="fas fa-chart-line"></i></div>
            <div class="stat-box-content">
                <span class="stat-box-label">MOYENNE</span>
                <span class="stat-box-value"><?php echo $moyenneRecettes; ?></span>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-box-icon" style="background:#e74c3c;"><i class="fas fa-star"></i></div>
            <div class="stat-box-content">
                <span class="stat-box-label">TOP CATÉGORIE</span>
                <span class="stat-box-value"><?php echo htmlspecialchars($categoriePlusRecettes['nom'] ?: '-'); ?></span>
            </div>
        </div>
    </div>
    
    <!-- Barre de recherche et tri -->
    <div class="search-sort-row">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Rechercher une catégorie..." onkeyup="filterCategories()">
        </div>
        <div class="sort-box">
            <label>Trier par :</label>
            <select id="sortSelect" onchange="sortCategories()">
                <option value="nom_asc">📝 Nom (A → Z)</option>
                <option value="nom_desc">📝 Nom (Z → A)</option>
                <option value="recettes_desc">🍽️ Plus de recettes</option>
                <option value="recettes_asc">🍽️ Moins de recettes</option>
                <option value="progression_desc">🎯 Progression ↓</option>
                <option value="progression_asc">🎯 Progression ↑</option>
            </select>
        </div>
    </div>
    
    <!-- Messages -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert-success-cat">✅ <?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert-error-cat">❌ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <!-- Tableau -->
    <div class="table-cat-container">
        <table class="table-cat">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Couleur</th>
                    <th>Nb Recettes</th>
                    <th>🎯 Objectif</th>
                    <th>Progression</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="categoriesTableBody">
                <?php if(empty($categories)): ?>
                    <tr class="empty-row">
                        <td colspan="7">
                            <i class="fas fa-tags"></i>
                            <h3>Aucune catégorie</h3>
                            <button onclick="showCreateModal()" class="btn-create-small">Créer une catégorie</button>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($categories as $categorie): 
                        $catId = $categorie['idCategorie'];
                        $objectif = $_SESSION['category_goals'][$catId] ?? 10;
                        $nbRecettes = $categorie['nb_recettes'] ?? 0;
                        $progression = $objectif > 0 ? min(100, round(($nbRecettes / $objectif) * 100)) : 0;
                        $progressionClass = $progression >= 100 ? 'completed' : ($progression >= 70 ? 'good' : ($progression >= 40 ? 'medium' : 'low'));
                    ?>
                        <tr data-id="<?php echo $categorie['idCategorie']; ?>" 
                            data-nom="<?php echo htmlspecialchars(strtolower($categorie['nom'])); ?>" 
                            data-recettes="<?php echo $nbRecettes; ?>"
                            data-progression="<?php echo $progression; ?>">
                            <td class="cat-name">
                                <i class="<?php echo $categorie['icon']; ?>" style="color: <?php echo $categorie['couleur']; ?>;"></i>
                                <strong><?php echo htmlspecialchars($categorie['nom']); ?></strong>
                                <?php if($progression >= 100): ?>
                                    <span class="badge-completed">🏆 Complet</span>
                                <?php elseif($nbRecettes == $objectif - 1): ?>
                                    <span class="badge-almost">🔥 Plus qu'une !</span>
                                <?php endif; ?>
                            </td>
                            <td class="cat-desc"><?php echo htmlspecialchars(substr($categorie['description'] ?? '', 0, 60)); ?></td>
                            <td class="cat-color">
                                <span class="color-dot" style="background: <?php echo $categorie['couleur']; ?>;"></span>
                                <?php echo $categorie['couleur']; ?>
                            </td>
                            <td class="cat-count">
                                <span class="count-badge"><i class="fas fa-utensils"></i> <?php echo $nbRecettes; ?></span>
                            </td>
                            <td class="cat-goal">
                                <div class="goal-display">
                                    <span class="goal-value">🎯 <?php echo $objectif; ?></span>
                                    <button onclick="showGoalModal(<?php echo $catId; ?>, '<?php echo addslashes($categorie['nom']); ?>', <?php echo $objectif; ?>)" class="btn-edit-goal" title="Modifier l'objectif">✏️</button>
                                </div>
                            </td>
                            <td class="cat-progression">
                                <div class="progress-container">
                                    <div class="progress-bar-cat <?php echo $progressionClass; ?>" style="width: <?php echo $progression; ?>%;">
                                        <span class="progress-text"><?php echo $progression; ?>%</span>
                                    </div>
                                </div>
                            </td>
                            <td class="cat-actions">
                                <button onclick='showViewModal(<?php echo $categorie['idCategorie']; ?>, <?php echo json_encode($categorie['nom']); ?>, <?php echo json_encode($categorie['description'] ?? ''); ?>, <?php echo json_encode($categorie['icon']); ?>, <?php echo json_encode($categorie['couleur']); ?>, <?php echo $nbRecettes; ?>)' class="btn-view-cat" title="Afficher">👁️</button>
                                <button onclick='showEditModal(<?php echo $categorie['idCategorie']; ?>, <?php echo json_encode($categorie['nom']); ?>, <?php echo json_encode($categorie['description'] ?? ''); ?>, <?php echo json_encode($categorie['icon']); ?>, <?php echo json_encode($categorie['couleur']); ?>)' class="btn-edit-cat" title="Modifier">✏️</button>
                                <button onclick='showDeleteModal(<?php echo $categorie['idCategorie']; ?>, <?php echo json_encode($categorie['nom']); ?>)' class="btn-delete-cat" title="Supprimer">🗑️</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div id="noResultsMsg" style="display:none; text-align:center; padding:2rem; background:#f8f9fa; border-radius:10px; margin-top:1rem;">
        <i class="fas fa-search" style="font-size:2rem; color:#ccc;"></i>
        <h3>Aucune catégorie trouvée</h3>
    </div>
</div>

<!-- MODAL MODIFIER OBJECTIF -->
<div id="goalModal" class="modal-cat">
    <div class="modal-cat-content" style="max-width: 400px;">
        <div class="modal-cat-header" style="background: #f39c12;">
            <h3><i class="fas fa-bullseye"></i> Définir un objectif</h3>
            <span class="close" onclick="closeGoalModal()">&times;</span>
        </div>
        <form method="POST" action="">
            <div class="modal-cat-body">
                <input type="hidden" name="category_id" id="goal_category_id">
                <input type="hidden" name="update_goal" value="1">
                <p>Catégorie : <strong id="goal_category_name"></strong></p>
                <div class="form-group-cat">
                    <label>Objectif (nombre de recettes) :</label>
                    <input type="number" name="goal_value" id="goal_value" min="1" max="100" required>
                    <small>Entre 1 et 100 recettes</small>
                </div>
                <div class="preview-goal">
                    <p>Aperçu de la progression :</p>
                    <div class="progress-container preview">
                        <div class="progress-bar-cat preview-bar" style="width: 0%;">
                            <span class="progress-text">0%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-cat-footer">
                <button type="button" class="btn-cancel-modal" onclick="closeGoalModal()">Annuler</button>
                <button type="submit" class="btn-confirm-modal">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL STATISTIQUES -->
<div id="statsModal" class="modal-cat">
    <div class="modal-cat-content">
        <div class="modal-cat-header" style="background: #3498db;">
            <h3><i class="fas fa-chart-pie"></i> Statistiques des catégories</h3>
            <span class="close" onclick="closeStatsModal()">&times;</span>
        </div>
        <div class="modal-cat-body">
            <div class="stats-summary">
                <div class="stat-item"><span>📊 Total :</span> <strong><?php echo $totalCategories; ?></strong></div>
                <div class="stat-item"><span>🍽️ Recettes :</span> <strong><?php echo $totalRecettes; ?></strong></div>
                <div class="stat-item"><span>✅ Avec recettes :</span> <strong><?php echo $categoriesAvecRecettes; ?></strong></div>
                <div class="stat-item"><span>📭 Vides :</span> <strong><?php echo $categoriesSansRecettes; ?></strong></div>
                <div class="stat-item"><span>🎯 Objectifs atteints :</span> <strong id="goalsAchieved">0</strong></div>
            </div>
            <div class="chart-wrapper">
                <canvas id="categoriesChart"></canvas>
            </div>
            <div id="chartLegend" class="chart-legend"></div>
        </div>
        <div class="modal-cat-footer">
            <button class="btn-close-modal" onclick="closeStatsModal()">Fermer</button>
        </div>
    </div>
</div>

<!-- MODAL AFFICHAGE -->
<div id="viewModal" class="modal-cat">
    <div class="modal-cat-content" style="max-width: 450px;">
        <div class="modal-cat-header" style="background: #3498db;">
            <h3><i class="fas fa-eye"></i> Détail de la catégorie</h3>
            <span class="close" onclick="closeViewModal()">&times;</span>
        </div>
        <div class="modal-cat-body">
            <div class="view-icon"><i id="view_icon" style="font-size: 3rem;"></i></div>
            <table class="view-table">
                <tr><td class="label">Nom :</td><td id="view_nom"></td></tr>
                <tr><td class="label">Description :</td><td id="view_description"></td></tr>
                <tr><td class="label">Couleur :</td><td id="view_couleur"></td></tr>
                <tr><td class="label">Nb recettes :</td><td id="view_nb_recettes"></td></tr>
                <tr><td class="label">Objectif :</td><td id="view_goal"></td><tr>
                <tr><td class="label">Progression :</td><td id="view_progression"></td></tr>
            </table>
        </div>
        <div class="modal-cat-footer">
            <button class="btn-close-modal" onclick="closeViewModal()">Fermer</button>
        </div>
    </div>
</div>

<!-- MODAL CRÉATION -->
<div id="createModal" class="modal-cat">
    <div class="modal-cat-content" style="max-width: 500px;">
        <div class="modal-cat-header">
            <h3><i class="fas fa-plus"></i> Nouvelle catégorie</h3>
            <span class="close" onclick="closeCreateModal()">&times;</span>
        </div>
        <form method="POST" id="createForm" action="index.php?action=backCreateCategorie">
            <div class="modal-cat-body">
                <div class="form-group-cat">
                    <label>Nom <span class="required">*</span></label>
                    <input type="text" name="nom" id="create_nom" required>
                    <div class="error-msg" id="create_nom_error"></div>
                </div>
                <div class="form-group-cat">
                    <label>Description</label>
                    <textarea name="description" id="create_description" rows="3"></textarea>
                </div>
                <div class="form-row-cat">
                    <div class="form-group-cat">
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
                        </select>
                    </div>
                    <div class="form-group-cat">
                        <label>Couleur</label>
                        <input type="color" name="couleur" id="create_couleur" value="#2ecc71">
                    </div>
                </div>
            </div>
            <div class="modal-cat-footer">
                <button type="button" class="btn-cancel-modal" onclick="closeCreateModal()">Annuler</button>
                <button type="submit" class="btn-confirm-modal">Créer</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL MODIFICATION -->
<div id="editModal" class="modal-cat">
    <div class="modal-cat-content" style="max-width: 500px;">
        <div class="modal-cat-header">
            <h3><i class="fas fa-edit"></i> Modifier la catégorie</h3>
            <span class="close" onclick="closeEditModal()">&times;</span>
        </div>
        <form method="POST" id="editForm" action="">
            <div class="modal-cat-body">
                <div class="form-group-cat">
                    <label>Nom <span class="required">*</span></label>
                    <input type="text" name="nom" id="edit_nom" required>
                    <div class="error-msg" id="edit_nom_error"></div>
                </div>
                <div class="form-group-cat">
                    <label>Description</label>
                    <textarea name="description" id="edit_description" rows="3"></textarea>
                </div>
                <div class="form-row-cat">
                    <div class="form-group-cat">
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
                        </select>
                    </div>
                    <div class="form-group-cat">
                        <label>Couleur</label>
                        <input type="color" name="couleur" id="edit_couleur" value="#2ecc71">
                    </div>
                </div>
            </div>
            <div class="modal-cat-footer">
                <button type="button" class="btn-cancel-modal" onclick="closeEditModal()">Annuler</button>
                <button type="submit" class="btn-confirm-modal">Modifier</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL SUPPRESSION -->
<div id="deleteModal" class="modal-cat">
    <div class="modal-cat-content" style="max-width: 400px;">
        <div class="modal-cat-header" style="background: #e74c3c;">
            <h3><i class="fas fa-exclamation-triangle"></i> Confirmer la suppression</h3>
            <span class="close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <div class="modal-cat-body">
            <p>Supprimer la catégorie :</p>
            <p class="delete-title"><strong id="deleteCategorieTitle"></strong> ?</p>
            <div class="warning-box-cat">
                <i class="fas fa-trash-alt"></i> Les recettes associées ne seront pas supprimées
            </div>
        </div>
        <div class="modal-cat-footer">
            <form method="POST" id="deleteForm" action="">
                <button type="button" class="btn-cancel-modal" onclick="closeDeleteModal()">Annuler</button>
                <button type="submit" class="btn-danger-modal">Supprimer</button>
            </form>
        </div>
    </div>
</div>

<style>
/* Styles généraux */
.categories-container {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

/* En-tête */
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
    margin: 0;
}

.top-bar-categories h1 i {
    color: #2ecc71;
    margin-right: 0.5rem;
}

.header-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn-export {
    background: linear-gradient(135deg, #1abc9c, #16a085);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.9rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-export:hover {
    transform: translateY(-2px);
}

.btn-stats {
    background: #3498db;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.9rem;
}

.btn-stats:hover {
    background: #2980b9;
    transform: translateY(-2px);
}

.btn-create {
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.9rem;
}

.btn-create:hover {
    transform: translateY(-2px);
}

/* Cartes statistiques */
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-box {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    border: 1px solid #e0e0e0;
}

.stat-box-icon {
    width: 50px;
    height: 50px;
    background: #667eea;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
}

.stat-box-content {
    display: flex;
    flex-direction: column;
}

.stat-box-label {
    font-size: 0.7rem;
    color: #999;
    text-transform: uppercase;
}

.stat-box-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a2a3a;
}

/* Recherche et tri */
.search-sort-row {
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
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 0.9rem;
}

.sort-box {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.sort-box select {
    padding: 9px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: white;
}

/* Alertes */
.alert-success-cat, .alert-error-cat {
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
}

.alert-success-cat {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error-cat {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Tableau */
.table-cat-container {
    overflow-x: auto;
    border-radius: 12px;
    border: 1px solid #e0e0e0;
}

.table-cat {
    width: 100%;
    border-collapse: collapse;
}

.table-cat thead {
    background: #2ecc71;
    color: white;
}

.table-cat th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
}

.table-cat td {
    padding: 1rem;
    border-bottom: 1px solid #e0e0e0;
}

.table-cat tbody tr:hover {
    background: #f8f9fa;
}

.empty-row td {
    text-align: center;
    padding: 3rem;
}

.cat-name i {
    margin-right: 8px;
}

.color-dot {
    display: inline-block;
    width: 20px;
    height: 20px;
    border-radius: 5px;
    margin-right: 8px;
    vertical-align: middle;
}

.count-badge {
    background: #e8f5e9;
    color: #2ecc71;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    font-size: 0.85rem;
}

/* Objectif et progression */
.goal-display {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.goal-value {
    font-weight: 600;
    color: #e67e22;
}

.btn-edit-goal {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    opacity: 0.6;
}

.btn-edit-goal:hover {
    opacity: 1;
    transform: scale(1.1);
}

.progress-container {
    background: #e0e0e0;
    border-radius: 20px;
    overflow: hidden;
    width: 120px;
    height: 24px;
}

.progress-bar-cat {
    height: 100%;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-right: 5px;
    transition: width 0.3s ease;
}

.progress-bar-cat.completed { background: #2ecc71; }
.progress-bar-cat.good { background: #27ae60; }
.progress-bar-cat.medium { background: #f39c12; }
.progress-bar-cat.low { background: #e74c3c; }

.progress-text {
    color: white;
    font-size: 0.7rem;
    font-weight: bold;
}

.badge-completed {
    background: #2ecc71;
    color: white;
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
    border-radius: 20px;
    margin-left: 8px;
}

.badge-almost {
    background: #f39c12;
    color: white;
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
    border-radius: 20px;
    margin-left: 8px;
}

/* Actions */
.cat-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-view-cat, .btn-edit-cat, .btn-delete-cat {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1rem;
}

.btn-view-cat { background: #3498db; color: white; }
.btn-edit-cat { background: #f39c12; color: white; }
.btn-delete-cat { background: #e74c3c; color: white; }
.btn-view-cat:hover, .btn-edit-cat:hover, .btn-delete-cat:hover { transform: scale(1.05); }

/* Modals */
.modal-cat {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
}

.modal-cat-content {
    background: white;
    margin: 5% auto;
    width: 90%;
    max-width: 550px;
    border-radius: 15px;
    animation: fadeIn 0.3s;
}

.modal-cat-header {
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
    border-radius: 15px 15px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-cat-header .close {
    font-size: 1.5rem;
    cursor: pointer;
}

.modal-cat-body {
    padding: 1.5rem;
}

.modal-cat-footer {
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    border-top: 1px solid #e0e0e0;
}

.stats-summary {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 1rem;
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
}

.stat-item {
    text-align: center;
}

.stat-item span {
    font-size: 0.8rem;
    color: #666;
}

.stat-item strong {
    font-size: 1.2rem;
    color: #2ecc71;
    display: block;
}

.chart-wrapper {
    max-width: 250px;
    margin: 0 auto;
}

.chart-legend {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.8rem;
    margin-top: 1rem;
}

.form-group-cat {
    margin-bottom: 1rem;
}

.form-group-cat label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.form-group-cat input, .form-group-cat select, .form-group-cat textarea {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 8px;
}

.form-row-cat {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.required { color: #e74c3c; }
.error-msg { color: #e74c3c; font-size: 0.75rem; margin-top: 0.3rem; display: none; }
.warning-box-cat { background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 10px; display: flex; align-items: center; gap: 0.5rem; margin-top: 1rem; }
.delete-title { text-align: center; font-size: 1.1rem; margin: 1rem 0; }
.btn-confirm-modal { background: #2ecc71; color: white; border: none; padding: 0.5rem 1.5rem; border-radius: 5px; cursor: pointer; }
.btn-cancel-modal { background: #95a5a6; color: white; border: none; padding: 0.5rem 1.5rem; border-radius: 5px; cursor: pointer; }
.btn-danger-modal { background: #e74c3c; color: white; border: none; padding: 0.5rem 1.5rem; border-radius: 5px; cursor: pointer; }
.btn-close-modal { background: #95a5a6; color: white; border: none; padding: 0.5rem 1.5rem; border-radius: 5px; cursor: pointer; }
.view-table { width: 100%; }
.view-table td { padding: 8px; }
.view-table .label { font-weight: bold; width: 40%; }
.preview-goal { margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e0e0e0; }
.preview-goal p { font-size: 0.8rem; margin-bottom: 0.5rem; }
.progress-container.preview { width: 100%; }

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@media (max-width: 768px) {
    .categories-container { padding: 1rem; }
    .top-bar-categories { flex-direction: column; text-align: center; }
    .stats-row { grid-template-columns: 1fr; }
    .search-sort-row { flex-direction: column; }
    .search-box { max-width: 100%; width: 100%; }
    .form-row-cat { grid-template-columns: 1fr; }
    .header-buttons { justify-content: center; }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
let categoriesChart = null;

function filterCategories() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#categoriesTableBody tr[data-id]');
    let visibleCount = 0;
    rows.forEach(row => {
        const nom = row.getAttribute('data-nom');
        if(nom.includes(searchTerm)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    document.getElementById('noResultsMsg').style.display = visibleCount === 0 ? 'block' : 'none';
}

function sortCategories() {
    const sortValue = document.getElementById('sortSelect').value;
    const tbody = document.getElementById('categoriesTableBody');
    const rows = Array.from(tbody.querySelectorAll('tr[data-id]'));
    rows.sort((a, b) => {
        if(sortValue === 'nom_asc') return a.getAttribute('data-nom').localeCompare(b.getAttribute('data-nom'));
        if(sortValue === 'nom_desc') return b.getAttribute('data-nom').localeCompare(a.getAttribute('data-nom'));
        if(sortValue === 'recettes_desc') return parseInt(b.getAttribute('data-recettes')) - parseInt(a.getAttribute('data-recettes'));
        if(sortValue === 'recettes_asc') return parseInt(a.getAttribute('data-recettes')) - parseInt(b.getAttribute('data-recettes'));
        if(sortValue === 'progression_desc') return parseInt(b.getAttribute('data-progression')) - parseInt(a.getAttribute('data-progression'));
        if(sortValue === 'progression_asc') return parseInt(a.getAttribute('data-progression')) - parseInt(b.getAttribute('data-progression'));
        return 0;
    });
    rows.forEach(row => tbody.appendChild(row));
}

// MODAL OBJECTIF
let currentCategoryId = null;
let currentRecettes = 0;

function showGoalModal(id, name, currentGoal) {
    currentCategoryId = id;
    document.getElementById('goal_category_id').value = id;
    document.getElementById('goal_category_name').innerHTML = name;
    document.getElementById('goal_value').value = currentGoal;
    document.getElementById('goalModal').style.display = 'block';
    
    // Récupérer le nombre de recettes actuelles
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if(row) {
        currentRecettes = parseInt(row.getAttribute('data-recettes')) || 0;
        updatePreview(currentGoal);
    }
    
    document.getElementById('goal_value').oninput = function() {
        updatePreview(this.value);
    };
}

function updatePreview(goal) {
    const previewBar = document.querySelector('.preview-bar');
    const progression = goal > 0 ? Math.min(100, Math.round((currentRecettes / goal) * 100)) : 0;
    previewBar.style.width = progression + '%';
    previewBar.querySelector('.progress-text').innerText = progression + '%';
    
    if(progression >= 100) {
        previewBar.className = 'progress-bar-cat preview-bar completed';
    } else if(progression >= 70) {
        previewBar.className = 'progress-bar-cat preview-bar good';
    } else if(progression >= 40) {
        previewBar.className = 'progress-bar-cat preview-bar medium';
    } else {
        previewBar.className = 'progress-bar-cat preview-bar low';
    }
}

function closeGoalModal() {
    document.getElementById('goalModal').style.display = 'none';
}

// STATISTIQUES
function showStatsModal() {
    document.getElementById('statsModal').style.display = 'block';
    generateChart();
    updateGoalsStats();
}

function closeStatsModal() {
    document.getElementById('statsModal').style.display = 'none';
}

function updateGoalsStats() {
    const rows = document.querySelectorAll('#categoriesTableBody tr[data-id]');
    let goalsAchieved = 0;
    rows.forEach(row => {
        const progression = parseInt(row.getAttribute('data-progression'));
        if(progression >= 100) goalsAchieved++;
    });
    document.getElementById('goalsAchieved').innerHTML = goalsAchieved;
}

function generateChart() {
    const ctx = document.getElementById('categoriesChart').getContext('2d');
    const rows = document.querySelectorAll('#categoriesTableBody tr[data-id]');
    let avecRecettes = 0, sansRecettes = 0;
    rows.forEach(row => {
        const recettes = parseInt(row.getAttribute('data-recettes'));
        if(recettes > 0) avecRecettes++;
        else sansRecettes++;
    });
    const labels = [], data = [], colors = [];
    if(avecRecettes > 0) { labels.push('✅ Avec recettes'); data.push(avecRecettes); colors.push('#2ecc71'); }
    if(sansRecettes > 0) { labels.push('📭 Sans recettes'); data.push(sansRecettes); colors.push('#e74c3c'); }
    if(data.length === 0) {
        ctx.canvas.parentElement.innerHTML = '<div style="text-align:center; padding:1rem;"><i class="fas fa-chart-pie" style="font-size:2rem; color:#ccc;"></i><p>Aucune donnée</p></div>';
        document.getElementById('chartLegend').innerHTML = '';
        return;
    }
    if(categoriesChart) categoriesChart.destroy();
    categoriesChart = new Chart(ctx, {
        type: 'pie',
        data: { labels: labels, datasets: [{ data: data, backgroundColor: colors, borderColor: 'white', borderWidth: 2 }] },
        options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { display: false }, tooltip: { callbacks: { label: (c) => { const total = c.dataset.data.reduce((a,b)=>a+b,0); return `${c.label}: ${c.raw} (${Math.round((c.raw/total)*100)}%)`; } } } } }
    });
    const total = data.reduce((a,b)=>a+b,0);
    document.getElementById('chartLegend').innerHTML = labels.map((l,i) => `<div style="display:flex; align-items:center; gap:0.4rem;"><div style="width:10px; height:10px; background:${colors[i]}; border-radius:3px;"></div><span>${l}</span><span style="font-weight:600;">${data[i]} (${Math.round((data[i]/total)*100)}%)</span></div>`).join('');
}

// MODALS
function showViewModal(id, nom, desc, icon, couleur, nb) {
    document.getElementById('view_icon').className = icon;
    document.getElementById('view_icon').style.color = couleur;
    document.getElementById('view_nom').innerHTML = nom;
    document.getElementById('view_description').innerHTML = desc || 'Aucune description';
    document.getElementById('view_couleur').innerHTML = '<span style="display:inline-block; width:20px; height:20px; background:'+couleur+'; border-radius:3px;"></span> '+couleur;
    document.getElementById('view_nb_recettes').innerHTML = nb;
    
    // Récupérer l'objectif et la progression
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if(row) {
        const progression = row.getAttribute('data-progression');
        const goalSpan = row.querySelector('.goal-value');
        if(goalSpan) {
            const goal = goalSpan.innerText.replace('🎯 ', '');
            document.getElementById('view_goal').innerHTML = goal;
        }
        document.getElementById('view_progression').innerHTML = '<div class="progress-container" style="width:100%"><div class="progress-bar-cat" style="width:'+progression+'%"><span class="progress-text">'+progression+'%</span></div></div>';
    }
    
    document.getElementById('viewModal').style.display = 'block';
}

function closeViewModal() { document.getElementById('viewModal').style.display = 'none'; }
function showCreateModal() { document.getElementById('createModal').style.display = 'block'; }
function closeCreateModal() { document.getElementById('createModal').style.display = 'none'; document.getElementById('createForm').reset(); }
function showEditModal(id, nom, desc, icon, couleur) {
    document.getElementById('editForm').action = 'index.php?action=backEditCategorie&id=' + id;
    document.getElementById('edit_nom').value = nom;
    document.getElementById('edit_description').value = desc || '';
    document.getElementById('edit_icon').value = icon || 'fas fa-tag';
    document.getElementById('edit_couleur').value = couleur || '#2ecc71';
    document.getElementById('editModal').style.display = 'block';
}
function closeEditModal() { document.getElementById('editModal').style.display = 'none'; }
function showDeleteModal(id, nom) {
    document.getElementById('deleteForm').action = 'index.php?action=backDeleteCategorie&id=' + id;
    document.getElementById('deleteCategorieTitle').innerHTML = nom;
    document.getElementById('deleteModal').style.display = 'block';
}
function closeDeleteModal() { document.getElementById('deleteModal').style.display = 'none'; }
window.onclick = function(e) { if(e.target.classList.contains('modal-cat')) e.target.style.display = 'none'; }
document.getElementById('createForm')?.addEventListener('submit', function(e) {
    let nom = document.getElementById('create_nom');
    let err = document.getElementById('create_nom_error');
    if(nom.value.trim() === '') {
        err.textContent = 'Le nom est requis';
        err.style.display = 'block';
        nom.style.borderColor = '#e74c3c';
        e.preventDefault();
    } else if(nom.value.trim().length < 2) {
        err.textContent = 'Minimum 2 caractères';
        err.style.display = 'block';
        nom.style.borderColor = '#e74c3c';
        e.preventDefault();
    } else {
        err.style.display = 'none';
        nom.style.borderColor = '#ddd';
    }
});
document.addEventListener('DOMContentLoaded', function() { filterCategories(); });
</script>

<?php 
$footerPath = dirname(__DIR__) . '/layout/footer.php';
if(file_exists($footerPath)) { include $footerPath; }
?>