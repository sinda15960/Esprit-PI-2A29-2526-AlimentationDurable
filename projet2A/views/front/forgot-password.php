<div class="auth-container">
    <div class="auth-card login-card-simple">
        <div class="auth-right">
            <div class="auth-right-content">
                <div class="logo-mini">🥗 NutriFlow AI</div>
                <h3>Forgot Password?</h3>
                <p class="auth-subtitle">Enter your email to receive a reset link</p>

                <?php if(isset($_SESSION['success'])): ?>
                    <div class="success-message"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?action=forgot_password" onsubmit="return validateForgotPasswordForm()">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="text" id="email" name="email">
                        <div class="error-text" id="emailError"></div>
                    </div>
                    <button type="submit" class="auth-btn">Send Reset Link</button>
                </form>

                <div class="auth-footer">
                    <p><a href="index.php?action=login">← Back to Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validateForgotPasswordForm() {
    let isValid = true;
    
    // Clear previous errors
    document.getElementById('emailError').textContent = '';
    
    // Get values
    const email = document.getElementById('email').value.trim();
    
    // Validate email
    if(email === '') {
        document.getElementById('emailError').textContent = 'Email address is required';
        isValid = false;
    } else {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(!emailRegex.test(email)) {
            document.getElementById('emailError').textContent = 'Please enter a valid email address';
            isValid = false;
        }
    }
    
    return isValid;
}
</script>
