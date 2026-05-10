<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/../../model/Allergie.php';

$id = $_GET['id'] ?? 0;
$allergie = Allergie::findById($id);

if (!$allergie) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $allergie->setNom($_POST['nom']);
    $allergie->setCategorie($_POST['categorie']);
    $allergie->setDescription($_POST['description']);
    $allergie->setSymptomes($_POST['symptomes']);
    $allergie->setDeclencheurs($_POST['declencheurs']);
    $allergie->setGravite($_POST['gravite']);
    
    if ($allergie->save()) {
        $success = "Allergie modifiée avec succès !";
        header('refresh:2;url=dashboard.php');
    } else {
        $error = "Erreur lors de la modification";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une allergie</title>
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
        button { background: #2196F3; color: white; padding: 0.8rem 1.5rem; border: none; border-radius: 5px; cursor: pointer; margin-top: 1rem; }
        button:hover { background: #0b7dda; }
        .error { color: #f44336; margin-bottom: 1rem; }
        .success { color: #4caf50; margin-bottom: 1rem; }
        .back { display: inline-block; margin-top: 1rem; color: #2d5016; text-decoration: none; }
    </style>
</head>
<body>
    <div class="header"><h1>NutriFlow AI - Administration</h1></div>
    <div class="container">
        <h2>✏️ Modifier l'allergie</h2>
        <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?= $success ?></div><?php endif; ?>
        <form method="POST">
            <div class="form-group"><label>Nom *</label><input type="text" name="nom" value="<?= htmlspecialchars($allergie->getNom()) ?>" required></div>
            <div class="form-group"><label>Catégorie *</label><select name="categorie" required><option value="">Sélectionner</option><option <?= $allergie->getCategorie() == 'Alimentaire' ? 'selected' : '' ?>>Alimentaire</option><option <?= $allergie->getCategorie() == 'Respiratoire' ? 'selected' : '' ?>>Respiratoire</option><option <?= $allergie->getCategorie() == 'Cutane' ? 'selected' : '' ?>>Cutane</option><option <?= $allergie->getCategorie() == 'Médicamenteuse' ? 'selected' : '' ?>>Médicamenteuse</option></select></div>
            <div class="form-group"><label>Description *</label><textarea name="description" required><?= htmlspecialchars($allergie->getDescription()) ?></textarea></div>
            <div class="form-group"><label>Symptômes *</label><textarea name="symptomes" required><?= htmlspecialchars($allergie->getSymptomes()) ?></textarea></div>
            <div class="form-group"><label>Déclencheurs *</label><input type="text" name="declencheurs" value="<?= htmlspecialchars($allergie->getDeclencheurs()) ?>" required></div>
            <div class="form-group"><label>Gravité *</label><select name="gravite" required><option value="">Sélectionner</option><option value="legere" <?= $allergie->getGravite() == 'legere' ? 'selected' : '' ?>>Légère</option><option value="moderate" <?= $allergie->getGravite() == 'moderate' ? 'selected' : '' ?>>Modérée</option><option value="severe" <?= $allergie->getGravite() == 'severe' ? 'selected' : '' ?>>Sévère</option></select></div>
            <button type="submit">💾 Enregistrer les modifications</button>
        </form>
        <a href="dashboard.php" class="back">← Retour</a>
    </div>
</body>
</html>