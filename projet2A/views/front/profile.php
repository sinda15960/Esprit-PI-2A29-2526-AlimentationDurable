<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar" id="dynamicAvatar">
                <span id="avatarEmoji">👤</span>
            </div>
            <h2><?php echo htmlspecialchars($_SESSION['full_name'] ?: $_SESSION['username']); ?></h2>
            <p class="profile-email"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <span class="role-badge"><?php echo ucfirst($_SESSION['role']); ?></span>
        </div>

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

        <!-- Daily Affirmation Section -->
        <div class="affirmation-section">
            <div class="affirmation-card">
                <div class="affirmation-icon">💭</div>
                <div class="affirmation-content">
                    <p id="affirmationText" class="affirmation-text">Loading your daily inspiration...</p>
                    <button class="affirmation-refresh" onclick="refreshAffirmation()">🔄 New affirmation</button>
                </div>
            </div>
        </div>

        <!-- Login Streak Section -->
        <div class="streak-section">
            <div class="streak-card">
                <div class="streak-icon">🔥</div>
                <div class="streak-info">
                    <span class="streak-number" id="streakDays">0</span>
                    <span class="streak-label">day streak</span>
                </div>
                <div class="streak-reward" id="streakReward">
                    <span>🏆</span>
                    <span>Next reward in <span id="daysToReward">7</span> days</span>
                </div>
            </div>
            <div class="streak-milestones">
                <div class="milestone" data-days="7">
                    <span>7️⃣</span>
                    <span>7 days</span>
                    <span class="milestone-badge" id="badge7">🔒</span>
                </div>
                <div class="milestone" data-days="14">
                    <span>🏅</span>
                    <span>14 days</span>
                    <span class="milestone-badge" id="badge14">🔒</span>
                </div>
                <div class="milestone" data-days="30">
                    <span>🎖️</span>
                    <span>30 days</span>
                    <span class="milestone-badge" id="badge30">🔒</span>
                </div>
                <div class="milestone" data-days="100">
                    <span>🏆</span>
                    <span>100 days</span>
                    <span class="milestone-badge" id="badge100">🔒</span>
                </div>
            </div>
        </div>

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

        <!-- Theme Customizer Section -->
        <div class="theme-section">
            <h3>🎨 Theme Customizer</h3>
            <div class="theme-options">
                <button class="theme-option" data-theme="green" onclick="setTheme('green')">
                    <span class="theme-color green"></span> Green
                </button>
                <button class="theme-option" data-theme="blue" onclick="setTheme('blue')">
                    <span class="theme-color blue"></span> Blue
                </button>
                <button class="theme-option" data-theme="purple" onclick="setTheme('purple')">
                    <span class="theme-color purple"></span> Purple
                </button>
                <button class="theme-option" data-theme="orange" onclick="setTheme('orange')">
                    <span class="theme-color orange"></span> Orange
                </button>
                <button class="theme-option" data-theme="pink" onclick="setTheme('pink')">
                    <span class="theme-color pink"></span> Pink
                </button>
                <button class="theme-option" data-theme="dark" onclick="setTheme('dark')">
                    <span class="theme-color dark"></span> Dark
                </button>
            </div>
            <button class="reset-theme-btn" onclick="resetTheme()">Reset to Default</button>
        </div>

        <!-- Time Machine Section -->
        <div class="timemachine-section">
            <h3 class="timemachine-title">🕰️ Login Time Machine</h3>
            <div class="timemachine-card" id="timemachineCard">
                <div class="timemachine-stats">
                    <div class="timestat">
                        <span class="timestat-label">First login</span>
                        <span class="timestat-value" id="firstLogin">--</span>
                    </div>
                    <div class="timestat">
                        <span class="timestat-label">Last login</span>
                        <span class="timestat-value" id="lastLogin">--</span>
                    </div>
                    <div class="timestat">
                        <span class="timestat-label">Total logins</span>
                        <span class="timestat-value" id="totalLogins">0</span>
                    </div>
                </div>
                <div class="timemachine-history" id="loginHistory">
                    <p class="history-placeholder">Loading your login history...</p>
                </div>
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

<style>
/* Daily Affirmation Section */
.affirmation-section {
    margin-bottom: 1.5rem;
}

.affirmation-card {
    background: linear-gradient(135deg, #f3e8ff, #e9d5ff);
    border-radius: 20px;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.affirmation-icon {
    font-size: 2rem;
}

.affirmation-content {
    flex: 1;
}

.affirmation-text {
    font-size: 0.9rem;
    color: #4c1d95;
    font-style: italic;
    margin-bottom: 0.5rem;
    transition: opacity 0.2s ease;
}

.affirmation-refresh {
    background: none;
    border: none;
    font-size: 0.7rem;
    color: #7c3aed;
    cursor: pointer;
    transition: all 0.3s;
}

.affirmation-refresh:hover {
    transform: rotate(180deg);
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

.reactivated-message::before {
    content: '✨';
    position: absolute;
    top: -10px;
    right: -10px;
    font-size: 3rem;
    opacity: 0.2;
    animation: sparkle 2s infinite;
}

@keyframes sparkle {
    0%, 100% { transform: rotate(0deg); opacity: 0.2; }
    50% { transform: rotate(180deg); opacity: 0.4; }
}

.reactivated-icon {
    font-size: 2.5rem;
    animation: bounce 0.6s ease;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
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

/* Streak Section */
.streak-section {
    background: linear-gradient(135deg, #fff7ed, #fffbeb);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    text-align: center;
}

.streak-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #fde68a;
}

.streak-icon {
    font-size: 2.5rem;
    animation: flameFlicker 1s infinite;
}

@keyframes flameFlicker {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.9; }
}

.streak-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: #ea580c;
    display: block;
    line-height: 1;
}

.streak-label {
    font-size: 0.8rem;
    color: #9a3412;
}

.streak-reward {
    background: #fef3c7;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.8rem;
    color: #b45309;
}

.streak-milestones {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 1rem;
}

.milestone {
    text-align: center;
    font-size: 0.7rem;
    color: #92400e;
}

.milestone-badge {
    display: block;
    font-size: 1rem;
    margin-top: 0.25rem;
}

.milestone-badge.unlocked {
    filter: drop-shadow(0 0 5px #fbbf24);
}

/* Goal Tracker Section */
.goals-section {
    background: #f8fafc;
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
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

.goal-info {
    flex: 1;
}

.goal-name {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.9rem;
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

.friends-section h3 {
    font-size: 1.1rem;
    color: #0c4a6e;
    margin-bottom: 1rem;
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

.friend-activity {
    font-size: 0.7rem;
    color: #64748b;
}

.friend-time {
    font-size: 0.65rem;
    color: #94a3b8;
}

/* Theme Customizer Section */
.theme-section {
    background: #f1f5f9;
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    text-align: center;
}

.theme-section h3 {
    font-size: 1.1rem;
    color: #1e293b;
    margin-bottom: 1rem;
}

.theme-options {
    display: flex;
    justify-content: center;
    gap: 0.75rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.theme-option {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 50px;
    padding: 0.4rem 0.8rem;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.theme-color {
    width: 16px;
    height: 16px;
    border-radius: 50%;
}

.theme-color.green { background: #16a34a; }
.theme-color.blue { background: #3b82f6; }
.theme-color.purple { background: #8b5cf6; }
.theme-color.orange { background: #f97316; }
.theme-color.pink { background: #ec4899; }
.theme-color.dark { background: #1e293b; }

.theme-option:hover {
    transform: translateY(-2px);
    border-color: #16a34a;
}

.theme-option.active {
    background: linear-gradient(135deg, #16a34a, #14532d);
    color: white;
    border-color: #16a34a;
}

.reset-theme-btn {
    background: #e2e8f0;
    border: none;
    padding: 0.4rem 1rem;
    border-radius: 50px;
    font-size: 0.7rem;
    cursor: pointer;
    transition: all 0.3s;
}

.reset-theme-btn:hover {
    background: #cbd5e0;
}

/* Time Machine Section */
.timemachine-section {
    background: linear-gradient(135deg, #1e293b, #0f172a);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    color: white;
}

.timemachine-title {
    font-size: 1.1rem;
    margin-bottom: 1rem;
    text-align: center;
}

.timemachine-card {
    background: rgba(255,255,255,0.1);
    border-radius: 16px;
    padding: 1rem;
}

.timemachine-stats {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
}

.timestat {
    text-align: center;
}

.timestat-label {
    display: block;
    font-size: 0.7rem;
    color: #94a3b8;
}

.timestat-value {
    display: block;
    font-size: 1rem;
    font-weight: 600;
    color: #facc15;
}

.timemachine-history {
    max-height: 150px;
    overflow-y: auto;
    font-size: 0.8rem;
}

.history-item {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.history-date {
    color: #94a3b8;
}

.history-time {
    color: #facc15;
}

/* Profile Avatar Animation */
@keyframes avatarPop {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.avatar-updated {
    animation: avatarPop 0.5s ease;
}

/* Error messages */
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
.success-message {
    background: #dcfce7;
    color: #166534;
    padding: 0.5rem;
    border-radius: 8px;
    margin-bottom: 1rem;
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

/* Responsive */
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
    
    .streak-card {
        flex-direction: column;
        text-align: center;
    }
    
    .theme-options {
        gap: 0.5rem;
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
// ========== DAILY AFFIRMATION ==========
const affirmations = [
    "🌟 Small steps every day lead to big results. Keep going!",
    "💪 You are stronger than you think. Every meal is a victory!",
    "🥗 Healthy eating is a form of self-respect. You've got this!",
    "🎯 Consistency over intensity. One day at a time!",
    "🌱 Your body deserves the best fuel. You're doing amazing!",
    "🏆 Every healthy choice brings you closer to your goals!",
    "🧘 Progress, not perfection. Celebrate small wins!",
    "🍎 You are what you eat. Make it count today!",
    "💧 Hydration is key! Drink water and feel energized!",
    "🌟 You're building habits that will last a lifetime!"
];

function getDailyAffirmation() {
    const today = new Date().toDateString();
    let savedAffirmation = localStorage.getItem('dailyAffirmation');
    let savedDate = localStorage.getItem('affirmationDate');
    
    if(savedDate !== today) {
        const randomIndex = Math.floor(Math.random() * affirmations.length);
        savedAffirmation = affirmations[randomIndex];
        localStorage.setItem('dailyAffirmation', savedAffirmation);
        localStorage.setItem('affirmationDate', today);
    }
    
    const affirmationText = document.getElementById('affirmationText');
    if(affirmationText) affirmationText.textContent = savedAffirmation || affirmations[0];
}

function refreshAffirmation() {
    const randomIndex = Math.floor(Math.random() * affirmations.length);
    const newAffirmation = affirmations[randomIndex];
    const affirmationText = document.getElementById('affirmationText');
    if(affirmationText) {
        affirmationText.style.opacity = '0';
        setTimeout(() => {
            affirmationText.textContent = newAffirmation;
            affirmationText.style.opacity = '1';
        }, 200);
    }
}

// ========== GOAL TRACKER ==========
let userGoals = [];

function loadGoals() {
    const savedGoals = localStorage.getItem('userGoals');
    if(savedGoals) {
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
    if(!goalsList) return;
    
    if(userGoals.length === 0) {
        goalsList.innerHTML = '<p style="text-align: center; color: #94a3b8; padding: 1rem;">No goals yet. Click "Add Goal" to start! 🎯</p>';
        document.getElementById('goalsStats').innerHTML = '<span>🎯 0/0 goals completed</span>';
        return;
    }
    
    let completedCount = 0;
    goalsList.innerHTML = userGoals.map(goal => {
        const progress = (goal.current / goal.target) * 100;
        const isCompleted = goal.current >= goal.target;
        if(isCompleted) completedCount++;
        
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
    
    if(!name || !target || !unit) {
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
    if(goal && goal.current < goal.target) {
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
    if(!friendsList) return;
    
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

// ========== THEME CUSTOMIZER ==========
function setTheme(theme) {
    const profileCard = document.querySelector('.profile-card');
    const themes = {
        green: { background: 'linear-gradient(135deg, #dcfce7, #ecfdf5)', text: '#14532d' },
        blue: { background: 'linear-gradient(135deg, #dbeafe, #eff6ff)', text: '#1e3a8a' },
        purple: { background: 'linear-gradient(135deg, #ede9fe, #f5f3ff)', text: '#4c1d95' },
        orange: { background: 'linear-gradient(135deg, #ffedd5, #fff7ed)', text: '#9a3412' },
        pink: { background: 'linear-gradient(135deg, #fce7f3, #fdf2f8)', text: '#9d174d' },
        dark: { background: 'linear-gradient(135deg, #1e293b, #0f172a)', text: '#e2e8f0' }
    };
    
    if(profileCard && themes[theme]) {
        profileCard.style.background = themes[theme].background;
        profileCard.style.color = themes[theme].text;
    }
    
    document.querySelectorAll('.theme-option').forEach(btn => {
        btn.classList.remove('active');
        if(btn.dataset.theme === theme) btn.classList.add('active');
    });
    
    localStorage.setItem('userTheme', theme);
}

function loadTheme() {
    const savedTheme = localStorage.getItem('userTheme');
    if(savedTheme && ['green', 'blue', 'purple', 'orange', 'pink', 'dark'].includes(savedTheme)) {
        setTheme(savedTheme);
    } else {
        setTheme('green');
    }
}

function resetTheme() {
    localStorage.removeItem('userTheme');
    setTheme('green');
}

// ========== LOGIN STREAK & TIME MACHINE ==========
function updateLoginStreak() {
    const today = new Date().toDateString();
    const lastLogin = localStorage.getItem('lastLoginDate');
    let streak = parseInt(localStorage.getItem('loginStreak')) || 0;
    let totalLogins = parseInt(localStorage.getItem('totalLogins')) || 0;
    
    let loginHistory = JSON.parse(localStorage.getItem('loginHistory')) || [];
    
    if(lastLogin !== today) {
        const yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);
        const yesterdayStr = yesterday.toDateString();
        
        if(lastLogin === yesterdayStr) {
            streak++;
        } else if(lastLogin !== today) {
            streak = 1;
        }
        
        const now = new Date();
        const loginEntry = {
            date: now.toLocaleDateString(),
            time: now.toLocaleTimeString(),
            timestamp: now.getTime()
        };
        loginHistory.unshift(loginEntry);
        
        if(loginHistory.length > 10) loginHistory.pop();
        
        totalLogins++;
        
        localStorage.setItem('lastLoginDate', today);
        localStorage.setItem('loginStreak', streak);
        localStorage.setItem('totalLogins', totalLogins);
        localStorage.setItem('loginHistory', JSON.stringify(loginHistory));
        
        if(!localStorage.getItem('firstLoginDate')) {
            localStorage.setItem('firstLoginDate', now.toLocaleDateString());
        }
    }
    
    const streakElement = document.getElementById('streakDays');
    if(streakElement) streakElement.textContent = streak;
    
    updateStreakRewards(streak);
    updateTimeMachine();
}

function updateStreakRewards(streak) {
    const milestones = [7, 14, 30, 100];
    const badges = {
        7: document.getElementById('badge7'),
        14: document.getElementById('badge14'),
        30: document.getElementById('badge30'),
        100: document.getElementById('badge100')
    };
    
    milestones.forEach(day => {
        const badge = badges[day];
        if(badge) {
            if(streak >= day) {
                badge.textContent = '✅';
                badge.classList.add('unlocked');
            } else {
                badge.textContent = '🔒';
                badge.classList.remove('unlocked');
            }
        }
    });
    
    const daysToReward = document.getElementById('daysToReward');
    if(daysToReward) {
        if(streak < 7) daysToReward.textContent = 7 - streak;
        else if(streak < 14) daysToReward.textContent = 14 - streak;
        else if(streak < 30) daysToReward.textContent = 30 - streak;
        else if(streak < 100) daysToReward.textContent = 100 - streak;
        else daysToReward.textContent = '0';
    }
}

function updateTimeMachine() {
    const firstLogin = localStorage.getItem('firstLoginDate');
    const lastLogin = localStorage.getItem('lastLoginDate');
    const totalLogins = localStorage.getItem('totalLogins') || 0;
    const loginHistory = JSON.parse(localStorage.getItem('loginHistory')) || [];
    
    const firstLoginEl = document.getElementById('firstLogin');
    const lastLoginEl = document.getElementById('lastLogin');
    const totalLoginsEl = document.getElementById('totalLogins');
    const historyContainer = document.getElementById('loginHistory');
    
    if(firstLoginEl) firstLoginEl.textContent = firstLogin || 'Today';
    if(lastLoginEl) lastLoginEl.textContent = lastLogin || 'Today';
    if(totalLoginsEl) totalLoginsEl.textContent = totalLogins;
    
    if(historyContainer) {
        if(loginHistory.length === 0) {
            historyContainer.innerHTML = '<p class="history-placeholder">✨ Your login history will appear here</p>';
        } else {
            historyContainer.innerHTML = loginHistory.map(entry => `
                <div class="history-item">
                    <span class="history-date">${entry.date}</span>
                    <span class="history-time">${entry.time}</span>
                </div>
            `).join('');
        }
    }
}

// ========== DYNAMIC AVATAR GENERATOR ==========
function generateAvatar(username, email) {
    const name = username || email.split('@')[0];
    const colors = ['#16a34a', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4', '#6366f1'];
    const colorIndex = name.length % colors.length;
    const backgroundColor = colors[colorIndex];
    const emojis = ['😊', '🌟', '💪', '🎯', '🍎', '🥗', '🏃', '🧘', '📚', '🎨'];
    const emojiIndex = name.charCodeAt(0) % emojis.length;
    const avatarEmoji = emojis[emojiIndex];
    
    const avatarElement = document.getElementById('dynamicAvatar');
    const avatarSpan = document.getElementById('avatarEmoji');
    
    if(avatarSpan) {
        avatarSpan.textContent = avatarEmoji;
        avatarSpan.style.fontSize = '3rem';
    }
    
    if(avatarElement) {
        avatarElement.style.background = `linear-gradient(135deg, ${backgroundColor}, ${backgroundColor}dd)`;
        avatarElement.classList.add('avatar-updated');
        setTimeout(() => avatarElement.classList.remove('avatar-updated'), 500);
    }
}

// ========== INITIALIZE ALL ==========
document.addEventListener('DOMContentLoaded', function() {
    const username = '<?php echo htmlspecialchars($_SESSION['username']); ?>';
    const email = '<?php echo htmlspecialchars($_SESSION['email']); ?>';
    
    generateAvatar(username, email);
    updateLoginStreak();
    getDailyAffirmation();
    loadGoals();
    loadFriends();
    loadTheme();
});

// ========== DELETE ACCOUNT MODAL ==========
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
    if(confirmInput === 'DELETE') {
        if(confirm('Are you absolutely sure? This action cannot be undone!')) {
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
    if(username && username.value.length < 3) {
        document.getElementById('usernameError').textContent = 'Username must be at least 3 characters';
        isValid = false;
    } else {
        document.getElementById('usernameError').textContent = '';
    }
    
    const age = document.getElementById('age');
    if(age && age.value && (age.value < 1 || age.value > 120)) {
        document.getElementById('ageError').textContent = 'Age must be between 1 and 120';
        isValid = false;
    } else {
        document.getElementById('ageError').textContent = '';
    }
    
    const weight = document.getElementById('weight');
    if(weight && weight.value && (weight.value < 20 || weight.value > 300)) {
        document.getElementById('weightError').textContent = 'Weight must be between 20 and 300 kg';
        isValid = false;
    } else {
        document.getElementById('weightError').textContent = '';
    }
    
    const height = document.getElementById('height');
    if(height && height.value && (height.value < 100 || height.value > 250)) {
        document.getElementById('heightError').textContent = 'Height must be between 100 and 250 cm';
        isValid = false;
    } else {
        document.getElementById('heightError').textContent = '';
    }
    
    return isValid;
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    const addGoalModal = document.getElementById('addGoalModal');
    if (event.target == modal) closeDeleteModal();
    if (event.target == addGoalModal) closeAddGoalModal();
}
</script>
