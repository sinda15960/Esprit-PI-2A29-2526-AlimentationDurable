<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}
require_once __DIR__ . '/../../Controller/AllergieController.php';
$controller = new AllergieController();
$allergies = $controller->getAllAllergies();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gestion des Allergies</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-bar">
        <h2>Gestion des Allergies</h2>
        <a href="dashboard.php">← Retour</a>
    </div>
    
    <div class="container">
        <button class="btn-add" onclick="window.location.href='addAllergie.php'">+ Ajouter une allergie</button>
        
        <table class="admin-table">
            <thead>
                <tr><th>ID</th><th>Nom</th><th>Catégorie</th><th>Gravité</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($allergies as $a): ?>
                <tr>
                    <td><?= $a->getId() ?></td>
                    <td><?= htmlspecialchars($a->getNom()) ?></td>
                    <td><?= htmlspecialchars($a->getCategorie()) ?></td>
                    <td><?= $a->getGravite() ?></td>
                    <td>
                        <a href="editAllergie.php?id=<?= $a->getId() ?>" class="btn-edit">✏️</a>
                        <a href="deleteAllergie.php?id=<?= $a->getId() ?>" class="btn-delete" onclick="return confirm('Supprimer ?')">🗑️</a>
                        <a href="showAllergie.php?id=<?= $a->getId() ?>" class="btn-view">👁️</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>