<?php
require_once __DIR__ . '/Controller/TraitementController.php';

$traitementController = new TraitementController();
$rows = $traitementController->getAllTraitementsWithAllergies();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriFlow — Traitements</title>
    <link rel="stylesheet" href="assets/css/front.css">
</head>
<body class="nf-home">
    <header class="nf-topbar">
        <a class="nf-brand" href="index.php">Nutri<span>Flow</span></a>
        <nav class="nf-toplinks">
            <a href="index.php">Accueil</a>
            <a href="allergies.php">Allergies</a>
            <a href="traitements.php">Traitements</a>
        </nav>
    </header>

    <main class="nf-wrap">
        <section class="nf-hero" style="padding-top:1rem;">
            <h1>Traitements</h1>
            <p>Conseils et interdits par allergie.</p>
        </section>

        <div class="nf-card">
            <div class="nf-card-head">
                <h2>Liste des traitements</h2>
                <a class="nf-btn" href="allergies.php">Voir les allergies</a>
            </div>
            <div class="nf-table-wrap">
                <?php if (empty($rows)): ?>
                    <p class="nf-empty">Aucun traitement en base.</p>
                <?php else: ?>
                    <table class="nf-table">
                        <thead>
                            <tr>
                                <th>Allergie</th>
                                <th>Conseil</th>
                                <th>Niveau urgence</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $t): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($t['allergie_nom'] ?? ''); ?></strong></td>
                                <td><?php
                                    $c = (string)($t['conseil'] ?? '');
                                    if (strlen($c) > 140) {
                                        $c = substr($c, 0, 137) . '...';
                                    }
                                    echo htmlspecialchars($c);
                                ?></td>
                                <td><span class="nf-badge nf-badge-active"><?php echo htmlspecialchars($t['niveau_urgence'] ?? ''); ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <footer class="nf-footer">
            <a href="index.php">← Retour accueil</a>
        </footer>
    </main>
</body>
</html>
