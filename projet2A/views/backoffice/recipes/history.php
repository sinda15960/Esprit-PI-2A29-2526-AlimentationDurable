<?php 
$pageTitle = "Historique des modifications";
$activeMenu = "recipes";
$breadcrumb = [
    ['label' => 'Tableau de bord', 'url' => 'index.php?action=backRecipes'],
    ['label' => 'Recettes', 'url' => 'index.php?action=backRecipes'],
    ['label' => htmlspecialchars($recipe['title']), 'url' => 'index.php?action=backEditRecipe&id=' . $recipe['id']],
    ['label' => 'Historique']
];

$headerPath = dirname(__DIR__) . '/layout/header.php';
if(file_exists($headerPath)) {
    include $headerPath;
}
?>

<div class="history-container">
    <div class="history-header">
        <h1><i class="fas fa-history"></i> Historique des modifications</h1>
        <div class="recipe-info-header">
            <h2><?php echo htmlspecialchars($recipe['title']); ?></h2>
            <a href="index.php?action=backEditRecipe&id=<?php echo $recipe['id']; ?>" class="btn-back">
                <i class="fas fa-arrow-left"></i> Retour à la recette
            </a>
        </div>
    </div>
    
    <?php if(empty($versions)): ?>
        <div class="no-history">
            <i class="fas fa-history"></i>
            <h3>Aucun historique disponible</h3>
            <p>Les modifications seront enregistrées automatiquement lors des prochaines mises à jour.</p>
        </div>
    <?php else: ?>
        <div class="timeline">
            <?php foreach($versions as $index => $version): ?>
                <div class="timeline-item">
                    <div class="timeline-badge">
                        <i class="fas fa-code-branch"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="version-header">
                            <span class="version-number">Version <?php echo $version['version_number']; ?></span>
                            <span class="version-date">
                                <i class="far fa-calendar-alt"></i> 
                                <?php echo date('d/m/Y H:i', strtotime($version['modified_at'])); ?>
                            </span>
                        </div>
                        <div class="version-author">
                            <i class="fas fa-user"></i> Modifié par : <strong><?php echo htmlspecialchars($version['modified_by']); ?></strong>
                        </div>
                        <?php if(!empty($version['change_comment'])): ?>
                            <div class="version-comment">
                                <i class="fas fa-comment"></i> <?php echo htmlspecialchars($version['change_comment']); ?>
                            </div>
                        <?php endif; ?>
                        <div class="version-diff">
                            <details>
                                <summary><i class="fas fa-eye"></i> Voir les détails de la version</summary>
                                <div class="diff-content">
                                    <div class="diff-section">
                                        <h4>Titre</h4>
                                        <p><?php echo htmlspecialchars($version['title']); ?></p>
                                    </div>
                                    <div class="diff-section">
                                        <h4>Description</h4>
                                        <p><?php echo nl2br(htmlspecialchars(substr($version['description'], 0, 200))); ?>...</p>
                                    </div>
                                    <div class="diff-section">
                                        <h4>Ingrédients</h4>
                                        <p><?php echo nl2br(htmlspecialchars(substr($version['ingredients'], 0, 200))); ?>...</p>
                                    </div>
                                    <div class="diff-stats">
                                        <span><i class="fas fa-clock"></i> <?php echo $version['prep_time'] + $version['cook_time']; ?> min</span>
                                        <span><i class="fas fa-fire"></i> <?php echo $version['calories'] ?? 'N/A'; ?> cal</span>
                                        <span><i class="fas fa-chart-line"></i> <?php echo ucfirst($version['difficulty']); ?></span>
                                    </div>
                                </div>
                            </details>
                        </div>
                        <?php if($index > 0): ?>
                            <div class="version-actions">
                                <form method="POST" action="index.php?action=restoreVersion" onsubmit="return confirm('Restaurer cette version ?')">
                                    <input type="hidden" name="recipe_id" value="<?php echo $recipe['id']; ?>">
                                    <input type="hidden" name="version_number" value="<?php echo $version['version_number']; ?>">
                                    <input type="hidden" name="restore_version" value="1">
                                    <button type="submit" class="btn-restore">
                                        <i class="fas fa-undo-alt"></i> Restaurer cette version
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.history-container {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.history-header {
    margin-bottom: 2rem;
}

.history-header h1 {
    font-size: 1.5rem;
    color: #1a2a3a;
    margin-bottom: 1rem;
}

.history-header h1 i {
    color: #9b59b6;
    margin-right: 0.5rem;
}

.recipe-info-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.recipe-info-header h2 {
    font-size: 1.2rem;
    color: #666;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: #95a5a6;
    color: white;
    text-decoration: none;
    border-radius: 8px;
}

.no-history {
    text-align: center;
    padding: 3rem;
    background: #f8f9fa;
    border-radius: 15px;
}

.no-history i {
    font-size: 3rem;
    color: #ccc;
    margin-bottom: 1rem;
}

.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e0e0e0;
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
}

.timeline-badge {
    position: absolute;
    left: -28px;
    top: 0;
    width: 40px;
    height: 40px;
    background: #9b59b6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    z-index: 1;
}

.timeline-content {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1rem;
    margin-left: 1rem;
}

.version-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 0.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e0e0e0;
}

.version-number {
    background: #9b59b6;
    color: white;
    padding: 0.2rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
}

.version-date {
    font-size: 0.75rem;
    color: #999;
}

.version-author {
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
    color: #666;
}

.version-comment {
    background: #fff3cd;
    padding: 0.5rem;
    border-radius: 8px;
    font-size: 0.8rem;
    color: #856404;
    margin-bottom: 0.5rem;
}

.version-diff details {
    margin-top: 0.5rem;
}

.version-diff summary {
    cursor: pointer;
    color: #3498db;
    font-size: 0.85rem;
}

.diff-content {
    margin-top: 0.5rem;
    padding: 0.5rem;
    background: white;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.diff-section {
    margin-bottom: 0.8rem;
}

.diff-section h4 {
    font-size: 0.8rem;
    color: #999;
    margin-bottom: 0.2rem;
}

.diff-section p {
    font-size: 0.85rem;
    color: #333;
}

.diff-stats {
    display: flex;
    gap: 1rem;
    padding-top: 0.5rem;
    border-top: 1px solid #e0e0e0;
    font-size: 0.75rem;
}

.version-actions {
    margin-top: 0.8rem;
    text-align: right;
}

.btn-restore {
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
    border: none;
    padding: 0.4rem 1rem;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.8rem;
}

.btn-restore:hover {
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .history-container {
        padding: 1rem;
    }
    
    .timeline {
        padding-left: 1rem;
    }
    
    .timeline-badge {
        width: 30px;
        height: 30px;
        left: -18px;
    }
    
    .version-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
</style>

<?php 
$footerPath = dirname(__DIR__) . '/layout/footer.php';
if(file_exists($footerPath)) {
    include $footerPath;
}
?>