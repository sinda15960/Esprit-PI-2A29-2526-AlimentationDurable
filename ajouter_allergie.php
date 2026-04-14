<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/../../Model/Allergie.php';
require_once __DIR__ . '/../../Model/Traitement.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $allergie = new Allergie(
        $_POST['nom'],
        $_POST['categorie'],
        $_POST['description'],
        $_POST['symptomes'],
        $_POST['declencheurs'],
        $_POST['gravite']
    );
    
    if ($allergie->save()) {
        $traitement = new Traitement(
            $allergie->getId(),
            $_POST['conseil'],
            $_POST['interdits'],
            $_POST['medicaments'] ?? null,
            $_POST['duree'] ?? null,
            $_POST['niveau_urgence']
        );
        $traitement->save();
        $success = "Allergie ajoutée avec succès !";
        header('refresh:2;url=dashboard.php');
    } else {
        $error = "Erreur lors de l'ajout";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une allergie</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; }
        .header { background: linear-gradient(135deg, #2d5016 0%, #4a7c2b 100%); color: white; padding: 1rem 2rem; }
        .container { max-width: 800px; margin: 2rem auto; background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #2d5016; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; font-weight: bold; margin-bottom: 0.5rem; }
        input, select, textarea { width: 100%; padding: 0.6rem; border: 1px solid #ddd; border-radius: 5px; font-family: inherit; }
        textarea { resize: vertical; min-height: 80px; }
        button { background: #2d5016; color: white; padding: 0.8rem 1.5rem; border: none; border-radius: 5px; cursor: pointer; margin-top: 1rem; }
        button:hover { background: #4a7c2b; }
        .error { color: #f44336; margin-bottom: 1rem; }
        .success { color: #4caf50; margin-bottom: 1rem; }
        .back { display: inline-block; margin-top: 1rem; color: #2d5016; text-decoration: none; }
        hr { margin: 1.5rem 0; }
    </style>
</head>
<body>
    <div class="header"><h1>NutriFlow AI - Administration</h1></div>
    <div class="container">
        <h2>➕ Ajouter une allergie</h2>
        <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?= $success ?></div><?php endif; ?>
        <form method="POST">
            <div class="form-group"><label>Nom *</label><input type="text" name="nom" required></div>
            <div class="form-group"><label>Catégorie *</label><select name="categorie" required><option value="">Sélectionner</option><option>Alimentaire</option><option>Respiratoire</option><option>Cutane</option><option>Médicamenteuse</option></select></div>
            <div class="form-group"><label>Description *</label><textarea name="description" required></textarea></div>
            <div class="form-group"><label>Symptômes *</label><textarea name="symptomes" required></textarea></div>
            <div class="form-group"><label>Déclencheurs *</label><input type="text" name="declencheurs" required></div>
            <div class="form-group"><label>Gravité *</label><select name="gravite" required><option value="">Sélectionner</option><option value="legere">Légère</option><option value="moderate">Modérée</option><option value="severe">Sévère</option></select></div>
            <hr>
            <h3>💊 Traitement associé</h3>
            <div class="form-group"><label>Conseils *</label><textarea name="conseil" required></textarea></div>
            <div class="form-group"><label>Interdits *</label><textarea name="interdits" required></textarea></div>
            <div class="form-group"><label>Médicaments</label><input type="text" name="medicaments"></div>
            <div class="form-group"><label>Durée</label><input type="text" name="duree"></div>
            <div class="form-group"><label>Niveau d'urgence *</label><select name="niveau_urgence" required><option value="">Sélectionner</option><option value="faible">Faible</option><option value="moyen">Moyen</option><option value="eleve">Élevé</option></select></div>
            <button type="submit">💾 Enregistrer</button>
        </form>
        <a href="dashboard.php" class="back">← Retour</a>
    </div>
</body>
</html>