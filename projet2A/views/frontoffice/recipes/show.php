<?php 
$pageTitle = htmlspecialchars($recipe['title']);
$activePage = "recipes";
include dirname(__DIR__) . '/layout/header.php'; 
?>

<section class="recipe-detail">
    <div class="container">
        <!-- En-tête de la recette -->
        <div class="recipe-header">
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
                    <i class="fas fa-chart-line"></i>
                    <div>
                        <strong>Difficulté</strong>
                        <span class="difficulty <?php echo $recipe['difficulty']; ?>"><?php echo ucfirst($recipe['difficulty']); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="recipe-body">
            <!-- Colonne de gauche -->
            <div class="recipe-left">
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
                    </div>
                </div>
            </div>
            
            <!-- Colonne de droite - Sidebar -->
            <div class="recipe-right">
                <!-- Image -->
                <?php if($recipe['image_url']): ?>
                    <div class="recipe-image">
                        <img src="<?php echo $recipe['image_url']; ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                    </div>
                <?php endif; ?>
                
                <!-- Valeurs nutritionnelles -->
                <div class="sidebar-card">
                    <h3><i class="fas fa-chart-simple"></i> Valeurs Nutritionnelles</h3>
                    <div class="nutrition-grid">
                        <div class="nutrition-item">
                            <span>Calories</span>
                            <strong><?php echo $recipe['calories'] ?? 'N/A'; ?> <small>kcal</small></strong>
                        </div>
                        <div class="nutrition-item">
                            <span>Protéines</span>
                            <strong><?php echo $recipe['protein'] ?? '0'; ?> <small>g</small></strong>
                        </div>
                        <div class="nutrition-item">
                            <span>Glucides</span>
                            <strong><?php echo $recipe['carbs'] ?? '0'; ?> <small>g</small></strong>
                        </div>
                        <div class="nutrition-item">
                            <span>Lipides</span>
                            <strong><?php echo $recipe['fats'] ?? '0'; ?> <small>g</small></strong>
                        </div>
                    </div>
                </div>
                
                <!-- Informations -->
                <div class="sidebar-card">
                    <h3><i class="fas fa-info-circle"></i> Informations</h3>
                    <ul class="info-list">
                        <li>
                            <i class="far fa-calendar-alt"></i>
                            <span>Publiée le <?php echo date('d/m/Y', strtotime($recipe['created_at'])); ?></span>
                        </li>
                        <li>
                            <i class="fas fa-utensils"></i>
                            <span>Type: <?php echo $recipe['is_vegan'] ? 'Vegan' : ($recipe['is_vegetarian'] ? 'Végétarien' : 'Standard'); ?></span>
                        </li>
                    </ul>
                </div>
                
                <!-- Partager -->
                <div class="sidebar-card">
                    <h3><i class="fas fa-share-alt"></i> Partager</h3>
                    <div class="share-buttons">
                        <a href="#" class="share-btn facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="share-btn twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="share-btn pinterest"><i class="fab fa-pinterest"></i></a>
                        <a href="#" class="share-btn email"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="back-link">
            <a href="index.php?action=frontRecipes" class="btn-back">
                <i class="fas fa-arrow-left"></i> Retour aux recettes
            </a>
        </div>
    </div>
</section>

<style>
.recipe-detail {
    padding: 4rem 0;
    background: #f8f9fa;
    min-height: 80vh;
    margin-top: 70px;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* En-tête */
.recipe-header {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.recipe-badges {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.badge {
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    color: white;
}

.badge.vegan { background: #2ecc71; }
.badge.vegetarian { background: #f39c12; }
.badge.gluten-free { background: #3498db; }

.recipe-header h1 {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #1a2a3a;
}

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
    font-size: 1.2rem;
    color: #2ecc71;
}

.meta-item strong {
    display: block;
    font-size: 0.7rem;
    color: #999;
    font-weight: normal;
}

.meta-item span {
    font-size: 0.9rem;
    font-weight: 600;
}

.difficulty.facile { color: #2ecc71; }
.difficulty.moyen { color: #f39c12; }
.difficulty.difficile { color: #e74c3c; }

/* Corps de la recette */
.recipe-body {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

/* Section gauche */
.recipe-section {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.recipe-section h2 {
    font-size: 1.3rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #2ecc71;
    display: inline-block;
}

.recipe-section h2 i {
    color: #2ecc71;
    margin-right: 0.5rem;
}

.description-content {
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

/* Sidebar droite */
.recipe-right {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.recipe-image {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.recipe-image img {
    width: 100%;
    height: auto;
    display: block;
}

.sidebar-card {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.sidebar-card h3 {
    font-size: 1.1rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #2ecc71;
}

.sidebar-card h3 i {
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
    background: #f8f9fa;
    border-radius: 10px;
}

.nutrition-item span {
    display: block;
    font-size: 0.7rem;
    color: #999;
    margin-bottom: 0.3rem;
}

.nutrition-item strong {
    font-size: 1rem;
    color: #2ecc71;
}

.nutrition-item strong small {
    font-size: 0.7rem;
    color: #999;
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

.info-list i {
    width: 25px;
    color: #2ecc71;
}

/* Share buttons */
.share-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.share-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: transform 0.3s;
}

.share-btn:hover {
    transform: translateY(-3px);
}

.share-btn.facebook { background: #3b5998; }
.share-btn.twitter { background: #1da1f2; }
.share-btn.pinterest { background: #bd081c; }
.share-btn.email { background: #666; }

/* Back link */
.back-link {
    margin-top: 2rem;
    text-align: center;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.5rem;
    background: #95a5a6;
    color: white;
    text-decoration: none;
    border-radius: 25px;
    transition: background 0.3s;
}

.btn-back:hover {
    background: #7f8c8d;
}

/* Responsive */
@media (max-width: 992px) {
    .recipe-body {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .container {
        padding: 0 1rem;
    }
    
    .recipe-header h1 {
        font-size: 1.5rem;
    }
    
    .recipe-meta {
        gap: 1rem;
    }
    
    .ingredients-list {
        grid-template-columns: 1fr;
    }
    
    .nutrition-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>