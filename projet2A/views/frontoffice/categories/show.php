<?php 
$pageTitle = "Recettes - " . htmlspecialchars($categorie['nom']);
$activePage = "categories";
include dirname(__DIR__) . '/layout/header.php'; 
?>

<section class="categorie-recettes-section">
    <div class="container">
        <div class="categorie-header" data-aos="fade-up">
            <div class="categorie-icon" style="background: <?php echo $categorie['couleur']; ?>">
                <i class="<?php echo $categorie['icon']; ?>"></i>
            </div>
            <h1><?php echo htmlspecialchars($categorie['nom']); ?></h1>
            <p><?php echo htmlspecialchars($categorie['description'] ?? 'Découvrez nos meilleures recettes dans cette catégorie'); ?></p>
            <div class="recettes-count">
                <i class="fas fa-utensils"></i>
                <?php echo count($recettes); ?> recette(s)
            </div>
        </div>
        
        <?php if(empty($recettes)): ?>
            <div class="no-recettes" data-aos="fade-up">
                <i class="fas fa-search"></i>
                <h3>Aucune recette dans cette catégorie pour le moment</h3>
                <p>Consultez d'autres catégories ou revenez plus tard</p>
                <a href="index.php?action=searchByCategorie" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Voir toutes les catégories
                </a>
            </div>
        <?php else: ?>
            <div class="recipes-grid">
                <?php foreach($recettes as $recette): ?>
                    <div class="recipe-card" data-aos="fade-up">
                        <div class="card-badge">
                            <?php if($recette['is_vegan']): ?>
                                <span class="badge vegan"><i class="fas fa-seedling"></i> Vegan</span>
                            <?php elseif($recette['is_vegetarian']): ?>
                                <span class="badge vegetarian"><i class="fas fa-carrot"></i> Végétarien</span>
                            <?php endif; ?>
                            <?php if($recette['is_gluten_free']): ?>
                                <span class="badge gluten-free"><i class="fas fa-wheat-slash"></i> Sans gluten</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-image">
                            <?php if($recette['image_url']): ?>
                                <img src="<?php echo $recette['image_url']; ?>" alt="<?php echo htmlspecialchars($recette['title']); ?>">
                            <?php else: ?>
                                <div class="image-placeholder">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-content">
                            <h3><?php echo htmlspecialchars($recette['title']); ?></h3>
                            <p><?php echo substr(htmlspecialchars($recette['description']), 0, 100); ?>...</p>
                            <div class="recipe-meta">
                                <span><i class="far fa-clock"></i> <?php echo $recette['prep_time'] + $recette['cook_time']; ?> min</span>
                                <span><i class="fas fa-fire"></i> <?php echo $recette['calories'] ?? 'N/A'; ?> cal</span>
                            </div>
                            <a href="index.php?action=frontShowRecipe&id=<?php echo $recette['id']; ?>" class="btn-view">
                                Voir la recette <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="back-link">
                <a href="index.php?action=searchByCategorie" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Retour aux catégories
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.categorie-recettes-section {
    padding: 4rem 0;
    background: #f8f9fa;
    min-height: 80vh;
}

.categorie-header {
    text-align: center;
    margin-bottom: 3rem;
}

.categorie-icon {
    width: 80px;
    height: 80px;
    background: #2ecc71;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.categorie-icon i {
    font-size: 2rem;
    color: white;
}

.categorie-header h1 {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    color: #1a2a3a;
}

.recettes-count {
    display: inline-block;
    margin-top: 1rem;
    padding: 0.3rem 1rem;
    background: #e8f5e9;
    color: #2ecc71;
    border-radius: 20px;
    font-size: 0.85rem;
}

.recipes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 2rem;
}

.recipe-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
.badge.gluten-free { background: #3498db; }

.card-image {
    height: 180px;
    overflow: hidden;
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
}

.card-content {
    padding: 1rem;
}

.card-content h3 {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.recipe-meta {
    display: flex;
    gap: 1rem;
    margin: 0.5rem 0;
    font-size: 0.8rem;
    color: #666;
}

.btn-view {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: #2ecc71;
    color: white;
    text-decoration: none;
    border-radius: 20px;
    font-size: 0.8rem;
}

.btn-back {
    display: inline-block;
    margin-top: 2rem;
    padding: 0.5rem 1rem;
    background: #95a5a6;
    color: white;
    text-decoration: none;
    border-radius: 20px;
}

@media (max-width: 768px) {
    .recipes-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>