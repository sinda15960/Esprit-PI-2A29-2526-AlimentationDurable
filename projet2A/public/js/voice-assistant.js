// Voice Assistant - NutriFlow AI
class VoiceAssistant {
    constructor() {
        this.isListening = false;
        this.recognition = null;
        this.settings = {
            voiceGender: 'female',
            voiceRate: 1.0,
            voicePitch: 1.0,
            voiceVolume: 1.0,
            enabled: true
        };
        this.voices = [];
        
        this.init();
    }
    
    async init() {
        // Charger les paramètres
        await this.loadSettings();
        
        // Initialiser la reconnaissance vocale
        this.initSpeechRecognition();
        
        // Charger les voix disponibles
        this.loadVoices();
        
        // Ajouter l'écouteur de raccourci clavier
        this.initKeyboardShortcut();
        
        console.log('🎙️ Assistant vocal prêt - Appuyez sur Ctrl+Shift+V');
    }
    
    async loadSettings() {
        try {
            const response = await fetch('index.php?action=getVoiceSettings');
            const settings = await response.json();
            this.settings = { ...this.settings, ...settings };
        } catch(e) {
            console.error('Erreur chargement paramètres:', e);
        }
    }
    
    initSpeechRecognition() {
        if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
            console.warn('Reconnaissance vocale non supportée');
            return;
        }
        
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        this.recognition = new SpeechRecognition();
        this.recognition.lang = 'fr-FR';
        this.recognition.continuous = false;
        this.recognition.interimResults = false;
        this.recognition.maxAlternatives = 1;
        
        this.recognition.onstart = () => {
            this.isListening = true;
            this.showVoiceFeedback('Écoute...', 'listening');
        };
        
        this.recognition.onend = () => {
            this.isListening = false;
            this.hideVoiceFeedback();
        };
        
        this.recognition.onresult = (event) => {
            const command = event.results[0][0].transcript;
            this.processCommand(command);
        };
        
        this.recognition.onerror = (event) => {
            console.error('Erreur reconnaissance:', event.error);
            this.speak("Désolé, je n'ai pas pu comprendre", 'error');
            this.hideVoiceFeedback();
        };
    }
    
    initKeyboardShortcut() {
        document.addEventListener('keydown', (e) => {
            // Ctrl + Shift + V
            if (e.ctrlKey && e.shiftKey && e.key === 'V') {
                e.preventDefault();
                this.startListening();
            }
        });
    }
    
    startListening() {
        if (!this.recognition) {
            this.speak("La reconnaissance vocale n'est pas supportée par votre navigateur", 'error');
            return;
        }
        
        if (this.isListening) {
            this.recognition.stop();
        }
        
        try {
            this.recognition.start();
        } catch(e) {
            console.error('Erreur démarrage:', e);
        }
    }
    
    async processCommand(command) {
        console.log('Commande reçue:', command);
        this.showVoiceFeedback('Traitement...', 'processing');
        
        try {
            const response = await fetch('index.php?action=processCommand', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'command=' + encodeURIComponent(command)
            });
            
            const data = await response.json();
            
            // Parler la réponse
            this.speak(data.message, data.voice_type);
            
            // Exécuter l'action
            if (data.action === 'redirect' && data.url) {
                setTimeout(() => {
                    window.location.href = data.url;
                }, 1000);
            }
            else if (data.action === 'sendReport') {
                if (typeof sendWeeklyReport === 'function') {
                    sendWeeklyReport();
                }
            }
            else if (data.action === 'deleteRecipe' && data.recipe_id) {
                if (confirm(data.message + ' Confirmez-vous ?')) {
                    // Logique de suppression
                    window.location.href = 'index.php?action=backDeleteRecipe&id=' + data.recipe_id;
                }
            }
            
            this.hideVoiceFeedback();
        } catch(e) {
            console.error('Erreur:', e);
            this.speak("Erreur lors du traitement de la commande", 'error');
            this.hideVoiceFeedback();
        }
    }
    
    loadVoices() {
        if ('speechSynthesis' in window) {
            const loadVoicesHandler = () => {
                this.voices = window.speechSynthesis.getVoices();
                console.log('Voix chargées:', this.voices.length);
            };
            
            window.speechSynthesis.onvoiceschanged = loadVoicesHandler;
            loadVoicesHandler();
        }
    }
    
    speak(text, type = 'neutral') {
        if (!this.settings.enabled) return;
        if (!('speechSynthesis' in window)) return;
        
        // Annuler toute parole en cours
        window.speechSynthesis.cancel();
        
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'fr-FR';
        
        // Ajuster les paramètres selon le type d'alerte
        switch(type) {
            case 'success':
                utterance.rate = Math.min(1.2, this.settings.voiceRate + 0.2);
                utterance.pitch = Math.min(1.3, this.settings.voicePitch + 0.2);
                utterance.volume = this.settings.voiceVolume;
                break;
            case 'error':
                utterance.rate = Math.max(0.7, this.settings.voiceRate - 0.2);
                utterance.pitch = Math.max(0.7, this.settings.voicePitch - 0.2);
                utterance.volume = this.settings.voiceVolume;
                break;
            case 'warning':
                utterance.rate = this.settings.voiceRate;
                utterance.pitch = this.settings.voicePitch + 0.1;
                utterance.volume = this.settings.voiceVolume;
                break;
            default: // neutral
                utterance.rate = this.settings.voiceRate;
                utterance.pitch = this.settings.voicePitch;
                utterance.volume = this.settings.voiceVolume;
        }
        
        // Sélectionner la voix selon le genre
        if (this.voices.length > 0) {
            const voiceLang = 'fr';
            let selectedVoice = this.voices.find(v => 
                v.lang.includes(voiceLang) && 
                (this.settings.voiceGender === 'female' ? v.name.includes('Google') || v.name.includes('Samantha') : v.name.includes('Microsoft'))
            );
            
            if (!selectedVoice) {
                selectedVoice = this.voices.find(v => v.lang.includes(voiceLang));
            }
            
            if (selectedVoice) {
                utterance.voice = selectedVoice;
            }
        }
        
        window.speechSynthesis.speak(utterance);
    }
    
    showVoiceFeedback(message, type) {
        let feedbackDiv = document.getElementById('voice-feedback');
        if (!feedbackDiv) {
            feedbackDiv = document.createElement('div');
            feedbackDiv.id = 'voice-feedback';
            document.body.appendChild(feedbackDiv);
        }
        
        feedbackDiv.innerHTML = `
            <div class="voice-feedback ${type}">
                <i class="fas fa-microphone-alt"></i>
                <span>${message}</span>
                <div class="voice-wave">
                    <span></span><span></span><span></span><span></span>
                </div>
            </div>
        `;
        feedbackDiv.style.display = 'flex';
    }
    
    hideVoiceFeedback() {
        const feedbackDiv = document.getElementById('voice-feedback');
        if (feedbackDiv) {
            feedbackDiv.style.display = 'none';
        }
    }
}

// Initialiser l'assistant vocal au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    window.voiceAssistant = new VoiceAssistant();
});

// Fonction globale pour parler depuis d'autres scripts
window.speak = function(text, type = 'neutral') {
    if (window.voiceAssistant) {
        window.voiceAssistant.speak(text, type);
    }
};