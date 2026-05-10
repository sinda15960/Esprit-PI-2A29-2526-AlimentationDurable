<?php
require_once __DIR__ . '/AllergieController.php';
require_once __DIR__ . '/TraitementController.php';
require_once __DIR__ . '/FeedbackController.php';

$allergieController = new AllergieController();
$traitementController = new TraitementController();
$feedbackController = new FeedbackController();

$allergies = $allergieController->getAllAllergies();
$traitements = $traitementController->getAllTraitements();
$feedbacks = $feedbackController->getApprovedFeedbacks();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>NutriFlow AI - Accueil</title>
    <link rel="stylesheet" href="assets/css/front.css">
</head>
<body>
    <div class="banner">
        <h1>EAT HEALTHY</h1>
        <p>plan your meals</p>
    </div>
    
    <div class="container">
        <div class="nav-buttons">
            <a href="allergies.php" class="btn">🔬 Voir les Allergies</a>
            <a href="traitements.php" class="btn">💊 Voir les Traitements</a>
        </div>
    </div>
    
    <footer class="footer">
        <p>© 2024 NutriFlow AI - Mangez sainement</p>
    </footer>
</body>
</html>