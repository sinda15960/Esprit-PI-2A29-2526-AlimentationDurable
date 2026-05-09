<div class="auth-container">
    <div class="auth-card login-card-simple">
        <div class="auth-right">
            <div class="auth-right-content">
                <div class="logo-mini">
                    <span class="logo-icon">🥗</span>
                    <span>NutriFlow AI</span>
                </div>
                <h3>Contact Administrator</h3>
                <p class="auth-subtitle">Your account has been disabled. Please contact us to reactivate it.</p>

                <?php if(isset($_SESSION['contact_success'])): ?>
                    <div class="success-message"><?php echo $_SESSION['contact_success']; unset($_SESSION['contact_success']); ?></div>
                <?php endif; ?>

                <?php if(isset($_SESSION['contact_error'])): ?>
                    <div class="error"><?php echo $_SESSION['contact_error']; unset($_SESSION['contact_error']); ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?action=send_contact_request">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Your Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="4" style="width:100%; padding:0.75rem; border-radius:10px; border:2px solid #e2e8f0;" placeholder="My account was disabled. Please help me reactivate it."></textarea>
                    </div>
                    <button type="submit" class="auth-btn">Send Request</button>
                </form>

                <div class="auth-footer">
                    <p><a href="index.php?action=login">← Back to Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
