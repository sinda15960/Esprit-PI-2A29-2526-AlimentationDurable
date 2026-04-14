<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de recherche - NutriFlow AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/frontoffice.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <i class="fas fa-leaf"></i>
                <span>NutriFlow AI</span>
            </div>
            <div class="nav-links">
                <a href="index.php?action=frontRecipes">Recettes</a>
                <a href="#">Nutrition</a>
                <a href="#">Durable</a>
                <a href="#">Contact</a>
                <a href="index.php?action=backRecipes" class="admin-btn"><i class="fas fa-user-shield"></i> Admin</a>
            </div>
        </div>
    </nav>

    <section class="search-results">
        <div class="container">
            <div class="search-header">
                <h1>Résultats de recherche</h1>
                <p><?php echo count($recipes); ?> recette(s) trouvée(s)</p>
                <div class="search-again">
                    <form action="index.php" method="GET">
                        <input type="hidden" name="action" value="searchRecipes">
                        <input type="text" name="search" placeholder="Nouvelle recherche..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                        <button type="submit"><i class="fas fa-search"></i> Rechercher</button>
                    </form>
                </div>
            </div>
            
            <div class="recipes-grid">
                <?php foreach($recipes as $recipe): ?>
                    <div class="recipe-card">
                        <div class="card-badge">
                            <?php if($recipe['is_vegan']): ?>
                                <span class="badge vegan"><i class="fas fa-seedling"></i> Vegan</span>
                            <?php elseif($recipe['is_vegetarian']): ?>
                                <span class="badge vegetarian"><i class="fas fa-carrot"></i> Végétarien</span>
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
                        </div>
                        <div class="card-content">
                            <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>
                            <p><?php echo substr(htmlspecialchars($recipe['description']), 0, 100); ?>...</p>
                            <a href="index.php?action=frontShowRecipe&id=<?php echo $recipe['id']; ?>" class="btn-view">Voir la recette</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <script src="js/frontoffice.js"></script>
</body>
</html>