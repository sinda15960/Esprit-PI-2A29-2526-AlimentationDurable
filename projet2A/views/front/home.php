<?php if(isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
    <div class="account-deleted-message">
        <div class="deleted-alert">
            <span class="deleted-icon">👋</span>
            <div class="deleted-content">
                <strong>Your account has been successfully deleted!</strong>
                <p>We're sad to see you go. We hope to see you again soon!</p>
            </div>
            <button class="deleted-close" onclick="this.parentElement.parentElement.style.display='none'">&times;</button>
        </div>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['account_deleted'])): ?>
    <div class="account-deleted-message">
        <div class="deleted-alert">
            <span class="deleted-icon">👋</span>
            <div class="deleted-content">
                <strong><?php echo htmlspecialchars($_SESSION['account_deleted']); ?></strong>
                <p>We hope to see you again soon!</p>
            </div>
            <button class="deleted-close" onclick="this.parentElement.parentElement.style.display='none'">&times;</button>
        </div>
    </div>
    <?php unset($_SESSION['account_deleted']); ?>
<?php endif; ?>

<section class="hero">
    <div class="hero-container">
        <div class="hero-content">
            <div class="hero-badge">
                <span class="badge-icon">✨</span>
                <span>AI-Powered Nutrition</span>
            </div>
            <h1 class="hero-title">
                <span class="title-white">HEALTHY</span>
                <span class="title-white">EAT</span>
                <span class="title-white">HEALTHY</span>
            </h1>
            <p class="hero-subtitle">plan your meals</p>
            <p class="hero-description">
                Discover smart, sustainable nutrition with AI-powered meal planning. 
                Get personalized recommendations based on your dietary needs.
            </p>
            <div class="hero-buttons">
                <a href="index.php?action=register" class="btn-primary">Start Your Journey</a>
                <a href="#" class="btn-secondary">Learn More</a>
            </div>
        </div>
        <div class="hero-image">
            <div class="image-wrapper">
                <div class="floating-card card-1">
                    <span>🥑</span>
                    <p>Smart Meal Planning</p>
                </div>
                <div class="floating-card card-2">
                    <span>🌱</span>
                    <p>Sustainable Choices</p>
                </div>
                <div class="floating-card card-3">
                    <span>📊</span>
                    <p>AI Recommendations</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="features">
    <div class="container">
        <h2 class="section-title">Why Choose NutriFlow AI?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🧠</div>
                <h3>AI Intelligence</h3>
                <p>Personalized meal plans based on your preferences and goals</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🌍</div>
                <h3>Sustainable</h3>
                <p>Eco-friendly food choices for a better planet</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📱</div>
                <h3>Easy Tracking</h3>
                <p>Monitor your nutrition progress effortlessly</p>
            </div>
        </div>
    </div>
</section>