<?php 
require_once __DIR__ . '/../../controllers/AssociationController.php';
require_once __DIR__ . '/../../controllers/DonController.php';

// Créer les contrôleurs
$associationController = new AssociationController();
$donController = new DonController();

// Récupérer les données via les contrôleurs
$associations = $associationController->getAllAssociationsForDashboard();
$totalAssociations = count($associations);
$dons = $donController->getAllDonsForDashboard();
$totalDons = count($dons);
$totalMontant = $donController->getTotalDonationsForDashboard();

// Statistics by status
$statsParStatut = array('pending' => 0, 'confirmed' => 0, 'delivered' => 0, 'cancelled' => 0);
$donsMonetaires = 0;
$donsAlimentaires = 0;
$donsEquipement = 0;

foreach($dons as $don) {
    $statsParStatut[$don['status']]++;
    if($don['donation_type'] == 'monetary') $donsMonetaires++;
    elseif($don['donation_type'] == 'food') $donsAlimentaires++;
    elseif($don['donation_type'] == 'equipment') $donsEquipement++;
}

$donsRecents = array_slice($dons, 0, 5);
$associationsRecentes = array_slice($associations, 0, 5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - NutriFlow AI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @keyframes slideIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }
        .slide-in { animation: slideIn 0.5s ease-out; }
        .stat-card { transition: all 0.3s ease; background: white; border-radius: 20px; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.15); }
        .progress-bar { transition: width 1s ease-in-out; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-72 sidebar text-white shadow-2xl" style="background: linear-gradient(180deg, #1a2a0f 0%, #0d1a08 100%);">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-8 pb-4 border-b border-gray-700">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #2d4a1e, #1a3a0f);">
                        <i class="fas fa-leaf text-white text-2xl"></i>
                    </div>
                    <div>
                        <span class="text-xl font-bold">NutriFlow AI</span>
                        <p class="text-xs text-gray-400">Administration</p>
                    </div>
                </div>
                
                <nav class="space-y-2">
                    <a href="/nutriflow-ai/public/admin/dashboard" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl" style="background: rgba(45, 74, 30, 0.2); border-left: 4px solid #2d4a1e;">
                        <i class="fas fa-tachometer-alt w-5"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <div class="pt-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-2 px-4">Organizations</p>
                        <a href="/nutriflow-ai/public/admin/associations" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl">
                            <i class="fas fa-hand-holding-heart w-5"></i>
                            <span>Organizations list</span>
                        </a>
                        <a href="/nutriflow-ai/public/admin/associations/create" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl">
                            <i class="fas fa-plus-circle w-5"></i>
                            <span>Add organization</span>
                        </a>
                    </div>
                    
                    <div class="pt-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-2 px-4">Donations</p>
                        <a href="/nutriflow-ai/public/admin/dons" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl">
                            <i class="fas fa-donate w-5"></i>
                            <span>Donations list</span>
                        </a>
                        <a href="/nutriflow-ai/public/admin/dons/create" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl">
                            <i class="fas fa-plus-circle w-5"></i>
                            <span>Add donation</span>
                        </a>
                    </div>
                    
                    <div class="pt-8 mt-8 border-t border-gray-700">
                        <a href="/nutriflow-ai/public/" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl">
                            <i class="fas fa-globe w-5"></i>
                            <span>View website</span>
                        </a>
                    </div>
                </nav>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <!-- Top Bar -->
            <div class="bg-white shadow-sm sticky top-0 z-10">
                <div class="px-8 py-4 flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                        <p class="text-sm text-gray-500">Welcome to your administration space</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold" style="background: linear-gradient(135deg, #2d4a1e, #1a3a0f);">
                                A
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Administrator</p>
                                <p class="text-xs text-gray-500">Super Admin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-8 slide-in">
                <!-- Messages -->
                <?php if(isset($_SESSION['success'])): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Stats Cards - Couleurs SPRING -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Carte 1: Organizations - VERT PRINTEMPS -->
                    <div class="stat-card p-6" style="border-left: 4px solid #22c55e;">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Organizations</p>
                                <p class="text-3xl font-bold text-gray-800"><?php echo $totalAssociations; ?></p>
                                <p class="text-xs mt-2" style="color: #22c55e;"><i class="fas fa-check-circle"></i> Active</p>
                            </div>
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background: rgba(34, 197, 94, 0.1);">
                                <i class="fas fa-hand-holding-heart text-2xl" style="color: #22c55e;"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Carte 2: Total donations - ORANGE -->
                    <div class="stat-card p-6" style="border-left: 4px solid #f97316;">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Total donations</p>
                                <p class="text-3xl font-bold text-gray-800"><?php echo $totalDons; ?></p>
                                <p class="text-xs mt-2" style="color: #f97316;"><i class="fas fa-arrow-up"></i> Donations received</p>
                            </div>
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background: rgba(249, 115, 22, 0.1);">
                                <i class="fas fa-donate text-2xl" style="color: #f97316;"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Carte 3: Total amount - JAUNE -->
                    <div class="stat-card p-6" style="border-left: 4px solid #eab308;">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Total amount</p>
                                <p class="text-3xl font-bold" style="color: #eab308;"><?php echo number_format($totalMontant, 2); ?> DT</p>
                                <p class="text-xs text-gray-500 mt-2">Monetary donations</p>
                            </div>
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background: rgba(234, 179, 8, 0.1);">
                                <i class="fas fa-coins text-2xl" style="color: #eab308;"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Carte 4: Food donations - ROUGE -->
                    <div class="stat-card p-6" style="border-left: 4px solid #ef4444;">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Food donations</p>
                                <p class="text-3xl font-bold text-gray-800"><?php echo $donsAlimentaires; ?></p>
                                <p class="text-xs mt-2" style="color: #ef4444;"><i class="fas fa-apple-alt"></i> + food</p>
                            </div>
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background: rgba(239, 68, 68, 0.1);">
                                <i class="fas fa-apple-alt text-2xl" style="color: #ef4444;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-chart-pie mr-2" style="color: #2d4a1e;"></i>
                            Distribution by donation type
                        </h3>
                        <canvas id="donTypeChart" height="250"></canvas>
                        <div class="mt-4 flex justify-around text-sm">
                            <div class="flex items-center"><div class="w-3 h-3 rounded-full mr-1" style="background-color: #22c55e;"></div>Monetary (<?php echo $donsMonetaires; ?>)</div>
                            <div class="flex items-center"><div class="w-3 h-3 rounded-full mr-1" style="background-color: #f97316;"></div>Food (<?php echo $donsAlimentaires; ?>)</div>
                            <div class="flex items-center"><div class="w-3 h-3 rounded-full mr-1" style="background-color: #eab308;"></div>Equipment (<?php echo $donsEquipement; ?>)</div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-chart-line mr-2" style="color: #2d4a1e;"></i>
                            Donation status
                        </h3>
                        <canvas id="donStatusChart" height="250"></canvas>
                        <div class="mt-4 flex justify-around text-sm">
                            <div class="flex items-center"><div class="w-3 h-3 bg-yellow-500 rounded-full mr-1"></div>Pending (<?php echo $statsParStatut['pending']; ?>)</div>
                            <div class="flex items-center"><div class="w-3 h-3 rounded-full mr-1" style="background-color: #22c55e;"></div>Confirmed (<?php echo $statsParStatut['confirmed']; ?>)</div>
                            <div class="flex items-center"><div class="w-3 h-3 rounded-full mr-1" style="background-color: #f97316;"></div>Delivered (<?php echo $statsParStatut['delivered']; ?>)</div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Donations -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.05), rgba(34, 197, 94, 0.02));">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-clock mr-2" style="color: #22c55e;"></i>
                                Recent donations
                            </h3>
                        </div>
                        <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                            <?php foreach($donsRecents as $don): ?>
                            <div class="px-6 py-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-gray-900"><?php echo htmlspecialchars($don['donor_name']); ?></p>
                                        <p class="text-sm text-gray-500"><?php echo htmlspecialchars($don['association_name']); ?></p>
                                        <?php if($don['donation_type'] == 'monetary'): ?>
                                            <p class="text-sm font-semibold" style="color: #22c55e;"><?php echo number_format($don['amount'], 2); ?> DT</p>
                                        <?php else: ?>
                                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($don['food_type']); ?> - <?php echo $don['quantity']; ?> kg</p>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <span class="px-2 py-1 text-xs rounded-full <?php echo $don['status'] == 'confirmed' ? 'text-white' : ($don['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'); ?>" style="<?php echo $don['status'] == 'confirmed' ? 'background-color: #22c55e;' : ''; ?>">
                                            <?php echo $don['status'] == 'confirmed' ? 'Confirmed' : ($don['status'] == 'pending' ? 'Pending' : 'Delivered'); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200" style="background: linear-gradient(135deg, rgba(249, 115, 22, 0.05), rgba(249, 115, 22, 0.02));">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-building mr-2" style="color: #f97316;"></i>
                                Partner organizations
                            </h3>
                        </div>
                        <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                            <?php foreach($associations as $assoc): ?>
                            <div class="px-6 py-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-900"><?php echo htmlspecialchars($assoc['name']); ?></p>
                                        <p class="text-sm text-gray-500"><?php echo htmlspecialchars($assoc['city']); ?></p>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full <?php echo $assoc['status'] == 'active' ? 'text-white' : 'bg-red-100 text-red-800'; ?>" style="<?php echo $assoc['status'] == 'active' ? 'background-color: #22c55e;' : ''; ?>">
                                        <?php echo $assoc['status'] == 'active' ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="rounded-2xl shadow-lg p-8 text-white" style="background: linear-gradient(135deg, #2d4a1e, #1a3a0f);">
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-bolt mr-2"></i>
                        Quick actions
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="/nutriflow-ai/public/admin/associations/create" class="bg-white/20 backdrop-blur rounded-xl p-4 text-center hover:bg-white/30 transition">
                            <i class="fas fa-plus-circle text-2xl mb-2"></i>
                            <p>Add organization</p>
                        </a>
                        <a href="/nutriflow-ai/public/admin/dons/create" class="bg-white/20 backdrop-blur rounded-xl p-4 text-center hover:bg-white/30 transition">
                            <i class="fas fa-donate text-2xl mb-2"></i>
                            <p>Add donation</p>
                        </a>
                        <a href="/nutriflow-ai/public/associations" class="bg-white/20 backdrop-blur rounded-xl p-4 text-center hover:bg-white/30 transition">
                            <i class="fas fa-globe text-2xl mb-2"></i>
                            <p>View public website</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Donation type chart - Couleurs SPRING
        const ctx1 = document.getElementById('donTypeChart').getContext('2d');
        new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['Monetary', 'Food', 'Equipment'],
                datasets: [{
                    data: [<?php echo $donsMonetaires; ?>, <?php echo $donsAlimentaires; ?>, <?php echo $donsEquipement; ?>],
                    backgroundColor: ['#22c55e', '#f97316', '#eab308'],
                    borderWidth: 0
                }]
            },
            options: { responsive: true, maintainAspectRatio: true }
        });
        
        // Donation status chart
        const ctx2 = document.getElementById('donStatusChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Pending', 'Confirmed', 'Delivered', 'Cancelled'],
                datasets: [{
                    label: 'Number of donations',
                    data: [<?php echo $statsParStatut['pending']; ?>, <?php echo $statsParStatut['confirmed']; ?>, <?php echo $statsParStatut['delivered']; ?>, <?php echo $statsParStatut['cancelled']; ?>],
                    backgroundColor: ['#eab308', '#22c55e', '#f97316', '#ef4444'],
                    borderRadius: 10
                }]
            },
            options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { display: false } } }
        });
    </script>
</body>
</html>
