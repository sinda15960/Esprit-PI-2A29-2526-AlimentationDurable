<?php 
$pageTitle = "Recettes Durables et Intelligentes";
$activePage = "recipes";

include dirname(__DIR__) . '/layout/header.php'; 
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <div class="hero-badge">
            <i class="fas fa-robot"></i> Alimentation Durable & IA
        </div>
        <h1>Recettes <span class="gradient-text">Intelligentes</span><br>pour un Futur Durable</h1>
        <p>Découvrez des recettes alliant nutrition optimale et respect de l'environnement</p>
        
        <!-- RECHERCHE PAR CATÉGORIE (remplacée) -->
        <div class="search-categorie-container">
            <form method="POST" action="index.php?action=searchByCategorie" class="categorie-search-form-front">
                <div class="search-wrapper-categorie">
                    <i class="fas fa-tags search-icon-categorie"></i>
                    <select name="idCategorie" id="idCategorie" class="categorie-select" required>
                        <option value="">-- Choisissez une catégorie --</option>
                        <?php foreach($dbCategories as $categorie): ?>
                            <option value="<?php echo $categorie['idCategorie']; ?>">
                                <?php echo htmlspecialchars($categorie['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="search-btn-categorie">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="hero-wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
</section>

<!-- FILTRES RAPIDES (affichés en grandes sections) -->
<section class="filters-section">
    <div class="container">
        <div class="section-header">
            <h2>Filtres rapides</h2>
            <p>Trouvez rapidement selon vos préférences alimentaires</p>
        </div>
        
        <div class="filters-grid">
            <div class="filter-card vegan" onclick="window.location.href='index.php?action=searchRecipes&type=vegan'">
                <div class="filter-icon">
                    <i class="fas fa-seedling"></i>
                </div>
                <h3>Vegan</h3>
                <p>100% végétal, sans aucun produit animal</p>
                <span class="filter-count">
                    <?php 
                        $veganCount = 0;
                        foreach($recipes as $r) { if($r['is_vegan']) $veganCount++; }
                        echo $veganCount;
                    ?> recettes
                </span>
            </div>
            
            <div class="filter-card vegetarian" onclick="window.location.href='index.php?action=searchRecipes&type=vegetarian'">
                <div class="filter-icon">
                    <i class="fas fa-carrot"></i>
                </div>
                <h3>Végétarien</h3>
                <p>Sans viande ni poisson</p>
                <span class="filter-count">
                    <?php 
                        $vegCount = 0;
                        foreach($recipes as $r) { if($r['is_vegetarian']) $vegCount++; }
                        echo $vegCount;
                    ?> recettes
                </span>
            </div>
            
            <div class="filter-card gluten-free" onclick="window.location.href='index.php?action=searchRecipes&type=gluten_free'">
                <div class="filter-icon">
                    <i class="fas fa-wheat-slash"></i>
                </div>
                <h3>Sans Gluten</h3>
                <p>Idéal pour les intolérants au gluten</p>
                <span class="filter-count">
                    <?php 
                        $glutenCount = 0;
                        foreach($recipes as $r) { if($r['is_gluten_free']) $glutenCount++; }
                        echo $glutenCount;
                    ?> recettes
                </span>
            </div>
            
            <div class="filter-card quick" onclick="window.location.href='index.php?action=searchRecipes&type=quick'">
                <div class="filter-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3>Rapides</h3>
                <p>Préparation en moins de 30 minutes</p>
                <span class="filter-count">
                    <?php 
                        $quickCount = 0;
                        foreach($recipes as $r) { 
                            if(($r['prep_time'] + $r['cook_time']) <= 30) $quickCount++; 
                        }
                        echo $quickCount;
                    ?> recettes
                </span>
            </div>
        </div>
    </div>
</section>

<!-- Liste des recettes -->
<section class="recipes-section">
    <div class="container">
        <div class="section-header">
            <h2>Nos Recettes</h2>
            <p>Des plats savoureux, nutritifs et respectueux de la planète</p>
        </div>
        
        <?php if(empty($recipes)): ?>
            <div class="no-results">
                <i class="fas fa-search"></i>
                <h3>Aucune recette trouvée</h3>
                <a href="index.php?action=backCreateRecipe" class="btn-create">Ajouter une recette</a>
            </div>
        <?php else: ?>
            <div class="recipes-grid">
                <?php foreach($recipes as $recipe): ?>
                    <div class="recipe-card">
                        <div class="card-badge">
                            <?php if($recipe['is_vegan']): ?>
                                <span class="badge vegan">Vegan</span>
                            <?php endif; ?>
                            <?php if($recipe['is_vegetarian']): ?>
                                <span class="badge vegetarian">Végétarien</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-image">
                            <div class="image-placeholder">
                                <i class="fas fa-utensils"></i>
                            </div>
                        </div>
                        <div class="card-content">
                            <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>
                            <p><?php echo substr(htmlspecialchars($recipe['description']), 0, 100); ?>...</p>
                            <div class="recipe-meta">
                                <span><i class="far fa-clock"></i> <?php echo $recipe['prep_time'] + $recipe['cook_time']; ?> min</span>
                                <span><i class="fas fa-fire"></i> <?php echo $recipe['calories'] ?? 'N/A'; ?> cal</span>
                            </div>
                            <a href="index.php?action=frontShowRecipe&id=<?php echo $recipe['id']; ?>" class="btn-view">
                                Voir la recette <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
/* RECHERCHE PAR CATÉGORIE (NOUVEAU STYLE) */
.search-categorie-container {
    max-width: 600px;
    margin: 2rem auto 0;
    width: 100%;
}

.search-wrapper-categorie {
    position: relative;
    display: flex;
    background: white;
    border-radius: 60px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    transition: all 0.3s;
}

.search-wrapper-categorie:hover {
    box-shadow: 0 15px 40px rgba(0,0,0,0.25);
    transform: translateY(-2px);
}

.search-icon-categorie {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
    font-size: 1.1rem;
    pointer-events: none;
    z-index: 1;
}

.categorie-select {
    flex: 1;
    padding: 16px 20px 16px 50px;
    border: none;
    font-size: 1rem;
    outline: none;
    background: white;
    color: #333;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 20px center;
    background-size: 16px;
}

.search-btn-categorie {
    padding: 0 32px;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
    border: none;
    cursor: pointer;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s;
    white-space: nowrap;
}

.search-btn-categorie:hover {
    background: linear-gradient(135deg, #27ae60, #219a52);
}

/* Section des filtres rapides */
.filters-section {
    padding: 4rem 0;
    background: white;
}

.section-header {
    text-align: center;
    margin-bottom: 3rem;
}

.section-header h2 {
    font-size: 2rem;
    color: #1a2a3a;
    margin-bottom: 0.5rem;
}

.section-header p {
    color: #666;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 2rem;
    max-width: 1000px;
    margin: 0 auto;
}

.filter-card {
    background: #f8f9fa;
    border-radius: 20px;
    padding: 2rem 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.filter-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.filter-card.vegan:hover { background: linear-gradient(135deg, #2ecc71, #27ae60); color: white; }
.filter-card.vegetarian:hover { background: linear-gradient(135deg, #f39c12, #e67e22); color: white; }
.filter-card.gluten-free:hover { background: linear-gradient(135deg, #3498db, #2980b9); color: white; }
.filter-card.quick:hover { background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; }

.filter-card:hover .filter-icon {
    background: rgba(255,255,255,0.2);
}

.filter-card:hover .filter-count {
    background: rgba(255,255,255,0.2);
    color: white;
}

.filter-icon {
    width: 80px;
    height: 80px;
    background: rgba(0,0,0,0.05);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    transition: all 0.3s;
}

.filter-icon i {
    font-size: 2.5rem;
}

.filter-card.vegan .filter-icon i { color: #2ecc71; }
.filter-card.vegetarian .filter-icon i { color: #f39c12; }
.filter-card.gluten-free .filter-icon i { color: #3498db; }
.filter-card.quick .filter-icon i { color: #e74c3c; }

.filter-card:hover .filter-icon i {
    color: white;
}

.filter-card h3 {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.filter-card p {
    font-size: 0.85rem;
    margin-bottom: 1rem;
    opacity: 0.8;
}

.filter-count {
    display: inline-block;
    padding: 0.3rem 1rem;
    background: #e8f5e9;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.3s;
}

.filter-card.vegan .filter-count { background: #d5f5e3; color: #2ecc71; }
.filter-card.vegetarian .filter-count { background: #fef5e7; color: #f39c12; }
.filter-card.gluten-free .filter-count { background: #e8f4f8; color: #3498db; }
.filter-card.quick .filter-count { background: #fdedec; color: #e74c3c; }

/* Hero section */
.hero {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    text-align: center;
    padding: 4rem 2rem;
    margin-top: 70px;
}

.hero-badge {
    display: inline-block;
    background: rgba(255,255,255,0.2);
    padding: 0.5rem 1rem;
    border-radius: 50px;
    margin-bottom: 1rem;
}

.hero h1 {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.gradient-text {
    background: linear-gradient(135deg, #f093fb, #f5576c);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

/* Recipes section */
.recipes-section {
    padding: 3rem 0;
    background: #f8f9fa;
}

.recipes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
}

.recipe-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    position: relative;
    transition: transform 0.3s;
}

.recipe-card:hover {
    transform: translateY(-5px);
}

.card-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    display: flex;
    gap: 0.5rem;
    z-index: 1;
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

.card-image {
    height: 180px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
}

.image-placeholder i {
    font-size: 3rem;
    color: white;
}

.card-content {
    padding: 1.2rem;
}

.card-content h3 {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    color: #1a2a3a;
}

.card-content p {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 0.8rem;
    line-height: 1.4;
}

.recipe-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    font-size: 0.75rem;
    color: #999;
}

.recipe-meta i {
    margin-right: 0.3rem;
}

.btn-view {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.2rem;
    background: #2ecc71;
    color: white;
    text-decoration: none;
    border-radius: 25px;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-view:hover {
    background: #27ae60;
    transform: translateX(3px);
}

.no-results {
    text-align: center;
    padding: 3rem;
    background: white;
    border-radius: 15px;
}

.no-results i {
    font-size: 3rem;
    color: #ccc;
    margin-bottom: 1rem;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .search-wrapper-categorie {
        flex-direction: column;
        border-radius: 30px;
    }
    
    .categorie-select {
        padding: 14px 20px 14px 45px;
        text-align: center;
    }
    
    .search-icon-categorie {
        left: 18px;
    }
    
    .search-btn-categorie {
        padding: 12px;
        border-radius: 0 0 30px 30px;
    }
    
    .filters-grid {
        grid-template-columns: 1fr;
    }
    
    .recipes-grid {
        grid-template-columns: 1fr;
    }
    
    .hero h1 {
        font-size: 1.5rem;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 1rem;
    }
    
    .hero {
        padding: 2rem 1rem;
    }
    
    .filter-card {
        padding: 1.5rem;
    }
    
    .filter-icon {
        width: 60px;
        height: 60px;
    }
    
    .filter-icon i {
        font-size: 1.8rem;
    }
    
    .filter-card h3 {
        font-size: 1.2rem;
    }
}
</style>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>