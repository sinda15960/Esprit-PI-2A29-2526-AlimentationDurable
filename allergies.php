<?php
require_once __DIR__ . '/Controller/AllergieController.php';
require_once __DIR__ . '/Controller/TraitementController.php';

$allergieController = new AllergieController();
$traitementController = new TraitementController();

// Récupérer toutes les allergies depuis la base de données
$allergies = $allergieController->getAllAllergies();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriFlow AI - Allergies & Traitements</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; color: #333; }
        .banner { background: linear-gradient(135deg, #2d5016 0%, #4a7c2b 100%); padding: 3rem 2rem; text-align: center; color: white; }
        .banner h1 { font-size: 4rem; letter-spacing: 5px; margin-bottom: 0.5rem; }
        .banner p { font-size: 1.2rem; margin-bottom: 1rem; }
        .banner .subtitle { font-size: 1rem; opacity: 0.9; }
        .btn-primary { margin-top: 1rem; padding: 0.5rem 1.5rem; background: white; color: #2d5016; border: none; border-radius: 25px; cursor: pointer; font-weight: bold; }
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .section-title { font-size: 2rem; margin-bottom: 1rem; color: #2d5016; border-left: 5px solid #4a7c2b; padding-left: 1rem; }
        .section-subtitle { color: #666; margin-bottom: 2rem; }
        .info-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin: 2rem 0; }
        .info-card { background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .info-card h3 { color: #2d5016; margin-bottom: 1rem; }
        .search-box { margin: 1rem 0 2rem 0; }
        .search-box input { width: 100%; padding: 0.8rem; border: 2px solid #ddd; border-radius: 8px; font-size: 1rem; }
        .search-box input:focus { outline: none; border-color: #4a7c2b; }
        .result-table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .result-table th { background: #2d5016; color: white; padding: 1rem; text-align: left; }
        .result-table td { padding: 1rem; border-bottom: 1px solid #eee; }
        .result-table tr:hover { background: #f9f9f9; }
        .badge { display: inline-block; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: bold; }
        .badge-legere { background: #4caf50; color: white; }
        .badge-moderate { background: #ff9800; color: white; }
        .badge-severe { background: #f44336; color: white; }
        .badge-faible { background: #4caf50; color: white; }
        .badge-moyen { background: #ff9800; color: white; }
        .badge-eleve { background: #f44336; color: white; }
        .no-result { text-align: center; padding: 2rem; background: white; border-radius: 10px; color: #999; }
        .feedback-box { background: white; border-radius: 10px; margin-top: 2rem; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-top: 4px solid #2d5016; }
        .feedback-title { color: #2d5016; margin-bottom: 0.5rem; font-size: 1.3rem; display: flex; align-items: center; gap: 0.5rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: bold; color: #333; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 0.8rem; border: 2px solid #ddd; border-radius: 8px; font-size: 1rem; font-family: inherit; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #4a7c2b; }
        .form-group input.error, .form-group textarea.error { border-color: #f44336; background-color: #fff8f8; }
        .form-group input.valid, .form-group textarea.valid { border-color: #4caf50; background-color: #f0fff0; }
        .field-error { color: #f44336; font-size: 0.75rem; margin-top: 0.25rem; display: none; }
        .field-error.show { display: block; }
        .field-success { color: #4caf50; font-size: 0.75rem; margin-top: 0.25rem; display: none; }
        .field-success.show { display: block; }
        .btn-submit { background: #2d5016; color: white; padding: 0.8rem 1.5rem; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 1rem; width: 100%; }
        .btn-submit:hover { background: #4a7c2b; }
        .btn-submit:disabled { background: #ccc; cursor: not-allowed; }
        .feedback-card { background: #f9f9f9; padding: 1rem; border-radius: 8px; margin-bottom: 0.8rem; border-left: 4px solid #2d5016; }
        .feedback-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; flex-wrap: wrap; }
        .feedback-type { font-weight: bold; font-size: 0.85rem; }
        .feedback-date { font-size: 0.7rem; color: #999; }
        .feedback-message { color: #555; line-height: 1.5; margin: 0.5rem 0; }
        .alert { padding: 0.8rem; border-radius: 8px; margin-bottom: 1rem; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .footer { background: #2d5016; color: white; text-align: center; padding: 2rem; margin-top: 3rem; }
        @media (max-width: 768px) { .banner h1 { font-size: 2.5rem; } .result-table { font-size: 0.8rem; } .result-table th, .result-table td { padding: 0.5rem; } }
    </style>
</head>
<body>
    <div class="banner">
        <h1>EAT HEALTHY</h1>
        <p>plan your meals</p>
        <p class="subtitle">Menu comprends vos allergies, adoptez les bons traitements et vivez sainement.</p>
        <button class="btn-primary" onclick="scrollToAllergies()">En savoir plus</button>
    </div>

    <div class="container">
        <div class="info-cards">
            <div class="info-card"><h3>📋 Informations fiables</h3><p>Des informations claires sur les allergies et traitements.</p></div>
            <div class="info-card"><h3>🎯 Conseils personnalisés</h3><p>Des conseils adaptés à vos besoins.</p></div>
            <div class="info-card"><h3>💪 Vie plus saine</h3><p>Adaptez de meilleures habitudes au quotidien.</p></div>
        </div>
    </div>

    <!-- Section Allergies -->
    <div class="container" id="allergies-section">
        <h2 class="section-title">🔬 Nos Allergies</h2>
        <p class="section-subtitle">Informez-vous sur les différentes allergies, leurs symptômes et leur gravité.</p>
        
        <div class="search-box">
            <input type="text" id="searchAllergie" placeholder="Rechercher une allergie (ex: Gluten, Lactose, Arachides...)" onkeyup="searchAllergie()">
        </div>
        
        <div id="allergie-result">
            <table class="result-table">
                <thead>
                    <tr><th>Allergie</th><th>Description</th><th>Symptômes</th><th>Déclencheurs</th><th>Gravité</th></tr>
                </thead>
                <tbody id="allergie-table-body">
                    <?php foreach ($allergies as $a): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($a['nom']) ?></strong><br><small><?= htmlspecialchars($a['categorie']) ?></small></td>
                        <td><?= htmlspecialchars(substr($a['description'], 0, 100)) ?>...</td>
                        <td><?= htmlspecialchars(substr($a['symptomes'], 0, 80)) ?>...</td>
                        <td><?= htmlspecialchars(substr($a['declencheurs'], 0, 80)) ?>...</td>
                        <td><span class="badge badge-<?= $a['gravite'] ?>"><?= ucfirst($a['gravite']) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section Traitements -->
    <div class="container" style="background: #f0f4f8; border-radius: 10px; margin-top: 1rem;">
        <h2 class="section-title">💊 Nos Traitements</h2>
        <p class="section-subtitle">Découvrez les conseils, interdits et traitements associés.</p>
        
        <div class="search-box">
            <input type="text" id="searchTraitement" placeholder="Rechercher un traitement par allergie (ex: Gluten, Lactose...)" onkeyup="searchTraitement()">
        </div>
        
        <div id="traitement-result">
            <table class="result-table">
                <thead>
                    <tr><th>Allergie</th><th>Conseils</th><th>Interdits</th><th>Médicaments</th><th>Durée</th><th>Urgence</th></tr>
                </thead>
                <tbody id="traitement-table-body">
                    <?php
                    // Récupérer tous les traitements avec jointure
                    $db = Database::getInstance()->getConnection();
                    $stmt = $db->query("
                        SELECT t.*, a.nom as allergie_nom 
                        FROM traitements t 
                        JOIN allergies a ON t.allergie_id = a.id 
                        ORDER BY a.nom
                    ");
                    $traitements = $stmt->fetchAll();
                    ?>
                    <?php foreach ($traitements as $t): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($t['allergie_nom']) ?></strong></td>
                        <td><?= htmlspecialchars(substr($t['conseil'], 0, 80)) ?>...</td>
                        <td><?= htmlspecialchars(substr($t['interdits'], 0, 80)) ?>...</td>
                        <td><?= htmlspecialchars($t['medicaments'] ?? 'Aucun') ?></td>
                        <td><?= htmlspecialchars($t['duree'] ?? 'Non spécifiée') ?></td>
                        <td><span class="badge badge-<?= $t['niveau_urgence'] ?>"><?= ucfirst($t['niveau_urgence']) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Feedback Box -->
    <div class="container">
        <div class="feedback-box">
            <div class="feedback-title">💬 feedback</div>
            <p style="color: #666; margin-bottom: 1rem; font-size: 0.9rem;">Signalez une erreur, suggérez un traitement ou partagez votre expérience</p>
            <div id="feedback-message"></div>
            <form id="feedback-form" action="Controller/save_feedback.php" method="POST">
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
                <h3 style="color: #2d5016; margin-bottom: 0.8rem; font-size: 1rem;">📢 Derniers avis</h3>
                <div id="recent-feedback">
                    <?php
                    $db = Database::getInstance()->getConnection();
                    $stmt = $db->query("SELECT * FROM feedbacks WHERE status = 'approuve' ORDER BY date_creation DESC LIMIT 3");
                    $feedbacks = $stmt->fetchAll();
                    ?>
                    <?php foreach ($feedbacks as $fb): ?>
                    <div class="feedback-card">
                        <div class="feedback-header"><strong><?= htmlspecialchars($fb['type']) ?></strong><span><?= $fb['date_creation'] ?></span></div>
                        <div class="feedback-message">"<?= htmlspecialchars(substr($fb['message'], 0, 100)) ?>"</div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer"><p>© 2024 NutriFlow AI - Mangez sainement, vivez pleinement</p></footer>

    <script>
        function scrollToAllergies() { document.getElementById('allergies-section').scrollIntoView({ behavior: 'smooth' }); }
        
        function searchAllergie() {
            let input = document.getElementById('searchAllergie').value.toLowerCase();
            let rows = document.querySelectorAll('#allergie-table-body tr');
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(input) ? '' : 'none';
            });
        }
        
        function searchTraitement() {
            let input = document.getElementById('searchTraitement').value.toLowerCase();
            let rows = document.querySelectorAll('#traitement-table-body tr');
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(input) ? '' : 'none';
            });
        }
    </script>
</body>
</html>