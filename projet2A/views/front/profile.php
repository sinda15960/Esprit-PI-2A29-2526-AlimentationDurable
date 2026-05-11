<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar" id="dynamicAvatar">
                <div class="avatar-content" id="avatarContent">
                    <span class="avatar-emoji" id="avatarEmoji">👤</span>
                    <img id="avatarImage" class="avatar-image" src="" alt="Avatar" style="display: none;">
                </div>
            </div>
            <h2><?php echo htmlspecialchars($_SESSION['full_name'] ?: $_SESSION['username']); ?></h2>
            <p class="profile-email"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <span class="role-badge"><?php echo ucfirst($_SESSION['role']); ?></span>
        </div>

        <!-- Features Buttons (Donations, Recipes, Marketplace, Plans, Allergies) -->
        <?php include dirname(__DIR__) . '/front/components/features-buttons.php'; ?>

        <!-- Streak Widget -->
        <?php include dirname(__DIR__) . '/front/components/streak-widget.php'; ?>

        <!-- Daily Quote Widget -->
        <?php include dirname(__DIR__) . '/front/components/daily-quote.php'; ?>

        <!-- Avatar Generator -->
        <?php include dirname(__DIR__) . '/front/components/avatar-generator.php'; ?>

        <!-- Account Reactivated Message -->
        <?php if(isset($_SESSION['account_reactivated'])): ?>
            <div class="reactivated-message">
                <div class="reactivated-icon">🎉</div>
                <div class="reactivated-content">
                    <strong><?php echo $_SESSION['account_reactivated']; ?></strong>
                    <p>We're happy to have you back! Your account is now fully restored.</p>
                </div>
                <button class="reactivated-close" onclick="this.parentElement.style.display='none'">&times;</button>
            </div>
            <?php unset($_SESSION['account_reactivated']); ?>
        <?php endif; ?>

        <!-- Goal Tracker Section -->
        <div class="goals-section">
            <div class="section-header">
                <h3>🎯 Goal Tracker</h3>
                <button class="add-goal-btn" onclick="openAddGoalModal()">+ Add Goal</button>
            </div>
            <div id="goalsList" class="goals-list">
                <!-- Goals will be dynamically added here -->
            </div>
            <div class="goals-stats" id="goalsStats">
                <span>🎯 0/0 goals completed</span>
            </div>
        </div>

        <!-- Friend Activity Section -->
        <div class="friends-section">
            <h3>👥 Friend Activity</h3>
            <div id="friendsList" class="friends-list">
                <!-- Friends will be dynamically added here -->
            </div>
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

<!-- Add Goal Modal -->
<div id="addGoalModal" class="modal">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #16a34a, #14532d);">
            <span class="modal-close" onclick="closeAddGoalModal()">&times;</span>
            <h2>🎯 Add New Goal</h2>
        </div>
        <div class="modal-body" style="text-align: left;">
            <div class="form-group">
                <label for="goalName">Goal Name</label>
                <input type="text" id="goalName" placeholder="e.g., Lose 5 kg, Drink 2L water">
            </div>
            <div class="form-group">
                <label for="goalTarget">Target (number)</label>
                <input type="number" id="goalTarget" placeholder="e.g., 5, 10, 100">
            </div>
            <div class="form-group">
                <label for="goalUnit">Unit</label>
                <input type="text" id="goalUnit" placeholder="e.g., kg, days, glasses">
            </div>
            <div class="form-options" style="justify-content: center; margin-top: 1rem;">
                <button class="auth-btn" onclick="addGoal()" style="width: auto; padding: 0.6rem 2rem;">Add Goal →</button>
            </div>
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
                <label for="confirm_delete_input">Type <strong>DELETE</strong> to confirm:</label>
                <input type="text" id="confirm_delete_input" placeholder="DELETE">
            </div>
        </div>
        <div class="modal-footer delete-footer">
            <button class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn-delete-confirm" onclick="confirmDelete()">Yes, Delete My Account</button>
        </div>
    </div>
</div>

<style>
/* Profile Avatar */
.profile-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    margin-bottom: 1.5rem;
}

.profile-header h2 {
    margin: 0 0 0.35rem;
    font-size: 1.5rem;
    font-weight: 700;
}

.profile-header .profile-email {
    margin: 0 0 0.5rem;
    font-size: 0.95rem;
    color: #64748b;
}

.profile-header .role-badge {
    display: inline-block;
}

body.dark-mode .profile-header .profile-email {
    color: #94a3b8;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #16a34a, #14532d);
    border-radius: 50%;
    margin: 0 auto 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
}

.avatar-content {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.avatar-emoji {
    font-size: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
}

/* Reactivated Message */
.reactivated-message {
    background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    border-left: 5px solid #16a34a;
    border-radius: 16px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    animation: slideDownBanner 0.5s ease;
    position: relative;
    overflow: hidden;
}

.reactivated-icon {
    font-size: 2.5rem;
    animation: bounce 0.6s ease;
}

.reactivated-content strong {
    display: block;
    color: #166534;
    font-size: 1rem;
    margin-bottom: 0.25rem;
}

.reactivated-content p {
    color: #15803d;
    font-size: 0.85rem;
    margin: 0;
}

.reactivated-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #16a34a;
    transition: all 0.3s;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-left: auto;
}

.reactivated-close:hover {
    background: rgba(22, 163, 74, 0.2);
    transform: scale(1.1);
}

/* Goal Tracker Section */
.goals-section {
    background: #f8fafc;
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

body.dark-mode .goals-section {
    background: #1e293b;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.section-header h3 {
    font-size: 1.1rem;
    color: #1e293b;
}

body.dark-mode .section-header h3 {
    color: #f1f5f9;
}

.add-goal-btn {
    background: linear-gradient(135deg, #16a34a, #14532d);
    color: white;
    border: none;
    padding: 0.4rem 1rem;
    border-radius: 50px;
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.3s;
}

.add-goal-btn:hover {
    transform: scale(1.05);
}

.goals-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-bottom: 1rem;
    max-height: 250px;
    overflow-y: auto;
}

.goal-item {
    background: white;
    border-radius: 12px;
    padding: 0.75rem 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

body.dark-mode .goal-item {
    background: #0f172a;
}

.goal-info {
    flex: 1;
}

.goal-name {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.9rem;
}

body.dark-mode .goal-name {
    color: #f1f5f9;
}

.goal-progress-text {
    font-size: 0.7rem;
    color: #64748b;
}

.goal-progress-bar {
    width: 150px;
    height: 6px;
    background: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
}

.goal-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #16a34a, #14532d);
    border-radius: 10px;
    transition: width 0.3s ease;
}

.goal-actions {
    display: flex;
    gap: 0.5rem;
}

.goal-increment {
    background: #dcfce7;
    border: none;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    cursor: pointer;
    font-weight: bold;
    transition: all 0.3s;
}

.goal-increment:hover {
    background: #16a34a;
    color: white;
    transform: scale(1.1);
}

.goal-delete {
    background: #fee2e2;
    border: none;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s;
}

.goal-delete:hover {
    background: #ef4444;
    color: white;
    transform: scale(1.1);
}

.goals-stats {
    text-align: center;
    font-size: 0.8rem;
    color: #16a34a;
    font-weight: 600;
    padding-top: 0.75rem;
    border-top: 1px solid #e2e8f0;
}

/* Friend Activity Section */
.friends-section {
    background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

body.dark-mode .friends-section {
    background: linear-gradient(135deg, #0f172a, #1e293b);
}

.friends-section h3 {
    font-size: 1.1rem;
    color: #0c4a6e;
    margin-bottom: 1rem;
}

body.dark-mode .friends-section h3 {
    color: #7dd3fc;
}

.friends-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.friend-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: white;
    border-radius: 16px;
    transition: all 0.3s;
}

body.dark-mode .friend-item {
    background: #1e293b;
}

.friend-item:hover {
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.friend-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #16a34a, #14532d);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
}

.friend-info {
    flex: 1;
}

.friend-name {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.85rem;
}

body.dark-mode .friend-name {
    color: #f1f5f9;
}

.friend-activity {
    font-size: 0.7rem;
    color: #64748b;
}

.friend-time {
    font-size: 0.65rem;
    color: #94a3b8;
}

/* Messages */
.success-message {
    background: #dcfce7;
    color: #166534;
    padding: 0.75rem;
    border-radius: 10px;
    margin-bottom: 1rem;
}

.error-message {
    background: #fee2e2;
    color: #dc2626;
    padding: 0.75rem;
    border-radius: 10px;
    margin-bottom: 1rem;
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

.error-text {
    color: #ef4444;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

/* Form Row */
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

/* Delete Account Section */
.delete-account-section {
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
}

.delete-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
    margin: 1.5rem 0;
}

.delete-title {
    color: #dc2626;
    font-size: 1rem;
    margin-bottom: 0.5rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.delete-warning {
    color: #718096;
    font-size: 0.875rem;
    margin-bottom: 1rem;
    line-height: 1.5;
}

.delete-account-btn {
    background: transparent;
    border: 2px solid #dc2626;
    color: #dc2626;
    padding: 0.6rem 1.5rem;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.delete-account-btn:hover {
    background: #dc2626;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 38, 38, 0.3);
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    animation: fadeIn 0.3s ease;
}

.modal-content {
    background-color: white;
    margin: 10% auto;
    width: 90%;
    max-width: 500px;
    border-radius: 20px;
    animation: slideDownModal 0.3s ease;
    overflow: hidden;
}

.delete-modal {
    max-width: 450px;
}

.delete-header {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    padding: 1.25rem 1.5rem;
}

.delete-header h2 {
    font-size: 1.25rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: white;
}

.modal-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #16a34a 0%, #14532d 100%);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    font-size: 1.5rem;
}

.modal-close {
    font-size: 1.75rem;
    cursor: pointer;
    transition: opacity 0.3s;
}

.modal-close:hover {
    opacity: 0.7;
}

.modal-body {
    padding: 2rem;
    text-align: center;
}

.delete-icon {
    font-size: 3.5rem;
    margin-bottom: 1rem;
    animation: shake 0.5s ease;
}

.delete-warning-text {
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    font-weight: 500;
}

.delete-confirm {
    margin-top: 1.5rem;
    text-align: left;
    background: #fef2f2;
    padding: 1rem;
    border-radius: 12px;
}

.delete-confirm label {
    display: block;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    color: #4a5568;
    font-weight: 500;
}

.delete-confirm label strong {
    color: #dc2626;
}

.delete-confirm input {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 1rem;
    font-family: monospace;
    text-align: center;
    letter-spacing: 1px;
    transition: all 0.3s;
}

.delete-confirm input:focus {
    outline: none;
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.btn-delete-confirm {
    background: #dc2626;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-delete-confirm:hover {
    background: #b91c1c;
    transform: translateY(-2px);
}

.btn-cancel {
    background: #e2e8f0;
    color: #4a5568;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-cancel:hover {
    background: #cbd5e0;
    transform: translateY(-2px);
}

.delete-footer {
    display: flex;
    gap: 1rem;
    justify-content: center;
    padding: 1rem 1.5rem 1.5rem;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideDownModal {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

@keyframes slideDownBanner {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

/* Responsive */
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
    
    .modal-content {
        margin: 30% auto;
        width: 95%;
    }
    
    .delete-footer {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .btn-cancel,
    .btn-delete-confirm {
        width: 100%;
    }
}
</style>

<script>
// ========== AVATAR MANAGER ==========
let avatarManager;

class AvatarManager {
    constructor(username) {
        this.username = username;
        this.loadAvatar();
    }

    loadAvatar() {
        const savedAvatar = localStorage.getItem('userAvatar');
        if (savedAvatar) {
            try {
                this.currentAvatar = JSON.parse(savedAvatar);
            } catch(e) {
                this.setDefaultAvatar();
            }
        } else {
            this.setDefaultAvatar();
        }
        this.displayAvatar();
        this.updateMainProfileAvatar();
    }

    setDefaultAvatar() {
        this.currentAvatar = {
            type: 'initials',
            text: this.getInitials(),
            color: this.getColorFromName()
        };
    }

    getInitials() {
        if (this.username && this.username !== 'User') {
            return this.username.substring(0, 2).toUpperCase();
        }
        return '👤';
    }

    getColorFromName() {
        const colors = ['#16a34a', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4', '#6366f1'];
        const index = this.username ? this.username.length % colors.length : 0;
        return colors[index];
    }

    displayAvatar() {
        const emojiSpan = document.getElementById('avatarEmoji');
        const avatarImage = document.getElementById('avatarImage');
        
        if (!emojiSpan || !avatarImage) return;
        
        emojiSpan.style.display = 'flex';
        avatarImage.style.display = 'none';
        avatarImage.src = '';
        
        if (this.currentAvatar.type === 'initials') {
            emojiSpan.innerHTML = this.currentAvatar.text;
            emojiSpan.style.backgroundColor = this.currentAvatar.color;
            emojiSpan.style.fontSize = '1.5rem';
            emojiSpan.style.fontWeight = 'bold';
            emojiSpan.style.color = 'white';
        } 
        else if (this.currentAvatar.type === 'emoji') {
            emojiSpan.innerHTML = this.currentAvatar.emoji;
            emojiSpan.style.backgroundColor = 'transparent';
            emojiSpan.style.fontSize = '3rem';
            emojiSpan.style.fontWeight = 'normal';
            emojiSpan.style.color = 'white';
        } 
        else if (this.currentAvatar.type === 'photo' && this.currentAvatar.url) {
            emojiSpan.style.display = 'none';
            avatarImage.style.display = 'block';
            avatarImage.src = this.currentAvatar.url;
        }
        
        this.saveToLocal();
    }

    updateMainProfileAvatar() {
        const mainAvatarDiv = document.querySelector('.profile-avatar');
        if (!mainAvatarDiv) return;
        
        const contentDiv = mainAvatarDiv.querySelector('.avatar-content');
        if (!contentDiv) return;
        
        if (this.currentAvatar.type === 'initials') {
            const span = document.createElement('span');
            span.className = 'avatar-emoji';
            span.textContent = this.currentAvatar.text;
            span.style.backgroundColor = this.currentAvatar.color;
            span.style.fontSize = '1.5rem';
            span.style.fontWeight = 'bold';
            span.style.color = 'white';
            span.style.display = 'flex';
            span.style.alignItems = 'center';
            span.style.justifyContent = 'center';
            span.style.width = '100%';
            span.style.height = '100%';
            contentDiv.innerHTML = '';
            contentDiv.appendChild(span);
        } 
        else if (this.currentAvatar.type === 'emoji') {
            const span = document.createElement('span');
            span.className = 'avatar-emoji';
            span.textContent = this.currentAvatar.emoji;
            span.style.fontSize = '3rem';
            span.style.display = 'flex';
            span.style.alignItems = 'center';
            span.style.justifyContent = 'center';
            span.style.width = '100%';
            span.style.height = '100%';
            contentDiv.innerHTML = '';
            contentDiv.appendChild(span);
        } 
        else if (this.currentAvatar.type === 'photo' && this.currentAvatar.url) {
            const img = document.createElement('img');
            img.className = 'avatar-image';
            img.src = this.currentAvatar.url;
            img.style.width = '100%';
            img.style.height = '100%';
            img.style.objectFit = 'cover';
            contentDiv.innerHTML = '';
            contentDiv.appendChild(img);
        }
    }

    saveToLocal() {
        localStorage.setItem('userAvatar', JSON.stringify(this.currentAvatar));
    }

    setAvatar(avatarData) {
        this.currentAvatar = avatarData;
        this.displayAvatar();
        this.updateMainProfileAvatar();
        this.showToast('✅ Avatar updated!');
    }

    showToast(message) {
        const existing = document.querySelectorAll('.avatar-toast');
        existing.forEach(t => t.remove());
        
        const toast = document.createElement('div');
        toast.className = 'avatar-toast';
        toast.textContent = message;
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
    }
}

// ========== GOAL TRACKER ==========
let userGoals = [];

function loadGoals() {
    const savedGoals = localStorage.getItem('userGoals');
    if (savedGoals) {
        userGoals = JSON.parse(savedGoals);
    } else {
        userGoals = [
            { id: 1, name: "Drink Water", target: 8, current: 5, unit: "glasses" },
            { id: 2, name: "Exercise", target: 30, current: 15, unit: "minutes" },
            { id: 3, name: "Eat Vegetables", target: 5, current: 3, unit: "portions" }
        ];
        saveGoals();
    }
    renderGoals();
}

function saveGoals() {
    localStorage.setItem('userGoals', JSON.stringify(userGoals));
}

function renderGoals() {
    const goalsList = document.getElementById('goalsList');
    if (!goalsList) return;
    
    if (userGoals.length === 0) {
        goalsList.innerHTML = '<p style="text-align: center; color: #94a3b8; padding: 1rem;">No goals yet. Click "Add Goal" to start! 🎯</p>';
        document.getElementById('goalsStats').innerHTML = '<span>🎯 0/0 goals completed</span>';
        return;
    }
    
    let completedCount = 0;
    goalsList.innerHTML = userGoals.map(goal => {
        const progress = (goal.current / goal.target) * 100;
        const isCompleted = goal.current >= goal.target;
        if (isCompleted) completedCount++;
        
        return `
            <div class="goal-item">
                <div class="goal-info">
                    <div class="goal-name">${escapeHtml(goal.name)} ${isCompleted ? '✅' : ''}</div>
                    <div class="goal-progress-text">${goal.current}/${goal.target} ${goal.unit} (${Math.min(100, Math.floor(progress))}%)</div>
                </div>
                <div class="goal-progress-bar">
                    <div class="goal-progress-fill" style="width: ${Math.min(100, progress)}%"></div>
                </div>
                <div class="goal-actions">
                    ${!isCompleted ? `<button class="goal-increment" onclick="incrementGoal(${goal.id})">+1</button>` : ''}
                    <button class="goal-delete" onclick="deleteGoal(${goal.id})">🗑️</button>
                </div>
            </div>
        `;
    }).join('');
    
    const totalGoals = userGoals.length;
    const statsText = totalGoals > 0 ? `🎯 ${completedCount}/${totalGoals} goals completed` : '🎯 0/0 goals completed';
    document.getElementById('goalsStats').innerHTML = `<span>${statsText}</span>`;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

let nextGoalId = 4;

function openAddGoalModal() {
    document.getElementById('addGoalModal').style.display = 'block';
}

function closeAddGoalModal() {
    document.getElementById('addGoalModal').style.display = 'none';
    document.getElementById('goalName').value = '';
    document.getElementById('goalTarget').value = '';
    document.getElementById('goalUnit').value = '';
}

function addGoal() {
    const name = document.getElementById('goalName').value.trim();
    const target = parseInt(document.getElementById('goalTarget').value);
    const unit = document.getElementById('goalUnit').value.trim();
    
    if (!name || !target || !unit) {
        alert('Please fill all fields');
        return;
    }
    
    userGoals.push({
        id: nextGoalId++,
        name: name,
        target: target,
        current: 0,
        unit: unit
    });
    
    saveGoals();
    renderGoals();
    closeAddGoalModal();
}

function incrementGoal(id) {
    const goal = userGoals.find(g => g.id === id);
    if (goal && goal.current < goal.target) {
        goal.current = Math.min(goal.target, goal.current + 1);
        saveGoals();
        renderGoals();
    }
}

function deleteGoal(id) {
    userGoals = userGoals.filter(g => g.id !== id);
    saveGoals();
    renderGoals();
}

// ========== FRIEND ACTIVITY ==========
const friendsData = [
    { name: "Sarah Chen", avatar: "👩‍🦱", activity: "completed her weekly goal! 🎯", time: "2 min ago" },
    { name: "Mike Johnson", avatar: "👨‍🦰", activity: "tried a new vegan recipe 🥗", time: "15 min ago" },
    { name: "Emma Wilson", avatar: "👩‍🦳", activity: "reached level 5! 🏆", time: "1 hour ago" },
    { name: "David Kim", avatar: "👨‍🦱", activity: "logged 3 healthy meals 🍽️", time: "3 hours ago" },
    { name: "Lisa Martin", avatar: "👩", activity: "shared a workout plan 💪", time: "5 hours ago" }
];

function loadFriends() {
    const friendsList = document.getElementById('friendsList');
    if (!friendsList) return;
    
    friendsList.innerHTML = friendsData.map(friend => `
        <div class="friend-item">
            <div class="friend-avatar">${friend.avatar}</div>
            <div class="friend-info">
                <div class="friend-name">${friend.name}</div>
                <div class="friend-activity">${friend.activity}</div>
            </div>
            <div class="friend-time">${friend.time}</div>
        </div>
    `).join('');
}

// ========== DELETE ACCOUNT MODAL ==========
function openDeleteModal() {
    document.getElementById('deleteModal').style.display = 'block';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
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

// ========== PROFILE FORM VALIDATION ==========
function validateProfileForm() {
    let isValid = true;
    
    const username = document.getElementById('username');
    if (username && username.value.length < 3) {
        document.getElementById('usernameError').textContent = 'Username must be at least 3 characters';
        isValid = false;
    } else {
        document.getElementById('usernameError').textContent = '';
    }
    
    const age = document.getElementById('age');
    if (age && age.value && (age.value < 1 || age.value > 120)) {
        document.getElementById('ageError').textContent = 'Age must be between 1 and 120';
        isValid = false;
    } else {
        document.getElementById('ageError').textContent = '';
    }
    
    const weight = document.getElementById('weight');
    if (weight && weight.value && (weight.value < 20 || weight.value > 300)) {
        document.getElementById('weightError').textContent = 'Weight must be between 20 and 300 kg';
        isValid = false;
    } else {
        document.getElementById('weightError').textContent = '';
    }
    
    const height = document.getElementById('height');
    if (height && height.value && (height.value < 100 || height.value > 250)) {
        document.getElementById('heightError').textContent = 'Height must be between 100 and 250 cm';
        isValid = false;
    } else {
        document.getElementById('heightError').textContent = '';
    }
    
    return isValid;
}

// ========== INITIALIZATION ==========
document.addEventListener('DOMContentLoaded', function() {
    const username = '<?php echo htmlspecialchars($_SESSION['username']); ?>';
    avatarManager = new AvatarManager(username);
    loadGoals();
    loadFriends();
});

// Close modals when clicking outside
window.onclick = function(event) {
    const deleteModal = document.getElementById('deleteModal');
    const addGoalModal = document.getElementById('addGoalModal');
    
    if (event.target == deleteModal) closeDeleteModal();
    if (event.target == addGoalModal) closeAddGoalModal();
}

// Animation keyframes
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
