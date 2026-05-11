<?php
/* Préfixe assets : évite les CSS 404 si l’URL ou le dossier virtuel change */
$nfScriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
$nfScriptDir = str_replace('\\', '/', (string) $nfScriptDir);
$nfScriptDir = rtrim($nfScriptDir, '/');
$nfAssetPrefix = ($nfScriptDir === '' || $nfScriptDir === '.' || $nfScriptDir === '/') ? '' : $nfScriptDir . '/';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'NutriFlow AI - Healthy Eating Made Smart'; ?></title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars($nfAssetPrefix); ?>assets/css/front-style.css">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($nfAssetPrefix); ?>assets/css/dark-mode.css">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($nfAssetPrefix); ?>assets/css/auth.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar" aria-label="Main navigation">
        <div class="nav-container">
            <a href="index.php?action=home" class="logo">
                <span class="logo-icon" aria-hidden="true">🥗</span>
                <span class="logo-text">NutriFlow AI</span>
            </a>
            <ul class="nav-links">
                <li><a href="index.php?action=home">Home</a></li>
                <li><a href="index.php?action=home#features-section">Features</a></li>
                <li><a href="index.php?action=home#footer">About</a></li>
                <li><a href="index.php?action=home#footer">Contact</a></li>
            </ul>
            <div class="nav-actions">
                <ul class="nav-auth">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                            <li><a href="index.php?action=admin_dashboard" class="nav-btn-login">Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="index.php?action=profile" class="nav-link-muted">Profile</a></li>
                        <li><a href="index.php?action=logout" class="nav-link-muted">Logout</a></li>
                    <?php else: ?>
                        <li><a href="index.php?action=login" class="nav-btn-login">Login</a></li>
                    <?php endif; ?>
                </ul>
                <!-- Dark mode toggle inserted here by dark-mode.js -->
            </div>
        </div>
    </nav>

    <main>
        <?php echo $content; ?>
    </main>

    <footer id="footer" class="site-footer" aria-labelledby="footer-contact-heading">
        <div class="footer-inner">
            <h2 id="footer-contact-heading" class="footer-contact-heading">Contact</h2>
            <p class="footer-contact-line">
                <a class="footer-link" href="mailto:support@nutriflowai.com">support@nutriflowai.com</a>
                <span class="footer-sep" aria-hidden="true">·</span>
                <a class="footer-link" href="tel:+123456789">+123456789</a>
                <span class="footer-sep" aria-hidden="true">·</span>
                <a class="footer-link" href="https://www.nutriflowai.com" rel="noopener noreferrer">www.nutriflowai.com</a>
            </p>
            <p class="footer-copy">&copy; <?php echo date('Y'); ?> NutriFlow AI. All rights reserved.</p>
        </div>
    </footer>

    <script src="<?php echo htmlspecialchars($nfAssetPrefix); ?>assets/js/validation.js"></script>
    <script src="<?php echo htmlspecialchars($nfAssetPrefix); ?>assets/js/dark-mode.js"></script>
    <script src="<?php echo htmlspecialchars($nfAssetPrefix); ?>assets/js/confetti.js"></script>
</body>
</html>
