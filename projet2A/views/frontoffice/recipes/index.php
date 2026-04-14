<?php 
$pageTitle = "Recettes Durables et Intelligentes";
$activePage = "recipes";

// Correction du chemin du header
$headerPath = dirname(__DIR__) . '/layout/header.php';
if(file_exists($headerPath)) {
    include $headerPath;
} else {
    // Fallback si le fichier n'existe pas
    echo "<!-- Header non trouvé: " . $headerPath . " -->";
}
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content" data-aos="fade-up">
        <div class="hero-badge">
            <i class="fas fa-robot"></i> Alimentation Durable & IA
        </div>
        <h1>Recettes <span class="gradient-text">Intelligentes</span><br>pour un Futur Durable</h1>
        <p>Découvrez des recettes alliant nutrition optimale et respect de l'environnement,<br>générées par notre intelligence artificielle.</p>
        
        <div class="hero-stats">
            <div class="stat">
                <div class="stat-number">500+</div>
                <div class="stat-label">Recettes Durables</div>
            </div>
            <div class="stat">
                <div class="stat-number">50k+</div>
                <div class="stat-label">Utilisateurs Actifs</div>
            </div>
            <div class="stat">
                <div class="stat-number">100%</div>
                <div class="stat-label">Sans Gaspillage</div>
            </div>
        </div>
    </div>
    
    <div class="hero-wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Explorer par Catégorie</h2>
            <p>Trouvez la recette parfaite selon vos préférences alimentaires</p>
        </div>
        
        <div class="categories-grid">
            <div class="category-card" data-aos="fade-up" data-aos-delay="0">
                <div class="category-icon">
                    <i class="fas fa-seedling"></i>
                </div>
                <h3>Vegan</h3>
                <p>100% végétal</p>
            </div>
            <div class="category-card" data-aos="fade-up" data-aos-delay="100">
                <div class="category-icon">
                    <i class="fas fa-carrot"></i>
                </div>
                <h3>Végétarien</h3>
                <p>Sans viande</p>
            </div>
            <div class="category-card" data-aos="fade-up" data-aos-delay="200">
                <div class="category-icon">
                    <i class="fas fa-wheat-slash"></i>
                </div>
                <h3>Sans Gluten</h3>
                <p>Sans gluten</p>
            </div>
            <div class="category-card" data-aos="fade-up" data-aos-delay="300">
                <div class="category-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Rapides</h3>
                <p>-30 minutes</p>
            </div>
            <div class="category-card" data-aos="fade-up" data-aos-delay="400">
                <div class="category-icon">
                    <i class="fas fa-fire"></i>
                </div>
                <h3>Healthy</h3>
                <p>-500 calories</p>
            </div>
            <div class="category-card" data-aos="fade-up" data-aos-delay="500">
                <div class="category-icon">
                    <i class="fas fa-euro-sign"></i>
                </div>
                <h3>Économiques</h3>
                <p>Petit budget</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Recipes Section -->
<section class="recipes-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Nos Recettes à l'Honneur</h2>
            <p>Des plats savoureux, nutritifs et respectueux de la planète</p>
        </div>
        
        <?php if(empty($recipes)): ?>
            <div class="no-results" data-aos="fade-up">
                <div class="no-results-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>Aucune recette trouvée</h3>
                <p>Nous n'avons pas encore de recettes disponibles. Revenez bientôt !</p>
            </div>
        <?php else: ?>
            <div class="recipes-grid">
                <?php foreach($recipes as $index => $recipe): ?>
                    <div class="recipe-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 50; ?>">
                        <div class="card-badge">
                            <?php if($recipe['is_vegan']): ?>
                                <span class="badge vegan"><i class="fas fa-seedling"></i> Vegan</span>
                            <?php elseif($recipe['is_vegetarian']): ?>
                                <span class="badge vegetarian"><i class="fas fa-carrot"></i> Végétarien</span>
                            <?php endif; ?>
                            <?php if($recipe['is_gluten_free']): ?>
                                <span class="badge gluten-free"><i class="fas fa-wheat-slash"></i> Sans Gluten</span>
                            <?php endif; ?>
                            <?php if(($recipe['prep_time'] + $recipe['cook_time']) <= 30): ?>
                                <span class="badge quick"><i class="fas fa-bolt"></i> Rapide</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-image">
                            <?php if($recipe['image_url']): ?>
                                <img src="<?php echo $recipe['image_url']; ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                            <?php else: ?>
                                <div class="image-placeholder">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-overlay">
                                <a href="index.php?action=frontShowRecipe&id=<?php echo $recipe['id']; ?>" class="btn-quick-view">
                                    <i class="fas fa-eye"></i> Voir la recette
                                </a>
                            </div>
                        </div>
                        
                        <div class="card-content">
                            <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>
                            <p class="description"><?php echo substr(htmlspecialchars($recipe['description']), 0, 100); ?>...</p>
                            
                            <div class="recipe-meta">
                                <div class="meta-item">
                                    <i class="far fa-clock"></i>
                                    <span><?php echo $recipe['prep_time'] + $recipe['cook_time']; ?> min</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-fire"></i>
                                    <span><?php echo $recipe['calories'] ?? 'N/A'; ?> cal</span>
                                </div>
                                <div class="meta-item difficulty <?php echo $recipe['difficulty']; ?>">
                                    <i class="fas fa-chart-line"></i>
                                    <span><?php echo ucfirst($recipe['difficulty']); ?></span>
                                </div>
                            </div>
                            
                            <div class="nutrition-stats">
                                <div class="stat">
                                    <span>Protéines</span>
                                    <strong><?php echo $recipe['protein'] ?? '0'; ?>g</strong>
                                    <div class="progress-bar">
                                        <div class="progress" style="width: <?php echo min(($recipe['protein'] ?? 0) * 2, 100); ?>%"></div>
                                    </div>
                                </div>
                                <div class="stat">
                                    <span>Glucides</span>
                                    <strong><?php echo $recipe['carbs'] ?? '0'; ?>g</strong>
                                    <div class="progress-bar">
                                        <div class="progress" style="width: <?php echo min(($recipe['carbs'] ?? 0), 100); ?>%"></div>
                                    </div>
                                </div>
                                <div class="stat">
                                    <span>Lipides</span>
                                    <strong><?php echo $recipe['fats'] ?? '0'; ?>g</strong>
                                    <div class="progress-bar">
                                        <div class="progress" style="width: <?php echo min(($recipe['fats'] ?? 0), 100); ?>%"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <a href="index.php?action=frontShowRecipe&id=<?php echo $recipe['id']; ?>" class="btn-view">
                                    Voir la recette <i class="fas fa-arrow-right"></i>
                                </a>
                                <button class="btn-save" onclick="saveRecipe(<?php echo $recipe['id']; ?>)">
                                    <i class="far fa-bookmark"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.hero-stats {
    display: flex;
    justify-content: center;
    gap: 3rem;
    margin-top: 3rem;
    flex-wrap: wrap;
}

.hero-stats .stat {
    text-align: center;
}

.hero-stats .stat-number {
    font-size: 2rem;
    font-weight: 800;
    color: #f093fb;
}

.hero-stats .stat-label {
    font-size: 0.85rem;
    opacity: 0.9;
}

.hero-wave {
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    overflow: hidden;
    line-height: 0;
}

.categories-section {
    padding: 4rem 0;
    background: white;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.category-card {
    background: #f8f9fa;
    padding: 2rem 1rem;
    text-align: center;
    border-radius: 15px;
    cursor: pointer;
    transition: all 0.3s;
}

.category-card:hover {
    transform: translateY(-10px);
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
}

.category-card:hover .category-icon {
    color: white;
}

.category-icon {
    font-size: 2.5rem;
    color: #2ecc71;
    margin-bottom: 1rem;
}

.category-card h3 {
    margin-bottom: 0.5rem;
}

.progress-bar {
    height: 4px;
    background: #e0e0e0;
    border-radius: 2px;
    overflow: hidden;
    margin-top: 5px;
}

.progress {
    height: 100%;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    border-radius: 2px;
}

.card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
}

.btn-save {
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-save:hover {
    background: #2ecc71;
    color: white;
}

.badge.quick {
    background: linear-gradient(135deg, #f39c12, #e67e22);
}

@media (max-width: 768px) {
    .hero-stats {
        gap: 1.5rem;
    }
    
    .hero-stats .stat-number {
        font-size: 1.5rem;
    }
    
    .categories-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.no-results {
    text-align: center;
    padding: 4rem;
    background: #f8f9fa;
    border-radius: 20px;
}

.no-results-icon {
    font-size: 4rem;
    color: #ccc;
    margin-bottom: 1rem;
}
</style>

<script>
function saveRecipe(id) {
    alert('Recette sauvegardée !');
}
</script>

<?php 
// Correction du chemin du footer
$footerPath = dirname(__DIR__) . '/layout/footer.php';
if(file_exists($footerPath)) {
    include $footerPath;
} else {
    echo "<!-- Footer non trouvé: " . $footerPath . " -->";
}
?>