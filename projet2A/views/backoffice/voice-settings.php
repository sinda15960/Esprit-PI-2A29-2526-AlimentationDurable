<div class="settings-container">
    <h1><i class="fas fa-microphone-alt"></i> Paramètres vocaux</h1>
    
    <div class="settings-card">
        <h3>🎤 Assistant vocal</h3>
        
        <div class="setting-group">
            <label>
                <input type="checkbox" id="voiceEnabled" <?php echo $settings['enabled'] ? 'checked' : ''; ?>>
                Activer l'assistant vocal
            </label>
        </div>
        
        <div class="setting-group">
            <label>Genre de voix :</label>
            <select id="voiceGender">
                <option value="female" <?php echo $settings['voice_gender'] === 'female' ? 'selected' : ''; ?>>👩 Féminine</option>
                <option value="male" <?php echo $settings['voice_gender'] === 'male' ? 'selected' : ''; ?>>👨 Masculine</option>
            </select>
        </div>
        
        <div class="setting-group">
            <label>Vitesse : <?php echo $settings['voice_rate']; ?></label>
            <input type="range" id="voiceRate" min="0.5" max="1.5" step="0.1" value="<?php echo $settings['voice_rate']; ?>">
        </div>
        
        <div class="setting-group">
            <label>Hauteur : <?php echo $settings['voice_pitch']; ?></label>
            <input type="range" id="voicePitch" min="0.5" max="1.5" step="0.1" value="<?php echo $settings['voice_pitch']; ?>">
        </div>
        
        <div class="setting-group">
            <label>Volume : <?php echo $settings['voice_volume']; ?></label>
            <input type="range" id="voiceVolume" min="0" max="1" step="0.1" value="<?php echo $settings['voice_volume']; ?>">
        </div>
        
        <button class="btn-test-voice" onclick="testVoice()">
            <i class="fas fa-play"></i> Tester la voix
        </button>
    </div>
    
    <div class="commands-card">
        <h3>📋 Commandes disponibles</h3>
        <ul>
            <li><kbd>🎙️</kbd> "Ajouter une recette"</li>
            <li><kbd>🎙️</kbd> "Liste des recettes"</li>
            <li><kbd>🎙️</kbd> "Envoyer le rapport"</li>
            <li><kbd>🎙️</kbd> "Ouvrir les catégories"</li>
            <li><kbd>🎙️</kbd> "Supprimer la recette [numéro]"</li>
            <li><kbd>🎙️</kbd> "Aide"</li>
        </ul>
        <p class="shortcut-hint">
            <i class="fas fa-keyboard"></i> Raccourci : <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>V</kbd>
        </p>
    </div>
</div>

<style>
.settings-container {
    max-width: 600px;
    margin: 0 auto;
}
.settings-card, .commands-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.setting-group {
    margin-bottom: 20px;
}
.setting-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
}
input[type="range"] {
    width: 100%;
}
.btn-test-voice {
    background: #3498db;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
}
.commands-card ul {
    list-style: none;
    padding: 0;
}
.commands-card li {
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}
kbd {
    background: #f0f0f0;
    padding: 3px 8px;
    border-radius: 5px;
    font-family: monospace;
}
.shortcut-hint {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #eee;
    text-align: center;
}
</style>

<script>
function testVoice() {
    const settings = {
        voiceGender: document.getElementById('voiceGender').value,
        voiceRate: parseFloat(document.getElementById('voiceRate').value),
        voicePitch: parseFloat(document.getElementById('voicePitch').value),
        voiceVolume: parseFloat(document.getElementById('voiceVolume').value),
        enabled: document.getElementById('voiceEnabled').checked
    };
    
    if (window.voiceAssistant) {
        window.voiceAssistant.settings = settings;
        window.voiceAssistant.speak("Bonjour ! Ceci est un test de ma voix. Les paramètres sont bien configurés.", 'success');
    }
}

document.getElementById('voiceRate').addEventListener('input', function() {
    document.querySelector('#voiceRate + label').innerHTML = 'Vitesse : ' + this.value;
});
document.getElementById('voicePitch').addEventListener('input', function() {
    document.querySelector('#voicePitch + label').innerHTML = 'Hauteur : ' + this.value;
});
document.getElementById('voiceVolume').addEventListener('input', function() {
    document.querySelector('#voiceVolume + label').innerHTML = 'Volume : ' + this.value;
});

document.getElementById('voiceEnabled').addEventListener('change', async function() {
    const settings = {
        voice_gender: document.getElementById('voiceGender').value,
        voice_rate: parseFloat(document.getElementById('voiceRate').value),
        voice_pitch: parseFloat(document.getElementById('voicePitch').value),
        voice_volume: parseFloat(document.getElementById('voiceVolume').value),
        enabled: this.checked ? 1 : 0
    };
    
    const formData = new URLSearchParams(settings);
    const response = await fetch('index.php?action=updateVoiceSettings', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: formData
    });
    
    const data = await response.json();
    if (data.success && window.voiceAssistant) {
        window.voiceAssistant.settings.enabled = settings.enabled;
        if (settings.enabled) {
            window.voiceAssistant.speak("Assistant vocal activé", 'success');
        }
    }
});
</script>