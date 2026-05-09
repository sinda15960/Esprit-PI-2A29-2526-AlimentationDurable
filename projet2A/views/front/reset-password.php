<div class="auth-container">
    <div class="auth-card login-card-simple">
        <div class="auth-right">
            <div class="auth-right-content">
                <div class="logo-mini">🥗 NutriFlow AI</div>
                <h3>Reset Password</h3>
                <p class="auth-subtitle">Enter your new password</p>

                <?php if(isset($_SESSION['error'])): ?>
                    <div class="error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <?php if(isset($_SESSION['success'])): ?>
                    <div class="success-message"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?action=reset_password" onsubmit="return validateResetPasswordForm()">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" id="password" name="password" placeholder="••••••••">
                        <div class="error-text" id="passwordError"></div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••">
                        <div class="error-text" id="confirmPasswordError"></div>
                    </div>
                    <button type="submit" class="auth-btn">Reset Password</button>
                </form>

                <div class="auth-footer">
                    <p><a href="index.php?action=login">← Back to Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.success-message {
    background: #dcfce7;
    color: #166534;
    padding: 0.75rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    font-size: 0.85rem;
}

.error {
    background: #fee2e2;
    color: #dc2626;
    padding: 0.75rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    font-size: 0.85rem;
}

.error-text {
    color: #ef4444;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.auth-btn {
    width: 100%;
    padding: 0.8rem;
    background: linear-gradient(135deg, #16a34a, #14532d);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.auth-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(22,163,74,0.3);
}
</style>

<script>
function validateResetPasswordForm() {
    let isValid = true;
    
    // Clear previous errors
    document.getElementById('passwordError').textContent = '';
    document.getElementById('confirmPasswordError').textContent = '';
    
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    // Validate password
    if(password === '') {
        document.getElementById('passwordError').textContent = 'Password is required';
        isValid = false;
    } else if(password.length < 6) {
        document.getElementById('passwordError').textContent = 'Password must be at least 6 characters';
        isValid = false;
    }
    
    // Validate confirm password
    if(confirmPassword === '') {
        document.getElementById('confirmPasswordError').textContent = 'Please confirm your password';
        isValid = false;
    } else if(password !== confirmPassword) {
        document.getElementById('confirmPasswordError').textContent = 'Passwords do not match';
        isValid = false;
    }
    
    return isValid;
}

// Vérifier si le token est valide au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get('token');
    
    if (!token || token === '') {
        alert('Invalid reset link. Please request a new one.');
        window.location.href = 'index.php?action=forgot_password';
    }
    
    // Afficher un message d'information pour les tokens de démo
    if (token && token.startsWith('demo_')) {
        const infoDiv = document.createElement('div');
        infoDiv.className = 'success-message';
        infoDiv.style.background = '#fef3c7';
        infoDiv.style.color = '#92400e';
        infoDiv.style.marginBottom = '1rem';
        infoDiv.innerHTML = '🔧 <strong>Demo Mode:</strong> This is a simulated reset link. Your password will be updated in the system.';
        
        const form = document.querySelector('form');
        if (form) {
            form.insertBefore(infoDiv, form.firstChild);
        }
    }
});
</script>
