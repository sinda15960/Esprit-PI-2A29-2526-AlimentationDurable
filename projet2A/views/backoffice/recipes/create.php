<?php 
$pageTitle = "Créer une nouvelle recette";
$activeMenu = "recipes";
$breadcrumb = [
    ['label' => 'Tableau de bord', 'url' => 'index.php?action=backRecipes'],
    ['label' => 'Recettes', 'url' => 'index.php?action=backRecipes'],
    ['label' => 'Créer']
];

// Récupérer les catégories pour le select
require_once dirname(__DIR__) . '/../../models/Categorie.php';
$categorieObj = new Categorie();
$stmtCategories = $categorieObj->readAll();
$categories = [];
while($cat = $stmtCategories->fetch(PDO::FETCH_ASSOC)) {
    $categories[] = $cat;
}

$headerPath = dirname(__DIR__) . '/layout/header.php';
if(file_exists($headerPath)) {
    include $headerPath;
}
?>

<div class="form-container" data-aos="fade-up">
    <form method="POST" id="recipeForm" action="index.php?action=backCreateRecipe" novalidate>
        <div class="form-header">
            <h2><i class="fas fa-plus-circle"></i> Nouvelle recette</h2>
            <p>Créez une nouvelle recette durable et intelligente</p>
        </div>
        
        <!-- Affichage des erreurs globales -->
        <div id="globalErrors" class="global-errors" style="display: none;"></div>
        
        <div class="form-sections">
            <!-- Section 1: Informations générales -->
            <div class="form-section">
                <h3><i class="fas fa-info-circle"></i> Informations générales</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="title">Titre de la recette <span class="required">*</span></label>
                        <input type="text" id="title" name="title" 
                               value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>"
                               placeholder="Ex: Bowl Végétal aux Quinoa">
                        <div class="error-message" id="titleError"></div>
                        <small class="form-hint">Minimum 3 caractères, maximum 100 caractères</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="difficulty">Difficulté <span class="required">*</span></label>
                        <select id="difficulty" name="difficulty">
                            <option value="">Sélectionnez une difficulté</option>
                            <option value="facile" <?php echo (isset($_POST['difficulty']) && $_POST['difficulty'] == 'facile') ? 'selected' : ''; ?>>Facile</option>
                            <option value="moyen" <?php echo (isset($_POST['difficulty']) && $_POST['difficulty'] == 'moyen') ? 'selected' : ''; ?>>Moyen</option>
                            <option value="difficile" <?php echo (isset($_POST['difficulty']) && $_POST['difficulty'] == 'difficile') ? 'selected' : ''; ?>>Difficile</option>
                        </select>
                        <div class="error-message" id="difficultyError"></div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description <span class="required">*</span></label>
                    <textarea id="description" name="description" rows="4" 
                              placeholder="Décrivez brièvement votre recette..."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    <div class="error-message" id="descriptionError"></div>
                    <small class="form-hint">Minimum 20 caractères, maximum 1000 caractères</small>
                    <div class="char-counter"><span id="descCount">0</span>/1000</div>
                </div>
                
                <div class="form-group">
                    <label for="ingredients">Ingrédients <span class="required">*</span></label>
                    <textarea id="ingredients" name="ingredients" rows="6" 
                              placeholder="Listez les ingrédients, un par ligne&#10;Exemple:&#10;200g de quinoa&#10;2 courgettes&#10;1 poivron rouge"><?php echo isset($_POST['ingredients']) ? htmlspecialchars($_POST['ingredients']) : ''; ?></textarea>
                    <div class="error-message" id="ingredientsError"></div>
                    <small class="form-hint">Minimum 10 caractères, un ingrédient par ligne</small>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="prep_time">Temps de préparation <span class="required">*</span></label>
                        <div class="input-with-unit">
                            <input type="number" id="prep_time" name="prep_time" 
                                   value="<?php echo isset($_POST['prep_time']) ? $_POST['prep_time'] : ''; ?>">
                            <span class="unit">minutes</span>
                        </div>
                        <div class="error-message" id="prepTimeError"></div>
                        <small class="form-hint">Entre 1 et 999 minutes</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="cook_time">Temps de cuisson <span class="required">*</span></label>
                        <div class="input-with-unit">
                            <input type="number" id="cook_time" name="cook_time" 
                                   value="<?php echo isset($_POST['cook_time']) ? $_POST['cook_time'] : ''; ?>">
                            <span class="unit">minutes</span>
                        </div>
                        <div class="error-message" id="cookTimeError"></div>
                        <small class="form-hint">Entre 0 et 999 minutes</small>
                    </div>
                </div>
                
                <!-- Sélection de la catégorie -->
                <div class="form-group">
                    <label for="idCategorie">Catégorie</label>
                    <select id="idCategorie" name="idCategorie">
                        <option value="">-- Sélectionnez une catégorie --</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?php echo $cat['idCategorie']; ?>" 
                                    data-icon="<?php echo $cat['icon']; ?>"
                                    style="border-left: 3px solid <?php echo $cat['couleur']; ?>"
                                    <?php echo (isset($_POST['idCategorie']) && $_POST['idCategorie'] == $cat['idCategorie']) ? 'selected' : ''; ?>>
                                <i class="<?php echo $cat['icon']; ?>" style="color: <?php echo $cat['couleur']; ?>"></i>
                                <?php echo htmlspecialchars($cat['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="error-message" id="categorieError"></div>
                    <small class="form-hint">Optionnel - Classez votre recette dans une catégorie</small>
                </div>
                
                <div class="form-group">
                    <label for="image_url">URL de l'image</label>
                    <input type="text" id="image_url" name="image_url" 
                           value="<?php echo isset($_POST['image_url']) ? htmlspecialchars($_POST['image_url']) : ''; ?>"
                           placeholder="https://exemple.com/image.jpg">
                    <div class="error-message" id="imageUrlError"></div>
                    <small class="form-hint">Format: http:// ou https:// (optionnel)</small>
                </div>
                
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_vegan" value="1" <?php echo isset($_POST['is_vegan']) ? 'checked' : ''; ?>>
                        <span class="checkbox-custom"></span>
                        <i class="fas fa-seedling"></i> Vegan
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_vegetarian" value="1" <?php echo isset($_POST['is_vegetarian']) ? 'checked' : ''; ?>>
                        <span class="checkbox-custom"></span>
                        <i class="fas fa-carrot"></i> Végétarien
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_gluten_free" value="1" <?php echo isset($_POST['is_gluten_free']) ? 'checked' : ''; ?>>
                        <span class="checkbox-custom"></span>
                        <i class="fas fa-wheat-slash"></i> Sans gluten
                    </label>
                </div>
            </div>
            
            <!-- Section 2: Informations nutritionnelles -->
            <div class="form-section">
                <h3><i class="fas fa-chart-line"></i> Informations nutritionnelles</h3>
                <p class="section-subtitle">Optionnel mais recommandé pour une meilleure expérience</p>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="calories">Calories</label>
                        <div class="input-with-unit">
                            <input type="number" id="calories" name="calories" 
                                   value="<?php echo isset($_POST['calories']) ? $_POST['calories'] : ''; ?>">
                            <span class="unit">kcal</span>
                        </div>
                        <div class="error-message" id="caloriesError"></div>
                        <small class="form-hint">Entre 0 et 2000 kcal</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="protein">Protéines</label>
                        <div class="input-with-unit">
                            <input type="number" step="0.01" id="protein" name="protein" 
                                   value="<?php echo isset($_POST['protein']) ? $_POST['protein'] : ''; ?>">
                            <span class="unit">g</span>
                        </div>
                        <div class="error-message" id="proteinError"></div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="carbs">Glucides</label>
                        <div class="input-with-unit">
                            <input type="number" step="0.01" id="carbs" name="carbs" 
                                   value="<?php echo isset($_POST['carbs']) ? $_POST['carbs'] : ''; ?>">
                            <span class="unit">g</span>
                        </div>
                        <div class="error-message" id="carbsError"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="fats">Lipides</label>
                        <div class="input-with-unit">
                            <input type="number" step="0.01" id="fats" name="fats" 
                                   value="<?php echo isset($_POST['fats']) ? $_POST['fats'] : ''; ?>">
                            <span class="unit">g</span>
                        </div>
                        <div class="error-message" id="fatsError"></div>
                    </div>
                </div>
            </div>
            
            <!-- Section 3: Instructions -->
            <div class="form-section">
                <h3><i class="fas fa-list-ol"></i> Instructions de préparation</h3>
                <p class="section-subtitle">Ajoutez les étapes de préparation dans l'ordre</p>
                
                <div id="instructions-container">
                    <?php if(isset($_POST['instructions']) && is_array($_POST['instructions'])): ?>
                        <?php foreach($_POST['instructions'] as $index => $instruction): ?>
                            <?php if(!empty($instruction['description'])): ?>
                                <div class="instruction-group">
                                    <div class="instruction-header">
                                        <span class="step-number-badge">Étape <?php echo $index + 1; ?></span>
                                        <button type="button" class="btn-remove-instruction" onclick="this.closest('.instruction-group').remove(); updateStepNumbers();">
                                            <i class="fas fa-trash-alt"></i> Supprimer
                                        </button>
                                    </div>
                                    <div class="form-group">
                                        <textarea name="instructions[<?php echo $index; ?>][description]" rows="3" 
                                                  placeholder="Décrivez cette étape de préparation..." required><?php echo htmlspecialchars($instruction['description']); ?></textarea>
                                        <div class="error-message instruction-error" id="instructionError_<?php echo $index; ?>"></div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="instructions[<?php echo $index; ?>][tip]" 
                                               placeholder="Astuce (optionnel)" 
                                               value="<?php echo isset($instruction['tip']) ? htmlspecialchars($instruction['tip']) : ''; ?>">
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="instruction-group">
                            <div class="instruction-header">
                                <span class="step-number-badge">Étape 1</span>
                            </div>
                            <div class="form-group">
                                <textarea name="instructions[0][description]" rows="3" 
                                          placeholder="Décrivez cette étape de préparation..." required></textarea>
                                <div class="error-message instruction-error" id="instructionError_0"></div>
                            </div>
                            <div class="form-group">
                                <input type="text" name="instructions[0][tip]" 
                                       placeholder="Astuce (optionnel)">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <button type="button" id="addInstruction" class="btn-add">
                    <i class="fas fa-plus"></i> Ajouter une étape
                </button>
                <div class="error-message" id="instructionsError"></div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-save"></i> Créer la recette
                </button>
                <a href="index.php?action=backRecipes" class="btn-cancel">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </div>
    </form>
</div>

<style>
/* Styles du formulaire */
.form-container {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.global-errors {
    background: #f8d7da;
    color: #721c24;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    border: 1px solid #f5c6cb;
}

.global-errors ul {
    margin: 0;
    padding-left: 1.5rem;
}

.form-header {
    text-align: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f0f2f5;
}

.form-header h2 {
    font-size: 1.8rem;
    color: #1a2a3a;
}

.form-header h2 i {
    color: #2ecc71;
    margin-right: 0.5rem;
}

.form-sections {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.form-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 15px;
}

.form-section h3 {
    font-size: 1.3rem;
    margin-bottom: 1.5rem;
    color: #1a2a3a;
}

.form-section h3 i {
    color: #2ecc71;
    margin-right: 0.5rem;
}

.section-subtitle {
    color: #999;
    font-size: 0.85rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e0e0e0;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #1a2a3a;
}

.required {
    color: #e74c3c;
}

.form-group input, 
.form-group select, 
.form-group textarea {
    width: 100%;
    padding: 0.8rem;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-family: inherit;
    font-size: 0.95rem;
    transition: all 0.3s;
}

.form-group input:focus, 
.form-group select:focus, 
.form-group textarea:focus {
    outline: none;
    border-color: #2ecc71;
    box-shadow: 0 0 0 3px rgba(46,204,113,0.1);
}

.form-group input.error, 
.form-group select.error, 
.form-group textarea.error {
    border-color: #e74c3c;
    background-color: #fff5f5;
}

.input-with-unit {
    position: relative;
    display: flex;
    align-items: center;
}

.input-with-unit input {
    flex: 1;
    padding-right: 70px;
}

.input-with-unit .unit {
    position: absolute;
    right: 15px;
    color: #999;
    font-size: 0.85rem;
}

.form-hint {
    display: block;
    font-size: 0.7rem;
    color: #999;
    margin-top: 0.3rem;
}

.error-message {
    color: #e74c3c;
    font-size: 0.75rem;
    margin-top: 0.3rem;
    display: none;
}

.error-message.show {
    display: block;
}

.char-counter {
    text-align: right;
    font-size: 0.7rem;
    color: #999;
    margin-top: 0.3rem;
}

.char-counter.warning {
    color: #f39c12;
}

.char-counter.danger {
    color: #e74c3c;
}

.checkbox-group {
    display: flex;
    gap: 1.5rem;
    margin-top: 1rem;
    flex-wrap: wrap;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    padding: 0.5rem 1rem;
    background: white;
    border-radius: 10px;
    transition: all 0.3s;
}

.checkbox-label:hover {
    background: #e8f5e9;
}

.checkbox-label input {
    display: none;
}

.checkbox-custom {
    width: 20px;
    height: 20px;
    border: 2px solid #ddd;
    border-radius: 5px;
    position: relative;
    transition: all 0.3s;
}

.checkbox-label input:checked + .checkbox-custom {
    background: #2ecc71;
    border-color: #2ecc71;
}

.checkbox-label input:checked + .checkbox-custom::after {
    content: '\f00c';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 10px;
}

.instruction-group {
    background: white;
    padding: 1.5rem;
    border-radius: 15px;
    margin-bottom: 1rem;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    animation: slideIn 0.3s ease;
}

.instruction-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.step-number-badge {
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
    padding: 0.3rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.btn-remove-instruction {
    background: #e74c3c;
    color: white;
    border: none;
    padding: 0.3rem 0.8rem;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.8rem;
    transition: all 0.3s;
}

.btn-remove-instruction:hover {
    background: #c0392b;
    transform: scale(1.05);
}

.btn-add {
    padding: 0.8rem 1.5rem;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 0.95rem;
    font-weight: 600;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-add:hover {
    background: #2980b9;
    transform: translateY(-2px);
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e0e0e0;
}

.btn-submit {
    padding: 0.8rem 2rem;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(46,204,113,0.3);
}

.btn-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.btn-cancel {
    padding: 0.8rem 2rem;
    background: #95a5a6;
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-cancel:hover {
    background: #7f8c8d;
    transform: translateY(-2px);
}

.loading-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid white;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 0.6s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .form-container {
        padding: 1rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .checkbox-group {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-submit, .btn-cancel {
        justify-content: center;
    }
}
</style>

<script>
let instructionCount = document.querySelectorAll('.instruction-group').length;

// Compteur de caractères pour la description
const description = document.getElementById('description');
const descCount = document.getElementById('descCount');

if(description && descCount) {
    description.addEventListener('input', function() {
        const length = this.value.length;
        descCount.textContent = length;
        
        if(length > 900) {
            descCount.className = 'warning';
        } else if(length > 980) {
            descCount.className = 'danger';
        } else {
            descCount.className = '';
        }
    });
    descCount.textContent = description.value.length;
}

// Ajouter une instruction
document.getElementById('addInstruction').addEventListener('click', function() {
    const container = document.getElementById('instructions-container');
    const newInstruction = document.createElement('div');
    newInstruction.className = 'instruction-group';
    newInstruction.innerHTML = `
        <div class="instruction-header">
            <span class="step-number-badge">Étape ${instructionCount + 1}</span>
            <button type="button" class="btn-remove-instruction" onclick="this.closest('.instruction-group').remove(); updateStepNumbers();">
                <i class="fas fa-trash-alt"></i> Supprimer
            </button>
        </div>
        <div class="form-group">
            <textarea name="instructions[${instructionCount}][description]" rows="3" 
                      placeholder="Décrivez cette étape de préparation..." required></textarea>
            <div class="error-message instruction-error" id="instructionError_${instructionCount}"></div>
        </div>
        <div class="form-group">
            <input type="text" name="instructions[${instructionCount}][tip]" 
                   placeholder="Astuce (optionnel)">
        </div>
    `;
    container.appendChild(newInstruction);
    instructionCount++;
    updateStepNumbers();
});

function updateStepNumbers() {
    const groups = document.querySelectorAll('.instruction-group');
    groups.forEach((group, index) => {
        const badge = group.querySelector('.step-number-badge');
        if(badge) {
            badge.textContent = `Étape ${index + 1}`;
        }
        const textarea = group.querySelector('textarea');
        if(textarea) {
            textarea.name = `instructions[${index}][description]`;
        }
        const input = group.querySelector('input[type="text"]');
        if(input) {
            input.name = `instructions[${index}][tip]`;
        }
    });
    instructionCount = groups.length;
}

function isValidUrl(string) {
    try {
        const url = new URL(string);
        return url.protocol === 'http:' || url.protocol === 'https:';
    } catch (_) {
        return false;
    }
}

// Validation du formulaire
document.getElementById('recipeForm').addEventListener('submit', function(e) {
    let isValid = true;
    const errors = [];
    
    // Validation du titre
    const title = document.getElementById('title');
    const titleError = document.getElementById('titleError');
    const titleValue = title.value.trim();
    
    if(titleValue.length < 3) {
        titleError.textContent = 'Le titre doit contenir au moins 3 caractères';
        titleError.classList.add('show');
        title.classList.add('error');
        isValid = false;
        errors.push('Le titre est trop court (minimum 3 caractères)');
    } else if(titleValue.length > 100) {
        titleError.textContent = 'Le titre ne peut pas dépasser 100 caractères';
        titleError.classList.add('show');
        title.classList.add('error');
        isValid = false;
        errors.push('Le titre est trop long (maximum 100 caractères)');
    } else {
        titleError.classList.remove('show');
        title.classList.remove('error');
    }
    
    // Validation de la description
    const description = document.getElementById('description');
    const descriptionError = document.getElementById('descriptionError');
    const descriptionValue = description.value.trim();
    
    if(descriptionValue.length < 20) {
        descriptionError.textContent = 'La description doit contenir au moins 20 caractères';
        descriptionError.classList.add('show');
        description.classList.add('error');
        isValid = false;
        errors.push('La description est trop courte (minimum 20 caractères)');
    } else if(descriptionValue.length > 1000) {
        descriptionError.textContent = 'La description ne peut pas dépasser 1000 caractères';
        descriptionError.classList.add('show');
        description.classList.add('error');
        isValid = false;
        errors.push('La description est trop longue (maximum 1000 caractères)');
    } else {
        descriptionError.classList.remove('show');
        description.classList.remove('error');
    }
    
    // Validation des ingrédients
    const ingredients = document.getElementById('ingredients');
    const ingredientsError = document.getElementById('ingredientsError');
    const ingredientsValue = ingredients.value.trim();
    
    if(ingredientsValue.length < 10) {
        ingredientsError.textContent = 'La liste des ingrédients doit contenir au moins 10 caractères';
        ingredientsError.classList.add('show');
        ingredients.classList.add('error');
        isValid = false;
        errors.push('La liste des ingrédients est trop courte');
    } else {
        ingredientsError.classList.remove('show');
        ingredients.classList.remove('error');
    }
    
    // Validation de la difficulté
    const difficulty = document.getElementById('difficulty');
    const difficultyError = document.getElementById('difficultyError');
    
    if(!difficulty.value) {
        difficultyError.textContent = 'Veuillez sélectionner une difficulté';
        difficultyError.classList.add('show');
        difficulty.classList.add('error');
        isValid = false;
        errors.push('La difficulté n\'est pas sélectionnée');
    } else {
        difficultyError.classList.remove('show');
        difficulty.classList.remove('error');
    }
    
    // Validation du temps de préparation
    const prepTime = document.getElementById('prep_time');
    const prepTimeError = document.getElementById('prepTimeError');
    const prepTimeValue = parseInt(prepTime.value);
    
    if(isNaN(prepTimeValue) || prepTimeValue < 1) {
        prepTimeError.textContent = 'Le temps de préparation doit être un nombre positif (minimum 1 minute)';
        prepTimeError.classList.add('show');
        prepTime.classList.add('error');
        isValid = false;
        errors.push('Le temps de préparation n\'est pas valide');
    } else if(prepTimeValue > 999) {
        prepTimeError.textContent = 'Le temps de préparation ne peut pas dépasser 999 minutes';
        prepTimeError.classList.add('show');
        prepTime.classList.add('error');
        isValid = false;
        errors.push('Le temps de préparation est trop élevé');
    } else {
        prepTimeError.classList.remove('show');
        prepTime.classList.remove('error');
    }
    
    // Validation du temps de cuisson
    const cookTime = document.getElementById('cook_time');
    const cookTimeError = document.getElementById('cookTimeError');
    const cookTimeValue = parseInt(cookTime.value);
    
    if(isNaN(cookTimeValue) || cookTimeValue < 0) {
        cookTimeError.textContent = 'Le temps de cuisson doit être un nombre positif ou zéro';
        cookTimeError.classList.add('show');
        cookTime.classList.add('error');
        isValid = false;
        errors.push('Le temps de cuisson n\'est pas valide');
    } else if(cookTimeValue > 999) {
        cookTimeError.textContent = 'Le temps de cuisson ne peut pas dépasser 999 minutes';
        cookTimeError.classList.add('show');
        cookTime.classList.add('error');
        isValid = false;
        errors.push('Le temps de cuisson est trop élevé');
    } else {
        cookTimeError.classList.remove('show');
        cookTime.classList.remove('error');
    }
    
    // Validation des calories
    const calories = document.getElementById('calories');
    const caloriesError = document.getElementById('caloriesError');
    
    if(calories.value) {
        const caloriesValue = parseInt(calories.value);
        if(isNaN(caloriesValue) || caloriesValue < 0) {
            caloriesError.textContent = 'Les calories doivent être un nombre positif';
            caloriesError.classList.add('show');
            calories.classList.add('error');
            isValid = false;
            errors.push('La valeur des calories n\'est pas valide');
        } else if(caloriesValue > 2000) {
            caloriesError.textContent = 'Les calories ne peuvent pas dépasser 2000 kcal';
            caloriesError.classList.add('show');
            calories.classList.add('error');
            isValid = false;
            errors.push('La valeur des calories est trop élevée');
        } else {
            caloriesError.classList.remove('show');
            calories.classList.remove('error');
        }
    }
    
    // Validation de l'URL de l'image
    const imageUrl = document.getElementById('image_url');
    const imageUrlError = document.getElementById('imageUrlError');
    
    if(imageUrl.value && !isValidUrl(imageUrl.value)) {
        imageUrlError.textContent = 'Veuillez entrer une URL valide (http:// ou https://)';
        imageUrlError.classList.add('show');
        imageUrl.classList.add('error');
        isValid = false;
        errors.push('L\'URL de l\'image n\'est pas valide');
    } else {
        imageUrlError.classList.remove('show');
        imageUrl.classList.remove('error');
    }
    
    // Validation des instructions
    const instructionGroups = document.querySelectorAll('.instruction-group');
    let hasValidInstruction = false;
    
    instructionGroups.forEach((group, index) => {
        const textarea = group.querySelector('textarea');
        const errorDiv = group.querySelector('.instruction-error');
        
        if(textarea && textarea.value.trim().length > 0) {
            hasValidInstruction = true;
            if(textarea.value.trim().length < 5) {
                if(errorDiv) {
                    errorDiv.textContent = 'Chaque étape doit contenir au moins 5 caractères';
                    errorDiv.classList.add('show');
                }
                isValid = false;
                errors.push(`L'étape ${index + 1} est trop courte`);
            } else if(errorDiv) {
                errorDiv.classList.remove('show');
            }
        }
    });
    
    const instructionsError = document.getElementById('instructionsError');
    if(!hasValidInstruction) {
        instructionsError.textContent = 'Veuillez ajouter au moins une instruction valide';
        instructionsError.classList.add('show');
        isValid = false;
        errors.push('Aucune instruction valide n\'a été ajoutée');
    } else {
        instructionsError.classList.remove('show');
    }
    
    // Affichage des erreurs globales
    const globalErrors = document.getElementById('globalErrors');
    if(!isValid && errors.length > 0) {
        globalErrors.innerHTML = '<ul>' + errors.map(e => `<li>${e}</li>`).join('') + '</ul>';
        globalErrors.style.display = 'block';
        e.preventDefault();
        
        const firstError = document.querySelector('.error-message.show');
        if(firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    } else {
        globalErrors.style.display = 'none';
        
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="loading-spinner"></span> Création en cours...';
    }
});

// Raccourci clavier Ctrl+Entrée
document.addEventListener('keydown', function(e) {
    if(e.ctrlKey && e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('recipeForm').dispatchEvent(new Event('submit'));
    }
});
</script>

<?php 
$footerPath = dirname(__DIR__) . '/layout/footer.php';
if(file_exists($footerPath)) {
    include $footerPath;
}
?>