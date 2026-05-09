<div class="edit-form-container">
    <div class="form-header">
        <h2>Add New User</h2>
        <p>Create a new user account</p>
    </div>

    <?php if(isset($_SESSION['errors'])): ?>
        <div class="error-messages">
            <?php foreach($_SESSION['errors'] as $error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <form method="POST" action="index.php?action=admin_create_user" onsubmit="return validateAddUserForm()">
        <div class="form-row">
            <div class="form-group">
                <label for="username">Username *</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['old']['username'] ?? ''); ?>">
                <div class="error-text" id="usernameError"></div>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['old']['email'] ?? ''); ?>">
                <div class="error-text" id="emailError"></div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password">
                <div class="error-text" id="passwordError"></div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password *</label>
                <input type="password" id="confirm_password" name="confirm_password">
                <div class="error-text" id="confirmPasswordError"></div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($_SESSION['old']['full_name'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($_SESSION['old']['phone'] ?? ''); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($_SESSION['old']['age'] ?? ''); ?>">
                <div class="error-text" id="ageError"></div>
            </div>

            <div class="form-group">
                <label for="role">Role *</label>
                <select id="role" name="role">
                    <option value="user" <?php echo (($_SESSION['old']['role'] ?? '') == 'user') ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo (($_SESSION['old']['role'] ?? '') == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="weight">Weight (kg)</label>
                <input type="number" step="0.1" id="weight" name="weight" value="<?php echo htmlspecialchars($_SESSION['old']['weight'] ?? ''); ?>">
                <div class="error-text" id="weightError"></div>
            </div>

            <div class="form-group">
                <label for="height">Height (cm)</label>
                <input type="number" id="height" name="height" value="<?php echo htmlspecialchars($_SESSION['old']['height'] ?? ''); ?>">
                <div class="error-text" id="heightError"></div>
            </div>
        </div>

        <div class="form-group">
            <label for="dietary_preference">Dietary Preference</label>
            <select id="dietary_preference" name="dietary_preference">
                <option value="">Select...</option>
                <option value="omnivore" <?php echo (($_SESSION['old']['dietary_preference'] ?? '') == 'omnivore') ? 'selected' : ''; ?>>Omnivore</option>
                <option value="vegetarian" <?php echo (($_SESSION['old']['dietary_preference'] ?? '') == 'vegetarian') ? 'selected' : ''; ?>>Vegetarian</option>
                <option value="vegan" <?php echo (($_SESSION['old']['dietary_preference'] ?? '') == 'vegan') ? 'selected' : ''; ?>>Vegan</option>
                <option value="pescatarian" <?php echo (($_SESSION['old']['dietary_preference'] ?? '') == 'pescatarian') ? 'selected' : ''; ?>>Pescatarian</option>
                <option value="keto" <?php echo (($_SESSION['old']['dietary_preference'] ?? '') == 'keto') ? 'selected' : ''; ?>>Keto</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save">Create User</button>
            <a href="index.php?action=admin_users" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>

<script>
function validateAddUserForm() {
    let isValid = true;
    
    // Clear errors
    document.querySelectorAll('.error-text').forEach(el => el.textContent = '');
    
    const username = document.getElementById('username');
    if(username && username.value.length < 3) {
        document.getElementById('usernameError').textContent = 'Username must be at least 3 characters';
        isValid = false;
    }
    
    const email = document.getElementById('email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(email && !emailRegex.test(email.value)) {
        document.getElementById('emailError').textContent = 'Please enter a valid email address';
        isValid = false;
    }
    
    const password = document.getElementById('password');
    if(password && password.value.length < 6) {
        document.getElementById('passwordError').textContent = 'Password must be at least 6 characters';
        isValid = false;
    }
    
    const confirmPassword = document.getElementById('confirm_password');
    if(password && confirmPassword && password.value !== confirmPassword.value) {
        document.getElementById('confirmPasswordError').textContent = 'Passwords do not match';
        isValid = false;
    }
    
    const age = document.getElementById('age');
    if(age && age.value && (age.value < 1 || age.value > 120)) {
        document.getElementById('ageError').textContent = 'Age must be between 1 and 120';
        isValid = false;
    }
    
    const weight = document.getElementById('weight');
    if(weight && weight.value && (weight.value < 20 || weight.value > 300)) {
        document.getElementById('weightError').textContent = 'Weight must be between 20 and 300 kg';
        isValid = false;
    }
    
    const height = document.getElementById('height');
    if(height && height.value && (height.value < 100 || height.value > 250)) {
        document.getElementById('heightError').textContent = 'Height must be between 100 and 250 cm';
        isValid = false;
    }
    
    return isValid;
}
</script>

<style>
.form-header {
    text-align: center;
    margin-bottom: 2rem;
}

.form-header h2 {
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.form-header p {
    color: #718096;
    font-size: 0.9rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.error-messages {
    margin-bottom: 1rem;
}

.error {
    background: #fee2e2;
    color: #dc2626;
    padding: 0.5rem;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    font-size: 0.85rem;
}

.btn-save, .btn-cancel {
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.3s;
}

.btn-save {
    background: linear-gradient(135deg, #16a34a, #14532d);
    color: white;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(22, 163, 74, 0.3);
}

.btn-cancel {
    background: #e2e8f0;
    color: #4a5568;
}

.btn-cancel:hover {
    background: #cbd5e0;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
}
</style>
