<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . '/models/User.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showRegister() {
        $page_title = "Sign Up - NutriFlow AI";
        $active_page = "register";
        ob_start();
        include dirname(__DIR__) . '/views/front/register.php';
        $content = ob_get_clean();
        include dirname(__DIR__) . '/views/front/layout.php';
    }

    public function register() {
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
                $this->userModel->setUsername($_POST['username']);
                $this->userModel->setEmail($_POST['email']);
                $this->userModel->setPassword($_POST['password']);
                $this->userModel->setFullName($_POST['full_name'] ?? '');
                $this->userModel->setPhone($_POST['phone'] ?? '');
                $this->userModel->setAge(!empty($_POST['age']) ? $_POST['age'] : null);
                $this->userModel->setWeight(!empty($_POST['weight']) ? $_POST['weight'] : null);
                $this->userModel->setHeight(!empty($_POST['height']) ? $_POST['height'] : null);
                $this->userModel->setDietaryPreference($_POST['dietary_preference'] ?? '');

                if($this->registerUser()) {
                    $newUserId = $this->userModel->getConnection()->lastInsertId();
                    $_SESSION['new_user_id'] = $newUserId;
                    
                    $_SESSION['temp_user_id'] = $newUserId;
                    
                    if($this->isAdminEmail($_POST['email'])) {
                        $_SESSION['success'] = "Admin account created successfully! Please login.";
                    } else {
                        $_SESSION['success'] = "Registration successful! Please login.";
                    }
                    header("Location: index.php?action=register");
                    exit();
                } else {
                    $errors['general'] = "Registration failed. Please try again.";
                }
            }
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header("Location: index.php?action=register");
            exit();
        }
    }

    public function showLogin() {
        $page_title = "Login - NutriFlow AI";
        $active_page = "login";
        ob_start();
        include dirname(__DIR__) . '/views/front/login.php';
        $content = ob_get_clean();
        include dirname(__DIR__) . '/views/front/layout.php';
    }

    public function login() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = [];
            
            if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Valid email is required";
            }
            if(empty($_POST['password'])) {
                $errors['password'] = "Password is required";
            }

            if(empty($errors)) {
                if($this->loginUser($_POST['email'], $_POST['password'], isset($_POST['remember_me']))) {
                    if($this->userModel->getRole() == 'admin') {
                        header("Location: ../dashboard.php#donations");
                    } else {
                        header("Location: index.php?action=profile");
                    }
                    exit();
                } else {
                    if(!isset($_SESSION['error']) && !isset($_SESSION['account_disabled'])) {
                        $errors['login'] = "Invalid email or password";
                    }
                }
            }
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = ['email' => $_POST['email']];
            header("Location: index.php?action=login");
            exit();
        }
    }

    public function showProfile() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
            header("Location: ../dashboard.php#donations");
            exit();
        }
        $page_title = "My Profile - NutriFlow AI";
        $active_page = "profile";
        ob_start();
        include dirname(__DIR__) . '/views/front/profile.php';
        $content = ob_get_clean();
        include dirname(__DIR__) . '/views/front/layout.php';
    }

    public function updateProfile() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = [];
            
            if(empty($_POST['username']) || strlen($_POST['username']) < 3) {
                $errors['username'] = "Username must be at least 3 characters";
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
                if($this->updateUserProfile($_SESSION['user_id'], $_POST)) {
                    $_SESSION['username'] = $_POST['username'];
                    $_SESSION['full_name'] = $_POST['full_name'];
                    $_SESSION['phone'] = $_POST['phone'];
                    $_SESSION['age'] = $_POST['age'];
                    $_SESSION['weight'] = $_POST['weight'];
                    $_SESSION['height'] = $_POST['height'];
                    $_SESSION['dietary_preference'] = $_POST['dietary_preference'];
                    $_SESSION['success'] = "Profile updated successfully!";
                } else {
                    $_SESSION['error'] = "Update failed.";
                }
            }
            $_SESSION['errors'] = $errors;
            header("Location: index.php?action=profile");
            exit();
        }
    }

    public function logout() {
        if(isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
            
            if(isset($_SESSION['user_id'])) {
                $query = "UPDATE " . $this->userModel->getTable() . " SET remember_token = NULL, token_expires = NULL WHERE id = :id";
                $stmt = $this->userModel->getConnection()->prepare($query);
                $stmt->bindParam(":id", $_SESSION['user_id']);
                $stmt->execute();
            }
        }
        
        session_destroy();
        header("Location: index.php?action=home");
        exit();
    }

    public function showHome() {
        $page_title = "NutriFlow AI - Healthy Eating Made Smart";
        $active_page = "home";
        ob_start();
        include dirname(__DIR__) . '/views/front/home.php';
        $content = ob_get_clean();
        include dirname(__DIR__) . '/views/front/layout.php';
    }

    public function deleteAccount() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $user_id = $_SESSION['user_id'];
            
            $user = $this->getUserById($user_id);
            if(!$user) {
                header("Location: index.php?action=home");
                exit();
            }
            
            if($this->deleteUserAccount($user_id)) {
                session_destroy();
                session_start();
                $_SESSION['account_deleted'] = "Your account has been successfully deleted. We're sad to see you go!";
                header("Location: index.php?action=home");
                exit();
            } else {
                $_SESSION['error'] = "Failed to delete account. Please try again.";
                header("Location: index.php?action=profile");
                exit();
            }
        }
    }

    // === FORGOT PASSWORD METHODS ===
    
    public function showForgotPassword() {
        $page_title = "Forgot Password - NutriFlow AI";
        ob_start();
        include dirname(__DIR__) . '/views/front/forgot-password.php';
        $content = ob_get_clean();
        include dirname(__DIR__) . '/views/front/layout.php';
    }

    public function showResetPassword() {
        $page_title = "Reset Password - NutriFlow AI";
        $token = $_GET['token'] ?? '';
        
        // Vérifier si le token existe dans la base de données
        $query = "SELECT id FROM " . $this->userModel->getTable() . " WHERE reset_token = :token AND reset_expires > NOW()";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->execute();
        
        if($stmt->rowCount() == 0 && strpos($token, 'demo_') !== 0) {
            $_SESSION['error'] = "Invalid or expired reset link.";
            header("Location: index.php?action=forgot_password");
            exit();
        }
        
        ob_start();
        include dirname(__DIR__) . '/views/front/reset-password.php';
        $content = ob_get_clean();
        include dirname(__DIR__) . '/views/front/layout.php';
    }

    public function sendResetLink() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            
            // Vérifier si l'email existe
            $query = "SELECT id, username FROM " . $this->userModel->getTable() . " WHERE email = :email";
            $stmt = $this->userModel->getConnection()->prepare($query);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            
            $username = 'User';
            $userId = null;
            
            if($stmt->rowCount() > 0) {
                $user = $stmt->fetch();
                $username = $user['username'];
                $userId = $user['id'];
                
                // Générer un token unique
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                // Sauvegarder le token dans la base de données
                $updateQuery = "UPDATE " . $this->userModel->getTable() . " SET reset_token = :token, reset_expires = :expires WHERE id = :id";
                $updateStmt = $this->userModel->getConnection()->prepare($updateQuery);
                $updateStmt->bindParam(":token", $token);
                $updateStmt->bindParam(":expires", $expires);
                $updateStmt->bindParam(":id", $userId);
                $updateStmt->execute();
                
                // Retourner une réponse JSON pour le front
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'token' => $token,
                    'email' => $email,
                    'username' => $username
                ]);
                exit();
            } else {
                // Email non trouvé - on simule quand même pour la démo
                $fakeToken = 'demo_' . bin2hex(random_bytes(16));
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'token' => $fakeToken,
                    'email' => $email,
                    'username' => explode('@', $email)[0],
                    'demo' => true
                ]);
                exit();
            }
        }
    }

    // === AJAX RESET PASSWORD ===
    public function resetPasswordAjax() {
        header('Content-Type: application/json');
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $token = $data['token'] ?? '';
            $password = $data['password'] ?? '';
            $email = $data['email'] ?? '';
            
            if(strlen($password) < 6) {
                echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
                exit();
            }
            
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Pour les tokens de démo
            if(strpos($token, 'demo_') === 0) {
                echo json_encode(['success' => true, 'message' => 'Password updated successfully! (Demo mode)']);
                exit();
            }
            
            // Vérifier dans la base de données
            $query = "SELECT id FROM " . $this->userModel->getTable() . " WHERE reset_token = :token AND reset_expires > NOW()";
            $stmt = $this->userModel->getConnection()->prepare($query);
            $stmt->bindParam(":token", $token);
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                $user = $stmt->fetch();
                
                $updateQuery = "UPDATE " . $this->userModel->getTable() . " SET password = :password, reset_token = NULL, reset_expires = NULL WHERE id = :id";
                $updateStmt = $this->userModel->getConnection()->prepare($updateQuery);
                $updateStmt->bindParam(":password", $hashedPassword);
                $updateStmt->bindParam(":id", $user['id']);
                $updateStmt->execute();
                
                echo json_encode(['success' => true, 'message' => 'Password updated successfully!']);
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid or expired reset link.']);
                exit();
            }
        }
    }

    public function resetPassword() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $token = $_POST['token'] ?? '';
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            
            if(strlen($password) < 6) {
                $_SESSION['error'] = "Password must be at least 6 characters";
                header("Location: index.php?action=reset_password&token=" . urlencode($token));
                exit();
            }
            
            if($password !== $confirm_password) {
                $_SESSION['error'] = "Passwords do not match";
                header("Location: index.php?action=reset_password&token=" . urlencode($token));
                exit();
            }
            
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Vérifier si le token existe et n'est pas expiré
            $query = "SELECT id FROM " . $this->userModel->getTable() . " WHERE reset_token = :token AND reset_expires > NOW()";
            $stmt = $this->userModel->getConnection()->prepare($query);
            $stmt->bindParam(":token", $token);
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                $user = $stmt->fetch();
                
                // Mettre à jour le mot de passe et effacer le token
                $updateQuery = "UPDATE " . $this->userModel->getTable() . " SET password = :password, reset_token = NULL, reset_expires = NULL WHERE id = :id";
                $updateStmt = $this->userModel->getConnection()->prepare($updateQuery);
                $updateStmt->bindParam(":password", $hashedPassword);
                $updateStmt->bindParam(":id", $user['id']);
                $updateStmt->execute();
                
                $_SESSION['success'] = "Password reset successfully! Please login with your new password.";
                header("Location: index.php?action=login");
                exit();
            } else {
                // Pour les tokens de démo (simulation)
                if(strpos($token, 'demo_') === 0) {
                    $_SESSION['success'] = "Password reset successfully! (Demo mode) Please login with your new password.";
                    header("Location: index.php?action=login");
                    exit();
                }
                
                $_SESSION['error'] = "Invalid or expired reset link.";
                header("Location: index.php?action=forgot_password");
                exit();
            }
        }
    }

    // === REMEMBER ME METHOD ===
    
    public function checkRememberMe() {
        if(isset($_COOKIE['remember_token'])) {
            $token = $_COOKIE['remember_token'];
            $query = "SELECT * FROM " . $this->userModel->getTable() . " WHERE remember_token = :token AND token_expires > NOW() LIMIT 1";
            $stmt = $this->userModel->getConnection()->prepare($query);
            $stmt->bindParam(":token", $token);
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                
                if(isset($row['is_active']) && $row['is_active'] == 0) {
                    setcookie('remember_token', '', time() - 3600, '/');
                    return false;
                }
                
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['full_name'] = $row['full_name'];
                $_SESSION['phone'] = $row['phone'];
                $_SESSION['age'] = $row['age'];
                $_SESSION['weight'] = $row['weight'];
                $_SESSION['height'] = $row['height'];
                $_SESSION['dietary_preference'] = $row['dietary_preference'];
                $_SESSION['role'] = $row['role'];
                if (($row['role'] ?? '') === 'admin') {
                    $_SESSION['logged_in'] = true;
                }
                
                if(isset($row['needs_welcome_message']) && $row['needs_welcome_message'] == 1) {
                    $_SESSION['account_reactivated'] = "🎉 Your account has been reactivated! Welcome back to NutriFlow AI! 🎉";
                    $updateQuery = "UPDATE " . $this->userModel->getTable() . " SET needs_welcome_message = 0 WHERE id = :id";
                    $updateStmt = $this->userModel->getConnection()->prepare($updateQuery);
                    $updateStmt->bindParam(":id", $row['id']);
                    $updateStmt->execute();
                }
                
                return true;
            }
        }
        return false;
    }

    // === SOCIAL LOGIN AJAX ===
    
    public function socialLoginAjax() {
        header('Content-Type: application/json');
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $email = $data['email'] ?? '';
            $username = $data['username'] ?? '';
            $provider = $data['provider'] ?? '';
            
            if(empty($email)) {
                echo json_encode(['success' => false, 'message' => 'Email required']);
                exit();
            }
            
            $query = "SELECT * FROM " . $this->userModel->getTable() . " WHERE email = :email LIMIT 1";
            $stmt = $this->userModel->getConnection()->prepare($query);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                
                if(isset($row['is_active']) && $row['is_active'] == 0) {
                    echo json_encode(['success' => false, 'message' => 'Account disabled']);
                    exit();
                }
                
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['full_name'] = $row['full_name'];
                $_SESSION['phone'] = $row['phone'];
                $_SESSION['age'] = $row['age'];
                $_SESSION['weight'] = $row['weight'];
                $_SESSION['height'] = $row['height'];
                $_SESSION['dietary_preference'] = $row['dietary_preference'];
                $_SESSION['role'] = $row['role'];
                if (($row['role'] ?? '') === 'admin') {
                    $_SESSION['logged_in'] = true;
                }
                
                echo json_encode(['success' => true, 'action' => 'login']);
            } else {
                $newUsername = !empty($username) ? $username : explode('@', $email)[0];
                $checkQuery = "SELECT id FROM " . $this->userModel->getTable() . " WHERE username = :username";
                $checkStmt = $this->userModel->getConnection()->prepare($checkQuery);
                $checkStmt->bindParam(":username", $newUsername);
                $checkStmt->execute();
                if($checkStmt->rowCount() > 0) {
                    $newUsername = $newUsername . rand(100, 999);
                }
                
                $randomPassword = bin2hex(random_bytes(8));
                $hashedPassword = password_hash($randomPassword, PASSWORD_DEFAULT);
                
                $query = "INSERT INTO " . $this->userModel->getTable() . " (username, email, password, role, is_active, needs_welcome_message) VALUES (:username, :email, :password, 'user', 1, 0)";
                $stmt = $this->userModel->getConnection()->prepare($query);
                $stmt->bindParam(":username", $newUsername);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":password", $hashedPassword);
                $stmt->execute();
                
                $newId = $this->userModel->getConnection()->lastInsertId();
                
                $_SESSION['user_id'] = $newId;
                $_SESSION['username'] = $newUsername;
                $_SESSION['email'] = $email;
                $_SESSION['full_name'] = '';
                $_SESSION['phone'] = '';
                $_SESSION['age'] = null;
                $_SESSION['weight'] = null;
                $_SESSION['height'] = null;
                $_SESSION['dietary_preference'] = '';
                $_SESSION['role'] = 'user';
                
                echo json_encode(['success' => true, 'action' => 'register']);
            }
            exit();
        }
    }

    // === CONTACT MESSAGE METHOD ===
    
    public function sendContactMessage() {
        header('Content-Type: application/json');
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $message = $_POST['message'] ?? '';
            
            if(empty($name) || empty($email) || empty($message)) {
                echo json_encode(['success' => false, 'message' => 'All fields are required']);
                exit();
            }
            
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Invalid email address']);
                exit();
            }
            
            $query = "INSERT INTO contact_messages (name, email, message, status) VALUES (:name, :email, :message, 'unread')";
            $stmt = $this->userModel->getConnection()->prepare($query);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":message", $message);
            
            if($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to send message']);
            }
            exit();
        }
    }

    // === FACE ID METHODS ===
    
    public function saveFaceSignature() {
        header('Content-Type: application/json');
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $userId = $data['user_id'] ?? 0;
            $faceSignature = $data['face_signature'] ?? '';
            
            if($userId && $faceSignature) {
                $checkQuery = "SELECT id, role FROM " . $this->userModel->getTable() . " WHERE id = :id";
                $checkStmt = $this->userModel->getConnection()->prepare($checkQuery);
                $checkStmt->bindParam(":id", $userId);
                $checkStmt->execute();
                $user = $checkStmt->fetch();
                
                if(!$user) {
                    echo json_encode(['success' => false, 'message' => 'User not found']);
                    exit();
                }
                
                $deleteQuery = "DELETE FROM user_face_data WHERE user_id = :user_id";
                $deleteStmt = $this->userModel->getConnection()->prepare($deleteQuery);
                $deleteStmt->bindParam(":user_id", $userId);
                $deleteStmt->execute();
                
                $query = "INSERT INTO user_face_data (user_id, face_signature) VALUES (:user_id, :signature)";
                $stmt = $this->userModel->getConnection()->prepare($query);
                $stmt->bindParam(":user_id", $userId);
                $stmt->bindParam(":signature", $faceSignature);
                
                if($stmt->execute()) {
                    $updateQuery = "UPDATE " . $this->userModel->getTable() . " SET has_face_id = 1 WHERE id = :id";
                    $updateStmt = $this->userModel->getConnection()->prepare($updateQuery);
                    $updateStmt->bindParam(":id", $userId);
                    $updateStmt->execute();
                    
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Database error']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid data']);
            }
            exit();
        }
    }

    public function loginWithFace() {
        header('Content-Type: application/json');
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $faceSignature = $data['face_signature'] ?? '';
            
            if(empty($faceSignature)) {
                echo json_encode(['success' => false, 'message' => 'No face signature provided']);
                exit();
            }
            
            $tempUserId = $_SESSION['temp_user_id'] ?? null;
            
            if($tempUserId) {
                $query = "SELECT * FROM " . $this->userModel->getTable() . " WHERE id = :id AND is_active = 1 LIMIT 1";
                $stmt = $this->userModel->getConnection()->prepare($query);
                $stmt->bindParam(":id", $tempUserId);
                $stmt->execute();
                $user = $stmt->fetch();
                
                if($user) {
                    $deleteQuery = "DELETE FROM user_face_data WHERE user_id = :user_id";
                    $deleteStmt = $this->userModel->getConnection()->prepare($deleteQuery);
                    $deleteStmt->bindParam(":user_id", $tempUserId);
                    $deleteStmt->execute();
                    
                    $insertQuery = "INSERT INTO user_face_data (user_id, face_signature) VALUES (:user_id, :signature)";
                    $insertStmt = $this->userModel->getConnection()->prepare($insertQuery);
                    $insertStmt->bindParam(":user_id", $tempUserId);
                    $insertStmt->bindParam(":signature", $faceSignature);
                    $insertStmt->execute();
                    
                    $updateQuery = "UPDATE " . $this->userModel->getTable() . " SET has_face_id = 1 WHERE id = :id";
                    $updateStmt = $this->userModel->getConnection()->prepare($updateQuery);
                    $updateStmt->bindParam(":id", $tempUserId);
                    $updateStmt->execute();
                    
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['phone'] = $user['phone'];
                    $_SESSION['age'] = $user['age'];
                    $_SESSION['weight'] = $user['weight'];
                    $_SESSION['height'] = $user['height'];
                    $_SESSION['dietary_preference'] = $user['dietary_preference'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['face_id_enabled'] = true;
                    if (($user['role'] ?? '') === 'admin') {
                        $_SESSION['logged_in'] = true;
                    }
                    
                    unset($_SESSION['temp_user_id']);
                    
                    echo json_encode(['success' => true, 'user' => $user]);
                    exit();
                }
            }
            
            $query = "SELECT u.* FROM " . $this->userModel->getTable() . " u 
                      INNER JOIN user_face_data f ON u.id = f.user_id 
                      WHERE u.is_active = 1";
            $stmt = $this->userModel->getConnection()->prepare($query);
            $stmt->execute();
            $users = $stmt->fetchAll();
            
            $foundUser = null;
            foreach($users as $user) {
                if($user['role'] !== 'admin') {
                    $foundUser = $user;
                    break;
                }
            }
            
            if(!$foundUser && !empty($users)) {
                $foundUser = $users[0];
            }
            
            if($foundUser) {
                $_SESSION['user_id'] = $foundUser['id'];
                $_SESSION['username'] = $foundUser['username'];
                $_SESSION['email'] = $foundUser['email'];
                $_SESSION['full_name'] = $foundUser['full_name'];
                $_SESSION['phone'] = $foundUser['phone'];
                $_SESSION['age'] = $foundUser['age'];
                $_SESSION['weight'] = $foundUser['weight'];
                $_SESSION['height'] = $foundUser['height'];
                $_SESSION['dietary_preference'] = $foundUser['dietary_preference'];
                $_SESSION['role'] = $foundUser['role'];
                if (($foundUser['role'] ?? '') === 'admin') {
                    $_SESSION['logged_in'] = true;
                }
                
                echo json_encode(['success' => true, 'user' => $foundUser]);
                exit();
            }
            
            echo json_encode(['success' => false, 'message' => 'No face registered. Please register first.']);
            exit();
        }
    }

    // === PRIVATE METHODS ===
    
    private function isAdminEmail($email) {
        return strpos($email, '_admin@gmail.com') !== false;
    }

    private function emailExists($email) {
        $query = "SELECT id FROM " . $this->userModel->getTable() . " WHERE email = :email LIMIT 1";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    private function registerUser() {
        $role = $this->isAdminEmail($this->userModel->getEmail()) ? 'admin' : 'user';
        
        $query = "INSERT INTO " . $this->userModel->getTable() . " 
                  SET username=:username, email=:email, password=:password, 
                      full_name=:full_name, phone=:phone, age=:age, 
                      weight=:weight, height=:height, dietary_preference=:dietary_preference,
                      role=:role, is_active=1, needs_welcome_message=0, has_face_id=0";
        
        $stmt = $this->userModel->getConnection()->prepare($query);
        
        $hashed_password = password_hash($this->userModel->getPassword(), PASSWORD_DEFAULT);
        
        $stmt->bindParam(":username", $this->userModel->getUsername());
        $stmt->bindParam(":email", $this->userModel->getEmail());
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":full_name", $this->userModel->getFullName());
        $stmt->bindParam(":phone", $this->userModel->getPhone());
        $stmt->bindParam(":age", $this->userModel->getAge());
        $stmt->bindParam(":weight", $this->userModel->getWeight());
        $stmt->bindParam(":height", $this->userModel->getHeight());
        $stmt->bindParam(":dietary_preference", $this->userModel->getDietaryPreference());
        $stmt->bindParam(":role", $role);
        
        return $stmt->execute();
    }

    private function loginUser($email, $password, $remember = false) {
        $query = "SELECT * FROM " . $this->userModel->getTable() . " WHERE email = :email LIMIT 1";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            
            if(isset($row['is_active']) && $row['is_active'] == 0) {
                $_SESSION['account_disabled'] = "Your account has been disabled. Please contact the administrator at admin@nutriflowai.com";
                return false;
            }
            
            if(password_verify($password, $row['password'])) {
                $this->userModel->setId($row['id']);
                $this->userModel->setUsername($row['username']);
                $this->userModel->setEmail($row['email']);
                $this->userModel->setFullName($row['full_name']);
                $this->userModel->setPhone($row['phone']);
                $this->userModel->setAge($row['age']);
                $this->userModel->setWeight($row['weight']);
                $this->userModel->setHeight($row['height']);
                $this->userModel->setDietaryPreference($row['dietary_preference']);
                $this->userModel->setRole($row['role']);
                
                $_SESSION['user_id'] = $this->userModel->getId();
                $_SESSION['username'] = $this->userModel->getUsername();
                $_SESSION['email'] = $this->userModel->getEmail();
                $_SESSION['full_name'] = $this->userModel->getFullName();
                $_SESSION['phone'] = $this->userModel->getPhone();
                $_SESSION['age'] = $this->userModel->getAge();
                $_SESSION['weight'] = $this->userModel->getWeight();
                $_SESSION['height'] = $this->userModel->getHeight();
                $_SESSION['dietary_preference'] = $this->userModel->getDietaryPreference();
                $_SESSION['role'] = $this->userModel->getRole();
                if ($this->userModel->getRole() === 'admin') {
                    $_SESSION['logged_in'] = true;
                }
                
                if(isset($row['needs_welcome_message']) && $row['needs_welcome_message'] == 1) {
                    $_SESSION['account_reactivated'] = "🎉 Your account has been reactivated! Welcome back to NutriFlow AI! 🎉";
                    $updateQuery = "UPDATE " . $this->userModel->getTable() . " SET needs_welcome_message = 0 WHERE id = :id";
                    $updateStmt = $this->userModel->getConnection()->prepare($updateQuery);
                    $updateStmt->bindParam(":id", $row['id']);
                    $updateStmt->execute();
                }
                
                if($remember) {
                    $token = bin2hex(random_bytes(32));
                    $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
                    
                    $query = "UPDATE " . $this->userModel->getTable() . " SET remember_token = :token, token_expires = :expires WHERE id = :id";
                    $stmt = $this->userModel->getConnection()->prepare($query);
                    $stmt->bindParam(":token", $token);
                    $stmt->bindParam(":expires", $expires);
                    $stmt->bindParam(":id", $row['id']);
                    $stmt->execute();
                    
                    setcookie('remember_token', $token, time() + (86400 * 30), "/");
                }
                
                return true;
            }
        }
        return false;
    }

    private function updateUserProfile($id, $data) {
        $query = "UPDATE " . $this->userModel->getTable() . " 
                  SET username=:username, full_name=:full_name, phone=:phone, 
                      age=:age, weight=:weight, height=:height, 
                      dietary_preference=:dietary_preference 
                  WHERE id = :id";
        
        $stmt = $this->userModel->getConnection()->prepare($query);
        
        $stmt->bindParam(":username", $data['username']);
        $stmt->bindParam(":full_name", $data['full_name']);
        $stmt->bindParam(":phone", $data['phone']);
        $stmt->bindParam(":age", $data['age']);
        $stmt->bindParam(":weight", $data['weight']);
        $stmt->bindParam(":height", $data['height']);
        $stmt->bindParam(":dietary_preference", $data['dietary_preference']);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }

    private function getUserById($id) {
        $query = "SELECT * FROM " . $this->userModel->getTable() . " WHERE id = :id LIMIT 1";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    private function deleteUserAccount($id) {
        $deleteFaceQuery = "DELETE FROM user_face_data WHERE user_id = :id";
        $deleteFaceStmt = $this->userModel->getConnection()->prepare($deleteFaceQuery);
        $deleteFaceStmt->bindParam(":id", $id);
        $deleteFaceStmt->execute();
        
        $query = "DELETE FROM " . $this->userModel->getTable() . " WHERE id = :id";
        $stmt = $this->userModel->getConnection()->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
?>
