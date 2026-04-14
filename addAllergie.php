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
    <title>Ajouter une allergie</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="container">
        <h1>Ajouter une allergie</h1>
        <form action="../../Controller/Verification.php" method="POST" class="admin-form">
            <div class="form-group">
                <label>Nom de l'allergie *</label>
                <input type="text" name="nom" required>
            </div>
            <div class="form-group">
                <label>Catégorie *</label>
                <select name="categorie" required>
                    <option value="Alimentaire">Alimentaire</option>
                    <option value="Respiratoire">Respiratoire</option>
                    <option value="Médicamenteuse">Médicamenteuse</option>
                </select>
            </div>
            <div class="form-group