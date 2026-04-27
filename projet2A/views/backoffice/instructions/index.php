<?php 
$pageTitle = "Gestion des instructions";
$activeMenu = "recipes";
$breadcrumb = [
    ['label' => 'Tableau de bord', 'url' => 'index.php?action=backRecipes'],
    ['label' => 'Recettes', 'url' => 'index.php?action=backRecipes'],
    ['label' => 'Instructions']
];

$headerPath = dirname(__DIR__) . '/../layout/header.php';
if(file_exists($headerPath)) {
    include $headerPath;
}
?>

<div class="instructions-container">
    <div class="top-bar-instructions">
        <h1><i class="fas fa-list-ol"></i> Gestion des instructions</h1>
        <div class="header-buttons">
            <a href="index.php?action=backCreateInstruction&id=<?php echo $recipe_id; ?>" class="btn-create">
                <i class="fas fa-plus"></i> Ajouter une instruction
            </a>
            <a href="index.php?action=backEditRecipe&id=<?php echo $recipe_id; ?>" class="btn-back">
                <i class="fas fa-arrow-left"></i> Retour à la recette
            </a>
        </div>
    </div>
    
    <div class="recipe-info">
        <h2><i class="fas fa-utensils"></i> <?php echo htmlspecialchars($recipe['title']); ?></h2>
        <p><?php echo htmlspecialchars(substr($recipe['description'], 0, 150)); ?>...</p>
    </div>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <?php if(empty($instructions)): ?>
        <div class="no-instructions">
            <i class="fas fa-info-circle"></i>
            <h3>Aucune instruction pour cette recette</h3>
            <p>Ajoutez une première instruction pour guider la préparation</p>
            <a href="index.php?action=backCreateInstruction&id=<?php echo $recipe_id; ?>" class="btn-create">
                <i class="fas fa-plus"></i> Ajouter une instruction
            </a>
        </div>
    <?php else: ?>
        <div class="instructions-list">
            <?php foreach($instructions as $instruction): ?>
                <div class="instruction-card">
                    <div class="instruction-header">
                        <div class="step-number">Étape <?php echo $instruction['step_number']; ?></div>
                        <div class="instruction-actions">
                            <a href="index.php?action=backEditInstruction&id=<?php echo $instruction['id']; ?>" class="btn-action edit" title="Modifier">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <form method="POST" action="index.php?action=backDeleteInstruction&id=<?php echo $instruction['id']; ?>" style="display: inline;" onsubmit="return confirm('Supprimer cette instruction ?')">
                                <button type="submit" class="btn-action delete" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="instruction-description">
                        <p><?php echo nl2br(htmlspecialchars($instruction['description'])); ?></p>
                    </div>
                    <?php if(!empty($instruction['tip'])): ?>
                        <div class="instruction-tip">
                            <i class="fas fa-lightbulb"></i>
                            <span>Astuce : <?php echo htmlspecialchars($instruction['tip']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.instructions-container {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.top-bar-instructions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.top-bar-instructions h1 {
    font-size: 1.5rem;
    color: #1a2a3a;
    margin: 0;
}

.top-bar-instructions h1 i {
    color: #2ecc71;
    margin-right: 0.5rem;
}

.header-buttons {
    display: flex;
    gap: 1rem;
}

.btn-create {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.2rem;
    background: #2ecc71;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
}

.btn-create:hover {
    background: #27ae60;
    transform: translateY(-2px);
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.2rem;
    background: #7f8c8d;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-back:hover {
    background: #6c7a7a;
    transform: translateY(-2px);
}

.recipe-info {
    background: #f0f7ff;
    padding: 1.2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    border-left: 4px solid #2ecc71;
}

.recipe-info h2 {
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
    color: #1a2a3a;
}

.recipe-info h2 i {
    color: #2ecc71;
    margin-right: 0.5rem;
}

.recipe-info p {
    color: #555;
    font-size: 0.9rem;
}

/* Alert styles - plus clairs */
.alert {
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.no-instructions {
    text-align: center;
    padding: 3rem;
    background: #f8f9fa;
    border-radius: 15px;
}

.no-instructions i {
    font-size: 3rem;
    color: #ccc;
    margin-bottom: 1rem;
}

.no-instructions h3 {
    margin-bottom: 0.5rem;
    color: #1a2a3a;
}

.no-instructions p {
    color: #666;
    margin-bottom: 1.5rem;
}

.instructions-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.instruction-card {
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 15px;
    padding: 1.5rem;
    transition: all 0.3s;
}

.instruction-card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border-color: #2ecc71;
}

.instruction-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.8rem;
    border-bottom: 2px solid #2ecc71;
    flex-wrap: wrap;
    gap: 1rem;
}

.step-number {
    background: #2ecc71;
    color: white;
    padding: 0.3rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.instruction-actions {
    display: flex;
    gap: 0.8rem;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    font-size: 0.8rem;
    font-weight: 500;
}

.btn-action.edit {
    background: #f39c12;
    color: white;
}

.btn-action.edit:hover {
    background: #e67e22;
    transform: scale(1.02);
}

.btn-action.delete {
    background: #e74c3c;
    color: white;
}

.btn-action.delete:hover {
    background: #c0392b;
    transform: scale(1.02);
}

.instruction-description {
    line-height: 1.6;
    color: #333;
    margin-bottom: 1rem;
    background: #fafafa;
    padding: 1rem;
    border-radius: 10px;
}

.instruction-tip {
    background: #fff8e1;
    padding: 0.8rem;
    border-radius: 10px;
    font-size: 0.85rem;
    color: #e65100;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border-left: 3px solid #ff9800;
}

.instruction-tip i {
    color: #ff9800;
    font-size: 1rem;
}

@media (max-width: 768px) {
    .instructions-container {
        padding: 1rem;
    }
    
    .top-bar-instructions {
        flex-direction: column;
        text-align: center;
    }
    
    .header-buttons {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-create, .btn-back {
        justify-content: center;
    }
    
    .instruction-header {
        flex-direction: column;
        text-align: center;
    }
    
    .instruction-actions {
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