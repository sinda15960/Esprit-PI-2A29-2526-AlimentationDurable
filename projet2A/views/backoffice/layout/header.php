<?php
// Vérifier si la session est active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>Administration NutriFlow AI</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/backoffice.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            overflow-x: hidden;
        }
        
        .backoffice-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #1a2a3a 0%, #0f1a24 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header .logo {
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .sidebar-header .logo i {
            margin-right: 0.5rem;
            color: #2ecc71;
        }
        
        .sidebar-header small {
            display: block;
            font-size: 0.8rem;
            opacity: 0.7;
            margin-top: 0.3rem;
        }
        
        .user-info {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .user-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .user-details h4 {
            font-size: 0.9rem;
            margin-bottom: 0.2rem;
        }
        
        .user-details p {
            font-size: 0.75rem;
            opacity: 0.7;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.8rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: rgba(46,204,113,0.2);
            color: #2ecc71;
            border-left: 3px solid #2ecc71;
        }
        
        .sidebar-nav a i {
            width: 20px;
        }
        
        .sidebar-nav hr {
            margin: 1rem 0;
            border-color: rgba(255,255,255,0.1);
        }
        
        .menu-badge {
            margin-left: auto;
            background: #2ecc71;
            color: white;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.7rem;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 1rem;
            transition: all 0.3s;
        }
        
        /* Top Bar */
        .top-bar {
            background: white;
            border-radius: 15px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .top-bar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: #1a2a3a;
            display: none;
        }
        
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
        }
        
        .breadcrumb a {
            color: #2ecc71;
            text-decoration: none;
        }
        
        .breadcrumb span {
            color: #999;
        }
        
        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .notification-badge {
            position: relative;
            cursor: pointer;
        }
        
        .notification-badge .badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            position: relative;
        }
        
        .user-avatar-small {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Alert Container */
        .alert-container {
            margin-bottom: 1.5rem;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: slideIn 0.3s ease;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar-toggle {
                display: block;
            }
            
            .top-bar {
                flex-direction: column;
                gap: 1rem;
            }
            
            .top-bar-left {
                width: 100%;
                justify-content: space-between;
            }
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #2ecc71;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #27ae60;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>
    <div class="backoffice-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-leaf"></i>
                    <span>NutriFlow AI</span>
                    <small>Administration</small>
                </div>
            </div>
            
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="user-details">
                    <h4>Administrateur</h4>
                    <p>admin@nutriflow.ai</p>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <a href="index.php?action=backRecipes" class="<?php echo isset($activeMenu) && $activeMenu === 'recipes' ? 'active' : ''; ?>">
                    <i class="fas fa-utensils"></i>
                    <span>Recettes</span>
                </a>
                
                <!-- LIEN CATÉGORIES - AJOUTÉ ICI -->
                <a href="index.php?action=backCategories" class="<?php echo isset($activeMenu) && $activeMenu === 'categories' ? 'active' : ''; ?>">
                    <i class="fas fa-tags"></i>
                    <span>Catégories</span>
                </a>
                
                <a href="#" class="<?php echo isset($activeMenu) && $activeMenu === 'dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-pie"></i>
                    <span>Tableau de bord</span>
                </a>
                <a href="#" class="<?php echo isset($activeMenu) && $activeMenu === 'analytics' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i>
                    <span>Analytiques</span>
                </a>
                <hr>
                <a href="index.php?action=frontRecipes">
                    <i class="fas fa-globe"></i>
                    <span>Voir le site</span>
                </a>
                <a href="#">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="top-bar-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="breadcrumb">
                        <a href="index.php?action=backRecipes">Accueil</a>
                        <?php if(isset($breadcrumb)): ?>
                            <?php foreach($breadcrumb as $item): ?>
                                <i class="fas fa-chevron-right"></i>
                                <?php if(isset($item['url'])): ?>
                                    <a href="<?php echo $item['url']; ?>"><?php echo $item['label']; ?></a>
                                <?php else: ?>
                                    <span><?php echo $item['label']; ?></span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="top-bar-right">
                    <div class="notification-badge">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </div>
                    
                    <div class="user-menu">
                        <div class="user-avatar-small">
                            <i class="fas fa-user"></i>
                        </div>
                        <span>Admin</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>

            <!-- Alert Container -->
            <div class="alert-container">
                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible">
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo $_SESSION['success']; ?></span>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-error alert-dismissible">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo $_SESSION['error']; ?></span>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if(isset($_SESSION['warning'])): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span><?php echo $_SESSION['warning']; ?></span>
                    </div>
                    <?php unset($_SESSION['warning']); ?>
                <?php endif; ?>
            </div>

            <script>
                // Sidebar Toggle
                const sidebar = document.getElementById('sidebar');
                const sidebarToggle = document.getElementById('sidebarToggle');
                
                if(sidebarToggle) {
                    sidebarToggle.addEventListener('click', () => {
                        sidebar.classList.toggle('active');
                    });
                }
                
                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function(event) {
                    const isMobile = window.innerWidth <= 768;
                    if(isMobile && sidebar.classList.contains('active')) {
                        if(!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                            sidebar.classList.remove('active');
                        }
                    }
                });
            </script>