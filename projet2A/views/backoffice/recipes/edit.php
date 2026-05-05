<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la recette - BackOffice NutriFlow AI</title>
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
                <h1><i class="fas fa-edit"></i> Modifier la recette</h1>
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

            <form method="POST" class="form-container" id="recipeForm" enctype="multipart/form-data">
                <div class="form-sections">
                    <div class="form-section">
                        <h2><i class="fas fa-info-circle"></i> Informations générales</h2>
                        
                        <div class="form-group">
                            <label for="title">Titre de la recette *</label>
                            <input type="text" id="title" name="title" required 
                                   value="<?php echo htmlspecialchars($recipe['title']); ?>">
                            <div class="error-message" id="titleError"></div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description *</label>
                            <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($recipe['description']); ?></textarea>
                            <div class="error-message" id="descriptionError"></div>
                        </div>

                        <div class="form-group">
                            <label for="ingredients">Ingrédients * (un par ligne)</label>
                            <textarea id="ingredients" name="ingredients" rows="6" required><?php echo htmlspecialchars($recipe['ingredients']); ?></textarea>
                            <div class="error-message" id="ingredientsError"></div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="prep_time">Temps de préparation (minutes) *</label>
                                <input type="number" id="prep_time" name="prep_time" required 
                                       value="<?php echo $recipe['prep_time']; ?>">
                                <div class="error-message" id="prepTimeError"></div>
                            </div>

                            <div class="form-group">
                                <label for="cook_time">Temps de cuisson (minutes) *</label>
                                <input type="number" id="cook_time" name="cook_time" required 
                                       value="<?php echo $recipe['cook_time']; ?>">
                                <div class="error-message" id="cookTimeError"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="difficulty">Difficulté *</label>
                            <select id="difficulty" name="difficulty" required>
                                <option value="facile" <?php echo $recipe['difficulty'] == 'facile' ? 'selected' : ''; ?>>Facile</option>
                                <option value="moyen" <?php echo $recipe['difficulty'] == 'moyen' ? 'selected' : ''; ?>>Moyen</option>
                                <option value="difficile" <?php echo $recipe['difficulty'] == 'difficile' ? 'selected' : ''; ?>>Difficile</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2><i class="fas fa-chart-simple"></i> Informations nutritionnelles</h2>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="calories">Calories (kcal)</label>
                                <input type="number" id="calories" name="calories" 
                                       value="<?php echo $recipe['calories']; ?>">
                            </div>

                            <div class="form-group">
                                <label for="protein">Protéines (g)</label>
                                <input type="number" step="0.01" id="protein" name="protein" 
                                       value="<?php echo $recipe['protein']; ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="carbs">Glucides (g)</label>
                                <input type="number" step="0.01" id="carbs" name="carbs" 
                                       value="<?php echo $recipe['carbs']; ?>">
                            </div>

                            <div class="form-group">
                                <label for="fats">Lipides (g)</label>
                                <input type="number" step="0.01" id="fats" name="fats" 
                                       value="<?php echo $recipe['fats']; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="image">Image de la recette</label>
                            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" class="file-input">
                            <?php if(!empty($recipe['image_url'])): ?>
                                <div class="current-image">
                                    <p>Image actuelle :</p>
                                    <img src="<?php echo $recipe['image_url']; ?>" alt="Image actuelle" style="max-width: 150px; border-radius: 10px;">
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="delete_image" value="1">
                                        <span class="checkbox-custom"></span>
                                        Supprimer l'image actuelle
                                    </label>
                                </div>
                            <?php endif; ?>
                            <small class="form-hint">Formats acceptés : JPG, PNG, GIF, WEBP (max 2MB)</small>
                            <div class="error-message" id="imageError"></div>
                        </div>

                        <div class="checkbox-group">
                            <label>
                                <input type="checkbox" name="is_vegan" value="1" <?php echo $recipe['is_vegan'] ? 'checked' : ''; ?>>
                                <i class="fas fa-seedling"></i> Vegan
                            </label>
                            <label>
                                <input type="checkbox" name="is_vegetarian" value="1" <?php echo $recipe['is_vegetarian'] ? 'checked' : ''; ?>>
                                <i class="fas fa-carrot"></i> Végétarien
                            </label>
                            <label>
                                <input type="checkbox" name="is_gluten_free" value="1" <?php echo $recipe['is_gluten_free'] ? 'checked' : ''; ?>>
                                <i class="fas fa-wheat-slash"></i> Sans gluten
                            </label>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2><i class="fas fa-list-ol"></i> Instructions</h2>
                        <div id="instructions-container">
                            <?php foreach($existingInstructions as $index => $instruction): ?>
                                <div class="instruction-group">
                                    <div class="form-group">
                                        <label>Étape <?php echo $instruction['step_number']; ?></label>
                                        <textarea name="instructions[<?php echo $index; ?>][description]" rows="3" placeholder="Description de l'étape..." required><?php echo htmlspecialchars($instruction['description']); ?></textarea>
                                        <input type="text" name="instructions[<?php echo $index; ?>][tip]" placeholder="Astuce (optionnel)" value="<?php echo htmlspecialchars($instruction['tip']); ?>">
                                        <button type="button" class="btn-remove-instruction" onclick="this.closest('.instruction-group').remove()">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" id="addInstruction" class="btn-add">
                            <i class="fas fa-plus"></i> Ajouter une étape
                        </button>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">Mettre à jour</button>
                        <a href="index.php?action=backRecipes" class="btn-cancel">Annuler</a>
                    </div>
                </div>
            </form>
        </main>
    </div>

    
</body>
</html>