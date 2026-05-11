<?php
/**
 * Back office — tableau de bord dons & associations (Tailwind, « deuxième photo »).
 */
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

$dons = [];
$associations = [];
$totalAssociations = 0;
$totalDons = 0;
$totalMontant = 0.0;
$statsParStatut = ['pending' => 0, 'confirmed' => 0, 'delivered' => 0, 'cancelled' => 0];
$donsMonetaires = 0;
$donsAlimentaires = 0;
$donsEquipement = 0;
$loadError = null;

try {
    require_once __DIR__ . '/Association.php';
    require_once __DIR__ . '/Don.php';
    $associationModel = new Association();
    $donModel = new Don();
    $associations = $associationModel->findAll();
    $totalAssociations = count($associations);
    $dons = $donModel->getDonsWithAssociation();
    $totalDons = count($dons);
    $totalMontant = (float) $donModel->getTotalDonations();
    foreach ($dons as $don) {
        $st = $don['status'] ?? '';
        if (isset($statsParStatut[$st])) {
            $statsParStatut[$st]++;
        }
        $type = $don['donation_type'] ?? '';
        if ($type === 'monetary') {
            $donsMonetaires++;
        } elseif ($type === 'food') {
            $donsAlimentaires++;
        } elseif ($type === 'equipment') {
            $donsEquipement++;
        }
    }
} catch (Throwable $e) {
    $loadError = $e->getMessage();
}

$donsRecents = array_slice($dons, 0, 5);
$associationsRecentes = array_slice($associations, 0, 5);
$adminName = htmlspecialchars($_SESSION['username'] ?? $_SESSION['full_name'] ?? 'Administrator', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — NutriFlow AI Donations</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar-bg { background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); }
        .sidebar-item { transition: all 0.2s ease; }
        .sidebar-item:hover { background: rgba(16, 185, 129, 0.15); }
        .stat-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 24px rgba(0,0,0,0.08); }
    </style>
</head>
<body class="bg-gray-100">
<div class="flex min-h-screen">
    <aside class="w-72 sidebar-bg text-white shadow-xl flex-shrink-0">
        <div class="p-6 border-b border-gray-700">
            <div class="flex items-center gap-3 mb-1">
                <span class="text-2xl">🥗</span>
                <div>
                    <div class="font-bold text-lg">NutriFlow AI</div>
                    <div class="text-xs text-gray-400">Administration</div>
                </div>
            </div>
        </div>
        <nav class="p-4 space-y-1">
            <a href="donations_admin.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl bg-green-600/25 border-l-4 border-green-500">
                <i class="fas fa-tachometer-alt w-5"></i><span>Dashboard</span>
            </a>
            <div class="pt-4 pb-1 px-4 text-xs text-gray-500 uppercase tracking-wider">Organizations</div>
            <a href="organizations_public.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl">
                <i class="fas fa-hand-holding-heart w-5"></i><span>Organizations list</span>
            </a>
            <a href="organization_create_public.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl">
                <i class="fas fa-plus-circle w-5"></i><span>Add organization</span>
            </a>
            <div class="pt-4 pb-1 px-4 text-xs text-gray-500 uppercase tracking-wider">Donations</div>
            <a href="donations_admin.php#recent-dons" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl">
                <i class="fas fa-donate w-5"></i><span>Donations list</span>
            </a>
            <a href="donate_public.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl">
                <i class="fas fa-plus-circle w-5"></i><span>Add donation</span>
            </a>
            <div class="pt-6 mt-6 border-t border-gray-700">
                <a href="donations_hub.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl">
                    <i class="fas fa-globe w-5"></i><span>View website</span>
                </a>
                <a href="logout.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-red-300 hover:bg-red-900/20">
                    <i class="fas fa-sign-out-alt w-5"></i><span>Logout</span>
                </a>
            </div>
        </nav>
    </aside>

    <div class="flex-1 overflow-x-auto">
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="px-8 py-4 flex flex-wrap justify-between items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                    <p class="text-sm text-gray-500">Welcome to your administration space</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-green-500 to-emerald-600 flex items-center justify-center text-white font-bold">
                        <?php echo strtoupper(substr($adminName, 0, 1)); ?>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700"><?php echo $adminName; ?></p>
                        <p class="text-xs text-gray-500">Super Admin</p>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-8">
            <?php if ($loadError): ?>
                <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-900 p-4 mb-6 rounded-r-lg text-sm">
                    <?php echo htmlspecialchars($loadError); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-50 border border-green-200 text-green-800 p-4 mb-6 rounded-lg"><?php echo htmlspecialchars((string)$_SESSION['success']); unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
                <div class="stat-card bg-white rounded-2xl p-6 shadow">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Organizations</p>
                            <p class="text-3xl font-bold text-gray-800"><?php echo (int)$totalAssociations; ?></p>
                            <p class="text-xs text-green-600 mt-2"><i class="fas fa-check-circle"></i> Active</p>
                        </div>
                        <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-hand-holding-heart text-2xl text-green-600"></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card bg-white rounded-2xl p-6 shadow">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Total donations</p>
                            <p class="text-3xl font-bold text-gray-800"><?php echo (int)$totalDons; ?></p>
                            <p class="text-xs text-orange-600 mt-2"><i class="fas fa-arrow-up"></i> Donations received</p>
                        </div>
                        <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-donate text-2xl text-blue-600"></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card bg-white rounded-2xl p-6 shadow">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Total amount</p>
                            <p class="text-3xl font-bold text-green-600"><?php echo number_format($totalMontant, 2); ?> DT</p>
                            <p class="text-xs text-gray-500 mt-2">Monetary donations</p>
                        </div>
                        <div class="w-14 h-14 bg-yellow-100 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-coins text-2xl text-yellow-600"></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card bg-white rounded-2xl p-6 shadow">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Food donations</p>
                            <p class="text-3xl font-bold text-gray-800"><?php echo (int)$donsAlimentaires; ?></p>
                            <p class="text-xs text-green-600 mt-2"><i class="fas fa-apple-alt"></i> + food</p>
                        </div>
                        <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-apple-alt text-2xl text-orange-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4"><i class="fas fa-chart-pie text-green-600 mr-2"></i>Distribution by donation type</h3>
                    <canvas id="donTypeChart" height="220"></canvas>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4"><i class="fas fa-chart-bar text-green-600 mr-2"></i>Donation status</h3>
                    <canvas id="donStatusChart" height="220"></canvas>
                </div>
            </div>

            <div id="recent-dons" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b">
                        <h3 class="font-semibold text-gray-800"><i class="fas fa-clock text-green-600 mr-2"></i>Recent donations</h3>
                    </div>
                    <div class="divide-y max-h-96 overflow-y-auto">
                        <?php foreach ($donsRecents as $don): ?>
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex justify-between gap-2">
                                <div>
                                    <p class="font-medium text-gray-900"><?php echo htmlspecialchars($don['donor_name'] ?? ''); ?></p>
                                    <p class="text-sm text-gray-500"><?php echo htmlspecialchars($don['association_name'] ?? ''); ?></p>
                                    <?php if (($don['donation_type'] ?? '') === 'monetary'): ?>
                                        <p class="text-sm text-green-600 font-semibold"><?php echo number_format((float)($don['amount'] ?? 0), 2); ?> DT</p>
                                    <?php else: ?>
                                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars((string)($don['food_type'] ?? '')); ?> — <?php echo (int)($don['quantity'] ?? 0); ?> kg</p>
                                    <?php endif; ?>
                                </div>
                                <?php $st = $don['status'] ?? 'pending'; ?>
                                <span class="px-2 py-1 text-xs rounded-full h-fit <?php echo $st === 'confirmed' ? 'bg-green-100 text-green-800' : ($st === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($st === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')); ?>">
                                    <?php echo htmlspecialchars($st); ?>
                                </span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php if (count($donsRecents) === 0): ?>
                            <p class="p-6 text-gray-500 text-sm">No donations yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b">
                        <h3 class="font-semibold text-gray-800"><i class="fas fa-building text-green-600 mr-2"></i>Partner organizations</h3>
                    </div>
                    <div class="divide-y max-h-96 overflow-y-auto">
                        <?php foreach ($associationsRecentes as $assoc): ?>
                        <div class="px-6 py-4 hover:bg-gray-50 flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-900"><?php echo htmlspecialchars($assoc['name'] ?? ''); ?></p>
                                <p class="text-sm text-gray-500"><?php echo htmlspecialchars($assoc['city'] ?? ''); ?></p>
                            </div>
                            <?php $active = ($assoc['status'] ?? '') === 'active'; ?>
                            <span class="text-xs px-2 py-1 rounded-full <?php echo $active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                <?php echo $active ? 'Active' : 'Inactive'; ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                        <?php if (count($associationsRecentes) === 0): ?>
                            <p class="p-6 text-gray-500 text-sm">No organizations.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="dashboard.php" class="inline-flex items-center px-4 py-2 rounded-xl bg-gray-200 text-gray-800 text-sm font-medium hover:bg-gray-300">
                    <i class="fas fa-plug mr-2"></i>Legacy allergies / traitements dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var ctx1 = document.getElementById('donTypeChart');
    if (ctx1) {
        new Chart(ctx1.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Monetary', 'Food', 'Equipment'],
                datasets: [{
                    data: [<?php echo (int)$donsMonetaires; ?>, <?php echo (int)$donsAlimentaires; ?>, <?php echo (int)$donsEquipement; ?>],
                    backgroundColor: ['#10b981', '#f97316', '#3b82f6'],
                    borderWidth: 0
                }]
            },
            options: { responsive: true, maintainAspectRatio: true }
        });
    }
    var ctx2 = document.getElementById('donStatusChart');
    if (ctx2) {
        new Chart(ctx2.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Pending', 'Confirmed', 'Delivered', 'Cancelled'],
                datasets: [{
                    label: 'Count',
                    data: [
                        <?php echo (int)$statsParStatut['pending']; ?>,
                        <?php echo (int)$statsParStatut['confirmed']; ?>,
                        <?php echo (int)$statsParStatut['delivered']; ?>,
                        <?php echo (int)$statsParStatut['cancelled']; ?>
                    ],
                    backgroundColor: ['#fbbf24', '#10b981', '#3b82f6', '#ef4444'],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    }
})();
</script>
</body>
</html>
