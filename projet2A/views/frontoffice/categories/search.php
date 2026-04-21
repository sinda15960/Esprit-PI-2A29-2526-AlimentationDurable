<?php 
$pageTitle = "Rechercher par catégorie";
$activePage = "categories";
include dirname(__DIR__) . '/layout/header.php'; 
?>

<section class="search-categorie-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h1>Rechercher des recettes par catégorie</h1>
            <p>Trouvez facilement des recettes selon vos préférences</p>
        </div>
        
        <div class="search-form-container" data-aos="fade-up">
            <form method="POST" action="" class="categorie-search-form">
                <div class="form-group">
                    <label for="idCategorie">Sélectionnez une catégorie :</label>
                    <select name="idCategorie" id="idCategorie" required>
                        <option value="">-- Choisissez une catégorie --</option>
                        <?php foreach($categories as $categorie): ?>
                            <option value="<?php echo $categorie['idCategorie']; ?>" 
                                    data-icon="<?php echo $categorie['icon']; ?>"
                                    style="color: <?php echo $categorie['couleur']; ?>">
                                <i class="<?php echo $categorie['icon']; ?>"></i>
                                <?php echo htmlspecialchars($categorie['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="search" class="btn-search">
                    <i class="fas fa-search"></i> Rechercher
                </button>
            </form>
        </div>
        
        <?php if(isset($_POST['search']) && !empty($recettes)): ?>
            <div class="results-container" data-aos="fade-up">
                <h2>Albums correspondants au genre sélectionné :</h2>
                <div class="recipes-grid">
                    <?php foreach($recettes as $recette): ?>
                        <div class="recipe-card">
                            <div class="card-badge">
                                <?php if($recette['is_vegan']): ?>
                                    <span class="badge vegan">Vegan</span>
                                <?php endif; ?>
                            </div>
                            <div class="card-image">
                                <?php if($recette['image_url']): ?>
                                    <img src="<?php echo $recette['image_url']; ?>" alt="">
                                <?php else: ?>
                                    <div class="image-placeholder">
                                        <i class="fas fa-utensils"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-content">
                                <h3><?php echo htmlspecialchars($recette['title']); ?></h3>
                                <p><?php echo substr($recette['description'], 0, 100); ?>...</p>
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
            </div>
        <?php elseif(isset($_POST['search']) && empty($recettes)): ?>
            <div class="no-results" data-aos="fade-up">
                <i class="fas fa-search"></i>
                <h3>Aucune recette trouvée dans cette catégorie</h3>
                <p>Essayez une autre catégorie</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.search-categorie-section {
    padding: 4rem 0;
    min-height: 80vh;
}

.search-form-container {
    max-width: 500px;
    margin: 0 auto 3rem;
    background: white;
    padding: 2rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.categorie-search-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.categorie-search-form label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: block;
}

.categorie-search-form select {
    width: 100%;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 1rem;
}

.btn-search {
    padding: 12px;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.3s;
}

.btn-search:hover {
    transform: translateY(-2px);
}

.results-container h2 {
    margin-bottom: 2rem;
    text-align: center;
}

.recipes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
}
</style>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>