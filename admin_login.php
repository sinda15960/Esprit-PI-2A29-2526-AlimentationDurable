<?php
session_start();
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($_POST['username'] === 'admin' && $_POST['password'] === 'admin123') {
        $_SESSION['user'] = 'admin';
        header('Location: /nutriflow-ai/public/admin/associations');
        exit();
    } else {
        $error = "Identifiants incorrects";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-6 py-20">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold mb-6">Administration NutriFlow AI</h2>
            <?php if(isset($error)): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Utilisateur" class="w-full px-4 py-2 border rounded mb-4" required>
                <input type="password" name="password" placeholder="Mot de passe" class="w-full px-4 py-2 border rounded mb-4" required>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Se connecter</button>
            </form>
            <div class="mt-4 text-sm text-gray-600">
                <p>Identifiants par défaut : admin / admin123</p>
            </div>
        </div>
    </div>
</body>
</html>