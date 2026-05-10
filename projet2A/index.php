<?php
/**
 * Point d'entrée NutriFlow : page d'accueil = Healthy Eat Healthy (action=home par défaut).
 * Ancien mini-routeur frigo : uniquement si ?controller= est produit|categorie|commande.
 */
session_start();

require_once __DIR__ . '/config/database.php';

spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/models/' . $class . '.php',
        __DIR__ . '/controllers/' . $class . '.php',
        __DIR__ . '/app/model/' . $class . '.php',
        __DIR__ . '/app/contoller/' . $class . '.php',
    ];
    foreach ($paths as $file) {
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

$legacyControllers = ['produit', 'categorie', 'commande'];

/*
 * Les anciennes versions utilisaient $controller = $_GET['controller'] ?? 'produit', donc
 * ?action=home appelait ProduitController->home() → « Action introuvable ».
 * Les routes NutriFlow passent toujours en premier si ?action= est reconnu.
 */
$requestedAction = $_GET['action'] ?? '';
$nutriflowFrontActions = [
    'home', 'login', 'register', 'profile', 'logout', 'delete_account',
    'forgot_password', 'reset_password', 'sendResetLink', 'resetPasswordAjax',
    'social_login_ajax', 'send_contact_message', 'save_face_signature', 'login_with_face',
];
$isNutriflowAction = ($requestedAction !== ''
    && (
        in_array($requestedAction, $nutriflowFrontActions, true)
        || strpos($requestedAction, 'admin_') === 0
    ));

if (
    !$isNutriflowAction
    && isset($_GET['controller'])
    && in_array($_GET['controller'], $legacyControllers, true)
) {
    $map = [
        'produit'   => 'ProduitController',
        'categorie' => 'CategorieController',
        'commande'  => 'CommandeController',
    ];
    $controllerKey = $_GET['controller'];
    $controllerClass = $map[$controllerKey];
    require_once __DIR__ . '/app/contoller/' . $controllerClass . '.php';
    $ctrl = new $controllerClass();

    $action = $_GET['action'] ?? (
        $controllerKey === 'produit' ? 'frigo' : 'index'
    );

    if (!method_exists($ctrl, $action)) {
        die('Action introuvable.');
    }

    $ctrl->$action();
    exit;
}

require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/AdminController.php';

$userController = new UserController();

if (!isset($_SESSION['user_id'])) {
    $userController->checkRememberMe();
}

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'home':
        $userController->showHome();
        break;
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->register();
        } else {
            $userController->showRegister();
        }
        break;
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->login();
        } else {
            $userController->showLogin();
        }
        break;
    case 'profile':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->sendResetLink();
        } else {
            $userController->showForgotPassword();
        }
        break;
    case 'reset_password':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->resetPassword();
        } else {
            $userController->showResetPassword();
        }
        break;

    case 'sendResetLink':
        $userController->sendResetLink();
        break;
    case 'resetPasswordAjax':
        $userController->resetPasswordAjax();
        break;

    case 'social_login_ajax':
        $userController->socialLoginAjax();
        break;
    case 'send_contact_message':
        $userController->sendContactMessage();
        break;

    case 'save_face_signature':
        $userController->saveFaceSignature();
        break;
    case 'login_with_face':
        $userController->loginWithFace();
        break;

    case 'admin_dashboard':
        $adminController = new AdminController();
        $adminController->dashboard();
        break;
    case 'admin_users':
        $adminController = new AdminController();
        $adminController->listUsers();
        break;
    case 'admin_edit_user':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    case 'admin_analytics':
        $adminController = new AdminController();
        $adminController->getAnalyticsData();
        break;

    case 'admin_get_notifications':
        $adminController = new AdminController();
        $adminController->getNotifications();
        break;
    case 'admin_mark_notification_read':
        $adminController = new AdminController();
        $adminController->markNotificationRead();
        break;

    case 'admin_get_locations':
        $adminController = new AdminController();
        $adminController->getUserLocations();
        break;

    case 'admin_export_users':
        $adminController = new AdminController();
        $adminController->exportUsers();
        break;
    case 'admin_export_messages':
        $adminController = new AdminController();
        $adminController->exportMessages();
        break;

    case 'admin_save_widgets':
        $adminController = new AdminController();
        $adminController->saveWidgetSettings();
        break;
    case 'admin_get_widgets':
        $adminController = new AdminController();
        $adminController->getWidgetSettings();
        break;

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
        break;
}
