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
        $users = $this->getAllUsers();
        $unreadCount = $this->getUnreadCount();
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
        $users = $this->getAllUsers();
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
            $user = $this->getUserById($_GET['id']);
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
            
            if($this->updateUserByAdmin($_GET['id'], $data)) {
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
                if($this->deleteUserById($_GET['id'])) {
                    $_SESSION['success'] = "User deleted successfully";
                } else {
                    $_SESSION['error'] = "Delete failed";
                }
            }
        }
        header("Location: index.php?action=admin_users");
        exit();
    }

    // === ADD USER METHODS ===
    
    public function addUser() {
        $page_title = "Add User - NutriFlow AI Admin";
        $page_heading = "Add New User";
        $active_page = "users";
        $breadcrumb = [
            ['label' => 'Dashboard', 'url' => 'index.php?action=admin_dashboard'],
            ['label' => 'Users', 'url' => 'index.php?action=admin_users'],
            ['label' => 'Add User']
        ];
        ob_start();
        include dirname(__DIR__) . '/views/back/add-user.php';
        $content = ob_get_clean();
        include dirname(__DIR__) . '/views/back/layout.php';
    }

    public function createUser() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = [];
            
            if(empty($_POST['username']) || strlen($_POST['username']) < 3) {
                $errors['username'] = "Username must be at least 3 characters";
            }
            if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Valid email is required";
            }
            if($this->emailExists($_POST['email'])) {
                $errors['email'] = "Email already exists";
            }
            if(empty($_POST['password']) || strlen($_POST['password']) < 6) {
                $errors['password'] = "Password must be at least 6 characters";
            }
            if($_POST['password'] !== $_POST['confirm_password']) {
                $errors['confirm_password'] = "Passwords do not match";
            }
            if(!empty($_POST['age']) && ($_POST['age'] < 1 || $_POST['age'] > 120)) {
                $errors['age'] = "Age must be between 1 and 120";
            }
            if(!empty($_POST['weight']) && ($_POST['weight'] < 20 || $_POST['weight'] > 300)) {
                $errors['weight'] = "Weight must be between 20 and 300 kg";
            }
            if(!empty($_POST['height']) && ($_POST['height'] < 100 || $_POST['height'] > 250)) {
                $errors['height'] = "Height must be between 100 and 250 cm";
            }
            
            if(empty($errors)) {
                if($this->createNewUser($_POST)) {
                    $_SESSION['success'] = "User created successfully!";
                    $this->addNotification('user', "New user {$_POST['username']} has joined NutriFlow AI!", 'index.php?action=admin_users');
                    header("Location: index.php?action=admin_users");
                    exit();
                } else {
                    $_SESSION['error'] = "Failed to create user";
                }
            }
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header("Location: index.php?action=admin_add_user");
            exit();
        }
    }

    // === DISABLE/ENABLE USER METHODS ===
    
    public function disableUser() {
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            
            if($id == $_SESSION['user_id']) {
                $_SESSION['error'] = "You cannot disable your own account";
            } else {
                $user = $this->getUserById($id);
                if($this->setUserStatus($id, 0)) {
                    $_SESSION['success'] = "User has been disabled successfully";
                    $this->addNotification('alert', "User {$user['username']} has been disabled", 'index.php?action=admin_users');
                    $this->sendAccountDisabledEmail($user['email'], $user['username']);
                } else {
                    $_SESSION['error'] = "Failed to disable user";
                }
            }
        }
        header("Location: index.php?action=admin_users");
        exit();
    }

    public function enableUser() {
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            
            $user = $this->getUserById($id);
            $userEmail = $user['email'];
            $username = $user['username'];
            
            if($this->setUserStatus($id, 1)) {
                $_SESSION['success'] = "User has been enabled successfully";
                $this->addNotification('success', "User {$username} has been reactivated", 'index.php?action=admin_users');
                $this->sendAccountReactivatedEmail($userEmail, $username);
                
                $query = "UPDATE " . $this->userModel->getTable() . " SET needs_welcome_message = 1 WHERE id = :id";
                $stmt = $this->userModel->getConnection()->prepare($query);
                $stmt->bindParam(":id", $id);
                $stmt->execute();
            } else {
                $_SESSION['error'] = "Failed to enable user";
            }
        }
        header("Location: index.php?action=admin_users");
        exit();
    }

    // === CONTACT MESSAGES METHODS ===
    
    public function getContactMessages() {
        $query = "SELECT * FROM contact_messages ORDER BY created_at DESC";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUnreadCount() {
        $query = "SELECT COUNT(*) as count FROM contact_messages WHERE status = 'unread'";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'];
    }

    public function markAsRead($id) {
        $query = "UPDATE contact_messages SET status = 'read' WHERE id = :id";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function deleteMessage($id) {
        $query = "DELETE FROM contact_messages WHERE id = :id";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // === ANALYTICS & GRAPHS ===
    
    public function getAnalyticsData() {
        header('Content-Type: application/json');
        
        $query = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
                  FROM " . $this->userModel->getTable() . " 
                  WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                  GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                  ORDER BY month ASC";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->execute();
        $registrations = $stmt->fetchAll();
        
        $query = "SELECT DATE(login_time) as date, COUNT(DISTINCT user_id) as count 
                  FROM user_login_logs 
                  WHERE login_time >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                  GROUP BY DATE(login_time)
                  ORDER BY date ASC";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->execute();
        $activity = $stmt->fetchAll();
        
        $query = "SELECT dietary_preference, COUNT(*) as count 
                  FROM " . $this->userModel->getTable() . " 
                  WHERE dietary_preference IS NOT NULL AND dietary_preference != ''
                  GROUP BY dietary_preference";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->execute();
        $dietary = $stmt->fetchAll();
        
        echo json_encode([
            'success' => true,
            'registrations' => $registrations,
            'activity' => $activity,
            'dietary' => $dietary
        ]);
        exit();
    }

    // === NOTIFICATIONS ===
    
    public function getNotifications() {
        header('Content-Type: application/json');
        $query = "SELECT * FROM admin_notifications ORDER BY created_at DESC LIMIT 20";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->execute();
        $notifications = $stmt->fetchAll();
        
        $query = "SELECT COUNT(*) as count FROM admin_notifications WHERE is_read = 0";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->execute();
        $unreadCount = $stmt->fetch()['count'];
        
        echo json_encode(['success' => true, 'notifications' => $notifications, 'unreadCount' => $unreadCount]);
        exit();
    }

    public function markNotificationRead() {
        $id = $_GET['id'] ?? 0;
        $query = "UPDATE admin_notifications SET is_read = 1 WHERE id = :id";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->bindParam(":id", $id);
        $success = $stmt->execute();
        echo json_encode(['success' => $success]);
        exit();
    }

    public function addNotification($type, $message, $link = null) {
        $query = "INSERT INTO admin_notifications (type, message, link) VALUES (:type, :message, :link)";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->bindParam(":type", $type);
        $stmt->bindParam(":message", $message);
        $stmt->bindParam(":link", $link);
        return $stmt->execute();
    }

    // === WORLD MAP ===
    
    public function getUserLocations() {
        header('Content-Type: application/json');
        $query = "SELECT country, COUNT(*) as count, latitude, longitude 
                  FROM user_login_logs 
                  WHERE country IS NOT NULL 
                  GROUP BY country 
                  ORDER BY count DESC 
                  LIMIT 20";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->execute();
        $locations = $stmt->fetchAll();
        echo json_encode(['success' => true, 'locations' => $locations]);
        exit();
    }

    // === EXPORT REPORTS ===
    
    public function exportUsers() {
        $format = $_GET['format'] ?? 'csv';
        $users = $this->getAllUsers();
        
        if($format === 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="users_export_' . date('Y-m-d') . '.csv"');
            
            $output = fopen('php://output', 'w');
            fputcsv($output, ['ID', 'Username', 'Email', 'Full Name', 'Phone', 'Age', 'Role', 'Status', 'Registered']);
            
            foreach($users as $user) {
                fputcsv($output, [
                    $user['id'],
                    $user['username'],
                    $user['email'],
                    $user['full_name'] ?? '',
                    $user['phone'] ?? '',
                    $user['age'] ?? '',
                    $user['role'],
                    $user['is_active'] == 1 ? 'Active' : 'Disabled',
                    $user['created_at']
                ]);
            }
            fclose($output);
            exit();
        } elseif($format === 'excel') {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="users_export_' . date('Y-m-d') . '.xls"');
            
            echo '<table border="1">';
            echo '<tr><th>ID</th><th>Username</th><th>Email</th><th>Full Name</th><th>Phone</th><th>Age</th><th>Role</th><th>Status</th><th>Registered</th></tr>';
            foreach($users as $user) {
                echo '<tr>';
                echo '<td>' . $user['id'] . '</td>';
                echo '<td>' . htmlspecialchars($user['username']) . '</td>';
                echo '<td>' . htmlspecialchars($user['email']) . '</td>';
                echo '<td>' . htmlspecialchars($user['full_name'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($user['phone'] ?? '') . '</td>';
                echo '<td>' . ($user['age'] ?? '') . '</td>';
                echo '<td>' . $user['role'] . '</td>';
                echo '<td>' . ($user['is_active'] == 1 ? 'Active' : 'Disabled') . '</td>';
                echo '<td>' . $user['created_at'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            exit();
        }
    }

    public function exportMessages() {
        $format = $_GET['format'] ?? 'csv';
        $messages = $this->getContactMessages();
        
        if($format === 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="messages_export_' . date('Y-m-d') . '.csv"');
            
            $output = fopen('php://output', 'w');
            fputcsv($output, ['ID', 'Name', 'Email', 'Message', 'Status', 'Date']);
            
            foreach($messages as $msg) {
                fputcsv($output, [
                    $msg['id'],
                    $msg['name'],
                    $msg['email'],
                    $msg['message'],
                    $msg['status'],
                    $msg['created_at']
                ]);
            }
            fclose($output);
            exit();
        } elseif($format === 'excel') {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="messages_export_' . date('Y-m-d') . '.xls"');
            
            echo '<table border="1">';
            echo '<tr><th>ID</th><th>Name</th><th>Email</th><th>Message</th><th>Status</th><th>Date</th></tr>';
            foreach($messages as $msg) {
                echo '<tr>';
                echo '<td>' . $msg['id'] . '</td>';
                echo '<td>' . htmlspecialchars($msg['name']) . '</td>';
                echo '<td>' . htmlspecialchars($msg['email']) . '</td>';
                echo '<td>' . htmlspecialchars($msg['message']) . '</td>';
                echo '<td>' . $msg['status'] . '</td>';
                echo '<td>' . $msg['created_at'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            exit();
        }
    }

    // === WIDGET SETTINGS ===
    
    public function saveWidgetSettings() {
        header('Content-Type: application/json');
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $widgets = $data['widgets'] ?? [];
            $_SESSION['admin_widgets'] = $widgets;
            echo json_encode(['success' => true]);
            exit();
        }
    }

    public function getWidgetSettings() {
        header('Content-Type: application/json');
        $widgets = $_SESSION['admin_widgets'] ?? [
            'stats' => true,
            'analytics' => true,
            'worldmap' => true,
            'notifications' => true,
            'messages' => true
        ];
        echo json_encode(['success' => true, 'widgets' => $widgets]);
        exit();
    }

    // === EMAIL NOTIFICATION METHODS ===
    
    private function sendAccountDisabledEmail($email, $username) {
        $subject = "Your NutriFlow AI Account Has Been Disabled";
        $message = "
        <html>
        <head>
            <title>Account Disabled</title>
        </head>
        <body>
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: #fef2f2; border-radius: 10px;'>
                <div style='text-align: center;'>
                    <span style='font-size: 50px;'>🥗</span>
                    <h2 style='color: #dc2626;'>NutriFlow AI</h2>
                </div>
                <div style='background: white; padding: 20px; border-radius: 10px;'>
                    <h3>Hello $username,</h3>
                    <p>Your NutriFlow AI account has been <strong style='color: #dc2626;'>disabled</strong> by the administrator.</p>
                    <p>If you believe this is a mistake or want to reactivate your account, please contact us:</p>
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='mailto:admin@nutriflowai.com' 
                           style='background: #dc2626; 
                                  color: white; 
                                  padding: 12px 30px; 
                                  text-decoration: none; 
                                  border-radius: 25px;
                                  display: inline-block;'>
                            Contact Administrator
                        </a>
                    </div>
                    <p style='color: #666; font-size: 12px;'>We hope to see you back soon!</p>
                </div>
                <div style='text-align: center; margin-top: 20px; font-size: 12px; color: #999;'>
                    <p>&copy; 2025 NutriFlow AI. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: NutriFlow AI <admin@nutriflowai.com>" . "\r\n";
        
        mail($email, $subject, $message, $headers);
    }

    private function sendAccountReactivatedEmail($email, $username) {
        $subject = "Your NutriFlow AI Account Has Been Reactivated";
        $message = "
        <html>
        <head>
            <title>Account Reactivated</title>
        </head>
        <body>
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: #f0fdf4; border-radius: 10px;'>
                <div style='text-align: center;'>
                    <span style='font-size: 50px;'>🥗</span>
                    <h2 style='color: #16a34a;'>NutriFlow AI</h2>
                </div>
                <div style='background: white; padding: 20px; border-radius: 10px;'>
                    <h3>Hello $username,</h3>
                    <p>Good news! Your NutriFlow AI account has been <strong style='color: #16a34a;'>reactivated</strong> by the administrator.</p>
                    <p>You can now log in to your account and continue your nutrition journey.</p>
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='http://{$_SERVER['HTTP_HOST']}/nutriflow/index.php?action=login' 
                           style='background: linear-gradient(135deg, #16a34a, #14532d); 
                                  color: white; 
                                  padding: 12px 30px; 
                                  text-decoration: none; 
                                  border-radius: 25px;
                                  display: inline-block;'>
                            Login to Your Account
                        </a>
                    </div>
                    <p style='color: #666; font-size: 12px;'>If you have any questions, please contact us at admin@nutriflowai.com</p>
                </div>
                <div style='text-align: center; margin-top: 20px; font-size: 12px; color: #999;'>
                    <p>&copy; 2025 NutriFlow AI. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: NutriFlow AI <admin@nutriflowai.com>" . "\r\n";
        
        mail($email, $subject, $message, $headers);
    }

    // === PRIVATE METHODS ===
    
    private function getAllUsers() {
        $query = "SELECT * FROM " . $this->userModel->getTable() . " ORDER BY created_at DESC";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function getUserById($id) {
        $query = "SELECT * FROM " . $this->userModel->getTable() . " WHERE id = :id LIMIT 1";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    private function updateUserByAdmin($id, $data) {
        $query = "UPDATE " . $this->userModel->getTable() . " 
                  SET username=:username, email=:email, full_name=:full_name, 
                      phone=:phone, age=:age, role=:role 
                  WHERE id = :id";
        
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->bindParam(":username", $data['username']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":full_name", $data['full_name']);
        $stmt->bindParam(":phone", $data['phone']);
        $stmt->bindParam(":age", $data['age']);
        $stmt->bindParam(":role", $data['role']);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }

    private function deleteUserById($id) {
        $query = "DELETE FROM " . $this->userModel->getTable() . " WHERE id = :id";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    private function emailExists($email) {
        $query = "SELECT id FROM " . $this->userModel->getTable() . " WHERE email = :email LIMIT 1";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    private function createNewUser($data) {
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $query = "INSERT INTO " . $this->userModel->getTable() . " 
                  SET username=:username, email=:email, password=:password, 
                      full_name=:full_name, phone=:phone, age=:age, 
                      weight=:weight, height=:height, dietary_preference=:dietary_preference,
                      role=:role, is_active=1, needs_welcome_message=0";
        
        $stmt = $this->userModel->getConnection()->prepare($query);
        
        $stmt->bindParam(":username", $data['username']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":full_name", $data['full_name']);
        $stmt->bindParam(":phone", $data['phone']);
        $stmt->bindParam(":age", $data['age']);
        $stmt->bindParam(":weight", $data['weight']);
        $stmt->bindParam(":height", $data['height']);
        $stmt->bindParam(":dietary_preference", $data['dietary_preference']);
        $stmt->bindParam(":role", $data['role']);
        
        return $stmt->execute();
    }

    private function setUserStatus($id, $status) {
        $query = "UPDATE " . $this->userModel->getTable() . " SET is_active = :status WHERE id = :id";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
?>
