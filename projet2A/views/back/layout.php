<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriFlow AI - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar { background: linear-gradient(180deg, #1a2a0f 0%, #0d1a08 100%); }
        .sidebar-item:hover { background: rgba(45, 74, 30, 0.3); }
        .btn-primary { background: #2d4a1e; }
        .btn-primary:hover { background: #1a3a0f; }
        .sidebar-item-active { background: #2d4a1e; }
        .bg-empire { background-color: #2d4a1e; }
        .text-empire { color: #2d4a1e; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <div class="w-64 sidebar text-white">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: #2d4a1e;">
                        <i class="fas fa-leaf text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-bold">NutriFlow AI</span>
                </div>
                <nav>
                    <div class="mb-6">
                        <p class="text-xs text-gray-400 uppercase mb-2 px-3">ORGANIZATIONS MANAGEMENT</p>
                        <a href="/nutriflow-ai/public/admin/associations" 
                           class="sidebar-item block py-2 px-3 rounded mb-1 <?php echo (strpos($_SERVER['REQUEST_URI'], 'admin/associations') !== false && strpos($_SERVER['REQUEST_URI'], 'create') === false) ? 'bg-empire' : ''; ?>">
                            <i class="fas fa-hand-holding-heart w-5 mr-2"></i> All organizations
                        </a>
                        <a href="/nutriflow-ai/public/admin/associations/create" 
                           class="sidebar-item block py-2 px-3 rounded <?php echo (strpos($_SERVER['REQUEST_URI'], 'associations/create') !== false) ? 'bg-empire' : ''; ?>">
                            <i class="fas fa-plus-circle w-5 mr-2"></i> Add organization
                        </a>
                    </div>
                    
                    <div class="mb-6">
                        <p class="text-xs text-gray-400 uppercase mb-2 px-3">DONATIONS MANAGEMENT</p>
                        <a href="/nutriflow-ai/public/admin/dons" 
                           class="sidebar-item block py-2 px-3 rounded mb-1 <?php echo (strpos($_SERVER['REQUEST_URI'], 'admin/dons') !== false && strpos($_SERVER['REQUEST_URI'], 'create') === false && strpos($_SERVER['REQUEST_URI'], 'edit') === false) ? 'bg-empire' : ''; ?>">
                            <i class="fas fa-donate w-5 mr-2"></i> All donations
                        </a>
                        <a href="/nutriflow-ai/public/admin/dons/create" 
                           class="sidebar-item block py-2 px-3 rounded <?php echo (strpos($_SERVER['REQUEST_URI'], 'dons/create') !== false) ? 'bg-empire' : ''; ?>">
                            <i class="fas fa-plus-circle w-5 mr-2"></i> Add donation
                        </a>
                    </div>
                    
                    <div class="pt-6 border-t border-gray-700">
                        <a href="/nutriflow-ai/public/" class="sidebar-item block py-2 px-3 rounded">
                            <i class="fas fa-globe w-5 mr-2"></i> View website
                        </a>
                    </div>
                </nav>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto">
            <div class="bg-white shadow-sm px-8 py-4">
                <h1 class="text-2xl font-bold text-gray-800">Administration</h1>
                <p class="text-gray-500 text-sm">Manage your NutriFlow AI platform</p>
            </div>

            <div class="p-8">
                <?php if(isset($_SESSION['success'])): ?>
                    <div class="border-l-4 p-4 mb-6 rounded" style="background-color: rgba(45, 74, 30, 0.1); border-left-color: #2d4a1e; color: #1a3a0f;">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if(isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <?php echo $content; ?>
            </div>
        </div>
    </div>
</body>
</html>
