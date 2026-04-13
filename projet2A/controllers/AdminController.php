<?php
require_once dirname(__DIR__) . '/config/session.php';
require_once dirname(__DIR__) . '/models/User.php';

class AdminController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
    }

    public function dashboard() {
        $users = $this->userModel->getAllUsers();
        $page_title = "Dashboard - NutriFlow AI Admin";
        $page_heading = "Dashboard";
        $active_page = "dashboard";
        $breadcrumb = [['label' => 'Dashboard']];
        ob_start();
        include dirname(__DIR__) . '/views/back/dashboard.php';
        $content = ob_get_clean();
        include dirname(__DIR__) . '/views/back/layout.php';
    }

    public function listUsers() {
        $users = $this->userModel->getAllUsers();
        $page_title = "Users Management - NutriFlow AI Admin";
        $page_heading = "Users Management";
        $active_page = "users";
        $breadcrumb = [
            ['label' => 'Dashboard', 'url' => 'index.php?action=admin_dashboard'],
            ['label' => 'Users']
        ];
        ob_start();
        include dirname(__DIR__) . '/views/back/users.php';
        $content = ob_get_clean();
        include dirname(__DIR__) . '/views/back/layout.php';
    }

    public function editUser() {
        if(isset($_GET['id'])) {
            $user = $this->userModel->getUserById($_GET['id']);
            if(!$user) {
                header("Location: index.php?action=admin_users");
                exit();
            }
            $page_title = "Edit User - NutriFlow AI Admin";
            $page_heading = "Edit User";
            $active_page = "users";
            $breadcrumb = [
                ['label' => 'Dashboard', 'url' => 'index.php?action=admin_dashboard'],
                ['label' => 'Users', 'url' => 'index.php?action=admin_users'],
                ['label' => 'Edit User']
            ];
            ob_start();
            include dirname(__DIR__) . '/views/back/edit-user.php';
            $content = ob_get_clean();
            include dirname(__DIR__) . '/views/back/layout.php';
        }
    }

    public function updateUser() {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['id'])) {
            $data = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'full_name' => $_POST['full_name'],
                'phone' => $_POST['phone'],
                'age' => $_POST['age'],
                'role' => $_POST['role']
            ];
            
            if($this->userModel->updateUserByAdmin($_GET['id'], $data)) {
                $_SESSION['success'] = "User updated successfully";
            } else {
                $_SESSION['error'] = "Update failed";
            }
            header("Location: index.php?action=admin_users");
            exit();
        }
    }

    public function deleteUser() {
        if(isset($_GET['id'])) {
            if($_GET['id'] == $_SESSION['user_id']) {
                $_SESSION['error'] = "You cannot delete your own account";
            } else {
                if($this->userModel->deleteUser($_GET['id'])) {
                    $_SESSION['success'] = "User deleted successfully";
                } else {
                    $_SESSION['error'] = "Delete failed";
                }
            }
        }
        header("Location: index.php?action=admin_users");
        exit();
    }
}
?>