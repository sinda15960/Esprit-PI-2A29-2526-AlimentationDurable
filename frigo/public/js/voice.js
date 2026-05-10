// Reconnaissance vocale pour l'ajout d'aliments au frigo

let recognition = null;
let isListening = false;

function initVoiceRecognition() {
    if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
        document.getElementById('voice-status').innerHTML = '⚠️ Votre navigateur ne supporte pas la reconnaissance vocale. Utilisez Chrome ou Edge.';
        document.getElementById('voice-status').style.color = '#c0392b';
        return false;
    }
    
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    recognition = new SpeechRecognition();
    recognition.lang = 'fr-FR';
    recognition.continuous = false;
    recognition.interimResults = false;
    recognition.maxAlternatives = 1;
    
    recognition.onstart = function() {
        isListening = true;
        document.getElementById('voice-status').innerHTML = '🎤 Écoute en cours... Parlez maintenant';
        document.getElementById('voice-status').style.color = '#f0a500';
        document.getElementById('voice-btn').classList.remove('btn-danger');
        document.getElementById('voice-btn').classList.add('btn-warning');
    };
    
    recognition.onend = function() {
        isListening = false;
        document.getElementById('voice-status').innerHTML = '🎤 Cliquez sur le micro pour parler';
        document.getElementById('voice-status').style.color = '#6c757d';
        document.getElementById('voice-btn').classList.remove('btn-warning');
        document.getElementById('voice-btn').classList.add('btn-danger');
    };
    
    recognition.onresult = function(event) {
        const transcript = event.results[0][0].transcript;
        const confidence = event.results[0][0].confidence;
        
        document.getElementById('voice-status').innerHTML = '📝 Traitement: "' + transcript + '"';
        document.getElementById('voice-status').style.color = '#2d6a2d';
        
        // Envoyer au serveur pour vérification
        fetch('index.php?mode=front&controller=produit&action=ajouterParVoix', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'nom_aliment=' + encodeURIComponent(transcript)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Afficher le modal de confirmation
                showVoiceConfirmation(data.produit, transcript);
                document.getElementById('voice-status').innerHTML = '✅ Aliment trouvé ! Confirmation nécessaire.';
                document.getElementById('voice-status').style.color = '#2d6a2d';
            } else {
                document.getElementById('voice-status').innerHTML = '❌ ' + data.message;
                document.getElementById('voice-status').style.color = '#c0392b';
                
                // Proposer l'ajout manuel
                if (confirm(data.message + '\nVoulez-vous l\'ajouter manuellement ?')) {
                    // Ouvrir le modal d'ajout manuel et pré-remplir le nom
                    const modalAjout = new bootstrap.Modal(document.getElementById('modalAjout'));
                    document.getElementById('m-nom').value = data.nom_saisi || transcript;
                    // Déclencher l'événement input pour l'emoji
                    const event = new Event('input');
                    document.getElementById('m-nom').dispatchEvent(event);
                    modalAjout.show();
                }
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            document.getElementById('voice-status').innerHTML = '❌ Erreur lors du traitement: ' + error.message;
            document.getElementById('voice-status').style.color = '#c0392b';
        });
    };
    
    recognition.onerror = function(event) {
        console.error('Erreur reconnaissance:', event.error);
        let message = '';
        switch(event.error) {
            case 'not-allowed':
                message = 'Permission microphone refusée.';
                break;
            case 'no-speech':
                message = 'Aucune parole détectée.';
                break;
            case 'network':
                message = 'Erreur réseau.';
                break;
            default:
                message = 'Erreur: ' + event.error;
        }
        document.getElementById('voice-status').innerHTML = '❌ ' + message;
        document.getElementById('voice-status').style.color = '#c0392b';
        isListening = false;
        document.getElementById('voice-btn').classList.remove('btn-warning');
        document.getElementById('voice-btn').classList.add('btn-danger');
    };
    
    return true;
}

function startVoiceRecognition() {
    if (!recognition) {
        if (!initVoiceRecognition()) return;
    }
    
    if (isListening) {
        recognition.stop();
    } else {
        try {
            recognition.start();
        } catch(e) {
            console.error('Erreur démarrage:', e);
            document.getElementById('voice-status').innerHTML = '❌ Impossible de démarrer le microphone. Vérifiez les permissions.';
        }
    }
}

function showVoiceConfirmation(produit, texteOriginal) {
    // Supprimer l'ancien modal s'il existe
    const oldModal = document.getElementById('voiceConfirmModal');
    if (oldModal) oldModal.remove();
    
    // Créer le modal HTML
    const modalHtml = `
        <div class="modal fade" id="voiceConfirmModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">🎤 Confirmation vocale</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Vous avez dit : <strong class="text-success">"${texteOriginal}"</strong></p>
                        <div class="text-center my-3">
                            <span style="font-size:4rem">${produit.emoji}</span>
                        </div>
                        <p class="fw-bold text-center">${produit.nom}</p>
                        <p class="text-muted text-center small">Prix: ${parseFloat(produit.prix).toFixed(2)} TND</p>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Quantité :</label>
                            <input type="number" id="voice-quantite" class="form-control" value="1" min="1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-success" onclick="confirmVoiceAdd(${produit.id})">Confirmer l'ajout</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Ajouter le modal au body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Afficher le modal
    const modal = new bootstrap.Modal(document.getElementById('voiceConfirmModal'));
    modal.show();
}

function confirmVoiceAdd(produitId) {
    const quantite = document.getElementById('voice-quantite').value;
    
    // Fermer le modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('voiceConfirmModal'));
    if (modal) modal.hide();
    
    // Envoyer la requête d'ajout
    fetch('index.php?mode=front&controller=produit&action=confirmerAjoutVoix', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'produit_id=' + produitId + '&quantite=' + quantite
    })
    .then(response => {
        if (response.redirected) {
            window.location.href = response.url;
        } else {
            window.location.href = 'index.php?mode=front&controller=produit&action=frigo';
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        window.location.href = 'index.php?mode=front&controller=produit&action=frigo';
    });
}

// Initialiser la voix quand la page est chargée
document.addEventListener('DOMContentLoaded', function() {
    const voiceBtn = document.getElementById('voice-btn');
    if (voiceBtn) {
        voiceBtn.addEventListener('click', startVoiceRecognition);
        initVoiceRecognition();
    }
});