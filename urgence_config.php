<?php
session_start();
require_once __DIR__ . '/../../Controller/UrgenceController.php';
 
if (!isset($_SESSION['urgence_session_id'])) {
    $_SESSION['urgence_session_id'] = session_id() . '_urg_' . time();
}
$session_id = $_SESSION['urgence_session_id'];
 
$urgenceController = new UrgenceController();
$contacts = $urgenceController->getContacts($session_id);
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_contact'])) {
        $urgenceController->addContact($session_id, $_POST['nom'], $_POST['relation'], $_POST['telephone'], null);
        header('Location: urgence_config.php'); exit();
    }
    if (isset($_GET['delete'])) {
        $urgenceController->deleteContact($_GET['delete'], $session_id);
        header('Location: urgence_config.php'); exit();
    }
    if (isset($_GET['set_primary'])) {
        $urgenceController->setPrimaryContact($_GET['set_primary'], $session_id);
        header('Location: urgence_config.php'); exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contacts Urgence - NutriFlow AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Poppins',sans-serif;background:linear-gradient(135deg,#f5f7fa 0%,#e9ecef 100%);min-height:100vh;}
        body.dark-mode{background:linear-gradient(135deg,#1a1a2e 0%,#16213e 100%);color:#eee;}
        body.dark-mode .contact-card,body.dark-mode .add-form,body.dark-mode .sos-test-card{background:#2d2d44;color:#eee;}
        .dark-mode-btn{position:fixed;top:20px;right:20px;z-index:1000;background:#2d5016;color:white;border:none;border-radius:50px;padding:10px 15px;cursor:pointer;font-weight:600;}
        .banner{background:linear-gradient(135deg,#1a3c0e 0%,#3a6b1e 100%);padding:1.5rem 2rem;text-align:center;color:white;}
        .banner h1{font-size:1.8rem;}
        .container{max-width:900px;margin:0 auto;padding:2rem;}
        .back-btn{display:inline-flex;align-items:center;gap:8px;background:white;color:#2d5016;padding:0.5rem 1.2rem;border-radius:30px;text-decoration:none;margin-bottom:1.5rem;font-weight:500;}
        .contact-card{background:white;border-radius:20px;padding:1.2rem;margin-bottom:1rem;box-shadow:0 4px 18px rgba(0,0,0,0.07);border-left:4px solid #ff9800;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;}
        .contact-card.primary{border-left-color:#f44336;background:linear-gradient(135deg,#fff5f5 0%,#fff 100%);}
        .contact-info .name{font-weight:700;font-size:1rem;}
        .contact-info .relation{font-size:0.7rem;color:#888;}
        .contact-info .phone{font-size:0.85rem;color:#555;}
        .contact-badge{background:#ff9800;color:white;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.6rem;margin-left:8px;}
        .contact-badge.primary{background:#f44336;}
        .contact-actions{display:flex;gap:0.5rem;}
        .btn-icon{background:none;border:none;cursor:pointer;font-size:1.2rem;padding:0.3rem;}
        .btn-icon.star{color:#ff9800;} .btn-icon.star.primary{color:#f44336;} .btn-icon.delete{color:#f44336;}
        .add-form{background:white;border-radius:20px;padding:1.5rem;margin-top:1rem;box-shadow:0 4px 18px rgba(0,0,0,0.07);}
        .form-group{margin-bottom:1rem;}
        .form-group label{display:block;margin-bottom:0.4rem;font-weight:600;font-size:0.8rem;}
        .form-group input{width:100%;padding:0.7rem;border:2px solid #e0e0e0;border-radius:12px;font-family:'Poppins',sans-serif;}
        .btn-save{background:linear-gradient(135deg,#2d5016 0%,#4a7c2b 100%);color:white;padding:0.8rem;border:none;border-radius:12px;cursor:pointer;width:100%;font-weight:600;}
        .sos-test-card{background:linear-gradient(135deg,#ffebee 0%,#ffcdd2 100%);border-radius:20px;padding:1.5rem;margin-top:1.5rem;text-align:center;}
        .sos-test-card h3{color:#d32f2f;margin-bottom:0.5rem;}
        .btn-sos{background:#f44336;color:white;padding:0.8rem 1.5rem;border:none;border-radius:50px;cursor:pointer;font-weight:700;font-size:1rem;margin-top:0.5rem;transition:transform 0.2s;}
        .btn-sos:hover{transform:scale(1.02);}
        .loading-spinner{display:inline-block;width:30px;height:30px;border:3px solid #f3f3f3;border-top:3px solid #f44336;border-radius:50%;animation:spin 1s linear infinite;margin-bottom:10px;}
        @keyframes spin{0%{transform:rotate(0deg);}100%{transform:rotate(360deg);}}
        .result-box{margin-top:1rem;padding:1rem;border-radius:12px;}
        .result-success{background:#d4edda;color:#155724;}
        .result-error{background:#f8d7da;color:#721c24;}
        .result-warning{background:#fff3cd;color:#856404;}
        .info-banner{background:#e3f2fd;border:1px solid #90caf9;border-radius:12px;padding:0.8rem 1rem;margin-bottom:1.5rem;font-size:0.82rem;color:#1565c0;line-height:1.5;}
        .empty-state{text-align:center;padding:3rem;background:white;border-radius:20px;color:#999;}
        .footer{background:linear-gradient(135deg,#1a3c0e 0%,#2d5016 100%);color:white;text-align:center;padding:1.2rem;margin-top:2rem;}
        #map{height:300px;border-radius:20px;margin-top:1rem;margin-bottom:1rem;}
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body>
    <button class="dark-mode-btn" id="darkModeToggle">🌙 Mode sombre</button>
    <div class="banner">
        <h1>🆘 MES CONTACTS D'URGENCE</h1>
        <p>Ces personnes seront alertées par SMS en cas de réaction allergique sévère</p>
    </div>
    <div class="container">
        <a href="front_allergie_traitement.php" class="back-btn">← Retour</a>
 
        <div class="info-banner">
            📍 <strong>Votre position GPS réelle :</strong><br>
            La position est sauvegardée automatiquement. Un rafraîchissement ne change pas votre position.
        </div>
 
        <div id="map"></div>
 
        <?php if (empty($contacts)): ?>
        <div class="empty-state">
            <i class="fas fa-address-book" style="font-size:3rem;color:#ccc;margin-bottom:1rem;display:block;"></i>
            <p>Aucun contact d'urgence configuré</p>
            <p style="font-size:0.8rem;">Ajoutez au moins un numéro de téléphone</p>
        </div>
        <?php else: ?>
            <?php foreach ($contacts as $c): ?>
            <div class="contact-card <?= $c['is_primary'] ? 'primary' : '' ?>">
                <div class="contact-info">
                    <div class="name">
                        <?= htmlspecialchars($c['nom']) ?>
                        <?php if ($c['is_primary']): ?>
                        <span class="contact-badge primary">⭐ Principal</span>
                        <?php else: ?>
                        <span class="contact-badge">Contact</span>
                        <?php endif; ?>
                    </div>
                    <div class="relation"><?= htmlspecialchars($c['relation'] ?? 'Contact') ?></div>
                    <div class="phone"><i class="fas fa-phone"></i> <?= htmlspecialchars($c['telephone']) ?></div>
                </div>
                <div class="contact-actions">
                    <?php if (!$c['is_primary']): ?>
                    <a href="?set_primary=<?= $c['id'] ?>" class="btn-icon star"><i class="fas fa-star"></i></a>
                    <?php else: ?>
                    <span class="btn-icon star primary"><i class="fas fa-star"></i></span>
                    <?php endif; ?>
                    <a href="?delete=<?= $c['id'] ?>" class="btn-icon delete" onclick="return confirm('Supprimer ce contact ?')"><i class="fas fa-trash"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
 
        <div class="add-form">
            <h3><i class="fas fa-plus-circle"></i> Ajouter un contact d'urgence</h3>
            <form method="POST">
                <div class="form-group">
                    <label>👤 Nom complet *</label>
                    <input type="text" name="nom" required placeholder="Maman, Papa, Médecin...">
                </div>
                <div class="form-group">
                    <label>🤝 Relation</label>
                    <input type="text" name="relation" placeholder="Père, Mère...">
                </div>
                <div class="form-group">
                    <label>📞 Numéro de téléphone *</label>
                    <input type="tel" name="telephone" required placeholder="06 12 34 56 78">
                </div>
                <button type="submit" name="add_contact" class="btn-save">➕ Ajouter</button>
            </form>
        </div>
 
        <div class="sos-test-card">
            <h3><i class="fas fa-exclamation-triangle"></i> ALERTE D'URGENCE</h3>
            <p id="positionStatus">📍 Récupération de votre position GPS...</p>
            <button id="testSosButton" class="btn-sos">🚨 ENVOYER MON ALERTE SMS 🚨</button>
            <div id="sosResult" style="margin-top:1rem;display:none;"></div>
        </div>
    </div>
 
    <footer class="footer">
        <p>© 2024 NutriFlow AI - Alerte médicale</p>
    </footer>
 
    <script>
        // === MODE SOMBRE ===
        var darkModeToggle = document.getElementById('darkModeToggle');
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
            darkModeToggle.innerHTML = '☀️ Mode clair';
        }
        darkModeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
                darkModeToggle.innerHTML = '☀️ Mode clair';
            } else {
                localStorage.setItem('darkMode', 'disabled');
                darkModeToggle.innerHTML = '🌙 Mode sombre';
            }
        });
 
        // === VARIABLES GLOBALES ===
        var currentLat = null;
        var currentLng = null;
        var map = null;
        var marker = null;
 
        // === SAUVEGARDER LA POSITION ===
        function sauvegarderPosition(lat, lng, accuracy) {
            sessionStorage.setItem('lastLat', lat);
            sessionStorage.setItem('lastLng', lng);
            sessionStorage.setItem('lastAccuracy', accuracy);
            sessionStorage.setItem('lastPositionTime', Date.now());
        }
 
        // === CHARGER LA POSITION SAUVEGARDÉE ===
        function chargerPositionSauvegardee() {
            var lat = sessionStorage.getItem('lastLat');
            var lng = sessionStorage.getItem('lastLng');
            var accuracy = sessionStorage.getItem('lastAccuracy');
            var timestamp = sessionStorage.getItem('lastPositionTime');
            
            if (lat && lng && timestamp) {
                var age = Date.now() - parseInt(timestamp);
                // Position valide pendant 30 minutes
                if (age < 1800000) {
                    currentLat = parseFloat(lat);
                    currentLng = parseFloat(lng);
                    initMap(currentLat, currentLng);
                    document.getElementById('positionStatus').innerHTML = '✅ Position GPS - Précision: ±' + accuracy + ' mètres';
                    return true;
                }
            }
            return false;
        }
 
        // === INITIALISATION CARTE ===
        function initMap(lat, lng) {
            if (map === null) {
                map = L.map('map').setView([lat, lng], 15);
                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a>'
                }).addTo(map);
            } else {
                map.setView([lat, lng], 15);
            }
            
            if (marker !== null) {
                map.removeLayer(marker);
            }
            marker = L.marker([lat, lng]).addTo(map);
            marker.bindPopup("<b>📍 Votre position actuelle</b><br>Précise au mètre près").openPopup();
        }
 
        // === GPS AVEC HAUTE PRÉCISION ===
        function getRealPosition() {
            var statusDiv = document.getElementById('positionStatus');
            
            // D'abord, essayer de charger la position sauvegardée
            if (chargerPositionSauvegardee()) {
                return;
            }
            
            if (!navigator.geolocation) {
                statusDiv.innerHTML = '❌ Votre navigateur ne supporte pas la géolocalisation';
                return;
            }
            
            statusDiv.innerHTML = '📍 Recherche de votre position GPS réelle... (haute précision)';
            
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    currentLat = position.coords.latitude;
                    currentLng = position.coords.longitude;
                    var accuracy = Math.round(position.coords.accuracy);
                    
                    // Sauvegarder la position
                    sauvegarderPosition(currentLat, currentLng, accuracy);
                    
                    statusDiv.innerHTML = '✅ Position GPS trouvée ! Précision : ±' + accuracy + ' mètres (sauvegardée)';
                    initMap(currentLat, currentLng);
                    console.log('Position réelle:', currentLat, currentLng);
                },
                function(error) {
                    var msg = '';
                    switch(error.code) {
                        case 1: msg = '❌ Vous avez refusé la localisation. Cliquez sur 🔒 et autorisez.';
                            break;
                        case 2: msg = '❌ Position non disponible. Vérifiez votre connexion.';
                            break;
                        case 3: msg = '❌ Délai dépassé. Réessayez.';
                            break;
                        default: msg = '❌ Erreur inconnue';
                    }
                    statusDiv.innerHTML = msg;
                    // Position par défaut (Tunis) si pas de sauvegarde
                    if (!currentLat) {
                        currentLat = 36.8065;
                        currentLng = 10.1815;
                        initMap(36.8065, 10.1815);
                    }
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        }
 
        // === LANCER GPS AU CHARGEMENT ===
        getRealPosition();
 
        // === BOUTON SOS ===
        var sosButton = document.getElementById('testSosButton');
        if (sosButton) {
            sosButton.addEventListener('click', function() {
                var resultDiv = document.getElementById('sosResult');
                resultDiv.style.display = 'block';
                
                if (!currentLat || !currentLng) {
                    resultDiv.innerHTML = '<div class="result-box result-error">❌ Position GPS non disponible. Attendez quelques secondes.</div>';
                    getRealPosition();
                    setTimeout(function() {
                        if (currentLat && currentLng) {
                            envoyerAlerte();
                        }
                    }, 3000);
                    return;
                }
                
                envoyerAlerte();
                
                function envoyerAlerte() {
                    resultDiv.innerHTML = '<div class="loading-spinner"></div><p>📤 Envoi de l\'alerte avec votre position réelle...</p>';
                    
                    fetch('/EspritNutriFlowMVC/Controller/send_sos.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ latitude: currentLat, longitude: currentLng })
                    })
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        if (data.success) {
                            resultDiv.innerHTML = '<div class="result-box result-success">'
                                + '<strong>✅ ALERTE ENVOYÉE AVEC SUCCÈS !</strong><br><br>'
                                + '📍 <strong>VOTRE POSITION RÉELLE :</strong><br>'
                                + 'Latitude: ' + data.latitude + '<br>'
                                + 'Longitude: ' + data.longitude + '<br>'
                                + '🗺️ <a href="' + data.maps_link + '" target="_blank">Ouvrir dans Google Maps</a><br><br>'
                                + '📱 SMS envoyé à vos contacts<br><br>'
                                + '⚠️ <strong>N\'oubliez pas d\'appeler le 15 si besoin !</strong>'
                                + '</div>';
                        } else {
                            resultDiv.innerHTML = '<div class="result-box result-error">❌ ' + data.message + '</div>';
                        }
                    })
                    .catch(function(err) {
                        resultDiv.innerHTML = '<div class="result-box result-error">❌ Erreur: ' + err.message + '</div>';
                    });
                }
            });
        }
    </script>
</body>
</html>