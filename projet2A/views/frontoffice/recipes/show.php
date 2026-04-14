<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($recipe['title']); ?> - NutriFlow AI</title>
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

    <section class="recipe-hero">
        <div class="container">
            <a href="index.php?action=frontRecipes" class="back-link"><i class="fas fa-arrow-left"></i> Retour aux recettes</a>
            <div class="recipe-header">
                <div class="recipe-badges">
                    <?php if($recipe['is_vegan']): ?>
                        <span class="badge vegan"><i class="fas fa-seedling"></i> Vegan</span>
                    <?php endif; ?>
                    <?php if($recipe['is_vegetarian']): ?>
                        <span class="badge vegetarian"><i class="fas fa-carrot"></i> Végétarien</span>
                    <?php endif; ?>
                    <?php if($recipe['is_gluten_free']): ?>
                        <span class="badge gluten-free"><i class="fas fa-wheat-slash"></i> Sans Gluten</span>
                    <?php endif; ?>
                </div>
                <h1><?php echo htmlspecialchars($recipe['title']); ?></h1>
                <div class="recipe-meta-large">
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
                            <span class="difficulty-text <?php echo $recipe['difficulty']; ?>"><?php echo ucfirst($recipe['difficulty']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="recipe-content">
        <div class="container">
            <div class="recipe-grid">
                <div class="recipe-main">
                    <div class="recipe-image">
                        <?php if($recipe['image_url']): ?>
                            <img src="<?php echo $recipe['image_url']; ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                        <?php else: ?>
                            <div class="image-placeholder large">
                                <i class="fas fa-utensils"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="nutrition-card">
                        <h3><i class="fas fa-chart-simple"></i> Valeurs Nutritionnelles</h3>
                        <div class="nutrition-grid">
                            <div class="nutrition-item">
                                <span>Calories</span>
                                <strong><?php echo $recipe['calories'] ?? 'N/A'; ?> kcal</strong>
                            </div>
                            <div class="nutrition-item">
                                <span>Protéines</span>
                                <strong><?php echo $recipe['protein'] ?? '0'; ?> g</strong>
                            </div>
                            <div class="nutrition-item">
                                <span>Glucides</span>
                                <strong><?php echo $recipe['carbs'] ?? '0'; ?> g</strong>
                            </div>
                            <div class="nutrition-item">
                                <span>Lipides</span>
                                <strong><?php echo $recipe['fats'] ?? '0'; ?> g</strong>
                            </div>
                        </div>
                    </div>
                    
                    <div class="ingredients-card">
                        <h3><i class="fas fa-shopping-basket"></i> Ingrédients</h3>
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
                    
                    <div class="instructions-card">
                        <h3><i class="fas fa-list-ol"></i> Instructions</h3>
                        <div class="instructions-list">
                            <?php foreach($instructions as $instruction): ?>
                                <div class="instruction-item">
                                    <div class="step-number"><?php echo $instruction['step_number']; ?></div>
                                    <div class="step-content">
                                        <p><?php echo nl2br(htmlspecialchars($instruction['description'])); ?></p>
                                        <?php if($instruction['tip']): ?>
                                            <div class="step-tip">
                                                <i class="fas fa-lightbulb"></i>
                                                <span>💡 Astuce : <?php echo htmlspecialchars($instruction['tip']); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <div class="recipe-sidebar">
                    <div class="sidebar-card">
                        <h4><i class="fas fa-info-circle"></i> Informations</h4>
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
                    
                    <div class="sidebar-card">
                        <h4><i class="fas fa-share-alt"></i> Partager</h4>
                        <div class="share-buttons">
                            <a href="#" class="share-btn facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="share-btn twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="share-btn pinterest"><i class="fab fa-pinterest"></i></a>
                            <a href="#" class="share-btn email"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; 2024 NutriFlow AI - Tous droits réservés</p>
            </div>
        </div>
    </footer>

    <script src="js/frontoffice.js"></script>
</body>
</html>