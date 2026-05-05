<style>
    .sos-button-container {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 9999;
    }
    .sos-button {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(244,67,54,0.5);
        transition: all 0.3s ease;
        animation: pulse-sos 2s infinite;
        border: none;
    }
    .sos-button:hover { transform: scale(1.1); box-shadow: 0 6px 30px rgba(244,67,54,0.7); }
    @keyframes pulse-sos {
        0%   { box-shadow: 0 0 0 0 rgba(244,67,54,0.7); }
        70%  { box-shadow: 0 0 0 15px rgba(244,67,54,0); }
        100% { box-shadow: 0 0 0 0 rgba(244,67,54,0); }
    }
    .sos-button span { font-size: 2.5rem; font-weight: bold; color: white; }
    .sos-modal {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.85);
        z-index: 10000;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(5px);
    }
    .sos-modal.active { display: flex; }
    .sos-modal-content {
        background: white;
        border-radius: 30px;
        max-width: 500px;
        width: 90%;
        overflow: hidden;
        animation: slideIn 0.3s ease;
    }
    @keyframes slideIn { from { transform: translateY(-50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .sos-modal-header {
        background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
        color: white; padding: 1.5rem; text-align: center;
    }
    .sos-modal-header h2 { font-size: 1.8rem; }
    .sos-modal-body { padding: 1.5rem; text-align: center; }
    .sos-modal-footer { padding: 1rem; display: flex; gap: 1rem; justify-content: center; border-top: 1px solid #eee; }
    .btn-sos-confirm { background: #f44336; color: white; border: none; padding: 12px 25px; border-radius: 50px; font-weight: 600; cursor: pointer; }
    .btn-sos-cancel { background: #999; color: white; border: none; padding: 12px 25px; border-radius: 50px; font-weight: 600; cursor: pointer; }
    .loading-spinner { display: inline-block; width: 30px; height: 30px; border: 3px solid #f3f3f3; border-top: 3px solid #f44336; border-radius: 50%; animation: spin 1s linear infinite; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    .geo-warning { background: #fff3cd; border: 1px solid #ffc107; border-radius: 10px; padding: 0.6rem; margin-top: 0.5rem; font-size: 0.78rem; color: #856404; }
</style>
 
<div class="sos-button-container">
    <button class="sos-button" onclick="openSOSModal()"><span>SOS</span></button>
</div>
 
<div id="sosModal" class="sos-modal">
    <div class="sos-modal-content">
        <div class="sos-modal-header"><h2>🆘 ALERTE MÉDICALE 🆘</h2><p>Réaction allergique sévère ?</p></div>
        <div class="sos-modal-body" id="sosModalBody">
            <p style="margin-bottom:1rem;">⚠️ Cette action enverra votre position à vos contacts d'urgence.</p>
            <p style="color:#f44336;font-weight:bold;">Vérifiez que vous avez besoin de secours !</p>
            <div class="geo-warning">ℹ️ Sur PC/laptop, la position est basée sur votre réseau WiFi/IP.<br>Pour une précision maximale, utilisez un smartphone avec GPS activé.</div>
        </div>
        <div class="sos-modal-footer" id="sosModalFooter">
            <button class="btn-sos-cancel" onclick="closeSOSModal()">Annuler</button>
            <button class="btn-sos-confirm" onclick="sendSOSAlert()">🆘 ENVOYER ALERTE</button>
        </div>
    </div>
</div>
 
<script>
    function openSOSModal() { document.getElementById('sosModal').classList.add('active'); }
    function closeSOSModal() { document.getElementById('sosModal').classList.remove('active'); }
 
    function obtenirPositionParIP(callback) {
        fetch('https://ipapi.co/json/')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.latitude && data.longitude) {
                    callback({ coords: { latitude: data.latitude, longitude: data.longitude, accuracy: 1000 } });
                } else {
                    callback({ coords: { latitude: 36.8065, longitude: 10.1815, accuracy: 5000 } });
                }
            })
            .catch(function() {
                callback({ coords: { latitude: 36.8065, longitude: 10.1815, accuracy: 5000 } });
            });
    }
 
    function obtenirMeilleurePosition(callback) {
        if (!navigator.geolocation) { obtenirPositionParIP(callback); return; }
        navigator.geolocation.getCurrentPosition(
            function(pos) { callback(pos); },
            function() { obtenirPositionParIP(callback); },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    }
 
    function sendSOSAlert() {
        var modalBody = document.getElementById('sosModalBody');
        var modalFooter = document.getElementById('sosModalFooter');
        modalBody.innerHTML = '<div class="loading-spinner"></div><p style="margin-top:1rem;">📡 Détection de votre position...</p>';
 
        obtenirMeilleurePosition(function(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            var acc = Math.round(position.coords.accuracy);
            modalBody.innerHTML = '<div class="loading-spinner"></div><p>📍 Position détectée (±' + acc + 'm)<br>Envoi...</p>';
 
            fetch('../../Controller/send_sos.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ latitude: lat, longitude: lng, accuracy: acc })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var precisionLabel = acc < 100 ? '🟢 Bonne (±' + acc + 'm)' : (acc < 1000 ? '🟡 Moyenne (±' + acc + 'm)' : '🔴 Approximative (±' + acc + 'm)');
                if (data.success) {
                    modalBody.innerHTML = '<div style="background:#d4edda;padding:1rem;border-radius:12px;"><p>✅ <strong>ALERTE ENVOYÉE !</strong></p><p>📍 Position envoyée.</p><p>Précision : ' + precisionLabel + '</p><p><a href="' + data.maps_link + '" target="_blank" style="color:#4caf50;">🗺️ Voir sur Google Maps</a></p><p style="font-size:0.8rem;margin-top:0.5rem;">⚠️ N\'oubliez pas d\'appeler le 15 !</p></div>';
                    modalFooter.style.display = 'none';
                    setTimeout(function() { closeSOSModal(); }, 5000);
                } else {
                    modalBody.innerHTML = '<p>❌ ' + data.message + '</p>';
                }
            })
            .catch(function(err) { modalBody.innerHTML = '<p>❌ Erreur : ' + err.message + '</p>'; });
        });
    }
</script>