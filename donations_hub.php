<?php
/**
 * Front office — hub dons & associations (landing « première photo »).
 */
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriFlow AI — Dons & associations</title>
    <link rel="stylesheet" href="assets/css/donations-hub.css">
</head>
<body class="don-hub">
    <header class="don-hub-topbar">
        <a class="don-hub-brand" href="donations_hub.php">
            <span class="don-hub-leaf" aria-hidden="true">🌿</span>
            NutriFlow AI
        </a>
        <nav class="don-hub-nav">
            <a href="donations_hub.php">Home</a>
            <a href="organizations_public.php">Organizations</a>
            <a href="donate_public.php">Donate</a>
            <a href="login.php">Admin</a>
        </nav>
    </header>

    <main class="don-hub-main">
        <?php if (!empty($_GET['thanks'])): ?>
            <p class="don-hub-flash don-hub-flash-ok">Thank you — your donation has been recorded.</p>
        <?php endif; ?>
        <?php if (!empty($_SESSION['donation_success'])): ?>
            <p class="don-hub-flash don-hub-flash-ok"><?php echo htmlspecialchars($_SESSION['donation_success']); unset($_SESSION['donation_success']); ?></p>
        <?php endif; ?>

        <div class="don-hub-hero">
            <div class="don-hub-hero-icon" aria-hidden="true">🌿</div>
            <h1>NutriFlow AI</h1>
            <p class="don-hub-tagline">Together for sustainable food</p>
            <div class="don-hub-actions">
                <a class="don-hub-btn" href="organizations_public.php"><span>🤝</span> View Organizations</a>
                <a class="don-hub-btn" href="donate_public.php"><span>💵</span> Donate</a>
                <a class="don-hub-btn don-hub-btn-outline" href="login.php"><span>👑</span> Administration</a>
            </div>
        </div>
    </main>
</body>
</html>
