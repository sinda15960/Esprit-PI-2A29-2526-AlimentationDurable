<div class="dashboard-container">
    <!-- Widget Settings Button -->
    <div class="widget-settings">
        <button class="btn-settings" onclick="openWidgetSettings()">
            <span>⚙️</span> Customize Dashboard
        </button>
    </div>

    <!-- Stats Grid (Widget 1) -->
    <div id="widget-stats" class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <h3>Total Users</h3>
                <p class="stat-number"><?php echo count($users); ?></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">👑</div>
            <div class="stat-info">
                <h3>Admins</h3>
                <p class="stat-number">
                    <?php 
                        $adminCount = 0;
                        foreach($users as $user) {
                            if($user['role'] == 'admin') $adminCount++;
                        }
                        echo $adminCount;
                    ?>
                </p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">👤</div>
            <div class="stat-info">
                <h3>Regular Users</h3>
                <p class="stat-number"><?php echo count($users) - $adminCount; ?></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🍽️</div>
            <div class="stat-info">
                <h3>Total Recipes</h3>
                <p class="stat-number">128</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-info">
                <h3>Donations</h3>
                <p class="stat-number">$5,240</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🛒</div>
            <div class="stat-info">
                <h3>Market Orders</h3>
                <p class="stat-number">342</p>
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

    <!-- Management Sections -->
    <div class="management-sections">
        <div class="section-header">
            <h2>Management Dashboard</h2>
            <p>Manage all aspects of NutriFlow AI platform</p>
        </div>

        <div class="management-grid">
            <!-- Gestion Donation -->
            <div class="management-card">
                <div class="card-icon">💰</div>
                <h3>Gestion Donation</h3>
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
                    <button class="btn-card" onclick="showComingSoon('Donation Management')">Manage Donations</button>
                    <button class="btn-card secondary" onclick="showComingSoon('Add Campaign')">+ New Campaign</button>
                </div>
            </div>

            <!-- Gestion Recette -->
            <div class="management-card">
                <div class="card-icon">🍽️</div>
                <h3>Gestion Recette</h3>
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
                    <button class="btn-card" onclick="showComingSoon('Recipe Management')">Manage Recipes</button>
                    <button class="btn-card secondary" onclick="showComingSoon('Add Recipe')">+ New Recipe</button>
                </div>
            </div>

            <!-- Gestion Market Place -->
            <div class="management-card">
                <div class="card-icon">🛒</div>
                <h3>Gestion Market Place</h3>
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
                    <button class="btn-card" onclick="showComingSoon('Marketplace Management')">Manage Marketplace</button>
                    <button class="btn-card secondary" onclick="showComingSoon('Add Product')">+ Add Product</button>
                </div>
            </div>

            <!-- Gestion Plan -->
            <div class="management-card">
                <div class="card-icon">📋</div>
                <h3>Gestion Plan</h3>
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
                    <button class="btn-card" onclick="showComingSoon('Plan Management')">Manage Plans</button>
                    <button class="btn-card secondary" onclick="showComingSoon('Create Plan')">+ Create Plan</button>
                </div>
            </div>

            <!-- Gestion Allergies -->
            <div class="management-card">
                <div class="card-icon">⚠️</div>
                <h3>Gestion Allergies</h3>
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
                    <button class="btn-card" onclick="showComingSoon('Allergy Management')">Manage Allergies</button>
                    <button class="btn-card secondary" onclick="showComingSoon('Add Allergen')">+ Add Allergen</button>
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

<!-- Modal for Coming Soon -->
<div id="comingSoonModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="modal-close" onclick="closeModal()">&times;</span>
            <h2>🚀 Coming Soon</h2>
        </div>
        <div class="modal-body">
            <div class="coming-soon-icon">🔧</div>
            <p id="modalMessage">This feature is currently under development.</p>
            <p class="modal-subtext">We're working hard to bring you this functionality soon!</p>
            <div class="progress-bar">
                <div class="progress" style="width: 65%;"></div>
            </div>
            <p class="progress-text">Development in progress - 65%</p>
        </div>
        <div class="modal-footer">
            <button class="btn-modal" onclick="closeModal()">Got it!</button>
        </div>
    </div>
</div>

<style>
/* Dashboard Container */
.dashboard-container {
    padding: 0;
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

.coming-soon-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
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

.btn-modal {
    padding: 0.5rem 1.5rem;
    background: linear-gradient(135deg, #16a34a, #14532d);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
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
}
</style>

<script>
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

function showComingSoon(feature) {
    const modal = document.getElementById('comingSoonModal');
    const message = document.getElementById('modalMessage');
    if(modal && message) {
        message.innerHTML = `<strong>${feature}</strong> is currently under development.`;
        modal.style.display = 'block';
    }
}

function closeModal() {
    const modal = document.getElementById('comingSoonModal');
    if(modal) modal.style.display = 'none';
}

// ========== INITIALIZATION ==========
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    loadContactMessages();
    loadWidgetSettings();
    
    // Refresh notifications every 30 seconds
    notificationInterval = setInterval(loadNotifications, 30000);
    setInterval(loadContactMessages, 30000);
});

// Close modals when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('comingSoonModal');
    const widgetModal = document.getElementById('widgetModal');
    if(event.target == modal) closeModal();
    if(event.target == widgetModal) closeWidgetModal();
}
</script>
