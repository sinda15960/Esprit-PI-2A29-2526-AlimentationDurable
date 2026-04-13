<div class="auth-container">
    <div class="auth-card login-card-simple">
        <div class="auth-right">
            <div class="auth-right-content">
                <div class="logo-mini">
                    <span class="logo-icon">🥗</span>
                    <span>NutriFlow AI</span>
                </div>
                <h3>Welcome back!</h3>
                <p class="auth-subtitle">Log in to access your account</p>

                <?php if(isset($_SESSION['errors'])): ?>
                    <div class="error-messages">
                        <?php foreach($_SESSION['errors'] as $error): ?>
                            <div class="error"><?php echo htmlspecialchars($error); ?></div>
                        <?php endforeach; ?>
                    </div>
                    <?php unset($_SESSION['errors']); ?>
                <?php endif; ?>

                <?php if(isset($_SESSION['success'])): ?>
                    <div class="success-message"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?action=login" onsubmit="return validateLoginForm()">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" placeholder="example@nutriflow.com" value="<?php echo htmlspecialchars($_SESSION['old']['email'] ?? ''); ?>">
                        <div class="error-text" id="emailError"></div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="••••••••">
                        <div class="error-text" id="passwordError"></div>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember_me">
                            <span>Remember me</span>
                        </label>
                        <a href="#" class="forgot-link" onclick="showComingSoon(event)">Forgot password?</a>
                    </div>

                    <button type="submit" class="auth-btn">Log In</button>
                </form>

                <div class="divider">
                    <span>or continue with</span>
                </div>

                <div class="social-login">
                    <button class="social-btn google" onclick="showComingSoon(event)">
                        <span>G</span> Google
                    </button>
                    <button class="social-btn apple" onclick="showComingSoon(event)">
                        <span>🍎</span> Apple
                    </button>
                    <button class="social-btn discord" onclick="showComingSoon(event)">
                        <span>💬</span> Discord
                    </button>
                </div>

                <div class="auth-footer">
                    <p>New here? <a href="index.php?action=register">Create account</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Coming Soon -->
<div id="comingSoonModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="modal-close">&times;</span>
            <h2>🚀 Coming Soon</h2>
        </div>
        <div class="modal-body">
            <div class="coming-soon-icon">🔧</div>
            <p>This feature is currently under development.</p>
            <p class="modal-subtext">We're working hard to bring you this functionality soon!</p>
            <div class="progress-bar">
                <div class="progress" style="width: 65%;"></div>
            </div>
            <p class="progress-text">Development in progress - 65%</p>
        </div>
        <div class="modal-footer">
            <button class="btn-modal" onclick="closeModal()">Got it!</button>
        </div>
    </div>
</div>

<script>
function showComingSoon(event) {
    event.preventDefault();
    const modal = document.getElementById('comingSoonModal');
    modal.style.display = 'block';
}

function closeModal() {
    const modal = document.getElementById('comingSoonModal');
    modal.style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('comingSoonModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>