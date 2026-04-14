<?php
session_start();
 
// Si déjà connecté, rediriger selon le rôle
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: index.php?module=objectif&action=index&office=back");
    } else {
        header("Location: index.php?module=objectif&action=index&office=front");
    }
    exit;
}
 
$error = '';
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
 
    // Comptes utilisateurs (avec rôle)
    $users = [
        ['id' => 1, 'username' => 'alice',   'password' => 'alice123',   'nom' => 'Alice Martin',     'role' => 'user'],
        ['id' => 2, 'username' => 'bob',     'password' => 'bob123',     'nom' => 'Bob Dupont',       'role' => 'user'],
        ['id' => 3, 'username' => 'charlie', 'password' => 'charlie123', 'nom' => 'Charlie Durand',  'role' => 'user'],
        ['id' => 99, 'username' => 'admin',  'password' => 'admin123',   'nom' => 'Administrateur',  'role' => 'admin'],
    ];
 
    $found = false;
    foreach ($users as $u) {
        if ($u['username'] === $username && $u['password'] === $password) {
            $_SESSION['user_id']   = $u['id'];
            $_SESSION['user_nom']  = $u['nom'];
            $_SESSION['role']      = $u['role'];
            $found = true;
            
            // Rediriger selon le rôle
            if ($u['role'] === 'admin') {
                header("Location: index.php?module=objectif&action=index&office=back");
            } else {
                header("Location: index.php?module=objectif&action=index&office=front");
            }
            exit;
        }
    }
 
    if (!$found) {
        $error = "Identifiant ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EAT HEALTHY — Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a3a16 0%, #2d5a27 50%, #4a8f3f 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 48px 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-logo {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 8px;
        }
        .login-title {
            text-align: center;
            color: #2d5a27;
            font-weight: 800;
            font-size: 1.5rem;
            margin-bottom: 6px;
        }
        .login-subtitle {
            text-align: center;
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 32px;
        }
        .form-label { color: #444; font-weight: 600; font-size: 0.9rem; }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 12px 16px;
            font-size: 0.95rem;
        }
        .form-control:focus {
            border-color: #2d5a27;
            box-shadow: 0 0 0 3px rgba(45,90,39,0.15);
        }
        .btn-login {
            background: #2d5a27;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-size: 1rem;
            font-weight: 700;
            width: 100%;
            margin-top: 8px;
        }
        .btn-login:hover { background: #3d7a35; }
        .alert-danger { border-radius: 10px; font-size: 0.9rem; }
        .hint-box {
            background: #f0f7ee;
            border-left: 4px solid #2d5a27;
            border-radius: 8px;
            padding: 10px 14px;
            margin-top: 20px;
            font-size: 0.8rem;
            color: #555;
        }
        .hint-box strong { color: #2d5a27; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">🥗</div>
        <div class="login-title">EAT HEALTHY</div>
        <div class="login-subtitle">Connectez-vous pour accéder à votre espace</div>
 
        <?php if ($error): ?>
            <div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
 
        <form method="POST" action="login.php">
            <div class="mb-3">
                <label class="form-label">👤 Identifiant</label>
                <input type="text" name="username" class="form-control"
                       placeholder="Ex : alice ou admin"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
            </div>
            <div class="mb-4">
                <label class="form-label">🔒 Mot de passe</label>
                <input type="password" name="password" class="form-control"
                       placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-login">Se connecter →</button>
        </form>
 
        <div class="hint-box">
            <strong>Comptes de test :</strong><br>
            👤 alice / alice123 (utilisateur)<br>
            👤 bob / bob123 (utilisateur)<br>
            👤 charlie / charlie123 (utilisateur)<br>
            👑 <strong>admin / admin123 (administrateur)</strong>
        </div>
    </div>
</body>
</html>