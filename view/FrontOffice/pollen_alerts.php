<?php
session_start();
require_once __DIR__ . '/../../Config/Database.php';
require_once __DIR__ . '/../../Controller/PollenController.php';

if (!isset($_SESSION['pollen_session_id'])) {
    $_SESSION['pollen_session_id'] = session_id() . '_pollen_' . time();
}
$session_id = $_SESSION['pollen_session_id'];

$pollenController = new PollenController();
$preferences = $pollenController->getPreferences($session_id);
$pollenData = $pollenController->getPollenData($preferences->getVille());
$alertStatus = $pollenController->checkAndSendAlert($session_id);
$alertHistory = $pollenController->getAlertHistory($session_id, 5);
$cities = $pollenController->getAvailableCities();

$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'ville' => $_POST['ville'] ?? 'Tunis',
        'pollen_allergy' => isset($_POST['pollen_allergy']) ? 1 : 0,
        'acarien_allergy' => isset($_POST['acarien_allergy']) ? 1 : 0,
        'moisissure_allergy' => isset($_POST['moisissure_allergy']) ? 1 : 0,
        'alert_email' => $_POST['alert_email'] ?? null,
        'alert_phone' => $_POST['alert_phone'] ?? null,
        'alert_threshold' => $_POST['alert_threshold'] ?? 70
    ];
    
    if ($pollenController->savePreferences($session_id, $data)) {
        $success = true;
        $preferences = $pollenController->getPreferences($session_id);
        $pollenData = $pollenController->getPollenData($preferences->getVille());
        $alertStatus = $pollenController->checkAndSendAlert($session_id);
    }
}

$riskLevel = $pollenData['risk_level'] ?? 'moyen';
$pollenLevel = $pollenData['pm2_5'] ?? $pollenData['pm10'] ?? 50;
$villeName = $pollenData['ville'] ?? $preferences->getVille();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Alertes Pollen - NutriFlow AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Poppins',sans-serif;background:linear-gradient(135deg,#f5f7fa 0%,#e9ecef 100%);min-height:100vh;}
        body.dark-mode{background:linear-gradient(135deg,#1a1a2e 0%,#16213e 100%);color:#eee;}
        body.dark-mode .card,body.dark-mode .stats-card{background:#2d2d44;color:#eee;}
        body.dark-mode .pref-box{background:#1a1a2e;}
        .dark-mode-btn{position:fixed;top:20px;right:20px;z-index:1000;background:#2d5016;color:white;border:none;border-radius:50px;padding:10px 15px;cursor:pointer;font-weight:600;}
        .banner{background:linear-gradient(135deg,#1a3c0e 0%,#3a6b1e 100%);padding:1.5rem 2rem;text-align:center;color:white;position:relative;overflow:hidden;}
        .banner h1{font-size:2rem;letter-spacing:3px;}
        .banner::before{content:"🌿";position:absolute;font-size:120px;opacity:0.08;bottom:-30px;right:-30px;}
        .container{max-width:1300px;margin:0 auto;padding:2rem;}
        .back-btn{display:inline-flex;align-items:center;gap:8px;background:white;color:#2d5016;padding:0.5rem 1.2rem;border-radius:30px;text-decoration:none;margin-bottom:1.5rem;font-weight:500;}
        .alert-card{border-radius:20px;padding:1.2rem;margin-bottom:1.5rem;animation:pulse 2s infinite;}
        .alert-card.danger{background:linear-gradient(135deg,#ffebee 0%,#ffcdd2 100%);border-left:4px solid #f44336;}
        .alert-card.warning{background:linear-gradient(135deg,#fff3e0 0%,#ffe0b2 100%);border-left:4px solid #ff9800;}
        @keyframes pulse{0%{box-shadow:0 0 0 0 rgba(244,67,54,0.4);}70%{box-shadow:0 0 0 10px rgba(244,67,54,0);}100%{box-shadow:0 0 0 0 rgba(244,67,54,0);}}
        .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.2rem;margin-bottom:1.5rem;}
        .stats-card{background:white;border-radius:20px;padding:1.2rem;box-shadow:0 4px 18px rgba(0,0,0,0.07);transition:transform 0.2s;}
        .stats-card:hover{transform:translateY(-3px);}
        .stats-card .value{font-size:2rem;font-weight:700;color:#2d5016;}
        .stats-card .label{font-size:0.75rem;color:#666;}
        .risk-bar{height:8px;background:#eee;border-radius:4px;margin:0.5rem 0;overflow:hidden;}
        .risk-fill.faible{background:#4caf50;width:25%;}
        .risk-fill.moyen{background:#ff9800;width:50%;}
        .risk-fill.eleve{background:#f44336;width:75%;}
        .pollen-item{display:flex;justify-content:space-between;align-items:center;padding:0.6rem 0;border-bottom:1px solid #eee;}
        .pollen-name{font-weight:600;font-size:0.85rem;}
        .level-badge{padding:0.2rem 0.6rem;border-radius:20px;font-size:0.7rem;font-weight:600;}
        .level-high{background:#f44336;color:white;}
        .level-mid{background:#ff9800;color:white;}
        .level-low{background:#4caf50;color:white;}
        .card{background:white;border-radius:20px;padding:1.2rem;margin-bottom:1.5rem;box-shadow:0 4px 18px rgba(0,0,0,0.07);}
        .pref-box{background:#f8f9fa;border-radius:20px;padding:1.2rem;margin-top:1.5rem;}
        .form-group{margin-bottom:1rem;}
        .form-group label{display:block;margin-bottom:0.4rem;font-weight:600;font-size:0.8rem;}
        .form-group input,.form-group select{width:100%;padding:0.7rem;border:2px solid #e0e0e0;border-radius:12px;font-family:'Poppins',sans-serif;}
        .checkbox-group{display:flex;align-items:center;gap:10px;margin-bottom:0.8rem;}
        .btn-save{background:linear-gradient(135deg,#2d5016 0%,#4a7c2b 100%);color:white;padding:0.8rem;border:none;border-radius:12px;font-weight:600;cursor:pointer;width:100%;}
        .history-item{background:#f8f9fa;padding:0.8rem;border-radius:12px;margin-bottom:0.6rem;border-left:3px solid #ff9800;}
        .footer{background:linear-gradient(135deg,#1a3c0e 0%,#2d5016 100%);color:white;text-align:center;padding:1.2rem;margin-top:2rem;}
        @media (max-width:768px){.container{padding:1rem;}.stats-grid{grid-template-columns:1fr;}}
    </style>
</head>
<body>
    <button class="dark-mode-btn" id="darkModeToggle">🌙 Mode sombre</button>

    <div class="banner">
        <h1>🌤️ ALERTES SAISONNIERES</h1>
        <p>Surveillez le niveau de pollen et protegez-vous</p>
    </div>

    <div class="container">
        <a href="front_allergie_traitement.php" class="back-btn">← Retour aux allergies</a>
        
        <?php if(isset($alertStatus['alert']) && $alertStatus['alert'] && ($alertStatus['level'] ?? 0) >= 70): ?>
        <div class="alert-card danger">
            <h3>🚨 ALERTE POLLEN ELEVE 🚨</h3>
            <p>Niveau pollen à <?= $alertStatus['level'] ?? 70 ?>% à <?= htmlspecialchars($villeName) ?></p>
        </div>
        <?php elseif(isset($alertStatus['alert']) && $alertStatus['alert'] && ($alertStatus['level'] ?? 0) >= 50): ?>
        <div class="alert-card warning">
            <h3>⚠️ Niveau pollen modere</h3>
            <p>Niveau pollen à <?= $alertStatus['level'] ?? 50 ?>% à <?= htmlspecialchars($villeName) ?></p>
        </div>
        <?php endif; ?>
        
        <div class="stats-grid">
            <div class="stats-card">
                <div class="value"><?= $pollenLevel ?>%</div>
                <div class="label">🌾 Niveau pollen global</div>
                <div class="risk-bar"><div class="risk-fill <?= $riskLevel ?>"></div></div>
                <div class="label">Risque : <?= ucfirst($riskLevel) ?></div>
            </div>
            <div class="stats-card">
                <div class="value"><?= date('d/m/Y') ?></div>
                <div class="label">📅 Derniere mise a jour</div>
                <div class="label">📍 <?= htmlspecialchars($villeName) ?></div>
            </div>
            <div class="stats-card">
                <div class="value"><?= $preferences->getPollenAllergy() ? '⚠️ Oui' : '✅ Non' ?></div>
                <div class="label">🤧 Allergique au pollen</div>
                <div class="label">Seuil : <?= $preferences->getAlertThreshold() ?>%</div>
            </div>
        </div>
        
        <div class="card">
            <h3 style="color:#2d5016;">🌿 Pollens detectes</h3>
            <?php if(!empty($pollenData['pollens'])): ?>
                <?php foreach($pollenData['pollens'] as $pollen): ?>
                <?php $levelClass = ($pollen['niveau'] ?? 0) >= 70 ? 'level-high' : (($pollen['niveau'] ?? 0) >= 40 ? 'level-mid' : 'level-low'); ?>
                <div class="pollen-item">
                    <span class="pollen-name">🌿 <?= htmlspecialchars($pollen['nom'] ?? 'Inconnu') ?></span>
                    <span class="level-badge <?= $levelClass ?>"><?= $pollen['niveau'] ?? 0 ?>%</span>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune donnee pollen disponible</p>
            <?php endif; ?>
        </div>
        
        <div class="card" style="background:linear-gradient(135deg,#e8f5e9 0%,#c8e6c9 100%);">
            <h3 style="color:#2d5016;">💡 Recommandations</h3>
            <?php if(!empty($pollenData['recommendations'])): ?>
                <?php foreach($pollenData['recommendations'] as $rec): ?>
                <p>• <?= htmlspecialchars($rec) ?></p>
                <?php endforeach; ?>
            <?php else: ?>
                <p>• Restez informe du niveau de pollen</p>
                <p>• Consultez votre medecin</p>
            <?php endif; ?>
        </div>
        
        <div class="card">
            <h3 style="color:#2d5016;">📅 Calendrier pollinique</h3>
            <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:0.5rem;">
                <?php
                $months = ['Jan','Fev','Mar','Avr','Mai','Juin','Juil','Aou','Sep','Oct','Nov','Dec'];
                $currentMonth = (int)date('n') - 1;
                foreach($months as $index => $month):
                    $isActive = ($index == $currentMonth);
                    if($index>=2 && $index<=5) $season = 'Graminees';
                    elseif($index>=7 && $index<=9) $season = 'Ambroisie';
                    elseif($index>=1 && $index<=3) $season = 'Bouleau';
                    else $season = 'Faible';
                ?>
                <div style="text-align:center;padding:0.5rem;background:<?= $isActive ? '#2d5016' : '#f0f0f0'; ?>;border-radius:12px;min-width:55px;">
                    <div style="font-size:0.7rem;color:<?= $isActive ? 'white' : '#666'; ?>"><?= $month ?></div>
                    <div style="font-size:0.6rem;color:<?= $isActive ? '#ddd' : '#999'; ?>"><?= $season ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="pref-box">
            <h3 style="color:#2d5016;">⚙️ Mes preferences</h3>
            <?php if($success): ?>
            <div style="background:#d4edda;color:#155724;padding:0.8rem;border-radius:12px;margin-bottom:1rem;">✅ Preferences enregistrees !</div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label>📍 Ma ville</label>
                    <select name="ville">
                        <?php foreach($cities as $city): ?>
                        <option value="<?= $city ?>" <?= $preferences->getVille() == $city ? 'selected' : '' ?>><?= $city ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="pollen_allergy" <?= $preferences->getPollenAllergy() ? 'checked' : '' ?>>
                    <label>🤧 Allergique au pollen</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="acarien_allergy" <?= $preferences->getAcarienAllergy() ? 'checked' : '' ?>>
                    <label>🕷️ Allergique aux acariens</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="moisissure_allergy" <?= $preferences->getMoisissureAllergy() ? 'checked' : '' ?>>
                    <label>🍄 Allergique aux moisissures</label>
                </div>
                <div class="form-group">
                    <label>📧 Email alerte</label>
                    <input type="email" name="alert_email" value="<?= htmlspecialchars($preferences->getAlertEmail() ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>📱 Telephone alerte</label>
                    <input type="tel" name="alert_phone" value="<?= htmlspecialchars($preferences->getAlertPhone() ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>⚠️ Seuil d'alerte</label>
                    <select name="alert_threshold">
                        <option value="50">50% - Alerte precoce</option>
                        <option value="60">60% - Modere</option>
                        <option value="70" selected>70% - Eleve (recommandé)</option>
                        <option value="80">80% - Tres eleve</option>
                    </select>
                </div>
                <button type="submit" class="btn-save">💾 Enregistrer</button>
            </form>
        </div>
        
        <?php if(!empty($alertHistory)): ?>
        <div class="pref-box" style="margin-top:1rem;">
            <h3 style="color:#2d5016;">📜 Historique alertes</h3>
            <?php foreach($alertHistory as $alert): ?>
            <div class="history-item">
                <div class="history-date"><?= date('d/m/Y H:i', strtotime($alert['sent_at'] ?? 'now')) ?></div>
                <div>⚠️ <?= htmlspecialchars($alert['message'] ?? 'Alerte pollen') ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <div class="pref-box" style="background:linear-gradient(135deg,#e3f2fd,#bbdefb);">
            <h3 style="color:#1565c0;">📊 Conseils par saison</h3>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:0.8rem;">
                <div><strong>🌸 Printemps</strong><br>Bouleau, Chene<br>Lavez vos cheveux</div>
                <div><strong>🌾 Ete</strong><br>Graminees, Olivier<br>Masque recommande</div>
                <div><strong>🍂 Automne</strong><br>Ambroisie<br>Douche en rentrant</div>
                <div><strong>❄️ Hiver</strong><br>Peu de pollen<br>Attention acariens</div>
            </div>
        </div>
    </div>
    
    <footer class="footer">
        <p>© 2024 NutriFlow AI - Donnees pollen mises a jour quotidiennement</p>
    </footer>
    
    <script>
        const darkModeToggle = document.getElementById('darkModeToggle');
        if(localStorage.getItem('darkMode') === 'enabled'){
            document.body.classList.add('dark-mode');
            darkModeToggle.innerHTML = '☀️ Mode clair';
        }
        darkModeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            if(document.body.classList.contains('dark-mode')){
                localStorage.setItem('darkMode', 'enabled');
                darkModeToggle.innerHTML = '☀️ Mode clair';
            } else {
                localStorage.setItem('darkMode', 'disabled');
                darkModeToggle.innerHTML = '🌙 Mode sombre';
            }
        });
    </script>
</body>
</html>