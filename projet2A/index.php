<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'controllers/UserController.php';
require_once 'controllers/AdminController.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'home';
$userController = new UserController();

switch($action) {
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
    default:
        $userController->showHome();
}
?>