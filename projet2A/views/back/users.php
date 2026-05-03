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
        <button class="sort-btn" data-sort="full_name">Sort by Full Name 🔽</button>
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
                <th class="sortable" data-sort="date">Registered <span class="sort-icon">↕️</span></th>
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
                <td class="col-username"><?php echo htmlspecialchars($user['username']); ?></td>
                <td class="col-fullname"><?php echo htmlspecialchars($user['full_name'] ?? '-'); ?></td>
                <td class="col-email"><?php echo htmlspecialchars($user['email']); ?></td>
                <td class="col-phone"><?php echo htmlspecialchars($user['phone'] ?? '-'); ?></td>
                <td class="col-age"><?php echo $user['age'] ?? '-'; ?></td>
                <td class="col-role"><span class="role-badge <?php echo $user['role']; ?>"><?php echo $user['role']; ?></span></td>
                <td class="col-status">
                    <?php if(isset($user['is_active']) && $user['is_active'] == 1): ?>
                        <span class="status-badge active">🟢 Active</span>
                    <?php else: ?>
                        <span class="status-badge inactive">🔴 Disabled</span>
                    <?php endif; ?>
                </td>
                <td class="col-date"><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                <td class="col-actions">
                    <a href="index.php?action=admin_edit_user&id=<?php echo $user['id']; ?>" class="btn-edit">Edit</a>
                    <?php if($user['id'] != $_SESSION['user_id']): ?>
                        <?php if(isset($user['is_active']) && $user['is_active'] == 1): ?>
                            <a href="javascript:void(0)" 
                               class="btn-disable" 
                               onclick="openDisableUserModal(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')">Disable</a>
                        <?php else: ?>
                            <a href="javascript:void(0)" 
                               class="btn-enable" 
                               onclick="openEnableUserModal(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')">Enable</a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <a href="javascript:void(0)" 
                       class="btn-delete" 
                       onclick="openDeleteUserModal(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Disable User Modal -->
<div id="disableUserModal" class="modal">
    <div class="modal-content disable-modal">
        <div class="modal-header disable-header">
            <span class="modal-close" onclick="closeDisableUserModal()">&times;</span>
            <h2>🔒 Disable Account</h2>
        </div>
        <div class="modal-body">
            <div class="disable-icon">⚠️</div>
            <p id="disableModalMessage">Are you sure you want to disable this user?</p>
            <p class="disable-warning-text">This user will <strong>not be able to log in</strong> until re-enabled by an administrator.</p>
            <div class="disable-confirm">
                <label for="confirm_disable_input">Type <strong>DISABLE</strong> to confirm:</label>
                <input type="text" id="confirm_disable_input" placeholder="DISABLE">
            </div>
        </div>
        <div class="modal-footer disable-footer">
            <button class="btn-cancel" onclick="closeDisableUserModal()">Cancel</button>
            <button class="btn-disable-confirm" onclick="confirmDisableUser()">Yes, Disable Account</button>
        </div>
    </div>
</div>

<!-- Enable User Modal -->
<div id="enableUserModal" class="modal">
    <div class="modal-content enable-modal">
        <div class="modal-header enable-header">
            <span class="modal-close" onclick="closeEnableUserModal()">&times;</span>
            <h2>🔓 Enable Account</h2>
        </div>
        <div class="modal-body">
            <div class="enable-icon">✅</div>
            <p id="enableModalMessage">Are you sure you want to enable this user?</p>
            <p class="enable-warning-text">This user will be able to <strong>log in again</strong> and access their account.</p>
            <div class="enable-confirm">
                <label for="confirm_enable_input">Type <strong>ENABLE</strong> to confirm:</label>
                <input type="text" id="confirm_enable_input" placeholder="ENABLE">
            </div>
        </div>
        <div class="modal-footer enable-footer">
            <button class="btn-cancel" onclick="closeEnableUserModal()">Cancel</button>
            <button class="btn-enable-confirm" onclick="confirmEnableUser()">Yes, Enable Account</button>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div id="deleteUserModal" class="modal">
    <div class="modal-content delete-modal">
        <div class="modal-header delete-header">
            <span class="modal-close" onclick="closeDeleteUserModal()">&times;</span>
            <h2>🗑️ Delete Account</h2>
        </div>
        <div class="modal-body">
            <div class="delete-icon">⚠️</div>
            <p id="deleteModalMessage">Are you sure you want to delete this user?</p>
            <p class="delete-warning-text">This action <strong>cannot be undone</strong>. All data will be permanently removed.</p>
            <div class="delete-confirm">
                <label for="confirm_delete_input">Type <strong>DELETE</strong> to confirm:</label>
                <input type="text" id="confirm_delete_input" placeholder="DELETE">
            </div>
        </div>
        <div class="modal-footer delete-footer">
            <button class="btn-cancel" onclick="closeDeleteUserModal()">Cancel</button>
            <button class="btn-delete-confirm" onclick="confirmDeleteUser()">Yes, Delete Account</button>
        </div>
    </div>
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
    flex-wrap: wrap;
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
    cursor: pointer;
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
    cursor: pointer;
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
    cursor: pointer;
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

.col-date {
    white-space: nowrap;
    font-size: 0.85rem;
    color: #475569;
}

/* Disable Modal Styles */
.disable-header {
    background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
}

.disable-header h2 {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0;
    font-size: 1.25rem;
}

.disable-icon {
    font-size: 3.5rem;
    margin-bottom: 1rem;
    animation: shake 0.5s ease;
}

.disable-warning-text {
    color: #ea580c;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    font-weight: 500;
}

.disable-confirm {
    margin-top: 1.5rem;
    text-align: left;
    background: #fff7ed;
    padding: 1rem;
    border-radius: 12px;
}

.disable-confirm label {
    display: block;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    color: #4a5568;
    font-weight: 500;
}

.disable-confirm label strong {
    color: #ea580c;
}

.disable-confirm input {
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

.disable-confirm input:focus {
    outline: none;
    border-color: #f97316;
    box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
}

.btn-disable-confirm {
    background: #f97316;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-disable-confirm:hover {
    background: #ea580c;
    transform: translateY(-2px);
}

.disable-footer {
    display: flex;
    gap: 1rem;
    justify-content: center;
    padding: 1rem 1.5rem 1.5rem;
}

/* Enable Modal Styles */
.enable-header {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.enable-header h2 {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0;
    font-size: 1.25rem;
}

.enable-icon {
    font-size: 3.5rem;
    margin-bottom: 1rem;
    animation: bounce 0.5s ease;
}

.enable-warning-text {
    color: #059669;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    font-weight: 500;
}

.enable-confirm {
    margin-top: 1.5rem;
    text-align: left;
    background: #ecfdf5;
    padding: 1rem;
    border-radius: 12px;
}

.enable-confirm label {
    display: block;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    color: #4a5568;
    font-weight: 500;
}

.enable-confirm label strong {
    color: #059669;
}

.enable-confirm input {
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

.enable-confirm input:focus {
    outline: none;
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.btn-enable-confirm {
    background: #10b981;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-enable-confirm:hover {
    background: #059669;
    transform: translateY(-2px);
}

.enable-footer {
    display: flex;
    gap: 1rem;
    justify-content: center;
    padding: 1rem 1.5rem 1.5rem;
}

/* Delete Modal Styles */
.delete-header {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
}

.delete-header h2 {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0;
    font-size: 1.25rem;
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

.delete-footer {
    display: flex;
    gap: 1rem;
    justify-content: center;
    padding: 1rem 1.5rem 1.5rem;
}

/* Common Modal Styles */
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
    margin: 10% auto;
    width: 90%;
    max-width: 450px;
    border-radius: 20px;
    animation: slideDownModal 0.3s ease;
    overflow: hidden;
}

.modal-header {
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
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

.modal-footer {
    padding: 1rem 1.5rem 1.5rem;
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

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
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
    
    .modal-content {
        margin: 30% auto;
        width: 95%;
    }
    
    .disable-footer, .enable-footer, .delete-footer {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .btn-cancel,
    .btn-disable-confirm,
    .btn-enable-confirm,
    .btn-delete-confirm {
        width: 100%;
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
    const tbody = document.getElementById('usersTableBody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    let visibleCount = 0;
    
    rows.forEach(row => {
        const username = row.dataset.username || '';
        const email = row.dataset.email || '';
        const fullname = row.dataset.fullname || '';
        const role = row.dataset.role || '';
        const status = row.dataset.status || '';
        
        let matchesSearch = true;
        if(searchTerm) {
            matchesSearch = username.includes(searchTerm) || 
                           email.includes(searchTerm) || 
                           fullname.includes(searchTerm);
        }
        
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
    
    document.getElementById('showingCount').textContent = visibleCount;
    document.getElementById('totalCount').textContent = rows.length;
    
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
    
    rows.forEach(row => tbody.appendChild(row));
    
    document.querySelectorAll('.sort-btn').forEach(btn => {
        btn.classList.remove('active');
        const originalText = btn.textContent.replace(/[🔼🔽]/g, '').trim();
        btn.textContent = originalText + ' 🔽';
    });
    
    const activeSortBtn = document.querySelector(`.sort-btn[data-sort="${column}"]`);
    if(activeSortBtn) {
        activeSortBtn.classList.add('active');
        const originalText = activeSortBtn.textContent.replace(/[🔼🔽]/g, '').trim();
        activeSortBtn.textContent = originalText + (direction === 'asc' ? ' 🔼' : ' 🔽');
    }
    
    document.querySelectorAll('.sortable').forEach(th => {
        const thSort = th.dataset.sort;
        const sortIcon = th.querySelector('.sort-icon');
        if(sortIcon) {
            if(thSort === column) {
                sortIcon.textContent = direction === 'asc' ? '🔼' : '🔽';
            } else {
                sortIcon.textContent = '↕️';
            }
        }
    });
}

// ========== DISABLE USER MODAL FUNCTIONS ==========
let disableUserId = null;

function openDisableUserModal(userId, username) {
    disableUserId = userId;
    const modal = document.getElementById('disableUserModal');
    const messageParagraph = document.querySelector('#disableModalMessage');
    
    if(messageParagraph) {
        messageParagraph.innerHTML = `Are you sure you want to disable user <strong>${escapeHtml(username)}</strong>?`;
    }
    
    modal.style.display = 'block';
    document.getElementById('confirm_disable_input').value = '';
}

function closeDisableUserModal() {
    document.getElementById('disableUserModal').style.display = 'none';
    disableUserId = null;
}

function confirmDisableUser() {
    const confirmInput = document.getElementById('confirm_disable_input').value;
    if(confirmInput === 'DISABLE') {
        window.location.href = `index.php?action=admin_disable_user&id=${disableUserId}`;
    } else {
        alert('Please type DISABLE to confirm account disable.');
    }
}

// ========== ENABLE USER MODAL FUNCTIONS ==========
let enableUserId = null;

function openEnableUserModal(userId, username) {
    enableUserId = userId;
    const modal = document.getElementById('enableUserModal');
    const messageParagraph = document.querySelector('#enableModalMessage');
    
    if(messageParagraph) {
        messageParagraph.innerHTML = `Are you sure you want to enable user <strong>${escapeHtml(username)}</strong>?`;
    }
    
    modal.style.display = 'block';
    document.getElementById('confirm_enable_input').value = '';
}

function closeEnableUserModal() {
    document.getElementById('enableUserModal').style.display = 'none';
    enableUserId = null;
}

function confirmEnableUser() {
    const confirmInput = document.getElementById('confirm_enable_input').value;
    if(confirmInput === 'ENABLE') {
        window.location.href = `index.php?action=admin_enable_user&id=${enableUserId}`;
    } else {
        alert('Please type ENABLE to confirm account enable.');
    }
}

// ========== DELETE USER MODAL FUNCTIONS ==========
let deleteUserId = null;

function openDeleteUserModal(userId, username) {
    deleteUserId = userId;
    const modal = document.getElementById('deleteUserModal');
    const messageParagraph = document.querySelector('#deleteModalMessage');
    
    if(messageParagraph) {
        messageParagraph.innerHTML = `Are you sure you want to delete user <strong>${escapeHtml(username)}</strong>?`;
    }
    
    modal.style.display = 'block';
    document.getElementById('confirm_delete_input').value = '';
}

function closeDeleteUserModal() {
    document.getElementById('deleteUserModal').style.display = 'none';
    deleteUserId = null;
}

function confirmDeleteUser() {
    const confirmInput = document.getElementById('confirm_delete_input').value;
    if(confirmInput === 'DELETE') {
        window.location.href = `index.php?action=admin_delete_user&id=${deleteUserId}`;
    } else {
        alert('Please type DELETE to confirm account deletion.');
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ========== EVENT LISTENERS ==========
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if(searchInput) {
        searchInput.addEventListener('keyup', function() {
            filterAndSortUsers();
        });
    }
    
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.dataset.filter;
            filterAndSortUsers();
        });
    });
    
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
            filterAndSortUsers();
        });
    });
    
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
            filterAndSortUsers();
        });
    });
    
    filterAndSortUsers();
});

// Close modals when clicking outside
window.onclick = function(event) {
    const disableModal = document.getElementById('disableUserModal');
    const enableModal = document.getElementById('enableUserModal');
    const deleteModal = document.getElementById('deleteUserModal');
    
    if (event.target == disableModal) closeDisableUserModal();
    if (event.target == enableModal) closeEnableUserModal();
    if (event.target == deleteModal) closeDeleteUserModal();
}
</script>
