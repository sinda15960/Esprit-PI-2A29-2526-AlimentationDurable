<?php 
$pageTitle = "Détail de la recette";
$activeMenu = "recipes";
$breadcrumb = [
    ['label' => 'Tableau de bord', 'url' => 'index.php?action=backRecipes'],
    ['label' => 'Recettes', 'url' => 'index.php?action=backRecipes'],
    ['label' => htmlspecialchars($recipe['title'])]
];

$headerPath = dirname(__DIR__) . '/layout/header.php';
if(file_exists($headerPath)) {
    include $headerPath;
}
?>

<div class="recipe-detail-container">
    <!-- En-tête avec actions -->
    <div class="recipe-detail-header">
        <div class="recipe-detail-actions">
            <a href="index.php?action=backRecipes" class="btn-back">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
            <div class="action-buttons">
                <a href="index.php?action=backEditRecipe&id=<?php echo $recipe['id']; ?>" class="btn-edit">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <a href="index.php?action=backInstructions&id=<?php echo $recipe['id']; ?>" class="btn-instructions">
                    <i class="fas fa-list-ol"></i> Gérer les instructions
                </a>
                <button onclick="showDeleteModal(<?php echo $recipe['id']; ?>, '<?php echo addslashes($recipe['title']); ?>')" class="btn-delete">
                    <i class="fas fa-trash-alt"></i> Supprimer
                </button>
            </div>
        </div>
        
        <div class="recipe-detail-info">
            <div class="recipe-badges">
                <?php if($recipe['is_vegan']): ?>
                    <span class="badge vegan"><i class="fas fa-seedling"></i> Vegan</span>
                <?php endif; ?>
                <?php if($recipe['is_vegetarian']): ?>
                    <span class="badge vegetarian"><i class="fas fa-carrot"></i> Végétarien</span>
                <?php endif; ?>
                <?php if($recipe['is_gluten_free']): ?>
                    <span class="badge gluten-free"><i class="fas fa-wheat-slash"></i> Sans gluten</span>
                <?php endif; ?>
                <?php if(($recipe['prep_time'] + $recipe['cook_time']) <= 30): ?>
                    <span class="badge quick"><i class="fas fa-bolt"></i> Rapide</span>
                <?php endif; ?>
            </div>
            
            <h1><?php echo htmlspecialchars($recipe['title']); ?></h1>
            
            <div class="recipe-meta">
                <div class="meta-item">
                    <i class="far fa-clock"></i>
                    <div>
                        <strong>Préparation</strong>
                        <span><?php echo $recipe['prep_time']; ?> min</span>
                    </div>
                </div>
                <div class="meta-item">
                    <i class="fas fa-fire"></i>
                    <div>
                        <strong>Cuisson</strong>
                        <span><?php echo $recipe['cook_time']; ?> min</span>
                    </div>
                </div>
                <div class="meta-item">
                    <i class="fas fa-hourglass-half"></i>
                    <div>
                        <strong>Temps total</strong>
                        <span><?php echo $recipe['prep_time'] + $recipe['cook_time']; ?> min</span>
                    </div>
                </div>
                <div class="meta-item">
                    <i class="fas fa-chart-line"></i>
                    <div>
                        <strong>Difficulté</strong>
                        <span class="difficulty <?php echo $recipe['difficulty']; ?>"><?php echo ucfirst($recipe['difficulty']); ?></span>
                    </div>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar-alt"></i>
                    <div>
                        <strong>Créée le</strong>
                        <span><?php echo date('d/m/Y', strtotime($recipe['created_at'])); ?></span>
                    </div>
                </div>
                <div class="meta-item">
                    <i class="fas fa-edit"></i>
                    <div>
                        <strong>Modifiée le</strong>
                        <span><?php echo date('d/m/Y', strtotime($recipe['updated_at'])); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="recipe-detail-content">
        <div class="recipe-main">
            <!-- Image de la recette -->
            <?php if($recipe['image_url']): ?>
                <div class="recipe-image">
                    <img src="<?php echo $recipe['image_url']; ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                </div>
            <?php endif; ?>
            
            <!-- Description -->
            <div class="recipe-section">
                <h2><i class="fas fa-align-left"></i> Description</h2>
                <div class="description-content">
                    <p><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>
                </div>
            </div>
            
            <!-- Ingrédients -->
            <div class="recipe-section">
                <h2><i class="fas fa-shopping-basket"></i> Ingrédients</h2>
                <div class="ingredients-list">
                    <?php 
                    $ingredients = explode("\n", $recipe['ingredients']);
                    foreach($ingredients as $ingredient): 
                        if(trim($ingredient)):
                    ?>
                        <div class="ingredient-item">
                            <i class="fas fa-check-circle"></i>
                            <span><?php echo htmlspecialchars(trim($ingredient)); ?></span>
                        </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
            </div>
            
            <!-- Instructions -->
            <div class="recipe-section">
                <h2><i class="fas fa-list-ol"></i> Instructions</h2>
                <div class="instructions-list">
                    <?php if(empty($instructions)): ?>
                        <div class="no-instructions">
                            <i class="fas fa-info-circle"></i>
                            <p>Aucune instruction pour cette recette.</p>
                            <a href="index.php?action=backInstructions&id=<?php echo $recipe['id']; ?>" class="btn-add-instruction">
                                <i class="fas fa-plus"></i> Ajouter des instructions
                            </a>
                        </div>
                    <?php else: ?>
                        <?php foreach($instructions as $instruction): ?>
                            <div class="instruction-item">
                                <div class="step-number"><?php echo $instruction['step_number']; ?></div>
                                <div class="step-content">
                                    <p><?php echo nl2br(htmlspecialchars($instruction['description'])); ?></p>
                                    <?php if($instruction['tip']): ?>
                                        <div class="step-tip">
                                            <i class="fas fa-lightbulb"></i>
                                            <span>Astuce : <?php echo htmlspecialchars($instruction['tip']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="recipe-sidebar">
            <!-- Valeurs nutritionnelles -->
            <div class="info-card">
                <h3><i class="fas fa-chart-simple"></i> Valeurs nutritionnelles</h3>
                <div class="nutrition-grid">
                    <div class="nutrition-item">
                        <span>Calories</span>
                        <strong><?php echo $recipe['calories'] ?? 'N/A'; ?> <small>kcal</small></strong>
                        <?php if($recipe['calories']): ?>
                            <div class="progress-bar">
                                <div class="progress" style="width: <?php echo min(($recipe['calories'] / 800) * 100, 100); ?>%"></div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="nutrition-item">
                        <span>Protéines</span>
                        <strong><?php echo $recipe['protein'] ?? '0'; ?> <small>g</small></strong>
                        <?php if($recipe['protein']): ?>
                            <div class="progress-bar">
                                <div class="progress" style="width: <?php echo min(($recipe['protein'] / 50) * 100, 100); ?>%"></div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="nutrition-item">
                        <span>Glucides</span>
                        <strong><?php echo $recipe['carbs'] ?? '0'; ?> <small>g</small></strong>
                        <?php if($recipe['carbs']): ?>
                            <div class="progress-bar">
                                <div class="progress" style="width: <?php echo min(($recipe['carbs'] / 250) * 100, 100); ?>%"></div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="nutrition-item">
                        <span>Lipides</span>
                        <strong><?php echo $recipe['fats'] ?? '0'; ?> <small>g</small></strong>
                        <?php if($recipe['fats']): ?>
                            <div class="progress-bar">
                                <div class="progress" style="width: <?php echo min(($recipe['fats'] / 70) * 100, 100); ?>%"></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Informations complémentaires -->
            <div class="info-card">
                <h3><i class="fas fa-info-circle"></i> Informations</h3>
                <ul class="info-list">
                    <li>
                        <i class="fas fa-id-card"></i>
                        <div>
                            <strong>ID de la recette</strong>
                            <span>#<?php echo $recipe['id']; ?></span>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-utensils"></i>
                        <div>
                            <strong>Type</strong>
                            <span>
                                <?php 
                                if($recipe['is_vegan']) echo 'Vegan';
                                elseif($recipe['is_vegetarian']) echo 'Végétarien';
                                else echo 'Standard';
                                ?>
                            </span>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-chart-line"></i>
                        <div>
                            <strong>Difficulté</strong>
                            <span><?php echo ucfirst($recipe['difficulty']); ?></span>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong>Temps total</strong>
                            <span><?php echo $recipe['prep_time'] + $recipe['cook_time']; ?> minutes</span>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-list-ol"></i>
                        <div>
                            <strong>Nombre d'instructions</strong>
                            <span><?php echo count($instructions); ?> étape(s)</span>
                        </div>
                    </li>
                </ul>
            </div>
            
            <!-- Actions rapides -->
            <div class="info-card">
                <h3><i class="fas fa-link"></i> Actions rapides</h3>
                <div class="quick-actions">
                    <a href="index.php?action=frontShowRecipe&id=<?php echo $recipe['id']; ?>" target="_blank" class="quick-action">
                        <i class="fas fa-globe"></i>
                        <span>Voir sur le site</span>
                    </a>
                    <a href="index.php?action=backEditRecipe&id=<?php echo $recipe['id']; ?>" class="quick-action">
                        <i class="fas fa-edit"></i>
                        <span>Modifier</span>
                    </a>
                    <a href="index.php?action=backInstructions&id=<?php echo $recipe['id']; ?>" class="quick-action">
                        <i class="fas fa-list-ol"></i>
                        <span>Instructions</span>
                    </a>
                    <button onclick="showDeleteModal(<?php echo $recipe['id']; ?>, '<?php echo addslashes($recipe['title']); ?>')" class="quick-action delete-action">
                        <i class="fas fa-trash-alt"></i>
                        <span>Supprimer</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
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
/* Container principal */
.recipe-detail-container {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

/* En-tête */
.recipe-detail-header {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 2rem;
}

.recipe-detail-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: rgba(255,255,255,0.2);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s;
}

.btn-back:hover {
    background: rgba(255,255,255,0.3);
    transform: translateX(-3px);
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-edit, .btn-instructions, .btn-delete {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-edit {
    background: #f39c12;
    color: white;
}

.btn-instructions {
    background: #9b59b6;
    color: white;
}

.btn-delete {
    background: #e74c3c;
    color: white;
}

.btn-edit:hover, .btn-instructions:hover, .btn-delete:hover {
    transform: translateY(-2px);
}

/* Badges */
.recipe-badges {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.badge {
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
}

.badge.vegan {
    background: linear-gradient(135deg, #2ecc71, #27ae60);
}

.badge.vegetarian {
    background: linear-gradient(135deg, #f39c12, #e67e22);
}

.badge.gluten-free {
    background: linear-gradient(135deg, #3498db, #2980b9);
}

.badge.quick {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.recipe-detail-header h1 {
    font-size: 2.5rem;
    margin-bottom: 1.5rem;
}

/* Meta informations */
.recipe-meta {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.meta-item i {
    font-size: 1.5rem;
    opacity: 0.8;
}

.meta-item strong {
    display: block;
    font-size: 0.7rem;
    opacity: 0.7;
    font-weight: normal;
}

.meta-item span {
    font-size: 0.9rem;
    font-weight: 600;
}

.difficulty.facile { color: #2ecc71; }
.difficulty.moyen { color: #f39c12; }
.difficulty.difficile { color: #e74c3c; }

/* Contenu principal */
.recipe-detail-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    padding: 2rem;
}

/* Sections */
.recipe-section {
    margin-bottom: 2rem;
}

.recipe-section h2 {
    font-size: 1.3rem;
    color: #1a2a3a;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #2ecc71;
    display: inline-block;
}

.recipe-section h2 i {
    color: #2ecc71;
    margin-right: 0.5rem;
}

/* Image */
.recipe-image {
    margin-bottom: 2rem;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.recipe-image img {
    width: 100%;
    height: auto;
    display: block;
}

/* Description */
.description-content {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 15px;
    line-height: 1.6;
    color: #555;
}

/* Ingrédients */
.ingredients-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 0.8rem;
}

.ingredient-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    background: #f8f9fa;
    border-radius: 10px;
}

.ingredient-item i {
    color: #2ecc71;
}

/* Instructions */
.instructions-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.instruction-item {
    display: flex;
    gap: 1rem;
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 10px;
}

.step-number {
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    flex-shrink: 0;
}

.step-content {
    flex: 1;
}

.step-tip {
    background: #fff3e0;
    padding: 0.5rem;
    border-radius: 8px;
    margin-top: 0.5rem;
    font-size: 0.85rem;
    color: #e67e22;
}

.no-instructions {
    text-align: center;
    padding: 2rem;
    background: #f8f9fa;
    border-radius: 10px;
}

.no-instructions i {
    font-size: 2rem;
    color: #ccc;
    margin-bottom: 0.5rem;
}

.btn-add-instruction {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1rem;
    padding: 0.5rem 1rem;
    background: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 8px;
}

/* Sidebar */
.recipe-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.info-card {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 1.5rem;
}

.info-card h3 {
    font-size: 1.1rem;
    margin-bottom: 1rem;
    color: #1a2a3a;
}

.info-card h3 i {
    color: #2ecc71;
    margin-right: 0.5rem;
}

/* Nutrition */
.nutrition-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.nutrition-item {
    text-align: center;
    padding: 0.8rem;
    background: white;
    border-radius: 10px;
}

.nutrition-item span {
    display: block;
    font-size: 0.7rem;
    color: #999;
    margin-bottom: 0.3rem;
}

.nutrition-item strong {
    font-size: 1.1rem;
    color: #2ecc71;
}

.nutrition-item strong small {
    font-size: 0.7rem;
    color: #999;
}

.progress-bar {
    height: 4px;
    background: #e0e0e0;
    border-radius: 2px;
    overflow: hidden;
    margin-top: 0.5rem;
}

.progress {
    height: 100%;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    border-radius: 2px;
}

/* Info list */
.info-list {
    list-style: none;
}

.info-list li {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    padding: 0.8rem 0;
    border-bottom: 1px solid #e0e0e0;
}

.info-list li:last-child {
    border-bottom: none;
}

.info-list li i {
    width: 25px;
    color: #2ecc71;
}

.info-list li div {
    flex: 1;
}

.info-list li strong {
    display: block;
    font-size: 0.7rem;
    color: #999;
    font-weight: normal;
}

/* Quick actions */
.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.quick-action {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    padding: 0.8rem;
    background: white;
    color: #1a2a3a;
    text-decoration: none;
    border-radius: 10px;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    width: 100%;
    text-align: left;
}

.quick-action:hover {
    background: #2ecc71;
    color: white;
    transform: translateX(5px);
}

.quick-action.delete-action:hover {
    background: #e74c3c;
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
@media (max-width: 992px) {
    .recipe-detail-content {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .recipe-detail-header {
        padding: 1.5rem;
    }
    
    .recipe-detail-header h1 {
        font-size: 1.8rem;
    }
    
    .recipe-meta {
        gap: 1rem;
    }
    
    .recipe-detail-content {
        padding: 1.5rem;
    }
    
    .action-buttons {
        flex-wrap: wrap;
    }
    
    .ingredients-list {
        grid-template-columns: 1fr;
    }
    
    .nutrition-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
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

// Animation au chargement
document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('.recipe-section, .info-card');
    sections.forEach((section, index) => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(20px)';
        section.style.transition = `opacity 0.3s ease ${index * 0.1}s, transform 0.3s ease ${index * 0.1}s`;
        
        setTimeout(() => {
            section.style.opacity = '1';
            section.style.transform = 'translateY(0)';
        }, 100);
    });
});
</script>

<?php 
$footerPath = dirname(__DIR__) . '/layout/footer.php';
if(file_exists($footerPath)) {
    include $footerPath;
}
?>