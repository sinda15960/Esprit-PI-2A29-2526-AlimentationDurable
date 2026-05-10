<div class="dashboard-container">
    <!-- Widget Settings Button -->
    <div class="widget-settings">
        <button class="btn-settings" onclick="openWidgetSettings()">
            <span>⚙️</span> Customize Dashboard
        </button>
    </div>

    <!-- Stats Grid (Widget 1) -->
    <div id="widget-stats" class="stats-grid">
        <?php
        // Calcul des statistiques directement en PHP
        $adminCount = 0;
        $disabledCount = 0;
        $activeCount = 0;
        $newThisMonth = 0;
        $newThisWeek = 0;
        $firstDayOfMonth = date('Y-m-d H:i:s', strtotime('first day of this month'));
        $weekAgo = date('Y-m-d H:i:s', strtotime('-7 days'));
        
        foreach($users as $user) {
            if($user['role'] == 'admin') $adminCount++;
            if(isset($user['is_active']) && $user['is_active'] == 0) $disabledCount++;
            if(isset($user['is_active']) && $user['is_active'] == 1) $activeCount++;
            if(strtotime($user['created_at']) > strtotime($firstDayOfMonth)) $newThisMonth++;
            if(strtotime($user['created_at']) > strtotime($weekAgo)) $newThisWeek++;
        }
        $regularUsers = count($users) - $adminCount;
        ?>
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <h3>Total Users</h3>
                <p class="stat-number"><?php echo count($users); ?></p>
                <p class="stat-trend">📈 +<?php echo $newThisMonth; ?> this month</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">👑</div>
            <div class="stat-info">
                <h3>Admins</h3>
                <p class="stat-number"><?php echo $adminCount; ?></p>
                <p class="stat-trend">👑 Platform managers</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">👤</div>
            <div class="stat-info">
                <h3>Regular Users</h3>
                <p class="stat-number"><?php echo $regularUsers; ?></p>
                <p class="stat-trend">👥 Active community</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🚫</div>
            <div class="stat-info">
                <h3>Disabled Users</h3>
                <p class="stat-number"><?php echo $disabledCount; ?></p>
                <p class="stat-trend">⚠️ Accounts suspended</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🟢</div>
            <div class="stat-info">
                <h3>Active Users</h3>
                <p class="stat-number"><?php echo $activeCount; ?></p>
                <p class="stat-trend">✅ Currently active</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-info">
                <h3>New This Week</h3>
                <p class="stat-number"><?php echo $newThisWeek; ?></p>
                <p class="stat-trend">🆕 New registrations</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🍽️</div>
            <div class="stat-info">
                <h3>Total Recipes</h3>
                <p class="stat-number">128</p>
                <p class="stat-trend">📖 +12 this month</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-info">
                <h3>Donations</h3>
                <p class="stat-number">$5,240</p>
                <p class="stat-trend">💝 156 donors</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🛒</div>
            <div class="stat-info">
                <h3>Market Orders</h3>
                <p class="stat-number">342</p>
                <p class="stat-trend">📦 28 vendors</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">⭐</div>
            <div class="stat-info">
                <h3>Avg Rating</h3>
                <p class="stat-number">4.8</p>
                <p class="stat-trend">★★★★★</p>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div id="widget-analytics" class="analytics-section">
        <h3>📊 User Analytics</h3>
        <div class="charts-grid">
            <div class="chart-card">
                <h4>Monthly Registrations</h4>
                <canvas id="registrationsChart" width="400" height="200"></canvas>
            </div>
            <div class="chart-card">
                <h4>User Activity (Last 7 Days)</h4>
                <canvas id="activityChart" width="400" height="200"></canvas>
            </div>
            <div class="chart-card">
                <h4>Dietary Preferences</h4>
                <canvas id="dietaryChart" width="400" height="200"></canvas>
            </div>
            <div class="chart-card">
                <h4>Users by Role</h4>
                <canvas id="roleChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- World Map Section (Widget 3) -->
    <div id="widget-worldmap" class="worldmap-section">
        <h3>🌍 User Locations</h3>
        <div class="worldmap-container">
            <canvas id="worldMapCanvas" width="800" height="400"></canvas>
            <div class="map-stats">
                <div class="map-stat">
                    <span class="map-stat-label">🌎 Countries</span>
                    <span class="map-stat-value" id="countryCount">12</span>
                </div>
                <div class="map-stat">
                    <span class="map-stat-label">📍 Cities</span>
                    <span class="map-stat-value" id="cityCount">34</span>
                </div>
                <div class="map-stat">
                    <span class="map-stat-label">🌍 Continents</span>
                    <span class="map-stat-value">6</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications Section (Widget 4) -->
    <div id="widget-notifications" class="notifications-section">
        <h3>🔔 Notifications <span class="notification-badge" id="notificationBadge">0</span></h3>
        <div class="notifications-list" id="notificationsList">
            <div class="loading">Loading notifications...</div>
        </div>
    </div>

    <!-- Contact Messages Section (Widget 5) -->
    <div id="widget-messages" class="contact-messages-section">
        <div class="section-header">
            <h3>📬 Contact Messages</h3>
            <div class="export-buttons">
                <button onclick="exportData('users', 'csv')" class="btn-export">📊 Export Users CSV</button>
                <button onclick="exportData('users', 'excel')" class="btn-export">📊 Export Users Excel</button>
                <button onclick="exportData('messages', 'csv')" class="btn-export">📧 Export Messages CSV</button>
                <button onclick="exportData('messages', 'excel')" class="btn-export">📧 Export Messages Excel</button>
            </div>
        </div>
        <div class="messages-container" id="messagesContainer">
            <div class="loading-messages">Loading messages...</div>
        </div>
    </div>

    <!-- Management Sections — liens vers les modules du dépôt (dons, recettes, frigo, plans, allergies) -->
    <?php
    if (!function_exists('nf_repo_url')) {
        require_once dirname(__DIR__, 2) . '/config/paths.php';
    }
    $nfDonationsDashboard = nf_repo_url('dashboard.php') . '#donations';
    $nfDonationForm = nf_repo_url('form.php');
    $nfRecipesPublic = nf_projet_url('public/index.php');
    $nfFrigoIndex = nf_repo_url('frigo/index.php');
    $nfPlanBack = nf_repo_url('gestion_plan/index.php') . '?office=back&module=programme&action=index';
    $nfPlanCreate = nf_repo_url('gestion_plan/index.php') . '?office=back&module=programme&action=create';
    $nfGestionAllergies = nf_repo_url('gestion_allergies.php');
    $nfAddAllergie = nf_repo_url('addAllergie.php');
    ?>
    <div class="management-sections">
        <div class="section-header">
            <h2>Management Dashboard</h2>
            <p>Manage all aspects of NutriFlow AI platform</p>
        </div>

        <div class="management-grid">
            <!-- Gestion Donation -->
            <div class="management-card">
                <div class="card-icon">💰</div>
                <h3>Donation Management</h3>
                <p>Manage donations, track fundraising campaigns, and view donor history.</p>
                <div class="card-stats">
                    <div class="stat">
                        <span class="stat-label">Total Donations</span>
                        <span class="stat-value">$5,240</span>
                    </div>
                    <div class="stat">
                        <span class="stat-label">Donors</span>
                        <span class="stat-value">156</span>
                    </div>
                    <div class="stat">
                        <span class="stat-label">Campaigns</span>
                        <span class="stat-value">4</span>
                    </div>
                </div>
                <div class="card-actions">
                    <a class="btn-card" href="<?php echo htmlspecialchars($nfDonationsDashboard); ?>">Manage Donations</a>
                    <a class="btn-card secondary" href="<?php echo htmlspecialchars($nfDonationForm); ?>">+ New donation</a>
                </div>
            </div>

            <!-- Gestion Recette -->
            <div class="management-card">
                <div class="card-icon">🍽️</div>
                <h3>Recipe Management</h3>
                <p>Create, edit, and manage healthy recipes with nutritional information.</p>
                <div class="card-stats">
                    <div class="stat">
                        <span class="stat-label">Total Recipes</span>
                        <span class="stat-value">128</span>
                    </div>
                    <div class="stat">
                        <span class="stat-label">Categories</span>
                        <span class="stat-value">12</span>
                    </div>
                    <div class="stat">
                        <span class="stat-label">Avg Rating</span>
                        <span class="stat-value">4.8 ★</span>
                    </div>
                </div>
                <div class="card-actions">
                    <a class="btn-card" href="<?php echo htmlspecialchars($nfRecipesPublic); ?>?action=backRecipes">Manage Recipes</a>
                    <a class="btn-card secondary" href="<?php echo htmlspecialchars($nfRecipesPublic); ?>?action=backCreateRecipe">+ New Recipe</a>
                </div>
            </div>

            <!-- Gestion Market Place -->
            <div class="management-card">
                <div class="card-icon">🛒</div>
                <h3>Market Place Management</h3>
                <p>Manage products, orders, vendors, and marketplace transactions.</p>
                <div class="card-stats">
                    <div class="stat">
                        <span class="stat-label">Products</span>
                        <span class="stat-value">245</span>
                    </div>
                    <div class="stat">
                        <span class="stat-label">Orders</span>
                        <span class="stat-value">342</span>
                    </div>
                    <div class="stat">
                        <span class="stat-label">Vendors</span>
                        <span class="stat-value">28</span>
                    </div>
                </div>
                <div class="card-actions">
                    <a class="btn-card" href="<?php echo htmlspecialchars($nfFrigoIndex); ?>?mode=back&amp;controller=commande&amp;action=index">Manage Marketplace</a>
                    <a class="btn-card secondary" href="<?php echo htmlspecialchars($nfFrigoIndex); ?>?mode=back&amp;controller=produit&amp;action=create">+ Add Product</a>
                </div>
            </div>

            <!-- Gestion Plan -->
            <div class="management-card">
                <div class="card-icon">📋</div>
                <h3>Plan Management</h3>
                <p>Create and manage meal plans, subscription plans, and pricing.</p>
                <div class="card-stats">
                    <div class="stat">
                        <span class="stat-label">Active Plans</span>
                        <span class="stat-value">6</span>
                    </div>
                    <div class="stat">
                        <span class="stat-label">Subscribers</span>
                        <span class="stat-value">1,247</span>
                    </div>
                    <div class="stat">
                        <span class="stat-label">Revenue</span>
                        <span class="stat-value">$12,450</span>
                    </div>
                </div>
                <div class="card-actions">
                    <a class="btn-card" href="<?php echo htmlspecialchars($nfPlanBack); ?>">Manage Plans</a>
                    <a class="btn-card secondary" href="<?php echo htmlspecialchars($nfPlanCreate); ?>">+ Create Plan</a>
                </div>
            </div>

            <!-- Gestion Allergies -->
            <div class="management-card">
                <div class="card-icon">⚠️</div>
                <h3>Allergy Management</h3>
                <p>Manage allergens, dietary restrictions, and user allergy profiles.</p>
                <div class="card-stats">
                    <div class="stat">
                        <span class="stat-label">Allergens</span>
                        <span class="stat-value">14</span>
                    </div>
                    <div class="stat">
                        <span class="stat-label">Users with Allergies</span>
                        <span class="stat-value">892</span>
                    </div>
                    <div class="stat">
                        <span class="stat-label">Reports</span>
                        <span class="stat-value">23</span>
                    </div>
                </div>
                <div class="card-actions">
                    <a class="btn-card" href="<?php echo htmlspecialchars($nfGestionAllergies); ?>">Manage Allergies</a>
                    <a class="btn-card secondary" href="<?php echo htmlspecialchars($nfAddAllergie); ?>">+ Add Allergen</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Widget Settings Modal -->
<div id="widgetModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Customize Dashboard</h2>
            <span class="modal-close" onclick="closeWidgetModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>Select which widgets to display:</p>
            <div class="widget-options">
                <label><input type="checkbox" value="stats" checked> 📊 Statistics Cards</label>
                <label><input type="checkbox" value="analytics" checked> 📈 Analytics Charts</label>
                <label><input type="checkbox" value="worldmap" checked> 🌍 World Map</label>
                <label><input type="checkbox" value="notifications" checked> 🔔 Notifications</label>
                <label><input type="checkbox" value="messages" checked> 📬 Contact Messages</label>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-save" onclick="saveWidgetSettings()">Save Settings</button>
            <button class="btn-cancel" onclick="closeWidgetModal()">Cancel</button>
        </div>
    </div>
</div>

<style>
/* Dashboard Container */
.dashboard-container {
    padding: 0;
}

/* Integrated module cards */
.management-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.25rem;
}
.management-card {
    background: white;
    border-radius: 15px;
    padding: 1.25rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.management-card .card-icon {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}
.management-card h3 {
    margin: 0 0 0.5rem;
    color: #1e293b;
    font-size: 1.05rem;
}
.management-card > p {
    color: #64748b;
    font-size: 0.85rem;
    margin-bottom: 1rem;
    line-height: 1.45;
}
.card-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-bottom: 1rem;
}
.card-stats .stat {
    flex: 1;
    min-width: 72px;
    background: #f8fafc;
    border-radius: 10px;
    padding: 0.5rem 0.65rem;
}
.card-stats .stat-label {
    display: block;
    font-size: 0.65rem;
    color: #64748b;
}
.card-stats .stat-value {
    font-weight: 700;
    color: #16a34a;
    font-size: 0.85rem;
}
.card-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
a.btn-card {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    box-sizing: border-box;
    width: 100%;
    padding: 0.55rem 1rem;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: transform 0.2s, box-shadow 0.2s;
    background: linear-gradient(135deg, #16a34a, #14532d);
    color: white !important;
}
a.btn-card.secondary {
    background: #f1f5f9;
    color: #334155 !important;
    border: 1px solid #e2e8f0;
}
a.btn-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25);
}
a.btn-card.secondary:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

/* Widget Settings */
.widget-settings {
    text-align: right;
    margin-bottom: 1rem;
}

.btn-settings {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    padding: 0.5rem 1rem;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-settings:hover {
    background: #e2e8f0;
    transform: translateY(-2px);
}

/* Stat Cards */
.stat-card {
    position: relative;
    overflow: hidden;
}

.stat-trend {
    font-size: 0.7rem;
    color: #16a34a;
    margin-top: 0.5rem;
    font-weight: 500;
}

.stat-trend.negative {
    color: #ef4444;
}

/* Analytics Section */
.analytics-section {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.analytics-section h3 {
    margin-bottom: 1rem;
    color: #2d3748;
}

.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
}

.chart-card {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1rem;
    transition: all 0.3s;
}

.chart-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.chart-card h4 {
    text-align: center;
    margin-bottom: 1rem;
    color: #475569;
    font-size: 0.9rem;
}

.chart-card canvas {
    width: 100%;
    max-height: 200px;
}

/* World Map Section */
.worldmap-section {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.worldmap-section h3 {
    margin-bottom: 1rem;
    color: #2d3748;
}

.worldmap-container {
    text-align: center;
}

#worldMapCanvas {
    width: 100%;
    max-width: 800px;
    height: auto;
    background: linear-gradient(135deg, #1e3a5f, #0f172a);
    border-radius: 12px;
    margin-bottom: 1rem;
}

.map-stats {
    display: flex;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
}

.map-stat {
    text-align: center;
    padding: 0.5rem 1rem;
    background: #f1f5f9;
    border-radius: 12px;
}

.map-stat-label {
    display: block;
    font-size: 0.7rem;
    color: #64748b;
}

.map-stat-value {
    display: block;
    font-size: 1.2rem;
    font-weight: 700;
    color: #16a34a;
}

/* Notifications Section */
.notifications-section {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.notifications-section h3 {
    margin-bottom: 1rem;
    color: #2d3748;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.notification-badge {
    background: #ef4444;
    color: white;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    font-size: 0.75rem;
}

.notifications-list {
    max-height: 300px;
    overflow-y: auto;
}

.notification-item {
    padding: 0.75rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s;
    cursor: pointer;
}

.notification-item:hover {
    background: #f8fafc;
}

.notification-item.unread {
    background: #fef2f2;
    border-left: 3px solid #ef4444;
}

.notification-icon {
    font-size: 1.5rem;
}

.notification-content {
    flex: 1;
}

.notification-message {
    font-size: 0.85rem;
    color: #1e293b;
}

.notification-time {
    font-size: 0.7rem;
    color: #94a3b8;
}

/* Contact Messages Section */
.contact-messages-section {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e2e8f0;
    flex-wrap: wrap;
    gap: 1rem;
}

.section-header h3 {
    color: #2d3748;
    font-size: 1.2rem;
    margin: 0;
}

.export-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-export {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    font-size: 0.7rem;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-export:hover {
    background: #2563eb;
    transform: translateY(-2px);
}

.messages-container {
    max-height: 400px;
    overflow-y: auto;
}

.loading-messages, .loading {
    text-align: center;
    color: #94a3b8;
    padding: 2rem;
}

.message-card {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1rem;
    border-left: 4px solid #16a34a;
    transition: all 0.3s;
}

.message-card.unread {
    border-left-color: #ef4444;
    background: #fef2f2;
}

.message-card:hover {
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.message-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.message-sender {
    font-weight: 600;
    color: #1e293b;
}

.message-email {
    color: #16a34a;
    font-size: 0.8rem;
}

.message-date {
    font-size: 0.7rem;
    color: #94a3b8;
}

.message-content {
    color: #475569;
    font-size: 0.85rem;
    margin: 0.5rem 0;
    line-height: 1.5;
}

.message-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.btn-mark-read {
    background: #16a34a;
    color: white;
    border: none;
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    font-size: 0.7rem;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-mark-read:hover {
    background: #15803d;
}

.btn-delete-message {
    background: #ef4444;
    color: white;
    border: none;
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    font-size: 0.7rem;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-delete-message:hover {
    background: #dc2626;
}

.btn-reply {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    font-size: 0.7rem;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-reply:hover {
    background: #2563eb;
}

.no-messages {
    text-align: center;
    color: #94a3b8;
    padding: 2rem;
    font-size: 0.9rem;
}

/* Widget Modal */
.widget-options {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-top: 1rem;
}

.widget-options label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: all 0.3s;
}

.widget-options label:hover {
    background: #f1f5f9;
}

.widget-options input {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #16a34a;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 2000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    animation: fadeIn 0.3s ease;
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    width: 90%;
    max-width: 500px;
    border-radius: 20px;
    animation: slideDownModal 0.3s ease;
    overflow: hidden;
}

.modal-header {
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #16a34a, #14532d);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    font-size: 1.2rem;
}

.modal-close {
    font-size: 1.5rem;
    cursor: pointer;
    transition: opacity 0.3s;
}

.modal-close:hover {
    opacity: 0.7;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    padding: 1rem 1.5rem 1.5rem;
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.btn-save {
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #16a34a, #14532d);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-save:hover {
    transform: translateY(-2px);
}

.btn-cancel {
    padding: 0.5rem 1rem;
    background: #e2e8f0;
    color: #4a5568;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-cancel:hover {
    background: #cbd5e0;
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    margin: 1rem 0;
}

.progress {
    height: 100%;
    background: linear-gradient(90deg, #16a34a, #14532d);
    border-radius: 10px;
}

.progress-text {
    font-size: 0.7rem;
    color: #94a3b8;
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

/* Responsive */
@media (max-width: 768px) {
    .section-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .export-buttons {
        width: 100%;
    }
    
    .btn-export {
        flex: 1;
        text-align: center;
    }
    
    .modal-content {
        margin: 30% auto;
        width: 95%;
    }
    
    .charts-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .map-stats {
        gap: 1rem;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// ========== LOAD CHARTS ==========
function loadCharts() {
    // Données pour les graphiques
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const registrationsData = [15, 22, 18, 25, 30, 28, 35, 42, 48, 52, 58, 65];
    
    const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    const activityData = [45, 52, 48, 61, 58, 42, 38];
    
    const dietaryData = {
        labels: ['Omnivore', 'Vegetarian', 'Vegan', 'Pescatarian', 'Keto'],
        values: [45, 25, 15, 8, 7]
    };
    
    // Get actual counts from users table
    const totalUsers = <?php echo count($users); ?>;
    const adminCount = <?php echo $adminCount; ?>;
    const regularUsers = totalUsers - adminCount;
    
    // Role chart
    const ctxRole = document.getElementById('roleChart').getContext('2d');
    new Chart(ctxRole, {
        type: 'doughnut',
        data: {
            labels: ['Admins', 'Regular Users'],
            datasets: [{
                data: [adminCount, regularUsers],
                backgroundColor: ['#8b5cf6', '#16a34a'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Registration chart
    const ctxReg = document.getElementById('registrationsChart').getContext('2d');
    new Chart(ctxReg, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'New Users',
                data: registrationsData,
                borderColor: '#16a34a',
                backgroundColor: 'rgba(22, 163, 74, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#16a34a',
                pointBorderColor: '#fff',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Users'
                    }
                }
            }
        }
    });
    
    // Activity chart
    const ctxAct = document.getElementById('activityChart').getContext('2d');
    new Chart(ctxAct, {
        type: 'bar',
        data: {
            labels: days,
            datasets: [{
                label: 'Active Users',
                data: activityData,
                backgroundColor: '#3b82f6',
                borderRadius: 8,
                barPercentage: 0.7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Users'
                    }
                }
            }
        }
    });
    
    // Dietary chart
    const ctxDiet = document.getElementById('dietaryChart').getContext('2d');
    new Chart(ctxDiet, {
        type: 'pie',
        data: {
            labels: dietaryData.labels,
            datasets: [{
                data: dietaryData.values,
                backgroundColor: ['#16a34a', '#f59e0b', '#8b5cf6', '#06b6d4', '#ec4899'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

// ========== WORLD MAP ==========
function drawWorldMap() {
    const canvas = document.getElementById('worldMapCanvas');
    if(!canvas) return;
    
    const ctx = canvas.getContext('2d');
    const width = canvas.width;
    const height = canvas.height;
    
    // Simulate world map with circles
    const locations = [
        { x: width * 0.2, y: height * 0.3, name: 'North America', users: 8234 },
        { x: width * 0.35, y: height * 0.55, name: 'South America', users: 3421 },
        { x: width * 0.55, y: height * 0.25, name: 'Europe', users: 12567 },
        { x: width * 0.6, y: height * 0.5, name: 'Africa', users: 1234 },
        { x: width * 0.75, y: height * 0.35, name: 'Asia', users: 5678 },
        { x: width * 0.85, y: height * 0.7, name: 'Australia', users: 2345 }
    ];
    
    // Clear and draw background
    ctx.fillStyle = '#1e3a5f';
    ctx.fillRect(0, 0, width, height);
    
    // Draw grid lines
    ctx.strokeStyle = 'rgba(255,255,255,0.1)';
    ctx.lineWidth = 1;
    for(let i = 0; i < 5; i++) {
        ctx.beginPath();
        ctx.moveTo(0, height * (i / 4));
        ctx.lineTo(width, height * (i / 4));
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(width * (i / 4), 0);
        ctx.lineTo(width * (i / 4), height);
        ctx.stroke();
    }
    
    // Draw locations
    locations.forEach(loc => {
        const radius = Math.min(30, 10 + (loc.users / 500));
        
        // Outer glow
        ctx.beginPath();
        ctx.arc(loc.x, loc.y, radius + 5, 0, Math.PI * 2);
        ctx.fillStyle = 'rgba(34, 197, 94, 0.2)';
        ctx.fill();
        
        // Inner circle
        ctx.beginPath();
        ctx.arc(loc.x, loc.y, radius, 0, Math.PI * 2);
        ctx.fillStyle = '#16a34a';
        ctx.fill();
        ctx.strokeStyle = 'white';
        ctx.lineWidth = 2;
        ctx.stroke();
        
        // Pulse animation
        ctx.beginPath();
        ctx.arc(loc.x, loc.y, radius + 8, 0, Math.PI * 2);
        ctx.fillStyle = 'rgba(34, 197, 94, 0.3)';
        ctx.fill();
        
        // Label
        ctx.fillStyle = 'white';
        ctx.font = 'bold 10px Arial';
        ctx.shadowBlur = 4;
        ctx.shadowColor = 'black';
        ctx.fillText(loc.name, loc.x - 30, loc.y - radius - 5);
        ctx.fillText(loc.users + ' users', loc.x - 30, loc.y - radius - 15);
        ctx.shadowBlur = 0;
    });
}

// ========== NOTIFICATIONS ==========
let notificationInterval;

function loadNotifications() {
    fetch('index.php?action=admin_get_notifications')
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                const badge = document.getElementById('notificationBadge');
                if(badge) badge.textContent = data.unreadCount;
                
                const container = document.getElementById('notificationsList');
                if(!container) return;
                
                if(data.notifications.length === 0) {
                    container.innerHTML = '<div class="no-messages">🔔 No notifications</div>';
                    return;
                }
                
                container.innerHTML = data.notifications.map(notif => `
                    <div class="notification-item ${notif.is_read == 0 ? 'unread' : ''}" onclick="markNotificationRead(${notif.id})">
                        <div class="notification-icon">${getNotificationIcon(notif.type)}</div>
                        <div class="notification-content">
                            <div class="notification-message">${escapeHtml(notif.message)}</div>
                            <div class="notification-time">${formatDate(notif.created_at)}</div>
                        </div>
                    </div>
                `).join('');
            }
        })
        .catch(error => console.error('Error loading notifications:', error));
}

function getNotificationIcon(type) {
    const icons = {
        'user': '👤',
        'message': '📬',
        'alert': '⚠️',
        'success': '✅',
        'info': 'ℹ️'
    };
    return icons[type] || '🔔';
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    const now = new Date();
    const diff = Math.floor((now - date) / 1000 / 60);
    
    if(diff < 1) return 'Just now';
    if(diff < 60) return `${diff} min ago`;
    if(diff < 1440) return `${Math.floor(diff / 60)} hours ago`;
    return date.toLocaleDateString();
}

function markNotificationRead(id) {
    fetch('index.php?action=admin_mark_notification_read&id=' + id)
        .then(response => response.json())
        .then(data => {
            if(data.success) loadNotifications();
        });
}

// ========== EXPORT FUNCTIONS ==========
function exportData(type, format) {
    window.location.href = `index.php?action=admin_export_${type}&format=${format}`;
}

// ========== WIDGET SETTINGS ==========
function openWidgetSettings() {
    loadWidgetSettings();
    document.getElementById('widgetModal').style.display = 'block';
}

function closeWidgetModal() {
    document.getElementById('widgetModal').style.display = 'none';
}

function loadWidgetSettings() {
    fetch('index.php?action=admin_get_widgets')
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                const widgets = data.widgets;
                document.querySelectorAll('.widget-options input').forEach(checkbox => {
                    const value = checkbox.value;
                    checkbox.checked = widgets[value] !== false;
                });
            }
        });
}

function saveWidgetSettings() {
    const widgets = {};
    document.querySelectorAll('.widget-options input').forEach(checkbox => {
        widgets[checkbox.value] = checkbox.checked;
    });
    
    fetch('index.php?action=admin_save_widgets', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ widgets: widgets })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            applyWidgetVisibility(widgets);
            closeWidgetModal();
        }
    });
}

function applyWidgetVisibility(widgets) {
    const elements = {
        'stats': document.getElementById('widget-stats'),
        'analytics': document.getElementById('widget-analytics'),
        'worldmap': document.getElementById('widget-worldmap'),
        'notifications': document.getElementById('widget-notifications'),
        'messages': document.getElementById('widget-messages')
    };
    
    for(const [key, element] of Object.entries(elements)) {
        if(element) {
            element.style.display = widgets[key] ? 'block' : 'none';
        }
    }
}

// ========== CONTACT MESSAGES ==========
function loadContactMessages() {
    fetch('index.php?action=admin_get_messages')
        .then(response => response.json())
        .then(data => {
            renderMessages(data.messages);
        })
        .catch(error => console.error('Error:', error));
}

function renderMessages(messages) {
    const container = document.getElementById('messagesContainer');
    if(!container) return;
    
    if(messages.length === 0) {
        container.innerHTML = '<div class="no-messages">📭 No messages yet</div>';
        return;
    }
    
    container.innerHTML = messages.map(msg => `
        <div class="message-card ${msg.status === 'unread' ? 'unread' : ''}" data-id="${msg.id}">
            <div class="message-header">
                <div>
                    <span class="message-sender">${escapeHtml(msg.name)}</span>
                    <span class="message-email">(${escapeHtml(msg.email)})</span>
                </div>
                <span class="message-date">${msg.created_at}</span>
            </div>
            <div class="message-content">${escapeHtml(msg.message)}</div>
            <div class="message-actions">
                ${msg.status === 'unread' ? `<button class="btn-mark-read" onclick="markAsRead(${msg.id})">✓ Mark as read</button>` : ''}
                <button class="btn-reply" onclick="replyToMessage('${escapeHtml(msg.email)}', '${escapeHtml(msg.name)}')">✉ Reply</button>
                <button class="btn-delete-message" onclick="deleteMessage(${msg.id})">🗑 Delete</button>
            </div>
        </div>
    `).join('');
}

function markAsRead(id) {
    fetch('index.php?action=admin_mark_read&id=' + id)
        .then(response => response.json())
        .then(data => {
            if(data.success) loadContactMessages();
        });
}

function deleteMessage(id) {
    if(confirm('Are you sure you want to delete this message?')) {
        fetch('index.php?action=admin_delete_message&id=' + id)
            .then(response => response.json())
            .then(data => {
                if(data.success) loadContactMessages();
            });
    }
}

function replyToMessage(email, name) {
    window.location.href = `mailto:${email}?subject=Re: Your NutriFlow AI account&body=Hello ${name},%0D%0A%0D%0AThank you for contacting us.%0D%0A%0D%0AWe have reviewed your request.%0D%0A%0D%0ABest regards,%0D%0ANutriFlow AI Team`;
}

// ========== UTILITIES ==========
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ========== ANIMATION COUNTERS ==========
function animateNumbers() {
    const counters = document.querySelectorAll('.stat-number');
    counters.forEach(counter => {
        const target = parseInt(counter.innerText);
        let current = 0;
        const increment = target / 50;
        
        const updateCounter = () => {
            if(current < target) {
                current += increment;
                counter.innerText = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.innerText = target;
            }
        };
        updateCounter();
    });
}

// ========== INITIALIZATION ==========
document.addEventListener('DOMContentLoaded', function() {
    loadCharts();
    drawWorldMap();
    loadNotifications();
    loadContactMessages();
    loadWidgetSettings();
    animateNumbers();
    
    // Refresh notifications every 30 seconds
    notificationInterval = setInterval(loadNotifications, 30000);
    setInterval(loadContactMessages, 30000);
});

// Close modals when clicking outside
window.onclick = function(event) {
    const widgetModal = document.getElementById('widgetModal');
    if(event.target == widgetModal) closeWidgetModal();
}

// Resize handler for world map
window.addEventListener('resize', function() {
    drawWorldMap();
});
</script>
