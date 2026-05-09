<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Organizations Management</h2>
            <div class="flex space-x-3">
                <a href="/nutriflow-ai/public/admin/associations/export-pdf" 
                   class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </a>
                <a href="/nutriflow-ai/public/admin/associations/create" 
                   class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-800 transition flex items-center">
                    <i class="fas fa-plus mr-2"></i> New Organization
                </a>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-4">
            <input type="text" id="searchInput" placeholder="Search by name, email or city..." 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
            
            <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                <option value="">All statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            
            <select id="sortSelect" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                <option value="name_asc">Sort by Name (A-Z)</option>
                <option value="name_desc">Sort by Name (Z-A)</option>
                <option value="siret_asc">Sort by Tax ID (A-Z)</option>
                <option value="siret_desc">Sort by Tax ID (Z-A)</option>
            </select>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-green-600" onclick="sortTable('name')">
                        Name <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">City</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-green-600" onclick="sortTable('siret')">
                        Tax ID <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">QR Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="tableBody">
                <?php if(empty($associations)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-building text-4xl mb-2"></i>
                            <p>No organizations found</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($associations as $assoc): ?>
                    <tr class="hover:bg-gray-50 transition association-row" 
                        data-name="<?php echo strtolower($assoc['name']); ?>"
                        data-email="<?php echo strtolower($assoc['email']); ?>"
                        data-city="<?php echo strtolower($assoc['city']); ?>"
                        data-siret="<?php echo $assoc['siret']; ?>"
                        data-status="<?php echo $assoc['status']; ?>">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo htmlspecialchars($assoc['name']); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo htmlspecialchars($assoc['email']); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo htmlspecialchars($assoc['city']); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo htmlspecialchars($assoc['siret']); ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full <?php echo ($assoc['status'] == 'active') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                <?php echo ($assoc['status'] == 'active') ? 'Active' : 'Inactive'; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="#" onclick="openQRCode(<?php echo $assoc['id']; ?>); return false;" 
                               class="text-purple-600 hover:text-purple-800 transition" title="Generate QR Code">
                                <i class="fas fa-qrcode text-xl"></i>
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="/nutriflow-ai/public/admin/associations/edit/<?php echo $assoc['id']; ?>" class="text-blue-600 hover:text-blue-800 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="/nutriflow-ai/public/admin/associations/delete/<?php echo $assoc['id']; ?>" onclick="return confirm('Delete this organization?')" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Fonction pour ouvrir le QR Code dans une popup
function openQRCode(id) {
    window.open('/nutriflow-ai/public/admin/associations/qrcode/' + id, 'QRCode', 'width=320,height=320,resizable=yes');
}

// Stockage des données
var allRows = [];
var currentRows = [];

function initData() {
    var rows = document.querySelectorAll('.association-row');
    allRows = [];
    for(var i = 0; i < rows.length; i++) {
        var row = rows[i];
        allRows.push({
            element: row,
            name: row.getAttribute('data-name') || '',
            email: row.getAttribute('data-email') || '',
            city: row.getAttribute('data-city') || '',
            siret: row.getAttribute('data-siret') || '',
            status: row.getAttribute('data-status') || ''
        });
    }
    currentRows = allRows.slice();
    applyFiltersAndSort();
}

function applyFiltersAndSort() {
    var search = document.getElementById('searchInput') ? document.getElementById('searchInput').value.toLowerCase() : '';
    var status = document.getElementById('statusFilter') ? document.getElementById('statusFilter').value : '';
    var sort = document.getElementById('sortSelect') ? document.getElementById('sortSelect').value : 'name_asc';
    
    var filtered = [];
    for(var i = 0; i < allRows.length; i++) {
        var row = allRows[i];
        var show = true;
        
        if(search && row.name.indexOf(search) === -1 && row.email.indexOf(search) === -1 && row.city.indexOf(search) === -1) {
            show = false;
        }
        if(show && status && row.status !== status) {
            show = false;
        }
        if(show) {
            filtered.push(row);
        }
    }
    
    filtered.sort(function(a, b) {
        if(sort === 'name_asc') return a.name.localeCompare(b.name);
        if(sort === 'name_desc') return b.name.localeCompare(a.name);
        if(sort === 'siret_asc') return a.siret.localeCompare(b.siret);
        if(sort === 'siret_desc') return b.siret.localeCompare(a.siret);
        return 0;
    });
    
    currentRows = filtered;
    renderTable();
}

function renderTable() {
    var tbody = document.getElementById('tableBody');
    if(!tbody) return;
    
    tbody.innerHTML = '';
    
    if(currentRows.length === 0) {
        var tr = document.createElement('tr');
        tr.innerHTML = '<td colspan="7" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-search text-4xl mb-2"></i><p>No organizations match your filters</p></td>';
        tbody.appendChild(tr);
    } else {
        for(var i = 0; i < currentRows.length; i++) {
            tbody.appendChild(currentRows[i].element);
        }
    }
}

function sortTable(column) {
    var sortSelect = document.getElementById('sortSelect');
    if(column === 'name') {
        var currentVal = sortSelect.value;
        if(currentVal === 'name_asc') {
            sortSelect.value = 'name_desc';
        } else {
            sortSelect.value = 'name_asc';
        }
    } else if(column === 'siret') {
        var currentVal = sortSelect.value;
        if(currentVal === 'siret_asc') {
            sortSelect.value = 'siret_desc';
        } else {
            sortSelect.value = 'siret_asc';
        }
    }
    applyFiltersAndSort();
}

document.addEventListener('DOMContentLoaded', function() {
    initData();
    
    var searchInput = document.getElementById('searchInput');
    var statusFilter = document.getElementById('statusFilter');
    var sortSelect = document.getElementById('sortSelect');
    
    if(searchInput) searchInput.addEventListener('keyup', applyFiltersAndSort);
    if(statusFilter) statusFilter.addEventListener('change', applyFiltersAndSort);
    if(sortSelect) sortSelect.addEventListener('change', applyFiltersAndSort);
});
</script>
