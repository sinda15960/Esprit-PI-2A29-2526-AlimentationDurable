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
            
            // Validation JavaScript-style (pas HTML5)
            if(empty($_POST['username']) || strlen($_POST['username']) < 3) {
                $errors['username'] = "Username must be at least 3 characters";
            }
            if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Valid email is required";
            }
            if($this->userModel->emailExists($_POST['email'])) {
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
                $this->userModel->username = $_POST['username'];
                $this->userModel->email = $_POST['email'];
                $this->userModel->password = $_POST['password'];
                $this->userModel->full_name = $_POST['full_name'] ?? '';
                $this->userModel->phone = $_POST['phone'] ?? '';
                $this->userModel->age = !empty($_POST['age']) ? $_POST['age'] : null;
                $this->userModel->weight = !empty($_POST['weight']) ? $_POST['weight'] : null;
                $this->userModel->height = !empty($_POST['height']) ? $_POST['height'] : null;
                $this->userModel->dietary_preference = $_POST['dietary_preference'] ?? '';

                if($this->userModel->register()) {
                    // Message personnalisé selon le type de compte
                    if($this->userModel->isAdminEmail($_POST['email'])) {
                        $_SESSION['success'] = "Admin account created successfully! Please login.";
                    } else {
                        $_SESSION['success'] = "Registration successful! Please login.";
                    }
                    header("Location: index.php?action=login");
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
                $this->userModel->email = $_POST['email'];
                $this->userModel->password = $_POST['password'];

                if($this->userModel->login()) {
                    $_SESSION['user_id'] = $this->userModel->id;
                    $_SESSION['username'] = $this->userModel->username;
                    $_SESSION['email'] = $this->userModel->email;
                    $_SESSION['full_name'] = $this->userModel->full_name;
                    $_SESSION['phone'] = $this->userModel->phone;
                    $_SESSION['age'] = $this->userModel->age;
                    $_SESSION['weight'] = $this->userModel->weight;
                    $_SESSION['height'] = $this->userModel->height;
                    $_SESSION['dietary_preference'] = $this->userModel->dietary_preference;
                    $_SESSION['role'] = $this->userModel->role;
                    
                    // Redirection basée sur le rôle
                    if($this->userModel->role == 'admin') {
                        header("Location: index.php?action=admin_dashboard");
                    } else {
                        header("Location: index.php?action=profile");
                    }
                    exit();
                } else {
                    $errors['login'] = "Invalid email or password";
                }
            }
            $_SESSION['errors'] = $errors;
            header("Location: index.php?action=login");
            exit();
        }
    }

    public function showProfile() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        // Si c'est un admin, rediriger vers le dashboard
        if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
            header("Location: index.php?action=admin_dashboard");
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
                $this->userModel->id = $_SESSION['user_id'];
                $this->userModel->username = $_POST['username'];
                $this->userModel->full_name = $_POST['full_name'] ?? '';
                $this->userModel->phone = $_POST['phone'] ?? '';
                $this->userModel->age = !empty($_POST['age']) ? $_POST['age'] : null;
                $this->userModel->weight = !empty($_POST['weight']) ? $_POST['weight'] : null;
                $this->userModel->height = !empty($_POST['height']) ? $_POST['height'] : null;
                $this->userModel->dietary_preference = $_POST['dietary_preference'] ?? '';

                if($this->userModel->updateProfile()) {
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
        
        // Vérifier si l'utilisateur existe
        $user = $this->userModel->getUserById($user_id);
        if(!$user) {
            header("Location: index.php?action=home");
            exit();
        }
        
        if($this->userModel->deleteAccount($user_id)) {
            // Détruire la session
            session_destroy();
            
            // Démarrer une nouvelle session pour le message
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
}
?>