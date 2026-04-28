<div class="edit-form-container">
    <div class="form-header">
        <h2>Edit User</h2>
        <p>Update user information</p>
    </div>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="success-message"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="error-message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if(isset($_SESSION['errors'])): ?>
        <div class="error-messages">
            <?php foreach($_SESSION['errors'] as $error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <form method="POST" action="index.php?action=admin_edit_user&id=<?php echo $user['id']; ?>" onsubmit="return validateEditUserForm()">
        <div class="form-row">
            <div class="form-group">
                <label for="username">Username *</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                <div class="error-text" id="usernameError"></div>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                <div class="error-text" id="emailError"></div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" value="<?php echo $user['age'] ?? ''; ?>">
                <div class="error-text" id="ageError"></div>
            </div>

            <div class="form-group">
                <label for="role">Role *</label>
                <select id="role" name="role">
                    <option value="user" <?php echo ($user['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="weight">Weight (kg)</label>
                <input type="number" step="0.1" id="weight" name="weight" value="<?php echo $user['weight'] ?? ''; ?>">
                <div class="error-text" id="weightError"></div>
            </div>

            <div class="form-group">
                <label for="height">Height (cm)</label>
                <input type="number" id="height" name="height" value="<?php echo $user['height'] ?? ''; ?>">
                <div class="error-text" id="heightError"></div>
            </div>
        </div>

        <div class="form-group">
            <label for="dietary_preference">Dietary Preference</label>
            <select id="dietary_preference" name="dietary_preference">
                <option value="">Select...</option>
                <option value="omnivore" <?php echo (($user['dietary_preference'] ?? '') == 'omnivore') ? 'selected' : ''; ?>>Omnivore</option>
                <option value="vegetarian" <?php echo (($user['dietary_preference'] ?? '') == 'vegetarian') ? 'selected' : ''; ?>>Vegetarian</option>
                <option value="vegan" <?php echo (($user['dietary_preference'] ?? '') == 'vegan') ? 'selected' : ''; ?>>Vegan</option>
                <option value="pescatarian" <?php echo (($user['dietary_preference'] ?? '') == 'pescatarian') ? 'selected' : ''; ?>>Pescatarian</option>
                <option value="keto" <?php echo (($user['dietary_preference'] ?? '') == 'keto') ? 'selected' : ''; ?>>Keto</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save">Save Changes</button>
            <a href="index.php?action=admin_users" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>

<style>
.edit-form-container {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

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

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #2d3748;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
}

.error-text {
    color: #ef4444;
    font-size: 0.75rem;
    margin-top: 0.25rem;
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

.success-message,
.error-message {
    padding: 0.75rem;
    border-radius: 10px;
    margin-bottom: 1rem;
}

.success-message {
    background: #dcfce7;
    color: #166534;
}

.error-message {
    background: #fee2e2;
    color: #dc2626;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

.btn-save {
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #16a34a, #14532d);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(22, 163, 74, 0.3);
}

.btn-cancel {
    padding: 0.75rem 1.5rem;
    background: #e2e8f0;
    color: #4a5568;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    text-align: center;
    transition: all 0.3s;
}

.btn-cancel:hover {
    background: #cbd5e0;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
    
    .edit-form-container {
        padding: 1.5rem;
    }
}
</style>

<script>
function validateEditUserForm() {
    let isValid = true;
    
    // Clear previous errors
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
