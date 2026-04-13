<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">👤</div>
            <h2><?php echo htmlspecialchars($_SESSION['full_name'] ?: $_SESSION['username']); ?></h2>
            <p class="profile-email"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <span class="role-badge"><?php echo ucfirst($_SESSION['role']); ?></span>
        </div>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="success-message"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="error-message"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if(isset($_SESSION['errors'])): ?>
            <div class="error-messages">
                <?php foreach($_SESSION['errors'] as $error): ?>
                    <div class="error"><?php echo htmlspecialchars($error); ?></div>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <form method="POST" action="index.php?action=profile" onsubmit="return validateProfileForm()">
            <div class="form-group">
                <label for="username">Username *</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>">
                <div class="error-text" id="usernameError"></div>
            </div>

            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($_SESSION['full_name'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($_SESSION['phone'] ?? ''); ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($_SESSION['age'] ?? ''); ?>">
                    <div class="error-text" id="ageError"></div>
                </div>

                <div class="form-group">
                    <label for="weight">Weight (kg)</label>
                    <input type="number" step="0.1" id="weight" name="weight" value="<?php echo htmlspecialchars($_SESSION['weight'] ?? ''); ?>">
                    <div class="error-text" id="weightError"></div>
                </div>

                <div class="form-group">
                    <label for="height">Height (cm)</label>
                    <input type="number" id="height" name="height" value="<?php echo htmlspecialchars($_SESSION['height'] ?? ''); ?>">
                    <div class="error-text" id="heightError"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="dietary_preference">Dietary Preference</label>
                <select id="dietary_preference" name="dietary_preference">
                    <option value="">Select...</option>
                    <option value="omnivore" <?php echo (($_SESSION['dietary_preference'] ?? '') == 'omnivore') ? 'selected' : ''; ?>>Omnivore</option>
                    <option value="vegetarian" <?php echo (($_SESSION['dietary_preference'] ?? '') == 'vegetarian') ? 'selected' : ''; ?>>Vegetarian</option>
                    <option value="vegan" <?php echo (($_SESSION['dietary_preference'] ?? '') == 'vegan') ? 'selected' : ''; ?>>Vegan</option>
                    <option value="pescatarian" <?php echo (($_SESSION['dietary_preference'] ?? '') == 'pescatarian') ? 'selected' : ''; ?>>Pescatarian</option>
                    <option value="keto" <?php echo (($_SESSION['dietary_preference'] ?? '') == 'keto') ? 'selected' : ''; ?>>Keto</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="auth-btn">Update Profile</button>
            </div>
        </form>

        <!-- Delete Account Section -->
        <div class="delete-account-section">
            <div class="delete-divider"></div>
            <h3 class="delete-title">Danger Zone</h3>
            <p class="delete-warning">Once you delete your account, there is no going back. Please be certain.</p>
            <button class="delete-account-btn" onclick="openDeleteModal()">Delete Account</button>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content delete-modal">
        <div class="modal-header delete-header">
            <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
            <h2>⚠️ Delete Account</h2>
        </div>
        <div class="modal-body">
            <div class="delete-icon">🗑️</div>
            <p>Are you sure you want to delete your account?</p>
            <p class="delete-warning-text">This action <strong>cannot be undone</strong>. All your data will be permanently removed.</p>
            <div class="delete-confirm">
                <label for="confirm_delete">Type <strong>DELETE</strong> to confirm:</label>
                <input type="text" id="confirm_delete_input" placeholder="DELETE">
            </div>
        </div>
        <div class="modal-footer delete-footer">
            <button class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn-delete-confirm" onclick="confirmDelete()">Yes, Delete My Account</button>
        </div>
    </div>
</div>

<script>
function openDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'block';
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'none';
    document.getElementById('confirm_delete_input').value = '';
}

function confirmDelete() {
    const confirmInput = document.getElementById('confirm_delete_input').value;
    if (confirmInput === 'DELETE') {
        if (confirm('Are you absolutely sure? This action cannot be undone!')) {
            window.location.href = 'index.php?action=delete_account';
        }
    } else {
        alert('Please type DELETE to confirm account deletion.');
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target == modal) {
        closeDeleteModal();
    }
}
</script>