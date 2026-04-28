<div class="auth-container">
    <div class="auth-card login-card-simple">
        <div class="auth-right">
            <div class="auth-right-content">
                <div class="logo-mini">🥗 NutriFlow AI</div>
                <h3>Reset Password</h3>
                <p class="auth-subtitle">Enter your new password</p>

                <?php if(isset($_SESSION['error'])): ?>
                    <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?action=reset_password&token=<?php echo htmlspecialchars($_GET['token']); ?>" onsubmit="return validateResetPasswordForm()">
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" id="password" name="password">
                        <div class="error-text" id="passwordError"></div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password">
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

<script>
function validateResetPasswordForm() {
    let isValid = true;
    
    // Clear previous errors
    document.getElementById('passwordError').textContent = '';
    document.getElementById('confirmPasswordError').textContent = '';
    
    // Get values
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
</script>
