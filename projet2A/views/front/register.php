<div class="auth-container">
    <div class="auth-card large">
        <div class="auth-header">
            <div class="logo-mini">🥗 NutriFlow AI</div>
            <h2>Create Account</h2>
            <p>Start your sustainable nutrition journey</p>
        </div>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="success-message"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if(isset($_SESSION['errors'])): ?>
            <div class="error-messages">
                <?php foreach($_SESSION['errors'] as $error): ?>
                    <div class="error"><?php echo htmlspecialchars($error); ?></div>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <form method="POST" action="index.php?action=register" onsubmit="return validateRegisterForm()">
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

            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($_SESSION['old']['full_name'] ?? ''); ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($_SESSION['old']['phone'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($_SESSION['old']['age'] ?? ''); ?>">
                    <div class="error-text" id="ageError"></div>
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

            <button type="submit" class="auth-btn">Create Account</button>
        </form>

        <div class="auth-footer">
            <p>Already have an account? <a href="index.php?action=login">Login</a></p>
        </div>
    </div>
</div>
<?php unset($_SESSION['old']); ?>