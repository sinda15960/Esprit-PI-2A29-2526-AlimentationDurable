<?php
session_start();
require_once __DIR__ . '/../../Config/Database.php';
require_once __DIR__ . '/../../Controller/RatingController.php';
<?php require_once __DIR__ . '/urgence_button.php'; ?>

// Initialisation session pour l'urgence
if (!isset($_SESSION['urgence_session_id'])) {
    $_SESSION['urgence_session_id'] = session_id() . '_urg_' . time();
}

$allergie_id = $_GET['id'] ?? 0;
$allergie_nom = $_GET['nom'] ?? '';

$db = Database::getInstance()->getConnection();

// Incrémenter le compteur de vues
$stmt = $db->prepare("UPDATE allergies SET vue_count = vue_count + 1 WHERE id = ?");
$stmt->execute([$allergie_id]);

// Récupérer les détails de l'allergie
$stmtAllergie = $db->prepare("SELECT * FROM allergies WHERE id = ?");
$stmtAllergie->execute([$allergie_id]);
$allergie = $stmtAllergie->fetch(PDO::FETCH_ASSOC);

// Récupérer le traitement associé
$stmtTraitement = $db->prepare("SELECT * FROM traitements WHERE allergie_id = ?");
$stmtTraitement->execute([$allergie_id]);
$traitement = $stmtTraitement->fetch(PDO::FETCH_ASSOC);

$ratingController = new RatingController();
$rating = $ratingController->getAverageRating($allergie_id);
$hasRated = $ratingController->hasRated($allergie_id, $_SERVER['REMOTE_ADDR']);

if (!$allergie) {
    header('Location: front_allergie_traitement.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriFlow AI - Traitement : <?= htmlspecialchars($allergie['nom']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            color: #333;
            min-height: 100vh;
        }
        body.dark-mode {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #eee;
        }
        body.dark-mode .main-card, body.dark-mode .info-card, body.dark-mode .traitement-card,
        body.dark-mode .gallery-section, body.dark-mode .rating-section {
            background: #2d2d44;
            color: #eee;
        }
        body.dark-mode .info-card p, body.dark-mode .traitement-card p { color: #ccc; }
        .dark-mode-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: #2d5016;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 15px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .dark-mode-btn:hover { transform: scale(1.05); }
        .banner {
            background: linear-gradient(135deg, #1a3c0e 0%, #3a6b1e 100%);
            padding: 2rem 2rem;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .banner::before { content: "🌱"; position: absolute; font-size: 180px; opacity: 0.08; bottom: -40px; right: -40px; }
        .banner h1 { font-size: 3rem; letter-spacing: 5px; font-weight: 700; text-shadow: 2px 2px 4px rgba(0,0,0,0.2); }
        .banner p { font-size: 1.1rem; opacity: 0.95; font-weight: 300; }
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            color: #2d5016;
            padding: 0.6rem 1.2rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .back-button:hover { transform: translateX(-5px); box-shadow: 0 4px 12px rgba(0,0,0,0.12); }
        body.dark-mode .back-button { background: #2d2d44; color: #4a7c2b; }
        .main-card { background: white; border-radius: 28px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.1); margin-bottom: 2rem; }
        .allergie-header {
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2b 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }
        .allergie-header h2 { font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem; }
        .allergie-header .category { font-size: 1rem; opacity: 0.9; font-weight: 400; }
        .severity-badge { display: inline-block; padding: 0.4rem 1.2rem; border-radius: 50px; font-size: 0.8rem; font-weight: 600; margin-top: 1rem; }
        .severity-legere { background: #4caf50; color: white; }
        .severity-moderate { background: #ff9800; color: white; }
        .severity-severe { background: #f44336; color: white; }
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; padding: 2rem; background: #f8f9fa; }
        body.dark-mode .info-grid { background: #1a1a2e; }
        .info-card { background: white; padding: 1.5rem; border-radius: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); transition: transform 0.3s ease; }
        .info-card:hover { transform: translateY(-5px); }
        .info-card .icon { font-size: 2rem; margin-bottom: 1rem; }
        .info-card h3 { color: #2d5016; font-size: 1.2rem; margin-bottom: 0.8rem; font-weight: 600; }
        .info-card p { color: #555; line-height: 1.6; font-size: 0.95rem; }
        .gallery-section {
            background: #f8f9fa;
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .gallery-section h3 { color: #2d5016; margin-bottom: 1rem; }
        .gallery-img { text-align: center; }
        .gallery-img img { max-width: 100%; max-height: 300px; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .traitement-section { padding: 2rem; background: white; }
        body.dark-mode .traitement-section { background: #2d2d44; }
        .traitement-title { text-align: center; margin-bottom: 2rem; }
        .traitement-title h3 { font-size: 1.8rem; color: #2d5016; font-weight: 700; display: inline-flex; align-items: center; gap: 10px; }
        body.dark-mode .traitement-title h3 { color: #4a7c2b; }
        .traitement-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; }
        .traitement-card {
            background: linear-gradient(135deg, #f0f4f8 0%, #e8edf2 100%);
            padding: 1.5rem;
            border-radius: 20px;
            transition: all 0.3s ease;
            border-left: 4px solid #4a7c2b;
        }
        body.dark-mode .traitement-card { background: #1a1a2e; }
        .traitement-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .traitement-card .icon { font-size: 1.8rem; margin-bottom: 0.8rem; }
        .traitement-card h4 { color: #2d5016; font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem; }
        body.dark-mode .traitement-card h4 { color: #4a7c2b; }
        .traitement-card p { color: #555; font-size: 0.9rem; line-height: 1.5; }
        .urgence-badge { display: inline-block; padding: 0.2rem 0.8rem; border-radius: 20px; font-size: 0.7rem; font-weight: 600; margin-top: 0.5rem; }
        .urgence-faible { background: #4caf50; color: white; }
        .urgence-moyen { background: #ff9800; color: white; }
        .urgence-eleve { background: #f44336; color: white; }
        .no-treatment { text-align: center; padding: 3rem; background: #f8f9fa; border-radius: 20px; }
        .no-treatment .icon { font-size: 3rem; margin-bottom: 1rem; }
        .no-treatment p { font-size: 1.1rem; color: #666; }
        .rating-section {
            background: #f8f9fa;
            border-radius: 20px;
            padding: 1.5rem;
            margin-top: 1rem;
            text-align: center;
        }
        .rating-section h3 { color: #2d5016; margin-bottom: 1rem; }
        .stars { font-size: 2rem; cursor: pointer; margin-bottom: 0.5rem; }
        .stars i { color: #ffc107; transition: all 0.2s; margin: 0 2px; }
        .footer { background: linear-gradient(135deg, #1a3c0e 0%, #2d5016 100%); color: white; text-align: center; padding: 1.5rem; margin-top: 2rem; font-weight: 300; }
        @media (max-width: 768px) { .container { padding: 1rem; } .allergie-header h2 { font-size: 1.8rem; } .info-grid { grid-template-columns: 1fr; } .traitement-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <button class="dark-mode-btn" id="darkModeToggle">🌙 Mode sombre</button>

    <div class="banner">
        <h1>EAT HEALTHY</h1>
        <p>plan your meals - Traitement personnalisé</p>
    </div>

    <div class="container">
        <a href="front_allergie_traitement.php" class="back-button">← Retour aux allergies</a>

        <div class="main-card">
            <div class="allergie-header">
                <h2>🔬 <?= htmlspecialchars($allergie['nom']) ?></h2>
                <div class="category">📂 Catégorie : <?= htmlspecialchars($allergie['categorie']) ?></div>
                <span class="severity-badge severity-<?= $allergie['gravite'] ?>">⚡ Gravité : <?= ucfirst($allergie['gravite']) ?></span>
            </div>

            <div class="info-grid">
                <div class="info-card"><div class="icon">📝</div><h3>Description</h3><p><?= nl2br(htmlspecialchars($allergie['description'])) ?></p></div>
                <div class="info-card"><div class="icon">⚠️</div><h3>Symptômes</h3><p><?= nl2br(htmlspecialchars($allergie['symptomes'])) ?></p></div>
                <div class="info-card"><div class="icon">🚫</div><h3>Déclencheurs</h3><p><?= nl2br(htmlspecialchars($allergie['declencheurs'])) ?></p></div>
            </div>

            <!-- Galerie d'images -->
            <div class="gallery-section">
                <h3>🖼️ Galerie d'images</h3>
                <?php
                $imagePath = $allergie['image_url'] ?? null;
                if ($imagePath && file_exists(__DIR__ . '/../../' . $imagePath)):
                ?>
                <div class="gallery-img">
                    <img src="../../<?= $imagePath ?>" alt="<?= htmlspecialchars($allergie['nom']) ?>">
                </div>
                <?php else: ?>
                <div style="text-align: center; padding: 2rem; background: white; border-radius: 15px;">
                    <i class="fa-solid fa-image" style="font-size: 3rem; color: #ccc; margin-bottom: 0.5rem; display: block;"></i>
                    <p>Aucune image disponible pour cette allergie.</p>
                </div>
                <?php endif; ?>
            </div>

            <div class="traitement-section">
                <div class="traitement-title"><h3>💊 Traitement recommandé</h3></div>
                <?php if ($traitement): ?>
                    <div class="traitement-grid">
                        <div class="traitement-card"><div class="icon">💡</div><h4>Conseils</h4><p><?= nl2br(htmlspecialchars($traitement['conseil'])) ?></p></div>
                        <div class="traitement-card"><div class="icon">🚫</div><h4>Interdits</h4><p><?= nl2br(htmlspecialchars($traitement['interdits'])) ?></p></div>
                        <?php if ($traitement['medicaments']): ?>
                        <div class="traitement-card"><div class="icon">💊</div><h4>Médicaments</h4><p><?= nl2br(htmlspecialchars($traitement['medicaments'])) ?></p></div>
                        <?php endif; ?>
                        <?php if ($traitement['duree']): ?>
                        <div class="traitement-card"><div class="icon">⏰</div><h4>Durée du traitement</h4><p><?= nl2br(htmlspecialchars($traitement['duree'])) ?></p></div>
                        <?php endif; ?>
                        <div class="traitement-card"><div class="icon">🚨</div><h4>Niveau d'urgence</h4><p><span class="urgence-badge urgence-<?= $traitement['niveau_urgence'] ?>"><?= ucfirst($traitement['niveau_urgence']) ?></span></p></div>
                    </div>
                <?php else: ?>
                    <div class="no-treatment"><div class="icon">💊</div><p>Aucun traitement n'a encore été associé à cette allergie.</p><p style="font-size: 0.9rem; margin-top: 0.5rem;">Consultez votre médecin pour plus d'informations.</p></div>
                <?php endif; ?>
            </div>

            <!-- Section Évaluation -->
            <div class="rating-section">
                <h3>⭐ Évaluation de ce traitement</h3>
                <div style="display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <div class="stars" id="stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fa-regular fa-star" data-value="<?= $i ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <div>
                        <strong>Note moyenne :</strong> 
                        <span id="moyenne"><?= number_format($rating['moyenne'] ?? 0, 1) ?></span>/5
                        (<?= $rating['total'] ?? 0 ?> avis)
                    </div>
                </div>
                <div id="ratingMessage" style="margin-top: 1rem;"></div>
            </div>
        </div>
    </div>

    <footer class="footer"><p>© 2024 NutriFlow AI - Mangez sainement, vivez pleinement</p></footer>

    <!-- Intégration du bouton SOS -->
    <?php require_once __DIR__ . '/urgence_button.php'; ?>

    <script>
        // Mode sombre
        const darkModeToggle = document.getElementById('darkModeToggle');
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
            darkModeToggle.innerHTML = '☀️ Mode clair';
        }
        darkModeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
                darkModeToggle.innerHTML = '☀️ Mode clair';
            } else {
                localStorage.setItem('darkMode', 'disabled');
                darkModeToggle.innerHTML = '🌙 Mode sombre';
            }
        });

        // Système d'évaluation par étoiles
        const stars = document.querySelectorAll('.stars i');
        let currentRating = 0;
        const hasRated = <?= $hasRated ? 'true' : 'false' ?>;

        if (hasRated) {
            stars.forEach(star => {
                star.style.pointerEvents = 'none';
                star.style.opacity = '0.5';
            });
            document.getElementById('ratingMessage').innerHTML = '<div style="background:#cce5ff; color:#004085; padding:0.7rem; border-radius:16px;">ℹ️ Vous avez déjà noté ce traitement.</div>';
        } else {
            stars.forEach(star => {
                star.addEventListener('mouseenter', function() {
                    const value = parseInt(this.dataset.value);
                    highlightStars(value);
                });
                star.addEventListener('mouseleave', function() {
                    highlightStars(currentRating);
                });
                star.addEventListener('click', function() {
                    currentRating = parseInt(this.dataset.value);
                    highlightStars(currentRating);
                    
                    fetch('../../Controller/rate_traitement.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ allergie_id: <?= $allergie_id ?>, note: currentRating })
                    })
                    .then(response => response.json())
                    .then(data => {
                        const msgDiv = document.getElementById('ratingMessage');
                        if (data.success) {
                            msgDiv.innerHTML = '<div style="background:#d4edda; color:#155724; padding:0.7rem; border-radius:16px;">✅ Merci pour votre évaluation !</div>';
                            document.getElementById('moyenne').innerText = data.moyenne;
                            setTimeout(() => msgDiv.innerHTML = '', 3000);
                            stars.forEach(s => { s.style.pointerEvents = 'none'; s.style.opacity = '0.5'; });
                        } else {
                            msgDiv.innerHTML = '<div style="background:#f8d7da; color:#721c24; padding:0.7rem; border-radius:16px;">❌ ' + data.message + '</div>';
                        }
                    });
                });
            });
        }

        function highlightStars(value) {
            stars.forEach((star, index) => {
                if (index < value) {
                    star.className = 'fa-solid fa-star';
                } else {
                    star.className = 'fa-regular fa-star';
                }
            });
        }
    </script>

</body>
</html>