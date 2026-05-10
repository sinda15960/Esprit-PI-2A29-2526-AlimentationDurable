<?php
if (!function_exists('nf_repo_url')) {
    require_once dirname(__DIR__, 2) . '/config/paths.php';
}
$nfAdminDonationsUrl = nf_repo_url('dashboard.php') . '#donations';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Admin Panel - NutriFlow AI'; ?></title>
    <link rel="stylesheet" href="assets/css/back-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <span class="logo-icon">🥗</span>
                    <h2>NutriFlow AI</h2>
                </div>
                <p class="sidebar-subtitle">Administration Panel</p>
            </div>
            
            <nav class="sidebar-nav">
                <!-- Dashboard -->
                <a href="index.php?action=admin_dashboard" class="nav-item <?php echo ($active_page ?? '') == 'dashboard' ? 'active' : ''; ?>">
                    <span class="nav-icon">📊</span>
                    <span class="nav-text">Dashboard</span>
                </a>
                <a href="<?php echo htmlspecialchars($nfAdminDonationsUrl); ?>" class="nav-item">
                    <span class="nav-icon">💰</span>
                    <span class="nav-text">Gestion des dons</span>
                </a>
                
                <!-- User Management -->
                <a href="index.php?action=admin_users" class="nav-item <?php echo ($active_page ?? '') == 'users' ? 'active' : ''; ?>">
                    <span class="nav-icon">👥</span>
                    <span class="nav-text">Users Management</span>
                </a>
                
                <!-- Premium Features -->
                <div class="nav-divider"></div>
                <a href="index.php?action=admin_globe" class="nav-item <?php echo ($active_page ?? '') == 'globe' ? 'active' : ''; ?>">
                    <span class="nav-icon">🌍</span>
                    <span class="nav-text">Live Globe 3D</span>
                </a>
                <a href="index.php?action=admin_secret" class="nav-item <?php echo ($active_page ?? '') == 'secret' ? 'active' : ''; ?>">
                    <span class="nav-icon">🤫</span>
                    <span class="nav-text">Secret Zone</span>
                </a>
                <a href="index.php?action=admin_terminal" class="nav-item <?php echo ($active_page ?? '') == 'terminal' ? 'active' : ''; ?>">
                    <span class="nav-icon">💻</span>
                    <span class="nav-text">Retro Terminal</span>
                </a>
                
                <!-- TOP 5 New Features -->
                <div class="nav-divider"></div>
                <a href="index.php?action=admin_incognito" class="nav-item <?php echo ($active_page ?? '') == 'incognito' ? 'active' : ''; ?>">
                    <span class="nav-icon">🕵️</span>
                    <span class="nav-text">Incognito Mode</span>
                </a>
                <a href="index.php?action=admin_shortcuts" class="nav-item <?php echo ($active_page ?? '') == 'shortcuts' ? 'active' : ''; ?>">
                    <span class="nav-icon">⌨️</span>
                    <span class="nav-text">Shortcuts</span>
                </a>
                <a href="index.php?action=admin_comparison" class="nav-item <?php echo ($active_page ?? '') == 'comparison' ? 'active' : ''; ?>">
                    <span class="nav-icon">📊</span>
                    <span class="nav-text">Comparison</span>
                </a>
                <a href="index.php?action=admin_leaderboard" class="nav-item <?php echo ($active_page ?? '') == 'leaderboard' ? 'active' : ''; ?>">
                    <span class="nav-icon">🏆</span>
                    <span class="nav-text">Leaderboard</span>
                </a>
                <a href="index.php?action=admin_cleaner" class="nav-item <?php echo ($active_page ?? '') == 'cleaner' ? 'active' : ''; ?>">
                    <span class="nav-icon">🧹</span>
                    <span class="nav-text">DB Cleaner</span>
                </a>
                
                <div class="nav-divider"></div>
                <a href="index.php?action=home" class="nav-item">
                    <span class="nav-icon">🏠</span>
                    <span class="nav-text">View Site</span>
                </a>
                <a href="index.php?action=logout" class="nav-item logout">
                    <span class="nav-icon">🚪</span>
                    <span class="nav-text">Logout</span>
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <div class="admin-info-sidebar">
                    <div class="admin-avatar">👤</div>
                    <div class="admin-details">
                        <p class="admin-name"><?php echo htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']); ?></p>
                        <p class="admin-role">Administrator</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="admin-main">
            <div class="admin-top-bar">
                <div class="page-title">
                    <h1><?php echo $page_heading ?? 'Dashboard'; ?></h1>
                    <?php if(isset($page_subheading)): ?>
                        <p class="page-subheading"><?php echo $page_subheading; ?></p>
                    <?php endif; ?>
                </div>
                <div class="top-bar-actions">
                    <div class="user-menu">
                        <span class="user-avatar">👤</span>
                        <span class="user-name"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    </div>
                </div>
            </div>

            <?php if(isset($breadcrumb)): ?>
            <div class="breadcrumb">
                <a href="index.php?action=admin_dashboard">Home</a>
                <?php foreach($breadcrumb as $item): ?>
                    <span class="separator">/</span>
                    <?php if(isset($item['url'])): ?>
                        <a href="<?php echo $item['url']; ?>"><?php echo $item['label']; ?></a>
                    <?php else: ?>
                        <span class="current"><?php echo $item['label']; ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <span class="alert-icon">✅</span>
                    <span class="alert-message"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
                    <button class="alert-close">&times;</button>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <span class="alert-icon">❌</span>
                    <span class="alert-message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
                    <button class="alert-close">&times;</button>
                </div>
            <?php endif; ?>

            <div class="admin-content">
                <?php echo $content; ?>
            </div>
        </main>
    </div>

    <script src="assets/js/admin.js"></script>
    <script>
        document.querySelectorAll('.alert-close').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.alert').remove();
            });
        });
    </script>
</body>
</html>
