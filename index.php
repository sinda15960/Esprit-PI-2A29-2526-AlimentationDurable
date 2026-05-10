<?php
$associations = $associations ?? [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriFlow AI — Accueil</title>
    <link rel="stylesheet" href="assets/css/front.css">
</head>
<body class="nf-home">
    <header class="nf-topbar">
        <a class="nf-brand" href="index.php">Nutri<span>Flow</span></a>
        <nav class="nf-toplinks">
            <a href="allergies.php">Allergies</a>
            <a href="traitements.php">Traitements</a>
            <a href="gestion_plan/login.php">Plan repas</a>
            <a href="projet2A/public/index.php">Recettes</a>
            <a href="frigo/index.php">Frigo</a>
        </nav>
    </header>

    <main class="nf-wrap">
        <section class="nf-hero">
            <h1>Eat healthy</h1>
            <p>Planifiez vos repas, gérez les allergies et le frigo — au même endroit.</p>
        </section>

        <section class="nf-grid" aria-label="Modules du projet">
            <a class="nf-tile" href="allergies.php">
                <div class="nf-tile-icon" aria-hidden="true">🔬</div>
                <h2>Allergies</h2>
                <p>Liste, recherche et retours utilisateurs.</p>
            </a>
            <a class="nf-tile" href="traitements.php">
                <div class="nf-tile-icon" aria-hidden="true">💊</div>
                <h2>Traitements</h2>
                <p>Conseils liés à chaque allergie.</p>
            </a>
            <a class="nf-tile" href="gestion_plan/login.php">
                <div class="nf-tile-icon" aria-hidden="true">📋</div>
                <h2>Plan alimentaire</h2>
                <p>Objectifs, programmes et exercices.</p>
            </a>
            <a class="nf-tile" href="projet2A/public/index.php">
                <div class="nf-tile-icon" aria-hidden="true">🍽️</div>
                <h2>Recettes</h2>
                <p>Front / back office recettes NutriFlow.</p>
            </a>
            <a class="nf-tile" href="frigo/index.php">
                <div class="nf-tile-icon" aria-hidden="true">🧊</div>
                <h2>Frigo</h2>
                <p>Produits, panier et commandes.</p>
            </a>
        </section>

        <section class="nf-section" aria-labelledby="orgs-title">
            <p id="orgs-title" class="nf-section-title">Associations (module intégration)</p>
            <div class="nf-card">
                <div class="nf-card-head">
                    <h2>Organizations</h2>
                    <a class="nf-btn" href="projet2A/public/index.php">Ouvrir recettes</a>
                </div>
                <div class="nf-table-wrap">
                    <?php if (empty($associations)): ?>
                        <p class="nf-empty">Aucune association en base pour cette vue. Les données démo sont dans <code>nutriflow_db</code> (tables <code>associations</code> / <code>dons</code>) — branchez un contrôleur ici si besoin.</p>
                    <?php else: ?>
                        <table class="nf-table">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Ville</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($associations as $assoc): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($assoc['name'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($assoc['email'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($assoc['city'] ?? ''); ?></td>
                                    <td>
                                        <?php $active = ($assoc['status'] ?? '') === 'active'; ?>
                                        <span class="nf-badge <?php echo $active ? 'nf-badge-active' : 'nf-badge-inactive'; ?>">
                                            <?php echo $active ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <footer class="nf-footer">
            © <?php echo date('Y'); ?> NutriFlow AI — Esprit PI 2A29 · Alimentation durable
        </footer>
    </main>
</body>
</html>
