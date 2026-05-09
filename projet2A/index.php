<?php
session_start();
require_once 'config/database.php';

spl_autoload_register(function($class) {
    $paths = [
        'app/model/',
        'app/contoller/',
    ];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});


$controller = $_GET['controller'] ?? 'produit';
$action     = $_GET['action']     ?? 'frigo';

$map = [
    'produit'   => 'ProduitController',
    'categorie' => 'CategorieController',
    'commande'  => 'CommandeController',
];

$controllerClass = $map[$controller] ?? null;

if (!$controllerClass) {
    die("Contrôleur introuvable.");
}

require_once 'app/contoller/' . $controllerClass . '.php';

$ctrl = new $controllerClass();

// Liste de toutes les actions autorisées
$actionsAutorisees = [
    'frigo', 'index', 'create', 'store', 'edit', 'update',
    'delete', 'ajouterFrigo', 'ajouterManuel', 'supprimerDuFrigo',
    'envoyerAuPanier', 'modifierQuantiteFrigo',
    'panier', 'ajouterPanier', 'modifierPanier', 'retirerPanier',
    'checkout', 'confirmer', 'annuler', 'updateCommande',
    'deleteCommande',
    'admin', 'store', 'updateCommande'
];

if (!in_array($action, $actionsAutorisees) || !method_exists($ctrl, $action)) {
    die("Action introuvable.");
}

$ctrl->$action();

// Auto-login with Remember Me (if not logged in)
if(!isset($_SESSION['user_id'])) {
    $userController->checkRememberMe();
}

switch($action) {
    // ========== FRONT ROUTES ==========
    case 'home':
        $userController->showHome();
        break;
    case 'register':
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userController->register();
        } else {
            $userController->showRegister();
        }
        break;
    case 'login':
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userController->login();
        } else {
            $userController->showLogin();
        }
        break;
    case 'profile':
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userController->updateProfile();
        } else {
            $userController->showProfile();
        }
        break;
    case 'logout':
        $userController->logout();
        break;
    case 'delete_account':
        $userController->deleteAccount();
        break;
    case 'forgot_password':
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userController->sendResetLink();
        } else {
            $userController->showForgotPassword();
        }
        break;
    case 'reset_password':
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userController->resetPassword();
        } else {
            $userController->showResetPassword();
        }
        break;
    
    // ========== AJAX RESET PASSWORD ==========
    case 'sendResetLink':
        $userController->sendResetLink();
        break;
    case 'resetPasswordAjax':
        $userController->resetPasswordAjax();
        break;
    
    // ========== SOCIAL LOGIN ==========
    case 'social_login_ajax':
        $userController->socialLoginAjax();
        break;
    case 'send_contact_message':
        $userController->sendContactMessage();
        break;
    
    // ========== FACE ID ROUTES ==========
    case 'save_face_signature':
        $userController->saveFaceSignature();
        break;
    case 'login_with_face':
        $userController->loginWithFace();
        break;
    
    // ========== ADMIN ROUTES ==========
    case 'admin_dashboard':
        $adminController = new AdminController();
        $adminController->dashboard();
        break;
    case 'admin_users':
        $adminController = new AdminController();
        $adminController->listUsers();
        break;
    case 'admin_edit_user':
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminController = new AdminController();
            $adminController->updateUser();
        } else {
            $adminController = new AdminController();
            $adminController->editUser();
        }
        break;
    case 'admin_delete_user':
        $adminController = new AdminController();
        $adminController->deleteUser();
        break;
    case 'admin_add_user':
        $adminController = new AdminController();
        $adminController->addUser();
        break;
    case 'admin_create_user':
        $adminController = new AdminController();
        $adminController->createUser();
        break;
    case 'admin_disable_user':
        $adminController = new AdminController();
        $adminController->disableUser();
        break;
    case 'admin_enable_user':
        $adminController = new AdminController();
        $adminController->enableUser();
        break;
    
    // ========== ADMIN CONTACT MESSAGES ROUTES ==========
    case 'admin_get_messages':
        $adminController = new AdminController();
        $messages = $adminController->getContactMessages();
        $unreadCount = $adminController->getUnreadCount();
        header('Content-Type: application/json');
        echo json_encode(['messages' => $messages, 'unreadCount' => $unreadCount]);
        break;
    case 'admin_mark_read':
        $adminController = new AdminController();
        $id = $_GET['id'];
        $success = $adminController->markAsRead($id);
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        break;
    case 'admin_delete_message':
        $adminController = new AdminController();
        $id = $_GET['id'];
        $success = $adminController->deleteMessage($id);
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        break;
    
    // ========== ADMIN ANALYTICS ROUTES ==========
    case 'admin_analytics':
        $adminController = new AdminController();
        $adminController->getAnalyticsData();
        break;
    
    // ========== ADMIN NOTIFICATIONS ROUTES ==========
    case 'admin_get_notifications':
        $adminController = new AdminController();
        $adminController->getNotifications();
        break;
    case 'admin_mark_notification_read':
        $adminController = new AdminController();
        $adminController->markNotificationRead();
        break;
    
    // ========== ADMIN WORLD MAP ROUTE ==========
    case 'admin_get_locations':
        $adminController = new AdminController();
        $adminController->getUserLocations();
        break;
    
    // ========== ADMIN EXPORT ROUTES ==========
    case 'admin_export_users':
        $adminController = new AdminController();
        $adminController->exportUsers();
        break;
    case 'admin_export_messages':
        $adminController = new AdminController();
        $adminController->exportMessages();
        break;
    
    // ========== ADMIN WIDGET SETTINGS ROUTES ==========
    case 'admin_save_widgets':
        $adminController = new AdminController();
        $adminController->saveWidgetSettings();
        break;
    case 'admin_get_widgets':
        $adminController = new AdminController();
        $adminController->getWidgetSettings();
        break;
    
    // ========== PREMIUM FEATURES ==========
    case 'admin_globe':
        $adminController = new AdminController();
        $adminController->showGlobe();
        break;
    case 'admin_secret':
        $adminController = new AdminController();
        $adminController->showSecretZone();
        break;
    case 'admin_terminal':
        $adminController = new AdminController();
        $adminController->showTerminal();
        break;
    
    // ========== TOP 5 NEW FEATURES ==========
    case 'admin_incognito':
        $adminController = new AdminController();
        $adminController->showIncognito();
        break;
    case 'admin_shortcuts':
        $adminController = new AdminController();
        $adminController->showShortcuts();
        break;
    case 'admin_comparison':
        $adminController = new AdminController();
        $adminController->showComparison();
        break;
    case 'admin_leaderboard':
        $adminController = new AdminController();
        $adminController->showLeaderboard();
        break;
    case 'admin_cleaner':
        $adminController = new AdminController();
        $adminController->showCleaner();
        break;
    
    default:
        $userController->showHome();
}
?>
