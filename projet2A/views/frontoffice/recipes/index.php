<?php 
$pageTitle = "Recettes Durables et Intelligentes";
$activePage = "recipes";

// NE PAS inclure Categorie.php ici - les catégories doivent être passées par le contrôleur
// Les catégories seront disponibles via la variable $dbCategories passée par le contrôleur

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
        
        <!-- Barre de recherche -->
        <div class="search-container">
            <form action="index.php" method="GET" class="search-form">
                <input type="hidden" name="action" value="searchRecipes">
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" class="search-input" 
                           placeholder="Rechercher une recette..."
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="search-btn">Rechercher</button>
                </div>
            </form>
            
            <div class="categories-row">
                <span class="categories-label">Rechercher par catégorie :</span>
                <div class="categories-tags">
                    <a href="index.php?action=searchRecipes&type=vegan" class="cat-tag cat-vegan">
                        <i class="fas fa-seedling"></i> Vegan
                    </a>
                    <a href="index.php?action=searchRecipes&type=vegetarian" class="cat-tag cat-vegetarian">
                        <i class="fas fa-carrot"></i> Végétarien
                    </a>
                    <a href="index.php?action=searchRecipes&type=gluten_free" class="cat-tag cat-gluten">
                        <i class="fas fa-wheat-slash"></i> Sans Gluten
                    </a>
                    <a href="index.php?action=searchRecipes&type=quick" class="cat-tag cat-quick">
                        <i class="fas fa-bolt"></i> Rapides
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="hero-wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
</section>
    

<!-- Catégories spéciales (filtres rapides) -->
<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2>Filtres Rapides</h2>
            <p>Trouvez rapidement selon vos préférences</p>
        </div>
        
        <div class="categories-grid">
            <div class="category-card" onclick="window.location.href='index.php?action=searchRecipes&type=vegan'">
                <div class="category-icon" style="background: #2ecc71">
                    <i class="fas fa-seedling"></i>
                </div>
                <h3>Vegan</h3>
                <p>100% végétal</p>
            </div>
            
            <div class="category-card" onclick="window.location.href='index.php?action=searchRecipes&type=vegetarian'">
                <div class="category-icon" style="background: #f39c12">
                    <i class="fas fa-carrot"></i>
                </div>
                <h3>Végétarien</h3>
                <p>Sans viande</p>
            </div>
            
            <div class="category-card" onclick="window.location.href='index.php?action=searchRecipes&type=gluten_free'">
                <div class="category-icon" style="background: #3498db">
                    <i class="fas fa-wheat-slash"></i>
                </div>
                <h3>Sans Gluten</h3>
                <p>Sans gluten</p>
            </div>
            
            <div class="category-card" onclick="window.location.href='index.php?action=searchRecipes&type=quick'">
                <div class="category-icon" style="background: #e74c3c">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3>Rapides</h3>
                <p>-30 minutes</p>
            </div>
        </div>
    </div>
</section>

<!-- Catégories personnalisées depuis la BDD -->
<?php if(!empty($dbCategories)): ?>
<section class="custom-categories-section">
    <div class="container">
        <div class="section-header">
            <h2>Catégories</h2>
            <p>Explorez nos recettes par catégorie</p>
        </div>
        
        <div class="categories-grid">
            <?php foreach($dbCategories as $cat): ?>
                <div class="category-card" onclick="window.location.href='index.php?action=frontRecettesByCategorie&id=<?php echo $cat['idCategorie']; ?>'">
                    <div class="category-icon" style="background: <?php echo $cat['couleur']; ?>">
                        <i class="<?php echo $cat['icon']; ?>"></i>
                    </div>
                    <h3><?php echo htmlspecialchars($cat['nom']); ?></h3>
                    <p><?php echo htmlspecialchars($cat['description'] ?? 'Découvrez nos recettes'); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

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

.search-bar {
    max-width: 500px;
    margin: 2rem auto 0;
}

.search-bar form {
    display: flex;
    gap: 0.5rem;
}

.search-bar input {
    flex: 1;
    padding: 0.8rem;
    border: none;
    border-radius: 50px;
    font-size: 1rem;
}

.search-bar button {
    padding: 0.8rem 1.5rem;
    background: #2ecc71;
    color: white;
    border: none;
    border-radius: 50px;
    cursor: pointer;
}

.categories-section, .custom-categories-section {
    padding: 3rem 0;
    background: white;
}

.section-header {
    text-align: center;
    margin-bottom: 2rem;
}

.section-header h2 {
    font-size: 1.8rem;
    color: #1a2a3a;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1.5rem;
    max-width: 900px;
    margin: 0 auto;
}

.category-card {
    background: #f8f9fa;
    padding: 1.5rem;
    text-align: center;
    border-radius: 15px;
    cursor: pointer;
    transition: transform 0.3s;
}

.category-card:hover {
    transform: translateY(-5px);
    background: #2ecc71;
    color: white;
}

.category-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 1rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.category-icon i {
    font-size: 1.5rem;
    color: white;
}

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
}

.card-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    display: flex;
    gap: 0.5rem;
}

.badge {
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.7rem;
    color: white;
}

.badge.vegan { background: #2ecc71; }
.badge.vegetarian { background: #f39c12; }

.card-image {
    height: 160px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
}

.image-placeholder i {
    font-size: 2.5rem;
    color: white;
}

.card-content {
    padding: 1rem;
}

.card-content h3 {
    font-size: 1rem;
    margin-bottom: 0.5rem;
}

.recipe-meta {
    display: flex;
    gap: 1rem;
    margin: 0.5rem 0;
    font-size: 0.7rem;
    color: #666;
}

.btn-view {
    display: inline-block;
    padding: 0.4rem 1rem;
    background: #2ecc71;
    color: white;
    text-decoration: none;
    border-radius: 20px;
    font-size: 0.75rem;
}

.no-results {
    text-align: center;
    padding: 3rem;
    background: white;
    border-radius: 15px;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

@media (max-width: 768px) {
    .categories-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .recipes-grid {
        grid-template-columns: 1fr;
    }
    
    .search-bar form {
        flex-direction: column;
    }
}
</style>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>