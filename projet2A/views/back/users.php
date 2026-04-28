<div class="management-sections">
    <div class="section-header">
        <h2>Users Management</h2>
        <a href="index.php?action=admin_add_user" class="btn-add-user">+ Add New User</a>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="search-filter-bar">
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="🔍 Search by username, email or name...">
    </div>
    <div class="filter-buttons">
        <button class="filter-btn active" data-filter="all">All Users</button>
        <button class="filter-btn" data-filter="admin">Admins</button>
        <button class="filter-btn" data-filter="user">Regular Users</button>
        <button class="filter-btn" data-filter="active">Active</button>
        <button class="filter-btn" data-filter="disabled">Disabled</button>
    </div>
    <div class="sort-buttons">
        <button class="sort-btn" data-sort="username">Sort by Username 🔽</button>
        <button class="sort-btn" data-sort="email">Sort by Email 🔽</button>
        <button class="sort-btn" data-sort="date">Sort by Date 🔽</button>
    </div>
</div>

<div class="table-container">
    <div class="table-info">
        Showing <span id="showingCount">0</span> of <span id="totalCount">0</span> users
    </div>
    <table class="data-table" id="usersTable">
        <thead>
            <tr>
                <th class="sortable" data-sort="username">Username <span class="sort-icon">↕️</span></th>
                <th class="sortable" data-sort="full_name">Full Name <span class="sort-icon">↕️</span></th>
                <th class="sortable" data-sort="email">Email <span class="sort-icon">↕️</span></th>
                <th>Phone</th>
                <th>Age</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="usersTableBody">
            <?php foreach($users as $user): ?>
            <tr data-username="<?php echo strtolower(htmlspecialchars($user['username'])); ?>"
                data-email="<?php echo strtolower(htmlspecialchars($user['email'])); ?>"
                data-fullname="<?php echo strtolower(htmlspecialchars($user['full_name'] ?? '')); ?>"
                data-role="<?php echo $user['role']; ?>"
                data-status="<?php echo isset($user['is_active']) && $user['is_active'] == 1 ? 'active' : 'disabled'; ?>"
                data-date="<?php echo strtotime($user['created_at']); ?>">
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['full_name'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></tr>
                <td><?php echo htmlspecialchars($user['phone'] ?? '-'); ?></td>
                <td><?php echo $user['age'] ?? '-'; ?></td>
                <td><span class="role-badge <?php echo $user['role']; ?>"><?php echo $user['role']; ?></span></td>
                <td>
                    <?php if(isset($user['is_active']) && $user['is_active'] == 1): ?>
                        <span class="status-badge active">🟢 Active</span>
                    <?php else: ?>
                        <span class="status-badge inactive">🔴 Disabled</span>
                    <?php endif; ?>
                </td>
                <td class="actions">
                    <a href="index.php?action=admin_edit_user&id=<?php echo $user['id']; ?>" class="btn-edit">Edit</a>
                    <?php if($user['id'] != $_SESSION['user_id']): ?>
                        <?php if(isset($user['is_active']) && $user['is_active'] == 1): ?>
                            <a href="index.php?action=admin_disable_user&id=<?php echo $user['id']; ?>" 
                               class="btn-disable" 
                               onclick="return confirm('Are you sure you want to DISABLE this user?')">Disable</a>
                        <?php else: ?>
                            <a href="index.php?action=admin_enable_user&id=<?php echo $user['id']; ?>" 
                               class="btn-enable" 
                               onclick="return confirm('Are you sure you want to ENABLE this user?')">Enable</a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <a href="index.php?action=admin_delete_user&id=<?php echo $user['id']; ?>" 
                       class="btn-delete" 
                       onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<style>
/* Search and Filter Bar */
.search-filter-bar {
    background: white;
    border-radius: 15px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: center;
    justify-content: space-between;
}

.search-box {
    flex: 2;
    min-width: 200px;
}

.search-box input {
    width: 100%;
    padding: 0.6rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.search-box input:focus {
    outline: none;
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
}

.filter-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 0.4rem 1rem;
    background: #f1f5f9;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    font-size: 0.8rem;
    transition: all 0.3s;
}

.filter-btn:hover {
    background: #e2e8f0;
}

.filter-btn.active {
    background: linear-gradient(135deg, #16a34a, #14532d);
    color: white;
}

.sort-buttons {
    display: flex;
    gap: 0.5rem;
}

.sort-btn {
    padding: 0.4rem 1rem;
    background: #f1f5f9;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    font-size: 0.8rem;
    transition: all 0.3s;
}

.sort-btn:hover {
    background: #e2e8f0;
}

.sort-btn.active {
    background: #3b82f6;
    color: white;
}

.table-info {
    margin-bottom: 1rem;
    font-size: 0.8rem;
    color: #64748b;
}

.sortable {
    cursor: pointer;
    user-select: none;
}

.sortable:hover {
    background: #f1f5f9;
}

.sort-icon {
    font-size: 0.7rem;
    margin-left: 0.25rem;
    opacity: 0.5;
}

.btn-add-user {
    display: inline-block;
    padding: 0.6rem 1.2rem;
    background: linear-gradient(135deg, #16a34a, #14532d);
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-add-user:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(22, 163, 74, 0.3);
}

.management-sections .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.management-sections .section-header h2 {
    font-size: 1.5rem;
    color: #2d3748;
    margin: 0;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-badge.active {
    background: #dcfce7;
    color: #166534;
}

.status-badge.inactive {
    background: #fee2e2;
    color: #991b1b;
}

.btn-disable {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #f97316;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 0.75rem;
    transition: all 0.3s;
}

.btn-disable:hover {
    background: #ea580c;
    transform: translateY(-1px);
}

.btn-enable {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #10b981;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 0.75rem;
    transition: all 0.3s;
}

.btn-enable:hover {
    background: #059669;
    transform: translateY(-1px);
}

.btn-edit {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #16a34a;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 0.75rem;
    transition: all 0.3s;
}

.btn-edit:hover {
    background: #15803d;
    transform: translateY(-1px);
}

.btn-delete {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #ef4444;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 0.75rem;
    transition: all 0.3s;
}

.btn-delete:hover {
    background: #dc2626;
    transform: translateY(-1px);
}

.actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead {
    background: #ecfdf5;
}

.data-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: #14532d;
    border-bottom: 2px solid #16a34a;
}

.data-table td {
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.data-table tbody tr:hover {
    background: #ecfdf5;
}

@media (max-width: 768px) {
    .search-filter-bar {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-buttons, .sort-buttons {
        justify-content: center;
    }
    
    .data-table {
        display: block;
        overflow-x: auto;
    }
}
</style>

<script>
// ========== SEARCH AND FILTER FUNCTIONS ==========
let currentFilter = 'all';
let currentSort = null;
let currentSortDirection = 'asc';

function filterAndSortUsers() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#usersTableBody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const username = row.dataset.username || '';
        const email = row.dataset.email || '';
        const fullname = row.dataset.fullname || '';
        const role = row.dataset.role || '';
        const status = row.dataset.status || '';
        
        // Search condition
        let matchesSearch = true;
        if(searchTerm) {
            matchesSearch = username.includes(searchTerm) || 
                           email.includes(searchTerm) || 
                           fullname.includes(searchTerm);
        }
        
        // Filter condition
        let matchesFilter = true;
        if(currentFilter === 'admin') {
            matchesFilter = role === 'admin';
        } else if(currentFilter === 'user') {
            matchesFilter = role === 'user';
        } else if(currentFilter === 'active') {
            matchesFilter = status === 'active';
        } else if(currentFilter === 'disabled') {
            matchesFilter = status === 'disabled';
        }
        
        if(matchesSearch && matchesFilter) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Update showing count
    document.getElementById('showingCount').textContent = visibleCount;
    document.getElementById('totalCount').textContent = rows.length;
    
    // Apply sorting if active
    if(currentSort) {
        sortTable(currentSort, currentSortDirection);
    }
}

function sortTable(column, direction) {
    const tbody = document.getElementById('usersTableBody');
    const rows = Array.from(tbody.querySelectorAll('tr')).filter(row => row.style.display !== 'none');
    
    rows.sort((a, b) => {
        let aVal, bVal;
        
        if(column === 'username') {
            aVal = a.dataset.username || '';
            bVal = b.dataset.username || '';
        } else if(column === 'full_name') {
            aVal = a.dataset.fullname || '';
            bVal = b.dataset.fullname || '';
        } else if(column === 'email') {
            aVal = a.dataset.email || '';
            bVal = b.dataset.email || '';
        } else if(column === 'date') {
            aVal = parseInt(a.dataset.date) || 0;
            bVal = parseInt(b.dataset.date) || 0;
        } else {
            return 0;
        }
        
        if(typeof aVal === 'string') {
            if(direction === 'asc') {
                return aVal.localeCompare(bVal);
            } else {
                return bVal.localeCompare(aVal);
            }
        } else {
            if(direction === 'asc') {
                return aVal - bVal;
            } else {
                return bVal - aVal;
            }
        }
    });
    
    // Reorder rows
    rows.forEach(row => tbody.appendChild(row));
    
    // Update sort buttons styling
    document.querySelectorAll('.sort-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    const activeSortBtn = document.querySelector(`.sort-btn[data-sort="${column}"]`);
    if(activeSortBtn) {
        activeSortBtn.classList.add('active');
        activeSortBtn.textContent = `Sort by ${getColumnName(column)} ${direction === 'asc' ? '🔼' : '🔽'}`;
    }
}

function getColumnName(column) {
    const names = {
        username: 'Username',
        full_name: 'Name',
        email: 'Email',
        date: 'Date'
    };
    return names[column] || column;
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Search input
    const searchInput = document.getElementById('searchInput');
    if(searchInput) {
        searchInput.addEventListener('keyup', function() {
            filterAndSortUsers();
        });
    }
    
    // Filter buttons
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.dataset.filter;
            filterAndSortUsers();
        });
    });
    
    // Sort buttons
    document.querySelectorAll('.sort-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const sortColumn = this.dataset.sort;
            if(currentSort === sortColumn) {
                currentSortDirection = currentSortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort = sortColumn;
                currentSortDirection = 'asc';
            }
            sortTable(currentSort, currentSortDirection);
        });
    });
    
    // Column header sorting
    document.querySelectorAll('.sortable').forEach(th => {
        th.addEventListener('click', function() {
            const sortColumn = this.dataset.sort;
            if(currentSort === sortColumn) {
                currentSortDirection = currentSortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort = sortColumn;
                currentSortDirection = 'asc';
            }
            sortTable(currentSort, currentSortDirection);
        });
    });
    
    // Initialize
    filterAndSortUsers();
});
</script>
