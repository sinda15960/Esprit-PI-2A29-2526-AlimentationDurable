<div class="stats-grid">
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

<!-- Recent Users Section -->
<div class="recent-users">
    <div class="section-header">
        <h2>Recent Users</h2>
        <a href="index.php?action=admin_users" class="btn-link">View All →</a>
    </div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registered</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $recentUsers = array_slice($users, 0, 5);
                foreach($recentUsers as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><span class="role-badge <?php echo $user['role']; ?>"><?php echo $user['role']; ?></span></td>
                    <td><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Coming Soon -->
<div id="comingSoonModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="modal-close">&times;</span>
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

<script>
function showComingSoon(feature) {
    const modal = document.getElementById('comingSoonModal');
    const message = document.getElementById('modalMessage');
    message.innerHTML = `<strong>${feature}</strong> is currently under development.`;
    modal.style.display = 'block';
}

function closeModal() {
    const modal = document.getElementById('comingSoonModal');
    modal.style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('comingSoonModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>