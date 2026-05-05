// Voice AI pour lister le contenu du frigo

let speechSynthesis = window.speechSynthesis;
let isSpeaking = false;
let currentUtterance = null;

function initVoiceFrigo() {
    if (!speechSynthesis) {
        console.log('Synthèse vocale non supportée');
        const statusSpan = document.getElementById('voice-lister-status');
        if (statusSpan) {
            statusSpan.innerHTML = '⚠️ Synthèse vocale non supportée par ce navigateur';
            statusSpan.style.color = '#c0392b';
        }
        return false;
    }
    return true;
}

function listerFrigoParVoix() {
    const btn = document.getElementById('voice-lister-btn');
    const statusSpan = document.getElementById('voice-lister-status');
    
    if (!btn) return;
    
    // Si en train de lire, arrêter
    if (isSpeaking) {
        arreterLecture();
        return;
    }
    
    // Désactiver le bouton pendant le chargement
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Analyse...';
    
    if (statusSpan) {
        statusSpan.innerHTML = '🔄 Analyse du contenu du frigo...';
        statusSpan.style.color = '#f0a500';
    }
    
    fetch('/frigo/index.php?mode=front&controller=produit&action=listerFrigoParVoix', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (statusSpan) {
                statusSpan.innerHTML = '🎤 Lecture du contenu...';
                statusSpan.style.color = '#2d6a2d';
            }
            
            // Lire le texte
            lireTexte(data.texte);
            
            // Afficher un toast ou notification si le frigo est vide
            if (data.total_items === 0) {
                showEmptyFridgeNotification();
            }
        } else {
            if (statusSpan) {
                statusSpan.innerHTML = '❌ ' + (data.message || 'Erreur lors de la récupération');
                statusSpan.style.color = '#c0392b';
            }
            btn.disabled = false;
            btn.innerHTML = '🎤 Lister le contenu';
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        if (statusSpan) {
            statusSpan.innerHTML = '❌ Erreur de connexion au serveur';
            statusSpan.style.color = '#c0392b';
        }
        btn.disabled = false;
        btn.innerHTML = '🎤 Lister le contenu';
    });
}

function lireTexte(texte) {
    if (speechSynthesis.speaking) {
        speechSynthesis.cancel();
    }
    
    currentUtterance = new SpeechSynthesisUtterance(texte);
    currentUtterance.lang = 'fr-FR';
    currentUtterance.rate = 0.85;
    currentUtterance.pitch = 1.0;
    currentUtterance.volume = 1;
    
    currentUtterance.onstart = function() {
        isSpeaking = true;
        const statusSpan = document.getElementById('voice-lister-status');
        if (statusSpan) {
            statusSpan.innerHTML = '🔊 Lecture en cours...';
            statusSpan.style.color = '#2d6a2d';
        }
        const btn = document.getElementById('voice-lister-btn');
        if (btn) {
            btn.innerHTML = '⏹️ Arrêter';
            btn.disabled = false;
        }
        const stopBtn = document.getElementById('voice-stop-btn');
        if (stopBtn) {
            stopBtn.style.display = 'inline-block';
        }
    };
    
    currentUtterance.onend = function() {
        isSpeaking = false;
        const statusSpan = document.getElementById('voice-lister-status');
        if (statusSpan) {
            statusSpan.innerHTML = '✅ Lecture terminée';
            setTimeout(() => {
                if (statusSpan) statusSpan.innerHTML = '🎤 Cliquez pour lister le contenu';
                statusSpan.style.color = '#6c757d';
            }, 3000);
        }
        const btn = document.getElementById('voice-lister-btn');
        if (btn) {
            btn.innerHTML = '🎤 Lister le contenu';
        }
        const stopBtn = document.getElementById('voice-stop-btn');
        if (stopBtn) {
            stopBtn.style.display = 'none';
        }
    };
    
    currentUtterance.onerror = function(event) {
        console.error('Erreur de synthèse vocale:', event);
        isSpeaking = false;
        const statusSpan = document.getElementById('voice-lister-status');
        if (statusSpan) {
            statusSpan.innerHTML = '❌ Erreur de lecture';
            statusSpan.style.color = '#c0392b';
        }
        const btn = document.getElementById('voice-lister-btn');
        if (btn) {
            btn.innerHTML = '🎤 Lister le contenu';
            btn.disabled = false;
        }
    };
    
    speechSynthesis.speak(currentUtterance);
}

function arreterLecture() {
    if (speechSynthesis.speaking) {
        speechSynthesis.cancel();
        isSpeaking = false;
        const statusSpan = document.getElementById('voice-lister-status');
        if (statusSpan) {
            statusSpan.innerHTML = '⏹️ Lecture arrêtée';
            setTimeout(() => {
                if (statusSpan) statusSpan.innerHTML = '🎤 Cliquez pour lister le contenu';
            }, 2000);
        }
        const btn = document.getElementById('voice-lister-btn');
        if (btn) {
            btn.innerHTML = '🎤 Lister le contenu';
            btn.disabled = false;
        }
        const stopBtn = document.getElementById('voice-stop-btn');
        if (stopBtn) {
            stopBtn.style.display = 'none';
        }
    }
}

function showEmptyFridgeNotification() {
    // Créer une notification flottante
    const notification = document.createElement('div');
    notification.className = 'alert alert-info alert-dismissible fade show position-fixed bottom-0 end-0 m-3';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.innerHTML = `
        <strong>🍽️ Votre frigo est vide !</strong><br>
        📦 Cliquez sur "Supermarché" dans le menu pour commander des aliments.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(notification);
    
    // Disparaître après 5 secondes
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    initVoiceFrigo();
    
    const stopBtn = document.getElementById('voice-stop-btn');
    if (stopBtn) {
        stopBtn.addEventListener('click', arreterLecture);
        stopBtn.style.display = 'none';
    }
});