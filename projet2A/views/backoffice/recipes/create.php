<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une recette - BackOffice NutriFlow AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/backoffice.css">
</head>
<body>
    <div class="backoffice-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-leaf"></i>
                    <span>NutriFlow AI</span>
                    <small>Administration</small>
                </div>
            </div>
            <nav class="sidebar-nav">
                <a href="index.php?action=backRecipes" class="active">
                    <i class="fas fa-utensils"></i>
                    <span>Recettes</span>
                </a>
                <a href="#">
                    <i class="fas fa-chart-line"></i>
                    <span>Statistiques</span>
                </a>
                <a href="#">
                    <i class="fas fa-users"></i>
                    <span>Utilisateurs</span>
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-plus"></i> Nouvelle Recette</h1>
                <a href="index.php?action=backRecipes" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <?php if(isset($_SESSION['errors'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul>
                        <?php foreach($_SESSION['errors'] as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <form method="POST" class="form-container" id="recipeForm">
                <div class="form-sections">
                    <div class="form-section">
                        <h2><i class="fas fa-info-circle"></i> Informations générales</h2>
                        
                        <div class="form-group">
                            <label for="title">Titre de la recette *</label>
                            <input type="text" id="title" name="title" required 
                                   value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                            <div class="error-message" id="titleError"></div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description *</label>
                            <textarea id="description" name="description" rows="4" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                            <div class="error-message" id="descriptionError"></div>
                        </div>

                        <div class="form-group">
                            <label for="ingredients">Ingrédients * (un par ligne)</label>
                            <textarea id="ingredients" name="ingredients" rows="6" required><?php echo isset($_POST['ingredients']) ? htmlspecialchars($_POST['ingredients']) : ''; ?></textarea>
                            <div class="error-message" id="ingredientsError"></div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="prep_time">Temps de préparation (minutes) *</label>
                                <input type="number" id="prep_time" name="prep_time" required 
                                       value="<?php echo isset($_POST['prep_time']) ? $_POST['prep_time'] : ''; ?>">
                                <div class="error-message" id="prepTimeError"></div>
                            </div>

                            <div class="form-group">
                                <label for="cook_time">Temps de cuisson (minutes) *</label>
                                <input type="number" id="cook_time" name="cook_time" required 
                                       value="<?php echo isset($_POST['cook_time']) ? $_POST['cook_time'] : ''; ?>">
                                <div class="error-message" id="cookTimeError"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="difficulty">Difficulté *</label>
                            <select id="difficulty" name="difficulty" required>
                                <option value="facile" <?php echo (isset($_POST['difficulty']) && $_POST['difficulty'] == 'facile') ? 'selected' : ''; ?>>Facile</option>
                                <option value="moyen" <?php echo (isset($_POST['difficulty']) && $_POST['difficulty'] == 'moyen') ? 'selected' : ''; ?>>Moyen</option>
                                <option value="difficile" <?php echo (isset($_POST['difficulty']) && $_POST['difficulty'] == 'difficile') ? 'selected' : ''; ?>>Difficile</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2><i class="fas fa-chart-simple"></i> Informations nutritionnelles</h2>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="calories">Calories (kcal)</label>
                                <input type="number" id="calories" name="calories" 
                                       value="<?php echo isset($_POST['calories']) ? $_POST['calories'] : ''; ?>">
                            </div>

                            <div class="form-group">
                                <label for="protein">Protéines (g)</label>
                                <input type="number" step="0.01" id="protein" name="protein" 
                                       value="<?php echo isset($_POST['protein']) ? $_POST['protein'] : ''; ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="carbs">Glucides (g)</label>
                                <input type="number" step="0.01" id="carbs" name="carbs" 
                                       value="<?php echo isset($_POST['carbs']) ? $_POST['carbs'] : ''; ?>">
                            </div>

                            <div class="form-group">
                                <label for="fats">Lipides (g)</label>
                                <input type="number" step="0.01" id="fats" name="fats" 
                                       value="<?php echo isset($_POST['fats']) ? $_POST['fats'] : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="image_url">URL de l'image</label>
                            <input type="text" id="image_url" name="image_url" 
                                   value="<?php echo isset($_POST['image_url']) ? htmlspecialchars($_POST['image_url']) : ''; ?>"
                                   placeholder="https://exemple.com/image.jpg">
                        </div>

                        <div class="checkbox-group">
                            <label>
                                <input type="checkbox" name="is_vegan" value="1" <?php echo isset($_POST['is_vegan']) ? 'checked' : ''; ?>>
                                <i class="fas fa-seedling"></i> Vegan
                            </label>
                            <label>
                                <input type="checkbox" name="is_vegetarian" value="1" <?php echo isset($_POST['is_vegetarian']) ? 'checked' : ''; ?>>
                                <i class="fas fa-carrot"></i> Végétarien
                            </label>
                            <label>
                                <input type="checkbox" name="is_gluten_free" value="1" <?php echo isset($_POST['is_gluten_free']) ? 'checked' : ''; ?>>
                                <i class="fas fa-wheat-slash"></i> Sans gluten
                            </label>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2><i class="fas fa-list-ol"></i> Instructions</h2>
                        <div id="instructions-container">
                            <div class="instruction-group">
                                <div class="form-group">
                                    <label>Étape 1</label>
                                    <textarea name="instructions[0][description]" rows="3" placeholder="Description de l'étape..." required></textarea>
                                    <input type="text" name="instructions[0][tip]" placeholder="Astuce (optionnel)">
                                </div>
                            </div>
                        </div>
                        <button type="button" id="addInstruction" class="btn-add">
                            <i class="fas fa-plus"></i> Ajouter une étape
                        </button>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">Créer la recette</button>
                        <a href="index.php?action=backRecipes" class="btn-cancel">Annuler</a>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script src="js/backoffice.js"></script>
</body>
</html>