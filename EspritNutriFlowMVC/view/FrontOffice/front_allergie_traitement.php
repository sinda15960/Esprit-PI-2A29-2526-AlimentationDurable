<?php
require_once __DIR__ . '/../../Config/Database.php';

$db = Database::getInstance()->getConnection();

// Récupérer toutes les allergies
$stmtAllergies = $db->query("SELECT * FROM allergies ORDER BY nom");
$allergies = $stmtAllergies->fetchAll();

// Récupérer les feedbacks approuvés
$stmtFeedbacks = $db->query("
    SELECT * FROM feedbacks 
    WHERE status = 'approuve' 
    ORDER BY date_creation DESC 
    LIMIT 5
");
$feedbacks = $stmtFeedbacks->fetchAll();

// Les plus recherchées
$topViewed = $db->query("SELECT id, nom, gravite, vue_count FROM allergies ORDER BY vue_count DESC LIMIT 5")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriFlow AI - Allergies & Traitements</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            color: #333;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        body.dark-mode {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #eee;
        }
        body.dark-mode .allergy-card,
        body.dark-mode .info-card,
        body.dark-mode .feedback-box,
        body.dark-mode .advanced-search,
        body.dark-mode .trending-section {
            background: #2d2d44;
            color: #eee;
        }
        body.dark-mode .card-header {
            background: linear-gradient(135deg, #1a3c0e 0%, #2d5016 100%);
        }
        body.dark-mode .advanced-search select,
        body.dark-mode .advanced-search input {
            background: #1a1a2e;
            color: #eee;
            border-color: #444;
        }
        body.dark-mode .feedback-card { background: #1a1a2e; }

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
            padding: 3rem 2rem;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .banner::before {
            content: "🌱";
            position: absolute;
            font-size: 180px;
            opacity: 0.08;
            bottom: -40px;
            right: -40px;
        }
        .banner h1 { font-size: 3.5rem; letter-spacing: 5px; font-weight: 700; text-shadow: 2px 2px 4px rgba(0,0,0,0.2); }
        .banner p { font-size: 1.1rem; margin-bottom: 1rem; font-weight: 300; }
        .banner .subtitle { font-size: 0.9rem; opacity: 0.95; }
        .btn-primary {
            margin-top: 1rem;
            padding: 0.6rem 1.8rem;
            background: white;
            color: #2d5016;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,0.2); }

        /* NOUVEAUX BOUTONS FEATURE */
        .feature-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin: 1.5rem auto 0 auto;
            flex-wrap: wrap;
            max-width: 1200px;
            padding: 0 1rem;
        }
        .feature-btn {
            text-decoration: none;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .feature-btn:hover { transform: translateY(-3px); }
        .feature-btn.profile { background: #ff9800; color: white; box-shadow: 0 4px 12px rgba(255,152,0,0.3); }
        .feature-btn.chatbot { background: #2196F3; color: white; box-shadow: 0 4px 12px rgba(33,150,243,0.3); }
        .feature-btn.compare { background: #9c27b0; color: white; box-shadow: 0 4px 12px rgba(156,39,176,0.3); }

        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .section-title {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            color: #2d5016;
            border-left: 5px solid #4a7c2b;
            padding-left: 1rem;
            font-weight: 600;
        }
        body.dark-mode .section-title { color: #4a7c2b; }
        .section-subtitle { color: #666; margin-bottom: 1.5rem; font-weight: 400; font-size: 0.9rem; }
        body.dark-mode .section-subtitle { color: #aaa; }

        .info-cards { display: flex; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap; }
        .info-card {
            background: white;
            padding: 1.2rem;
            border-radius: 20px;
            flex: 1;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
            text-align: center;
        }
        .info-card:hover { transform: translateY(-5px); }
        .info-card h3 { color: #2d5016; margin-bottom: 0.5rem; font-size: 1.1rem; }
        .info-card p { color: #666; font-size: 0.85rem; }

        .trending-section {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }
        .trending-section h3 { color: #2d5016; margin-bottom: 1rem; }
        .trending-tag {
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2b 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: transform 0.2s;
        }
        .trending-tag:hover { transform: scale(1.05); }

        .advanced-search {
            background: #f8f9fa;
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .search-row { display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end; }
        .search-group { flex: 1; min-width: 150px; }
        .search-group label { display: block; margin-bottom: 0.3rem; font-weight: 600; font-size: 0.8rem; }
        .search-group input, .search-group select {
            width: 100%;
            padding: 0.6rem;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
        }
        .search-group input:focus, .search-group select:focus { outline: none; border-color: #4a7c2b; }
        .reset-btn {
            background: #999;
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
        }
        .reset-btn:hover { background: #777; }

        .carousel-container {
            position: relative;
            margin: 1rem 0;
        }
        .carousel-wrapper {
            overflow-x: auto;
            scroll-behavior: smooth;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .carousel-wrapper::-webkit-scrollbar { display: none; }
        .carousel-track {
            display: flex;
            gap: 1.5rem;
            width: max-content;
        }
        .allergy-card {
            width: 340px;
            flex-shrink: 0;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        .allergy-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 35px rgba(0,0,0,0.12);
        }
        @media (max-width: 768px) { .allergy-card { width: 280px; } }
        .card-header {
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2b 100%);
            color: white;
            padding: 1rem 1.2rem;
            position: relative;
        }
        .card-header h3 { font-size: 1.3rem; font-weight: 700; margin-bottom: 0.2rem; }
        .card-header .category { font-size: 0.75rem; opacity: 0.9; font-weight: 400; }
        .badge-gravite {
            position: absolute;
            top: 0.8rem;
            right: 0.8rem;
            padding: 0.2rem 0.7rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .badge-legere { background: #4caf50; color: white; }
        .badge-moderate { background: #ff9800; color: white; }
        .badge-severe { background: #f44336; color: white; }
        .card-body { padding: 1rem 1.2rem 1.2rem 1.2rem; flex: 1; }
        .card-body p { margin-bottom: 0.6rem; line-height: 1.45; font-size: 0.85rem; }
        .card-body strong { color: #2d5016; }
        .rating-stars { margin: 0.5rem 0; display: flex; align-items: center; gap: 5px; }
        .btn-traitement {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2b 100%);
            color: white;
            border: none;
            padding: 0.6rem 1rem;
            border-radius: 30px;
            cursor: pointer;
            margin-top: 0.8rem;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            width: 100%;
        }
        .btn-traitement:hover { transform: scale(1.02); box-shadow: 0 4px 12px rgba(45,80,22,0.3); }

        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.3rem;
            font-weight: bold;
            color: #2d5016;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .carousel-btn:hover {
            background: #2d5016;
            color: white;
            transform: translateY(-50%) scale(1.05);
        }
        .carousel-btn-prev { left: -20px; }
        .carousel-btn-next { right: -20px; }
        @media (max-width: 768px) {
            .carousel-btn-prev { left: -10px; width: 35px; height: 35px; font-size: 1rem; }
            .carousel-btn-next { right: -10px; width: 35px; height: 35px; font-size: 1rem; }
        }

        .carousel-dots {
            display: flex;
            justify-content: center;
            gap: 0.6rem;
            margin-top: 1.5rem;
        }
        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #ccc;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .dot.active {
            background: #2d5016;
            width: 25px;
            border-radius: 10px;
        }

        .feedback-box {
            background: white;
            border-radius: 24px;
            margin-top: 2rem;
            padding: 1.5rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border-top: 4px solid #2d5016;
        }
        .feedback-title { color: #2d5016; margin-bottom: 0.5rem; font-size: 1.3rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.4rem; font-weight: 600; color: #333; font-size: 0.85rem; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 0.7rem;
            border: 2px solid #e0e0e0;
            border-radius: 16px;
            font-size: 0.9rem;
            font-family: 'Poppins', sans-serif;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #4a7c2b; box-shadow: 0 0 0 3px rgba(74,124,43,0.1); }
        .btn-submit {
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2b 100%);
            color: white;
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn-submit:hover { transform: scale(1.02); box-shadow: 0 4px 12px rgba(45,80,22,0.3); }
        .feedback-card { background: #f8f9fa; padding: 0.8rem; border-radius: 16px; margin-bottom: 0.8rem; border-left: 4px solid #2d5016; }
        .feedback-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.3rem; flex-wrap: wrap; }
        .feedback-type { font-weight: 700; font-size: 0.8rem; color: #2d5016; }
        .feedback-date { font-size: 0.65rem; color: #999; }
        .feedback-message { color: #555; line-height: 1.4; margin: 0.3rem 0; font-size: 0.85rem; }
        .alert { padding: 0.7rem 1rem; border-radius: 16px; margin-bottom: 1rem; display: flex; align-items: center; gap: 10px; font-size: 0.85rem; }
        .alert-success { background: #d4edda; color: #155724; border-left: 4px solid #4caf50; }
        .alert-error { background: #f8d7da; color: #721c24; border-left: 4px solid #f44336; }
        .footer { background: linear-gradient(135deg, #1a3c0e 0%, #2d5016 100%); color: white; text-align: center; padding: 1.2rem; margin-top: 2rem; font-weight: 300; font-size: 0.85rem; }
        @media (max-width: 768px) {
            .banner h1 { font-size: 2.5rem; }
            .info-cards { flex-direction: column; }
            .container { padding: 1rem; }
            .search-row { flex-direction: column; }
            .search-group { width: 100%; }
            .feature-buttons { flex-direction: column; align-items: stretch; }
            .feature-btn { justify-content: center; }
        }
    </style>
</head>
<body>
    <button class="dark-mode-btn" id="darkModeToggle">🌙 Mode sombre</button>

    <div class="banner">
        <h1>EAT HEALTHY</h1>
        <p>plan your meals</p>
        <p class="subtitle">Menu comprends vos allergies, adoptez les bons traitements et vivez sainement.</p>
        <button class="btn-primary" onclick="scrollToAllergies()">En savoir plus</button>
    </div>

    <!-- NOUVEAUX BOUTONS D'ACCÈS RAPIDE AUX FONCTIONNALITÉS -->
    <div class="feature-buttons">
        <a href="profile_builder.php" class="feature-btn profile">
            <i class="fas fa-id-card"></i> 🆘 Mon profil allergique
        </a>
        <a href="chatbot.php" class="feature-btn chatbot">
            <i class="fas fa-robot"></i> 🤖 Assistant IA
        </a>
        <a href="compare_allergies.php" class="feature-btn compare">
            <i class="fas fa-chart-simple"></i> ⚖️ Comparateur d'allergies
        </a>
    </div>

    <div class="container">
        <div class="info-cards">
            <div class="info-card"><h3>📋 Informations fiables</h3><p>Des informations claires sur les allergies et traitements.</p></div>
            <div class="info-card"><h3>🎯 Conseils personnalisés</h3><p>Des conseils adaptés à vos besoins.</p></div>
            <div class="info-card"><h3>💪 Vie plus saine</h3><p>Adaptez de meilleures habitudes au quotidien.</p></div>
        </div>
    </div>

    <!-- Les plus recherchées -->
    <div class="container">
        <div class="trending-section">
            <h3>🔥 Les plus recherchées</h3>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <?php foreach ($topViewed as $tv): ?>
                <a href="traitement_details.php?id=<?= $tv['id'] ?>&nom=<?= urlencode($tv['nom']) ?>" class="trending-tag">
                    🔥 <?= htmlspecialchars($tv['nom']) ?> (<?= $tv['vue_count'] ?> vues)
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="container" id="allergies-section">
        <h2 class="section-title">🔬 Nos Allergies</h2>
        <p class="section-subtitle">Découvrez toutes les allergies et leurs traitements associés.</p>
        
        <div class="advanced-search">
            <div class="search-row">
                <div class="search-group">
                    <label>🔍 Nom</label>
                    <input type="text" id="searchNom" placeholder="Nom de l'allergie...">
                </div>
                <div class="search-group">
                    <label>📂 Catégorie</label>
                    <select id="searchCategorie">
                        <option value="">Toutes</option>
                        <option value="Alimentaire">Alimentaire</option>
                        <option value="Respiratoire">Respiratoire</option>
                        <option value="Médicamenteuse">Médicamenteuse</option>
                        <option value="Cutane">Cutane</option>
                    </select>
                </div>
                <div class="search-group">
                    <label>⚡ Gravité</label>
                    <select id="searchGravite">
                        <option value="">Toutes</option>
                        <option value="legere">Légère</option>
                        <option value="moderate">Modérée</option>
                        <option value="severe">Sévère</option>
                    </select>
                </div>
                <div><button id="resetBtn" class="reset-btn">↺ Réinitialiser</button></div>
            </div>
        </div>

        <div class="carousel-container">
            <button class="carousel-btn carousel-btn-prev" id="prevBtn">❮</button>
            <div class="carousel-wrapper" id="carouselWrapper">
                <div class="carousel-track" id="carouselTrack">
                    <?php foreach ($allergies as $a): 
                        $avgRating = $db->prepare("SELECT AVG(note) as moyenne FROM ratings WHERE allergie_id = ?");
                        $avgRating->execute([$a['id']]);
                        $ratingData = $avgRating->fetch(PDO::FETCH_ASSOC);
                        $moyenne = round($ratingData['moyenne'] ?? 0);
                    ?>
                    <div class="allergy-card" data-id="<?= $a['id'] ?>" data-nom="<?= strtolower(htmlspecialchars($a['nom'])) ?>" data-categorie="<?= htmlspecialchars($a['categorie']) ?>" data-gravite="<?= $a['gravite'] ?>">
                        <div class="card-header">
                            <h3><?= htmlspecialchars($a['nom']) ?></h3>
                            <div class="category"><?= htmlspecialchars($a['categorie']) ?></div>
                            <span class="badge-gravite badge-<?= $a['gravite'] ?>"><?= ucfirst($a['gravite']) ?></span>
                        </div>
                        <div class="card-body">
                            <p><strong>📝 Description :</strong><br><?= nl2br(htmlspecialchars($a['description'])) ?></p>
                            <p><strong>⚠️ Symptômes :</strong><br><?= nl2br(htmlspecialchars($a['symptomes'])) ?></p>
                            <p><strong>🚫 Déclencheurs :</strong><br><?= nl2br(htmlspecialchars($a['declencheurs'])) ?></p>
                            <div class="rating-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= $moyenne): ?>
                                        <i class="fa-solid fa-star" style="color: #ffc107; font-size: 0.8rem;"></i>
                                    <?php else: ?>
                                        <i class="fa-regular fa-star" style="color: #ffc107; font-size: 0.8rem;"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                <span style="font-size: 0.7rem; color: #666;">(<?= number_format($ratingData['moyenne'] ?? 0, 1) ?>)</span>
                            </div>
                            <a href="traitement_details.php?id=<?= $a['id'] ?>&nom=<?= urlencode($a['nom']) ?>" class="btn-traitement">💊 Voir le traitement associé</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <button class="carousel-btn carousel-btn-next" id="nextBtn">❯</button>
        </div>
        <div class="carousel-dots" id="carouselDots"></div>
    </div>

    <!-- Feedback Box -->
    <div class="container">
        <div class="feedback-box">
            <div class="feedback-title">💬 feedback</div>
            <p style="color: #666; margin-bottom: 1rem; font-size: 0.85rem;">Signalez une erreur, suggérez un traitement ou partagez votre expérience</p>
            
            <?php 
            if (isset($_GET['success'])): 
                echo '<div class="alert alert-success">✅ Merci ! Votre avis a été envoyé avec succès.</div>';
                echo '<script>window.history.replaceState({}, document.title, window.location.pathname);</script>';
            endif; 
            ?>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">❌ <?= htmlspecialchars($_GET['error']) ?></div>
                <?php echo '<script>window.history.replaceState({}, document.title, window.location.pathname);</script>'; ?>
            <?php endif; ?>
            
            <form action="../../Controller/save_feedback.php" method="POST">
                <div class="form-group">
                    <label>Type de remarque *</label>
                    <select name="type" required>
                        <option value="erreur">❌ Signaler une erreur</option>
                        <option value="suggestion">💡 Suggérer un traitement/conseil</option>
                        <option value="experience">📝 Partager mon expérience</option>
                        <option value="alternative">🍽️ Proposer une alternative alimentaire</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Votre message *</label>
                    <textarea name="message" rows="3" placeholder="Décrivez votre remarque..." required></textarea>
                </div>
                <div class="form-group">
                    <label>Votre email (optionnel)</label>
                    <input type="email" name="email" placeholder="exemple@email.com">
                </div>
                <button type="submit" class="btn-submit">📤 Envoyer</button>
            </form>
            
            <div style="margin-top: 1.5rem;">
                <h3 style="color: #2d5016; margin-bottom: 0.5rem; font-size: 1rem;">📢 Derniers avis</h3>
                <?php foreach ($feedbacks as $fb): ?>
                <div class="feedback-card">
                    <div class="feedback-header">
                        <span class="feedback-type"><?= htmlspecialchars($fb['type']) ?></span>
                        <span class="feedback-date"><?= $fb['date_creation'] ?></span>
                    </div>
                    <div class="feedback-message">"<?= htmlspecialchars(substr($fb['message'], 0, 100)) ?>"</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <footer class="footer"><p>© 2024 NutriFlow AI - Mangez sainement, vivez pleinement</p></footer>

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

        // CARROUSEL
        const wrapper = document.getElementById('carouselWrapper');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const cards = document.querySelectorAll('.allergy-card');
        const dotsContainer = document.getElementById('carouselDots');
        
        let currentPage = 0;
        let cardsPerPage = 3;
        
        function updateCardsPerPage() {
            if (window.innerWidth <= 600) cardsPerPage = 1;
            else if (window.innerWidth <= 900) cardsPerPage = 2;
            else cardsPerPage = 3;
        }
        
        function getVisibleCards() {
            return Array.from(cards).filter(card => card.style.display !== 'none');
        }
        
        function updateDots() {
            const dots = document.querySelectorAll('.dot');
            dots.forEach((dot, idx) => { dot.classList.toggle('active', idx === currentPage); });
        }
        
        function createDots() {
            const visibleCards = getVisibleCards();
            const totalPages = Math.ceil(visibleCards.length / cardsPerPage);
            dotsContainer.innerHTML = '';
            for (let i = 0; i < totalPages; i++) {
                const dot = document.createElement('div');
                dot.classList.add('dot');
                if (i === currentPage) dot.classList.add('active');
                dot.addEventListener('click', () => {
                    currentPage = i;
                    scrollToPage();
                });
                dotsContainer.appendChild(dot);
            }
        }
        
        function scrollToPage() {
            updateCardsPerPage();
            const visibleCards = getVisibleCards();
            if (visibleCards.length === 0) return;
            const cardWidth = cards[0].offsetWidth + 24;
            const scrollPosition = currentPage * cardsPerPage * cardWidth;
            wrapper.scrollTo({ left: scrollPosition, behavior: 'smooth' });
            updateDots();
        }
        
        function nextSlide() {
            const visibleCards = getVisibleCards();
            const totalPages = Math.ceil(visibleCards.length / cardsPerPage);
            if (currentPage + 1 < totalPages) { currentPage++; scrollToPage(); }
        }
        
        function prevSlide() {
            if (currentPage - 1 >= 0) { currentPage--; scrollToPage(); }
        }
        
        prevBtn.addEventListener('click', prevSlide);
        nextBtn.addEventListener('click', nextSlide);
        window.addEventListener('resize', () => { updateCardsPerPage(); scrollToPage(); });
        
        // RECHERCHE AUTOMATIQUE
        function filterAndUpdate() {
            const searchNom = document.getElementById('searchNom').value.toLowerCase();
            const searchCategorie = document.getElementById('searchCategorie').value;
            const searchGravite = document.getElementById('searchGravite').value;
            
            cards.forEach(card => {
                const nom = card.getAttribute('data-nom');
                const categorie = card.getAttribute('data-categorie');
                const gravite = card.getAttribute('data-gravite');
                let match = true;
                if (searchNom && !nom.includes(searchNom)) match = false;
                if (searchCategorie && categorie !== searchCategorie) match = false;
                if (searchGravite && gravite !== searchGravite) match = false;
                card.style.display = match ? '' : 'none';
            });
            
            currentPage = 0;
            updateCardsPerPage();
            createDots();
            scrollToPage();
        }
        
        document.getElementById('searchNom').addEventListener('input', filterAndUpdate);
        document.getElementById('searchCategorie').addEventListener('change', filterAndUpdate);
        document.getElementById('searchGravite').addEventListener('change', filterAndUpdate);
        
        document.getElementById('resetBtn').addEventListener('click', () => {
            document.getElementById('searchNom').value = '';
            document.getElementById('searchCategorie').value = '';
            document.getElementById('searchGravite').value = '';
            cards.forEach(card => card.style.display = '');
            currentPage = 0;
            updateCardsPerPage();
            createDots();
            scrollToPage();
        });
        
        updateCardsPerPage();
        createDots();
        setTimeout(scrollToPage, 100);
        
        function scrollToAllergies() {
            document.getElementById('allergies-section').scrollIntoView({ behavior: 'smooth' });
        }
        window.scrollToAllergies = scrollToAllergies;
    </script>
</body>
</html>