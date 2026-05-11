<?php
session_start();

if (isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header('Location: donations_admin.php');
        exit();
    } else {
        $error = "Identifiants incorrects";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BackOffice NutriFlow</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2b 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            width: 350px;
        }
        h2 { color: #2d5016; text-align: center; margin-bottom: 1.5rem; }
        input {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        button {
            width: 100%;
            padding: 0.8rem;
            background: #2d5016;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }
        button:hover { background: #4a7c2b; }
        .error { color: #f44336; text-align: center; margin-bottom: 1rem; }
        .info { text-align: center; margin-top: 1rem; font-size: 0.8rem; color: #999; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>🔐 Administration NutriFlow</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit" name="login">Se connecter</button>
        </form>
        <div class="info">Demo: admin / admin123</div>
    </div>
</body>
</html>