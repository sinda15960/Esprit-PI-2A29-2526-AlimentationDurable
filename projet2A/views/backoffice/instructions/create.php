<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une instruction - BackOffice NutriFlow AI</title>
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
                <a href="index.php?action=backRecipes">
                    <i class="fas fa-utensils"></i>
                    <span>Recettes</span>
                </a>
                <a href="index.php?action=backInstructions&id=<?php echo $recipe['id']; ?>" class="active">
                    <i class="fas fa-list-ol"></i>
                    <span>Instructions</span>
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-plus"></i> Ajouter une étape - <?php echo htmlspecialchars($recipe['title']); ?></h1>
                <a href="index.php?action=backInstructions&id=<?php echo $recipe['id']; ?>" class="btn-secondary">
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

            <form method="POST" class="form-container" id="instructionForm">
                <div class="form-sections">
                    <div class="form-section">
                        <h2><i class="fas fa-info-circle"></i> Détails de l'étape</h2>
                        
                        <div class="form-group">
                            <label for="step_number">Numéro de l'étape *</label>
                            <input type="number" id="step_number" name="step_number" required 
                                   value="<?php echo isset($_POST['step_number']) ? $_POST['step_number'] : ''; ?>">
                            <div class="error-message" id="stepNumberError"></div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description *</label>
                            <textarea id="description" name="description" rows="6" required 
                                      placeholder="Décrivez en détail cette étape de préparation..."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                            <div class="error-message" id="descriptionError"></div>
                        </div>

                        <div class="form-group">
                            <label for="tip">Astuce (optionnel)</label>
                            <textarea id="tip" name="tip" rows="3" 
                                      placeholder="Une astuce pour réussir cette étape..."><?php echo isset($_POST['tip']) ? htmlspecialchars($_POST['tip']) : ''; ?></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">Ajouter l'étape</button>
                        <a href="index.php?action=backInstructions&id=<?php echo $recipe['id']; ?>" class="btn-cancel">Annuler</a>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script>
        document.getElementById('instructionForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            const stepNumber = document.getElementById('step_number');
            const stepNumberError = document.getElementById('stepNumberError');
            if(stepNumber && (!stepNumber.value || stepNumber.value <= 0)) {
                stepNumberError.textContent = 'Le numéro de l\'étape doit être un nombre positif';
                isValid = false;
            } else if(stepNumberError) {
                stepNumberError.textContent = '';
            }
            
            const description = document.getElementById('description');
            const descriptionError = document.getElementById('descriptionError');
            if(description && description.value.trim().length < 5) {
                descriptionError.textContent = 'La description doit contenir au moins 5 caractères';
                isValid = false;
            } else if(descriptionError) {
                descriptionError.textContent = '';
            }
            
            if(!isValid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>