<?php 
$pageTitle = "Ajouter une instruction";
$activeMenu = "recipes";
$breadcrumb = [
    ['label' => 'Tableau de bord', 'url' => 'index.php?action=backRecipes'],
    ['label' => 'Recettes', 'url' => 'index.php?action=backRecipes'],
    ['label' => 'Instructions', 'url' => 'index.php?action=backInstructions&id=' . $recipe_id],
    ['label' => 'Ajouter']
];

$headerPath = dirname(__DIR__) . '/../layout/header.php';
if(file_exists($headerPath)) {
    include $headerPath;
}
?>

<div class="form-container">
    <form method="POST" class="instruction-form">
        <div class="form-header">
            <h2><i class="fas fa-plus-circle"></i> Ajouter une instruction</h2>
            <p>Pour la recette : <strong><?php echo htmlspecialchars($recipe['title']); ?></strong></p>
        </div>
        
        <div class="form-group">
            <label for="step_number">Numéro de l'étape <span class="required">*</span></label>
            <input type="number" id="step_number" name="step_number" 
                   value="<?php echo isset($_POST['step_number']) ? $_POST['step_number'] : ''; ?>"
                   min="1" required>
            <small class="form-hint">Exemple : 1, 2, 3...</small>
        </div>
        
        <div class="form-group">
            <label for="description">Description de l'étape <span class="required">*</span></label>
            <textarea id="description" name="description" rows="5" 
                      placeholder="Décrivez cette étape de préparation..." required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
            <small class="form-hint">Minimum 5 caractères</small>
        </div>
        
        <div class="form-group">
            <label for="tip">Astuce (optionnel)</label>
            <input type="text" id="tip" name="tip" 
                   value="<?php echo isset($_POST['tip']) ? htmlspecialchars($_POST['tip']) : ''; ?>"
                   placeholder="Ex: Ajoutez une pincée de sel pour plus de saveur">
            <small class="form-hint">Une astuce pour réussir cette étape</small>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Ajouter
            </button>
            <a href="index.php?action=backInstructions&id=<?php echo $recipe_id; ?>" class="btn-cancel">
                <i class="fas fa-times"></i> Annuler
            </a>
        </div>
    </form>
</div>

<style>
.form-container {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    max-width: 800px;
    margin: 0 auto;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.form-header {
    text-align: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f0f2f5;
}

.form-header h2 {
    font-size: 1.5rem;
    color: #1a2a3a;
}

.form-header h2 i {
    color: #2ecc71;
    margin-right: 0.5rem;
}

.form-header p {
    color: #666;
    margin-top: 0.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
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
.form-group textarea:focus {
    outline: none;
    border-color: #2ecc71;
    box-shadow: 0 0 0 3px rgba(46,204,113,0.1);
}

.form-hint {
    display: block;
    font-size: 0.7rem;
    color: #999;
    margin-top: 0.3rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
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

@media (max-width: 768px) {
    .form-container {
        padding: 1rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-submit, .btn-cancel {
        justify-content: center;
    }
}
</style>

<?php 
$footerPath = dirname(__DIR__) . '/../layout/footer.php';
if(file_exists($footerPath)) {
    include $footerPath;
}
?>