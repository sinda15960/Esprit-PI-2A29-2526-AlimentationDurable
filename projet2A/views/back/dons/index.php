<?php $pageTitle = "Donations Management"; ?>
<?php $pageSubtitle = "View and manage all received donations"; ?>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6" style="border-left: 4px solid #2d4a1e;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Donations</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo count($dons); ?></p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: rgba(45, 74, 30, 0.1);">
                <i class="fas fa-donate text-xl" style="color: #2d4a1e;"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6" style="border-left: 4px solid #2d4a1e;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Amount</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo number_format($total, 2); ?> DT</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: rgba(45, 74, 30, 0.1);">
                <i class="fas fa-coins text-xl" style="color: #2d4a1e;"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Pending Donations</p>
                <p class="text-2xl font-bold text-gray-800">
                    <?php 
                        $pending = 0;
                        foreach($dons as $don) {
                            if($don['status'] == 'pending') $pending++;
                        }
                        echo $pending;
                    ?>
                </p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6" style="border-left: 4px solid #2d4a1e;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Confirmed Donations</p>
                <p class="text-2xl font-bold text-gray-800">
                    <?php 
                        $confirmed = 0;
                        foreach($dons as $don) {
                            if($don['status'] == 'confirmed') $confirmed++;
                        }
                        echo $confirmed;
                    ?>
                </p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: rgba(45, 74, 30, 0.1);">
                <i class="fas fa-check-circle text-xl" style="color: #2d4a1e;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Donations List -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">All Received Donations</h2>
            <a href="/nutriflow-ai/public/admin/dons/export-pdf" 
               class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition flex items-center">
                <i class="fas fa-file-pdf mr-2"></i> Export PDF
            </a>
        </div>
        
        <!-- Barre de recherche et filtres -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <input type="text" id="searchInput" placeholder="Search by name, email..." 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
            
            <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">All statuses</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
            </select>
            
            <select id="typeFilter" class="px-4 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">All types</option>
                <option value="monetary">Monetary</option>
                <option value="food">Food</option>
                <option value="equipment">Equipment</option>
            </select>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="donationsTable">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organization</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount/Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="tableBody">
                <?php if(empty($dons)): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No donations received yet</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($dons as $don): ?>
                    <tr class="hover:bg-gray-50 transition donation-row" 
                        data-id="<?php echo $don['id']; ?>"
                        data-name="<?php echo strtolower($don['donor_name']); ?>"
                        data-email="<?php echo strtolower($don['donor_email']); ?>"
                        data-association="<?php echo strtolower($don['association_name']); ?>"
                        data-status="<?php echo $don['status']; ?>"
                        data-type="<?php echo $don['donation_type']; ?>"
                        data-amount="<?php echo $don['amount']; ?>"
                        data-date="<?php echo $don['created_at']; ?>">
                        <td class="px-6 py-4 text-sm text-gray-900">#<?php echo $don['id']; ?></td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($don['donor_name']); ?></div>
                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($don['donor_email']); ?></div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($don['association_name']); ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full" style="<?php echo $don['donation_type'] == 'monetary' ? 'background: rgba(45, 74, 30, 0.1); color: #2d4a1e;' : ($don['donation_type'] == 'food' ? 'background: rgba(45, 74, 30, 0.1); color: #2d4a1e;' : 'background: #dbeafe; color: #1e40af;'); ?>">
                                <?php 
                                    echo $don['donation_type'] == 'monetary' ? 'Monetary' : 
                                        ($don['donation_type'] == 'food' ? 'Food' : 'Equipment');
                                ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <?php if($don['donation_type'] == 'monetary'): ?>
                                <span class="font-semibold" style="color: #2d4a1e;"><?php echo number_format($don['amount'], 2); ?> DT</span>
                            <?php else: ?>
                                <span><?php echo htmlspecialchars($don['food_type']); ?> - <?php echo $don['quantity']; ?> kg</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <select onchange="updateStatus(<?php echo $don['id']; ?>, this.value)" 
                                    class="text-sm rounded-full px-3 py-1 border-0 font-semibold
                                        <?php echo $don['status'] == 'confirmed' ? 'text-white' : 
                                            ($don['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                            ($don['status'] == 'delivered' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')); ?>" 
                                    style="<?php echo $don['status'] == 'confirmed' ? 'background-color: #2d4a1e;' : ''; ?>">
                                <option value="pending" <?php echo $don['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="confirmed" <?php echo $don['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="delivered" <?php echo $don['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo $don['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <?php echo date('d/m/Y H:i', strtotime($don['created_at'])); ?>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <button onclick="viewDon(<?php echo $don['id']; ?>)" class="mr-3" style="color: #2d4a1e;">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="deleteDon(<?php echo $don['id']; ?>)" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function updateStatus(id, status) {
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = '/nutriflow-ai/public/admin/dons/update-status/' + id;
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'status';
    input.value = status;
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}

function viewDon(id) {
    window.location.href = '/nutriflow-ai/public/admin/dons/view/' + id;
}

function deleteDon(id) {
    if(confirm('Are you sure you want to delete this donation?')) {
        window.location.href = '/nutriflow-ai/public/admin/dons/delete/' + id;
    }
}

// Search and filters
var searchInput = document.getElementById('searchInput');
var statusFilter = document.getElementById('statusFilter');
var typeFilter = document.getElementById('typeFilter');

function applyFilters() {
    var search = searchInput ? searchInput.value.toLowerCase() : '';
    var status = statusFilter ? statusFilter.value : '';
    var type = typeFilter ? typeFilter.value : '';
    
    var rows = document.querySelectorAll('.donation-row');
    var visibleCount = 0;
    
    for(var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var show = true;
        
        if(search) {
            var name = row.getAttribute('data-name') || '';
            var email = row.getAttribute('data-email') || '';
            var assoc = row.getAttribute('data-association') || '';
            if(name.indexOf(search) === -1 && email.indexOf(search) === -1 && assoc.indexOf(search) === -1) {
                show = false;
            }
        }
        
        if(show && status) {
            var rowStatus = row.getAttribute('data-status') || '';
            if(rowStatus !== status) show = false;
        }
        
        if(show && type) {
            var rowType = row.getAttribute('data-type') || '';
            if(rowType !== type) show = false;
        }
        
        row.style.display = show ? '' : 'none';
        if(show) visibleCount++;
    }
    
    var tbody = document.getElementById('tableBody');
    var noResultMsg = document.getElementById('noResultMsg');
    if(visibleCount === 0 && rows.length > 0) {
        if(!noResultMsg) {
            var tr = document.createElement('tr');
            tr.id = 'noResultMsg';
            tr.innerHTML = '<td colspan="8" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-search text-4xl mb-2"></i><p>No donations match your filters</p></td>';
            tbody.appendChild(tr);
        }
    } else if(noResultMsg) {
        noResultMsg.remove();
    }
}

if(searchInput) searchInput.addEventListener('keyup', applyFilters);
if(statusFilter) statusFilter.addEventListener('change', applyFilters);
if(typeFilter) typeFilter.addEventListener('change', applyFilters);

applyFilters();
</script>
