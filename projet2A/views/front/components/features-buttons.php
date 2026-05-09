<!-- Features Buttons Section -->
<div class="features-buttons-grid">
    <!-- Donations Button -->
    <button class="feature-btn donations" onclick="openComingSoonModal('Donations')">
        <span class="feature-icon">💝</span>
        <span class="feature-name">Donations</span>
        <span class="feature-arrow">→</span>
    </button>

    <!-- Recipes Button -->
    <button class="feature-btn recipes" onclick="openComingSoonModal('Recipes')">
        <span class="feature-icon">🍽️</span>
        <span class="feature-name">Recipes</span>
        <span class="feature-arrow">→</span>
    </button>

    <!-- Marketplace Button -->
    <button class="feature-btn marketplace" onclick="openComingSoonModal('Marketplace')">
        <span class="feature-icon">🛒</span>
        <span class="feature-name">Marketplace</span>
        <span class="feature-arrow">→</span>
    </button>

    <!-- Meal Plans Button -->
    <button class="feature-btn plans" onclick="openComingSoonModal('Meal Plans')">
        <span class="feature-icon">📋</span>
        <span class="feature-name">Meal Plans</span>
        <span class="feature-arrow">→</span>
    </button>

    <!-- Allergies Button -->
    <button class="feature-btn allergies" onclick="openComingSoonModal('Allergies Management')">
        <span class="feature-icon">⚠️</span>
        <span class="feature-name">Allergies</span>
        <span class="feature-arrow">→</span>
    </button>
</div>

<!-- Coming Soon Modal -->
<div id="featureComingSoonModal" class="modal">
    <div class="modal-content">
        <div class="modal-header" id="featureModalHeader">
            <span class="modal-close" onclick="closeFeatureModal()">&times;</span>
            <div class="modal-header-icon" id="featureModalIcon">🚀</div>
            <h2 id="featureModalTitle">Coming Soon</h2>
        </div>
        <div class="modal-body">
            <p id="featureModalMessage">This feature is currently under development.</p>
            <p class="modal-subtext">We're working hard to bring you this functionality soon!</p>
            <div class="progress-bar">
                <div class="progress" id="featureProgress" style="width: 65%;"></div>
            </div>
            <p class="progress-text">Development in progress - <span id="featureProgressPercent">65</span>%</p>
        </div>
        <div class="modal-footer">
            <button class="btn-modal" id="featureModalBtn" onclick="closeFeatureModal()">Got it!</button>
        </div>
    </div>
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

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 2000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
    animation: fadeIn 0.3s ease;
}

.modal-content {
    background-color: white;
    margin: 10% auto;
    width: 90%;
    max-width: 480px;
    border-radius: 24px;
    animation: slideUpModal 0.4s ease;
    overflow: hidden;
}

body.dark-mode .modal-content {
    background-color: #1e293b;
}

.modal-header {
    padding: 1.5rem;
    text-align: center;
    color: white;
    position: relative;
}

.modal-header-icon {
    font-size: 2.8rem;
    margin-bottom: 0.5rem;
    animation: bounce 0.5s ease;
}

.modal-header h2 {
    margin: 0;
    font-size: 1.5rem;
}

.modal-close {
    position: absolute;
    top: 1rem;
    right: 1.5rem;
    font-size: 1.8rem;
    cursor: pointer;
    transition: opacity 0.3s;
    color: white;
}

.modal-close:hover {
    opacity: 0.7;
}

.modal-body {
    padding: 1.5rem;
    text-align: center;
}

.modal-body p {
    margin-bottom: 0.5rem;
    color: #4a5568;
}

body.dark-mode .modal-body p {
    color: #cbd5e0;
}

.modal-subtext {
    font-size: 0.85rem;
    color: #718096;
}

body.dark-mode .modal-subtext {
    color: #94a3b8;
}

.progress-bar {
    width: 100%;
    height: 8px;
    background: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    margin: 1rem 0;
}

body.dark-mode .progress-bar {
    background: #334155;
}

.progress {
    height: 100%;
    border-radius: 10px;
    transition: width 0.5s ease;
}

.progress-text {
    font-size: 0.7rem;
    color: #94a3b8;
}

.modal-footer {
    padding: 1rem 1.5rem 1.5rem;
    text-align: center;
}

.btn-modal {
    padding: 0.6rem 2rem;
    color: white;
    border: none;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-modal:hover {
    transform: translateY(-2px);
    filter: brightness(1.05);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUpModal {
    from {
        opacity: 0;
        transform: translateY(40px) scale(0.96);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
}

/* Responsive */
@media (max-width: 768px) {
    .features-buttons-grid {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    
    .modal-content {
        margin: 25% auto;
        width: 95%;
    }
    
    .feature-btn {
        padding: 0.85rem;
    }
    
    .feature-icon {
        font-size: 1.3rem;
    }
}
</style>

<script>
// Configurations des fonctionnalités
const featureConfig = {
    'Donations': {
        icon: '💝',
        color: 'linear-gradient(135deg, #f59e0b, #d97706)',
        message: 'Donation Management is currently under development.',
        progress: 65
    },
    'Recipes': {
        icon: '🍽️',
        color: 'linear-gradient(135deg, #16a34a, #14532d)',
        message: 'Recipe Management is currently under development.',
        progress: 60
    },
    'Marketplace': {
        icon: '🛒',
        color: 'linear-gradient(135deg, #3b82f6, #1e40af)',
        message: 'Marketplace Management is currently under development.',
        progress: 45
    },
    'Meal Plans': {
        icon: '📋',
        color: 'linear-gradient(135deg, #8b5cf6, #6d28d9)',
        message: 'Meal Plans Management is currently under development.',
        progress: 70
    },
    'Allergies Management': {
        icon: '⚠️',
        color: 'linear-gradient(135deg, #ef4444, #b91c1c)',
        message: 'Allergies Management is currently under development.',
        progress: 50
    }
};

function openComingSoonModal(feature) {
    const config = featureConfig[feature];
    if (!config) return;
    
    const modal = document.getElementById('featureComingSoonModal');
    const header = document.getElementById('featureModalHeader');
    const icon = document.getElementById('featureModalIcon');
    const title = document.getElementById('featureModalTitle');
    const message = document.getElementById('featureModalMessage');
    const progress = document.getElementById('featureProgress');
    const progressPercent = document.getElementById('featureProgressPercent');
    const btn = document.getElementById('featureModalBtn');
    
    // Mettre à jour le contenu
    icon.textContent = config.icon;
    title.textContent = 'Coming Soon';
    message.textContent = config.message;
    progress.style.width = `${config.progress}%`;
    progressPercent.textContent = config.progress;
    
    // Ajouter une animation sur l'icône
    icon.style.animation = 'none';
    setTimeout(() => {
        icon.style.animation = 'bounce 0.5s ease';
    }, 10);
    
    // Mettre à jour la couleur du header
    header.style.background = config.color;
    
    // Mettre à jour la couleur du bouton
    if (btn) {
        btn.style.background = config.color;
    }
    
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeFeatureModal() {
    const modal = document.getElementById('featureComingSoonModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Fermer le modal en cliquant à l'extérieur
window.onclick = function(event) {
    const modal = document.getElementById('featureComingSoonModal');
    if (event.target == modal) {
        closeFeatureModal();
    }
}

// Fermer avec la touche Echap
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('featureComingSoonModal');
        if (modal.style.display === 'block') {
            closeFeatureModal();
        }
    }
});
</script>
