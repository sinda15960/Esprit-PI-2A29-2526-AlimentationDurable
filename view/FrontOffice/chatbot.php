<?php
session_start();
require_once __DIR__ . '/../../Config/Database.php';

$db = Database::getInstance()->getConnection();

if (!isset($_SESSION['chat_session_id'])) {
    $_SESSION['chat_session_id'] = session_id() . '_chat_' . time();
}
$chat_session_id = $_SESSION['chat_session_id'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assistant IA - NutriFlow AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }
        .banner {
            background: linear-gradient(135deg, #1a3c0e 0%, #3a6b1e 100%);
            padding: 1.5rem 2rem;
            text-align: center;
            color: white;
        }
        .banner h1 { font-size: 2rem; letter-spacing: 3px; }
        .banner p { font-size: 0.8rem; opacity: 0.9; }
        .container { max-width: 1000px; margin: 0 auto; padding: 2rem; }
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            color: #2d5016;
            padding: 0.5rem 1.2rem;
            border-radius: 30px;
            text-decoration: none;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }
        .chat-container {
            background: white;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            height: 550px;
        }
        .chat-header {
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2b 100%);
            color: white;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .chat-header i { font-size: 2rem; }
        .chat-header h3 { font-size: 1.1rem; }
        .chat-header p { font-size: 0.7rem; opacity: 0.8; }
        .ai-badge {
            background: rgba(255,255,255,0.2);
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.6rem;
            margin-left: 10px;
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background: #f8f9fa;
        }
        .message {
            display: flex;
            margin-bottom: 1rem;
            animation: fadeIn 0.3s ease;
        }
        .message.user { justify-content: flex-end; }
        .message.bot { justify-content: flex-start; }
        .message-content {
            max-width: 75%;
            padding: 0.8rem 1.2rem;
            border-radius: 20px;
            line-height: 1.5;
            font-size: 0.9rem;
            white-space: pre-wrap;
        }
        .message.user .message-content {
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2b 100%);
            color: white;
            border-bottom-right-radius: 5px;
        }
        .message.bot .message-content {
            background: white;
            border: 1px solid #e0e0e0;
            border-bottom-left-radius: 5px;
            color: #333;
        }
        .chat-input {
            display: flex;
            padding: 1rem;
            background: white;
            border-top: 1px solid #eee;
            gap: 0.8rem;
        }
        .chat-input input {
            flex: 1;
            padding: 0.8rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 30px;
            font-family: 'Poppins', sans-serif;
        }
        .chat-input input:focus { outline: none; border-color: #4a7c2b; }
        .chat-input button {
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2b 100%);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 30px;
            cursor: pointer;
            font-weight: 600;
        }
        .suggestions {
            display: flex;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #f0f0f0;
            flex-wrap: wrap;
            border-top: 1px solid #e0e0e0;
        }
        .suggestion-btn {
            background: #e8f5e9;
            border: none;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.7rem;
            cursor: pointer;
            color: #2d5016;
        }
        .suggestion-btn:hover { background: #c8e6c9; }
        .typing {
            display: flex;
            gap: 4px;
            padding: 0.8rem 1.2rem;
        }
        .typing span {
            width: 8px;
            height: 8px;
            background: #999;
            border-radius: 50%;
            animation: typing 1.4s infinite;
        }
        .typing span:nth-child(2) { animation-delay: 0.2s; }
        .typing span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
            30% { transform: translateY(-10px); opacity: 1; }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .footer {
            background: linear-gradient(135deg, #1a3c0e 0%, #2d5016 100%);
            color: white;
            text-align: center;
            padding: 1.2rem;
            margin-top: 2rem;
        }
        @media (max-width: 768px) {
            .container { padding: 1rem; }
            .message-content { max-width: 85%; font-size: 0.8rem; }
        }
    </style>
</head>
<body>
    <div class="banner">
        <h1>🤖 Assistant IA NutriFlow</h1>
        <p>Posez vos questions sur les allergies - Intelligence Artificielle</p>
    </div>

    <div class="container">
        <a href="front_allergie_traitement.php" class="back-btn">← Retour aux allergies</a>
        
        <div class="chat-container">
            <div class="chat-header">
                <i class="fas fa-brain"></i>
                <div>
                    <h3>Assistant IA <span class="ai-badge">Gemini AI</span></h3>
                    <p>Je comprends vos symptômes et vous guide</p>
                </div>
            </div>
            
            <div class="chat-messages" id="chatMessages">
                <div class="message bot">
                    <div class="message-content">
                        🤖 **Bonjour ! Je suis votre assistant IA.**<br><br>
                        Je peux vous aider à :<br>
                        • Donner les symptômes d'une allergie (ex: "Symptômes du gluten")<br>
                        • Identifier une allergie selon vos symptômes (ex: "Je respire mal")<br>
                        • Donner des conseils en urgence<br><br>
                        <b>Comment puis-je vous aider ?</b>
                    </div>
                </div>
            </div>
            
            <div class="suggestions">
                <button class="suggestion-btn" onclick="sendSuggestion('Symptômes du gluten')">📋 Symptômes du gluten</button>
                <button class="suggestion-btn" onclick="sendSuggestion('Je respire mal, quelle allergie ?')">😮‍💨 Difficultés respiratoires</button>
                <button class="suggestion-btn" onclick="sendSuggestion('Urgence allergique')">🚨 Urgence</button>
                <button class="suggestion-btn" onclick="sendSuggestion('Je tousse et j\'ai des boutons')">🤧 Toux + boutons</button>
            </div>
            
            <div class="chat-input">
                <input type="text" id="messageInput" placeholder="Écrivez votre message..." onkeypress="if(event.key==='Enter') sendMessage()">
                <button id="sendBtn" onclick="sendMessage()">Envoyer <i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </div>
    
    <footer class="footer">
        <p>© 2024 NutriFlow AI - Assistant médical non substituable à un avis médical</p>
    </footer>

    <script>
        const chatMessages = document.getElementById('chatMessages');
        const messageInput = document.getElementById('messageInput');
        const sendBtn = document.getElementById('sendBtn');
        let isTyping = false;
        
        function addMessage(text, isUser) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user' : 'bot'}`;
            messageDiv.innerHTML = `<div class="message-content">${formatMessage(text)}</div>`;
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        function formatMessage(text) {
            return text.replace(/\n/g, '<br>').replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        }
        
        function showTyping() {
            isTyping = true;
            const typingDiv = document.createElement('div');
            typingDiv.className = 'message bot';
            typingDiv.id = 'typingIndicator';
            typingDiv.innerHTML = `<div class="message-content"><div class="typing"><span></span><span></span><span></span></div></div>`;
            chatMessages.appendChild(typingDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        function hideTyping() {
            isTyping = false;
            const typing = document.getElementById('typingIndicator');
            if (typing) typing.remove();
        }
        
        async function sendMessage() {
            const message = messageInput.value.trim();
            if (message === '' || isTyping) return;
            
            addMessage(message, true);
            messageInput.value = '';
            sendBtn.disabled = true;
            showTyping();
            
            try {
                const response = await fetch('../../Controller/chatbot_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        message: message,
                        session_id: '<?= $chat_session_id ?>'
                    })
                });
                const data = await response.json();
                hideTyping();
                if (data.success) {
                    addMessage(data.response, false);
                } else {
                    addMessage("❌ Désolé, une erreur s'est produite. Veuillez réessayer.", false);
                }
            } catch (error) {
                hideTyping();
                addMessage("❌ Erreur de connexion. Veuillez réessayer.", false);
            }
            sendBtn.disabled = false;
        }
        
        function sendSuggestion(text) {
            messageInput.value = text;
            sendMessage();
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>