<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Dashboard</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-bar">
        <h2>⚙️ Administration NutriFlow AI</h2>
        <a href="login.php?logout=1">🔓 Déconnexion</a>
    </div>
    
    <div class="container">
        <h1>Dashboard</h1>
        <div class="dashboard-cards">
            <div class="card">
                <h3>🔬 Allergies</h3>
                <a href="gestion_allergies.php">Gérer les allergies</a>
            </div>
            <div class="card">
                <h3>💊 Traitements</h3>
                <a href="gestion_traitements.php">Gérer les traitements</a>
            </div>
            <div class="card">
                <h3>💬 Feedbacks</h3>
                <a href="gestion_feedbacks.php">Modérer les feedbacks</a>
            </div>
        </div>
    </div>
</body>
</html>