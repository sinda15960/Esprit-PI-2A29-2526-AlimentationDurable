<?php 
require_once dirname(__DIR__, 3) . '/header.php'; 
?>

<style>
.coach-container { max-width: 850px; margin: 0 auto; }

.coach-header {
    background: linear-gradient(135deg, #2d5a27, #4a8f3f);
    border-radius: 16px; padding: 24px 30px;
    color: #fff; margin-bottom: 24px;
    display: flex; align-items: center; gap: 20px;
}
.coach-avatar {
    width: 70px; height: 70px;
    background: rgba(255,255,255,0.2); border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.2rem; flex-shrink: 0;
}
.coach-header h2 { margin: 0; font-size: 1.5rem; font-weight: 700; }
.coach-header p  { margin: 4px 0 0; opacity: .85; font-size: .9rem; }

.chat-box {
    background: #fff; border-radius: 16px;
    box-shadow: 0 2px 16px rgba(0,0,0,.08);
    height: 480px; overflow-y: auto;
    padding: 24px; display: flex;
    flex-direction: column; gap: 16px; margin-bottom: 16px;
}

.msg { display: flex; gap: 12px; align-items: flex-end; max-width: 80%; }
.msg.user  { align-self: flex-end; flex-direction: row-reverse; }
.msg.coach { align-self: flex-start; }

.msg-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.msg.coach .msg-avatar { background: #2d5a27; color: #fff; }
.msg.user  .msg-avatar { background: #e9ecef; }

.msg-bubble {
    padding: 12px 16px; border-radius: 18px;
    font-size: .92rem; line-height: 1.6; max-width: 100%;
}
.msg.coach .msg-bubble { background: #f0f7ee; border-bottom-left-radius: 4px; color: #1a1a1a; }
.msg.user  .msg-bubble { background: #2d5a27; color: #fff; border-bottom-right-radius: 4px; }

.typing-indicator { display: none; align-self: flex-start; gap: 12px; align-items: flex-end; }
.typing-indicator.visible { display: flex; }
.typing-dots {
    background: #f0f7ee; border-radius: 18px; border-bottom-left-radius: 4px;
    padding: 14px 18px; display: flex; gap: 5px; align-items: center;
}
.typing-dots span {
    width: 8px; height: 8px; background: #2d5a27;
    border-radius: 50%; animation: bounce 1.2s infinite;
}
.typing-dots span:nth-child(2) { animation-delay: .2s; }
.typing-dots span:nth-child(3) { animation-delay: .4s; }
@keyframes bounce {
    0%, 60%, 100% { transform: translateY(0); }
    30% { transform: translateY(-8px); }
}

.chat-input-area {
    background: #fff; border-radius: 16px;
    box-shadow: 0 2px 16px rgba(0,0,0,.08);
    padding: 16px; display: flex; gap: 12px; align-items: flex-end;
}
#userInput {
    flex: 1; border: 2px solid #e9ecef; border-radius: 12px;
    padding: 12px 16px; font-size: .92rem; font-family: inherit;
    resize: none; min-height: 48px; max-height: 120px;
    transition: border-color .2s; outline: none;
}
#userInput:focus { border-color: #2d5a27; }
#btnEnvoyer {
    background: #2d5a27; color: #fff; border: none;
    border-radius: 12px; padding: 12px 22px;
    font-size: .92rem; font-weight: 600;
    cursor: pointer; transition: background .2s; white-space: nowrap;
}
#btnEnvoyer:hover    { background: #3d7a35; }
#btnEnvoyer:disabled { background: #adb5bd; cursor: not-allowed; }

.quick-questions { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 16px; }
.quick-btn {
    background: #f0f7ee; border: 1px solid #c8e6c4;
    color: #2d5a27; border-radius: 20px;
    padding: 6px 14px; font-size: .82rem;
    cursor: pointer; transition: all .2s;
}
.quick-btn:hover { background: #2d5a27; color: #fff; border-color: #2d5a27; }

.chat-error {
    background: #fee2e2; color: #dc3545;
    border-radius: 12px; padding: 10px 16px;
    font-size: .85rem; display: none; margin-top: 8px;
}
</style>

<div class="coach-container">

    <div class="coach-header">
        <div class="coach-avatar">🤖</div>
        <div>
            <h2>CoachAI — Votre Coach Personnel</h2>
            <p>Je connais votre profil, vos objectifs et votre progression. Posez-moi toutes vos questions !</p>
        </div>
    </div>

    <div class="quick-questions">
        <button class="quick-btn" onclick="envoyerRapide('Comment je progresse cette semaine ?')">📈 Ma progression</button>
        <button class="quick-btn" onclick="envoyerRapide('Que dois-je manger aujourd\'hui selon mon objectif ?')">🥗 Conseil nutrition</button>
        <button class="quick-btn" onclick="envoyerRapide('Quel exercice me conseilles-tu aujourd\'hui ?')">🏋️ Exercice du jour</button>
        <button class="quick-btn" onclick="envoyerRapide('Est-ce que je m\'entraîne assez régulièrement ?')">📅 Ma régularité</button>
        <button class="quick-btn" onclick="envoyerRapide('J\'ai mal aux genoux, que faire ?')">🩹 Douleur / blessure</button>
        <button class="quick-btn" onclick="envoyerRapide('Comment atteindre mon objectif plus vite ?')">🎯 Atteindre mon objectif</button>
    </div>

    <div class="chat-box" id="chatBox">
        <div class="msg coach">
            <div class="msg-avatar">🤖</div>
            <div class="msg-bubble">
                Bonjour <strong><?= htmlspecialchars($user['nom'] ?? 'champion') ?></strong> ! 👋<br><br>
                Je suis votre coach personnel. Je connais votre profil complet :
                <?php if ($objectif): ?>
                    votre objectif est <strong><?= htmlspecialchars($objectif['titre'] ?? '') ?></strong>,
                    <?php if ($imc): ?>votre IMC est <strong><?= $imc ?></strong> et<?php endif; ?>
                    vous avez terminé <strong><?= (int)($stats['termines'] ?? 0) ?> exercice(s)</strong> jusqu'ici.
                <?php else: ?>
                    mais je vois que vous n'avez pas encore défini d'objectif. Commencez par en créer un !
                <?php endif; ?>
                <br><br>
                Comment puis-je vous aider aujourd'hui ? 💪
            </div>
        </div>

        <div class="typing-indicator" id="typingIndicator">
            <div class="msg-avatar" style="background:#2d5a27;color:#fff;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;">🤖</div>
            <div class="typing-dots"><span></span><span></span><span></span></div>
        </div>
    </div>

    <div class="chat-input-area">
        <textarea id="userInput" placeholder="Posez votre question au coach..." rows="1"></textarea>
        <button id="btnEnvoyer" onclick="envoyerMessage()">Envoyer ➤</button>
    </div>

    <div class="chat-error" id="chatError"></div>

</div>

<!-- Profil utilisateur passé en JS -->
<script>
const PROFIL = <?= json_encode([
    'nom'           => $user['nom']                        ?? '',
    'objectif_titre'=> $objectif['titre']                  ?? null,
    'objectif_type' => $objectif['type_objectif']          ?? null,
    'imc'           => $imc,
    'poids_actuel'  => $objectif['poids_actuel']           ?? null,
    'poids_cible'   => $objectif['poids_cible']            ?? null,
    'termines'      => $stats['termines']                  ?? 0,
    'total'         => $stats['total']                     ?? 0,
    'streak'        => $streak,
    'jours_inactif' => $joursInactif,
    'niveau'        => $niveau,
    'moy_reps'      => $stats['moy_reps']                  ?? null,
    'moy_poids'     => $stats['moy_poids']                 ?? null,
    'nb_difficile'  => $stats['nb_difficile']              ?? 0,
    'nb_facile'     => $stats['nb_facile']                 ?? 0,
    'total_minutes' => $stats['total_minutes']             ?? 0,
    'maladies'      => $objectif['maladies']               ?? null,
    'preferences'   => $objectif['preferences']            ?? null,
    'programme_nom' => $programme['nom']                   ?? null,
]) ?>;

const chatBox         = document.getElementById('chatBox');
const userInput       = document.getElementById('userInput');
const btnEnvoyer      = document.getElementById('btnEnvoyer');
const typingIndicator = document.getElementById('typingIndicator');
const chatError       = document.getElementById('chatError');

userInput.addEventListener('input', function () {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
});

userInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        envoyerMessage();
    }
});

function envoyerRapide(texte) {
    userInput.value = texte;
    envoyerMessage();
}

function ajouterMessage(texte, role) {
    const div    = document.createElement('div');
    div.className = 'msg ' + role;
    const avatar = document.createElement('div');
    avatar.className = 'msg-avatar';
    avatar.textContent = role === 'coach' ? '🤖' : '👤';
    const bubble = document.createElement('div');
    bubble.className = 'msg-bubble';
    // Convertit **texte** en gras et \n en retour ligne
    bubble.innerHTML = texte
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\n/g, '<br>');
    div.appendChild(avatar);
    div.appendChild(bubble);
    chatBox.insertBefore(div, typingIndicator);
    chatBox.scrollTop = chatBox.scrollHeight;
}

function setTyping(visible) {
    typingIndicator.classList.toggle('visible', visible);
    chatBox.scrollTop = chatBox.scrollHeight;
}

async function envoyerMessage() {
    const texte = userInput.value.trim();
    chatError.style.display = 'none';

    // Validation JS
    if (!texte) {
        chatError.textContent = '⚠️ Écrivez un message avant d\'envoyer.';
        chatError.style.display = 'block';
        return;
    }
    if (texte.length > 1000) {
        chatError.textContent = '⚠️ Message trop long (maximum 1000 caractères).';
        chatError.style.display = 'block';
        return;
    }

    userInput.value = '';
    userInput.style.height = 'auto';
    btnEnvoyer.disabled = true;

    ajouterMessage(texte, 'user');
    setTyping(true);

    try {
        const response = await fetch('coach_proxy.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                message: texte,
                profil:  PROFIL
            })
        });

        const data = await response.json();

        if (!response.ok || data.error) {
            throw new Error(data.error || 'Erreur serveur');
        }

        setTyping(false);
        ajouterMessage(data.reponse, 'coach');

    } catch (err) {
        setTyping(false);
        chatError.textContent = '⚠️ Erreur : ' + err.message;
        chatError.style.display = 'block';
    } finally {
        btnEnvoyer.disabled = false;
        userInput.focus();
    }
}
</script>

<?php 
require_once dirname(__DIR__, 3) . '/footer.php'; 
?>