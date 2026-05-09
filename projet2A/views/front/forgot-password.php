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

                <form method="POST" action="index.php?action=forgot_password" onsubmit="return validateForgotPasswordForm(event)">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['old']['email'] ?? ''); ?>">
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

<!-- Virtual Email Modal -->
<div id="virtualEmailModal" class="modal">
    <div class="modal-content virtual-email">
        <div class="modal-header virtual-header">
            <span class="modal-close" onclick="closeVirtualEmailModal()">&times;</span>
            <div class="email-app-icon">📧</div>
            <h2>Virtual Inbox</h2>
            <p class="virtual-subtitle">Simulated email for demo purposes</p>
        </div>
        <div class="modal-body virtual-body">
            <div class="email-sender">
                <div class="sender-avatar">🥗</div>
                <div class="sender-info">
                    <strong>NutriFlow AI Team</strong>
                    <span>noreply@nutriflowai.com</span>
                </div>
                <div class="email-time" id="emailTime">just now</div>
            </div>
            <div class="email-subject">
                <strong>Password Reset Request</strong>
            </div>
            <div class="email-content">
                <p>Hello <span id="userEmailDisplay">User</span>,</p>
                <p>We received a request to reset the password for your NutriFlow AI account.</p>
                <p>Click the button below to create a new password:</p>
                <div class="reset-link-box">
                    <button class="virtual-reset-btn" onclick="openResetPasswordModal()">🔐 Reset My Password</button>
                </div>
                <p class="email-note">This link will expire in <strong>1 hour</strong>.</p>
                <p class="email-note">If you didn't request this, please ignore this email.</p>
                <hr>
                <p class="email-footer">NutriFlow AI - Smart Nutrition for a Better Tomorrow</p>
            </div>
        </div>
        <div class="modal-footer virtual-footer">
            <button class="btn-copy-link" onclick="copyResetLink()">📋 Copy Link</button>
            <button class="btn-close-modal" onclick="closeVirtualEmailModal()">Close</button>
        </div>
    </div>
</div>

<!-- Reset Password Modal (popup) -->
<div id="resetPasswordModal" class="modal">
    <div class="modal-content reset-modal">
        <div class="modal-header reset-header">
            <span class="modal-close" onclick="closeResetPasswordModal()">&times;</span>
            <div class="reset-icon">🔐</div>
            <h2>Create New Password</h2>
            <p class="reset-subtitle">Enter your new password below</p>
        </div>
        <div class="modal-body reset-body">
            <div id="resetError" class="reset-error" style="display: none;"></div>
            <div id="resetSuccess" class="reset-success" style="display: none;"></div>
            
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" class="reset-input" placeholder="Enter new password">
                <div class="reset-strength" id="resetStrength">
                    <div class="strength-bar weak" style="width: 0%"></div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="confirm_new_password">Confirm Password</label>
                <input type="password" id="confirm_new_password" class="reset-input" placeholder="Confirm your new password">
            </div>
            
            <div class="password-requirements">
                <p>Password must contain:</p>
                <ul>
                    <li id="reqLength">✓ At least 6 characters</li>
                </ul>
            </div>
        </div>
        <div class="modal-footer reset-footer">
            <button class="btn-cancel-reset" onclick="closeResetPasswordModal()">Cancel</button>
            <button class="btn-reset-submit" onclick="submitNewPassword()">Update Password</button>
        </div>
    </div>
</div>

<style>
/* Virtual Email Modal Styles */
.virtual-email {
    max-width: 500px !important;
    overflow: hidden;
}

.virtual-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    text-align: center;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.email-app-icon {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.virtual-header h2 {
    font-size: 1.3rem;
    margin: 0;
}

.virtual-subtitle {
    font-size: 0.7rem;
    opacity: 0.8;
    margin-top: 0.25rem;
}

.virtual-body {
    padding: 1.5rem;
    background: #f8fafc;
}

.email-sender {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: white;
    border-radius: 12px;
    margin-bottom: 1rem;
    border: 1px solid #e2e8f0;
}

.sender-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #16a34a, #14532d);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.sender-info {
    flex: 1;
}

.sender-info strong {
    display: block;
    color: #1e293b;
    font-size: 0.9rem;
}

.sender-info span {
    font-size: 0.7rem;
    color: #64748b;
}

.email-time {
    font-size: 0.7rem;
    color: #94a3b8;
}

.email-subject {
    padding: 0.5rem 0.75rem;
    background: white;
    border-radius: 12px;
    margin-bottom: 1rem;
    font-size: 0.9rem;
    color: #1e293b;
    border: 1px solid #e2e8f0;
}

.email-content {
    background: white;
    border-radius: 12px;
    padding: 1.25rem;
    border: 1px solid #e2e8f0;
}

.email-content p {
    margin-bottom: 1rem;
    color: #334155;
    font-size: 0.85rem;
    line-height: 1.5;
}

.reset-link-box {
    text-align: center;
    margin: 1.5rem 0;
}

.virtual-reset-btn {
    display: inline-block;
    background: linear-gradient(135deg, #16a34a, #14532d);
    color: white !important;
    text-decoration: none;
    padding: 0.75rem 2rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
}

.virtual-reset-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(22,163,74,0.3);
}

.email-note {
    font-size: 0.7rem !important;
    color: #64748b !important;
}

.email-footer {
    font-size: 0.7rem;
    text-align: center;
    color: #94a3b8;
    margin-top: 1rem;
}

.virtual-footer {
    display: flex;
    gap: 1rem;
    justify-content: center;
    padding: 1rem 1.5rem 1.5rem;
    background: white;
}

.btn-copy-link {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 0.5rem 1.2rem;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.8rem;
    transition: all 0.3s;
}

.btn-copy-link:hover {
    background: #2563eb;
    transform: translateY(-2px);
}

.btn-close-modal {
    background: #e2e8f0;
    color: #475569;
    border: none;
    padding: 0.5rem 1.2rem;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.8rem;
    transition: all 0.3s;
}

.btn-close-modal:hover {
    background: #cbd5e0;
    transform: translateY(-2px);
}

/* Reset Password Modal Styles */
.reset-modal {
    max-width: 450px !important;
}

.reset-header {
    background: linear-gradient(135deg, #16a34a, #14532d);
    text-align: center;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.reset-icon {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.reset-header h2 {
    font-size: 1.3rem;
    margin: 0;
    color: white;
}

.reset-subtitle {
    font-size: 0.7rem;
    opacity: 0.8;
    margin-top: 0.25rem;
    color: white;
}

.reset-body {
    padding: 1.5rem;
    background: white;
}

.reset-input {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s;
}

.reset-input:focus {
    outline: none;
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22,163,74,0.1);
}

.reset-strength {
    margin-top: 0.5rem;
    height: 4px;
    background: #e2e8f0;
    border-radius: 4px;
    overflow: hidden;
}

.reset-strength .strength-bar {
    height: 100%;
    width: 0%;
    transition: all 0.3s;
}

.strength-bar.weak { background: #ef4444; width: 25%; }
.strength-bar.fair { background: #f59e0b; width: 50%; }
.strength-bar.good { background: #8b5cf6; width: 75%; }
.strength-bar.strong { background: #10b981; width: 100%; }

.password-requirements {
    margin-top: 1rem;
    padding: 0.75rem;
    background: #f8fafc;
    border-radius: 10px;
    font-size: 0.75rem;
}

.password-requirements p {
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #475569;
}

.password-requirements ul {
    list-style: none;
    padding-left: 0;
}

.password-requirements li {
    color: #94a3b8;
    margin-bottom: 0.25rem;
}

.password-requirements li.valid {
    color: #16a34a;
}

.reset-error {
    background: #fee2e2;
    color: #dc2626;
    padding: 0.75rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    font-size: 0.85rem;
}

.reset-success {
    background: #dcfce7;
    color: #166534;
    padding: 0.75rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    font-size: 0.85rem;
}

.reset-footer {
    display: flex;
    gap: 1rem;
    justify-content: center;
    padding: 1rem 1.5rem 1.5rem;
    background: white;
    border-top: 1px solid #e2e8f0;
}

.btn-cancel-reset {
    background: #e2e8f0;
    color: #475569;
    border: none;
    padding: 0.6rem 1.5rem;
    border-radius: 10px;
    cursor: pointer;
    font-size: 0.85rem;
    transition: all 0.3s;
}

.btn-cancel-reset:hover {
    background: #cbd5e0;
    transform: translateY(-2px);
}

.btn-reset-submit {
    background: linear-gradient(135deg, #16a34a, #14532d);
    color: white;
    border: none;
    padding: 0.6rem 1.5rem;
    border-radius: 10px;
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-reset-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(22,163,74,0.3);
}

/* Animations */
@keyframes slideInEmail {
    from {
        opacity: 0;
        transform: translateY(-30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.virtual-email {
    animation: slideInEmail 0.4s ease;
}

@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.reset-modal {
    animation: fadeInScale 0.3s ease;
}
</style>

<script>
let currentResetToken = null;
let currentUserEmail = null;
let currentUsername = null;

function validateForgotPasswordForm(event) {
    event.preventDefault();
    
    const email = document.getElementById('email').value.trim();
    const emailError = document.getElementById('emailError');
    
    if(email === '') {
        emailError.textContent = 'Email address is required';
        return false;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(!emailRegex.test(email)) {
        emailError.textContent = 'Please enter a valid email address';
        return false;
    }
    
    emailError.textContent = '';
    
    simulateSendResetEmail(email);
    return false;
}

function simulateSendResetEmail(email) {
    const btn = document.querySelector('.auth-btn');
    const originalText = btn.textContent;
    btn.textContent = '⏳ Sending...';
    btn.disabled = true;
    
    // Appel AJAX au serveur
    fetch('index.php?action=sendResetLink', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'email=' + encodeURIComponent(email)
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            currentResetToken = data.token;
            currentUserEmail = data.email;
            currentUsername = data.username;
            
            // Afficher l'email virtuel
            document.getElementById('userEmailDisplay').textContent = currentUsername;
            document.getElementById('emailTime').textContent = new Date().toLocaleTimeString();
            
            showVirtualEmailModal();
        } else {
            alert('Error: ' + data.message);
        }
        
        btn.textContent = originalText;
        btn.disabled = false;
        document.getElementById('email').value = '';
    })
    .catch(error => {
        console.error('Error:', error);
        btn.textContent = originalText;
        btn.disabled = false;
        
        // Pour la démo, on simule quand même
        currentResetToken = 'demo_' + Date.now();
        currentUserEmail = email;
        currentUsername = email.split('@')[0];
        
        document.getElementById('userEmailDisplay').textContent = currentUsername;
        document.getElementById('emailTime').textContent = new Date().toLocaleTimeString();
        showVirtualEmailModal();
        
        btn.textContent = originalText;
        btn.disabled = false;
        document.getElementById('email').value = '';
    });
}

function showVirtualEmailModal() {
    document.getElementById('virtualEmailModal').style.display = 'block';
}

function closeVirtualEmailModal() {
    document.getElementById('virtualEmailModal').style.display = 'none';
}

function openResetPasswordModal() {
    closeVirtualEmailModal();
    
    // Réinitialiser le modal
    document.getElementById('new_password').value = '';
    document.getElementById('confirm_new_password').value = '';
    document.getElementById('resetError').style.display = 'none';
    document.getElementById('resetSuccess').style.display = 'none';
    document.querySelector('.reset-strength .strength-bar').style.width = '0%';
    document.querySelector('.reset-strength .strength-bar').className = 'strength-bar';
    
    // Réinitialiser les exigences
    const reqLength = document.getElementById('reqLength');
    if (reqLength) {
        reqLength.classList.remove('valid');
        reqLength.innerHTML = '❌ At least 6 characters';
    }
    
    document.getElementById('resetPasswordModal').style.display = 'block';
}

function closeResetPasswordModal() {
    document.getElementById('resetPasswordModal').style.display = 'none';
}

// Vérification de la force du mot de passe en temps réel
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('new_password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.querySelector('.reset-strength .strength-bar');
            const reqLength = document.getElementById('reqLength');
            
            // Vérifier la longueur
            if (password.length >= 6) {
                reqLength.classList.add('valid');
                reqLength.innerHTML = '✅ At least 6 characters';
            } else {
                reqLength.classList.remove('valid');
                reqLength.innerHTML = '❌ At least 6 characters';
            }
            
            // Déterminer la force
            let strength = 0;
            if (password.length >= 6) strength = 1;
            if (password.length >= 8) strength = 2;
            if (password.length >= 10 && /[A-Z]/.test(password)) strength = 3;
            if (password.length >= 12 && /[A-Z]/.test(password) && /[0-9]/.test(password) && /[!@#$%^&*]/.test(password)) strength = 4;
            
            if (strength === 0 || password.length === 0) {
                strengthBar.style.width = '0%';
                strengthBar.className = 'strength-bar';
            } else if (strength === 1) {
                strengthBar.style.width = '25%';
                strengthBar.className = 'strength-bar weak';
            } else if (strength === 2) {
                strengthBar.style.width = '50%';
                strengthBar.className = 'strength-bar fair';
            } else if (strength === 3) {
                strengthBar.style.width = '75%';
                strengthBar.className = 'strength-bar good';
            } else if (strength === 4) {
                strengthBar.style.width = '100%';
                strengthBar.className = 'strength-bar strong';
            }
        });
    }
});

function submitNewPassword() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_new_password').value;
    const errorDiv = document.getElementById('resetError');
    const successDiv = document.getElementById('resetSuccess');
    
    errorDiv.style.display = 'none';
    successDiv.style.display = 'none';
    
    // Validation
    if (newPassword === '') {
        errorDiv.innerHTML = 'Please enter a new password';
        errorDiv.style.display = 'block';
        return;
    }
    
    if (newPassword.length < 6) {
        errorDiv.innerHTML = 'Password must be at least 6 characters';
        errorDiv.style.display = 'block';
        return;
    }
    
    if (newPassword !== confirmPassword) {
        errorDiv.innerHTML = 'Passwords do not match';
        errorDiv.style.display = 'block';
        return;
    }
    
    // Envoyer la requête au serveur
    const submitBtn = document.querySelector('.btn-reset-submit');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = '⏳ Updating...';
    submitBtn.disabled = true;
    
    fetch('index.php?action=resetPasswordAjax', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            token: currentResetToken,
            password: newPassword,
            email: currentUserEmail
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            successDiv.innerHTML = '✅ ' + data.message;
            successDiv.style.display = 'block';
            
            // Fermer le modal après 2 secondes et rediriger
            setTimeout(() => {
                closeResetPasswordModal();
                window.location.href = 'index.php?action=login';
            }, 2000);
        } else {
            errorDiv.innerHTML = data.message || 'An error occurred';
            errorDiv.style.display = 'block';
        }
        
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    })
    .catch(error => {
        console.error('Error:', error);
        errorDiv.innerHTML = 'Network error. Please try again.';
        errorDiv.style.display = 'block';
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
}

function copyResetLink() {
    const resetLink = window.location.origin + window.location.pathname + '?action=reset_password&token=' + currentResetToken;
    
    navigator.clipboard.writeText(resetLink).then(() => {
        const toast = document.createElement('div');
        toast.textContent = '✅ Link copied to clipboard!';
        toast.style.cssText = `
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #16a34a;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            z-index: 10001;
            font-size: 0.8rem;
            animation: fadeInUp 0.3s ease;
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    });
}

// Fermer les modals en cliquant à l'extérieur
window.onclick = function(event) {
    const virtualModal = document.getElementById('virtualEmailModal');
    const resetModal = document.getElementById('resetPasswordModal');
    
    if (event.target == virtualModal) {
        closeVirtualEmailModal();
    }
    if (event.target == resetModal) {
        closeResetPasswordModal();
    }
}

// Animation pour les toasts
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateX(-50%) translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
    }
`;
document.head.appendChild(style);
</script>
