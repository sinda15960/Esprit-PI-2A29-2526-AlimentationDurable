<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'NutriFlow AI - Healthy Eating Made Smart'; ?></title>
    <link rel="stylesheet" href="assets/css/front-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <span class="logo-icon">🥗</span>
                <span class="logo-text">NutriFlow AI</span>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php?action=home">Accueil</a></li>
                <li><a href="#">Fonctionnalités</a></li>
                <li><a href="#">À propos</a></li>
                <li><a href="#">Contact</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                        <li><a href="index.php?action=admin_dashboard" class="nav-btn admin">Dashboard</a></li>
                    <?php endif; ?>
                    <li><a href="index.php?action=profile" class="nav-btn">Profile</a></li>
                    <li><a href="index.php?action=logout" class="nav-btn logout">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="index.php?action=login" class="nav-btn">Connexion</a></li>
                    <li><a href="index.php?action=register" class="nav-btn signup">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <main>
        <?php echo $content; ?>
    </main>

    <footer>
        <div class="footer-content">
            <p>www.nutriflowai.com | +123456789</p>
            <p>&copy; 2024 NutriFlow AI. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="assets/js/validation.js"></script>
</body>
</html>