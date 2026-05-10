<?php
/**
 * Raccourcis vers les modules du dépôt (hors projet2A) depuis le profil utilisateur.
 */
$nfRepoRoot = '../..';
$nfRecipesFo = 'index.php';
$nfFrigo = $nfRepoRoot . '/frigo/index.php';
$nfPlan = $nfRepoRoot . '/gestion_plan/login.php';
?>
<!-- Features Buttons Section -->
<div class="features-buttons-grid">
    <!-- Donations — tableau de bord dons (ancre #donations) -->
    <a class="feature-btn donations" href="<?php echo htmlspecialchars($nfRepoRoot); ?>/dashboard.php#donations" target="_blank" rel="noopener noreferrer">
        <span class="feature-icon">💝</span>
        <span class="feature-name">Donations</span>
        <span class="feature-arrow">→</span>
    </a>

    <!-- Recipes — front-office recettes -->
    <a class="feature-btn recipes" href="<?php echo htmlspecialchars($nfRecipesFo); ?>?action=frontRecipes" target="_blank" rel="noopener noreferrer">
        <span class="feature-icon">🍽️</span>
        <span class="feature-name">Recipes</span>
        <span class="feature-arrow">→</span>
    </a>

    <!-- Marketplace — module frigo -->
    <a class="feature-btn marketplace" href="<?php echo htmlspecialchars($nfFrigo); ?>" target="_blank" rel="noopener noreferrer">
        <span class="feature-icon">🛒</span>
        <span class="feature-name">Marketplace</span>
        <span class="feature-arrow">→</span>
    </a>

    <!-- Meal Plans -->
    <a class="feature-btn plans" href="<?php echo htmlspecialchars($nfPlan); ?>" target="_blank" rel="noopener noreferrer">
        <span class="feature-icon">📋</span>
        <span class="feature-name">Meal Plans</span>
        <span class="feature-arrow">→</span>
    </a>

    <!-- Allergies — front office allergies -->
    <a class="feature-btn allergies" href="<?php echo htmlspecialchars($nfRepoRoot); ?>/allergies.php" target="_blank" rel="noopener noreferrer">
        <span class="feature-icon">⚠️</span>
        <span class="feature-name">Allergies</span>
        <span class="feature-arrow">→</span>
    </a>
</div>

<style>
.features-buttons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.feature-btn {
    background: white;
    border: none;
    padding: 1rem;
    border-radius: 16px;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    text-decoration: none;
    color: inherit;
}

body.dark-mode .feature-btn {
    background: #1e293b;
}

.feature-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.feature-icon {
    font-size: 1.5rem;
}

.feature-name {
    flex: 1;
    font-weight: 600;
    font-size: 0.9rem;
    text-align: left;
}

.feature-arrow {
    font-size: 1.2rem;
    opacity: 0;
    transform: translateX(-5px);
    transition: all 0.3s;
}

.feature-btn:hover .feature-arrow {
    opacity: 1;
    transform: translateX(0);
}

/* Couleurs personnalisées par bouton */
.feature-btn.donations {
    background: linear-gradient(135deg, #fef3c7, #fffbeb);
    color: #d97706;
}
body.dark-mode .feature-btn.donations {
    background: linear-gradient(135deg, #78350f, #451a03);
    color: #fbbf24;
}

.feature-btn.recipes {
    background: linear-gradient(135deg, #dcfce7, #ecfdf5);
    color: #16a34a;
}
body.dark-mode .feature-btn.recipes {
    background: linear-gradient(135deg, #14532d, #0a3b1a);
    color: #4ade80;
}

.feature-btn.marketplace {
    background: linear-gradient(135deg, #dbeafe, #eff6ff);
    color: #2563eb;
}
body.dark-mode .feature-btn.marketplace {
    background: linear-gradient(135deg, #1e3a8a, #172554);
    color: #60a5fa;
}

.feature-btn.plans {
    background: linear-gradient(135deg, #f3e8ff, #faf5ff);
    color: #9333ea;
}
body.dark-mode .feature-btn.plans {
    background: linear-gradient(135deg, #4c1d95, #2e1065);
    color: #c084fc;
}

.feature-btn.allergies {
    background: linear-gradient(135deg, #fee2e2, #fef2f2);
    color: #dc2626;
}
body.dark-mode .feature-btn.allergies {
    background: linear-gradient(135deg, #7f1d1d, #450a0a);
    color: #f87171;
}

/* Responsive */
@media (max-width: 768px) {
    .features-buttons-grid {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }

    .feature-btn {
        padding: 0.85rem;
    }

    .feature-icon {
        font-size: 1.3rem;
    }
}
</style>
