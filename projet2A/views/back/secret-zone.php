<!-- Secret Zone - Easter Eggs -->
<div class="secret-zone">
    <div class="secret-header">
        <h2>🤫 CLASSIFIED AREA</h2>
        <p class="secret-subtitle">You've discovered the hidden dimension of NutriFlow AI</p>
        <div class="access-code-status" id="accessStatus">
            <span class="lock-icon">🔒</span>
            <span id="accessMessage">Enter the Konami Code to unlock</span>
        </div>
    </div>
    
    <!-- Hidden initially, revealed after Konami code -->
    <div id="secretContent" style="display: none;">
        
        <!-- Easter Egg 1: Developer Memes -->
        <div class="easter-egg-section">
            <h3>🎭 Developer's Corner</h3>
            <div class="meme-gallery" id="memeGallery">
                <div class="meme-card" onclick="showMemeModal(1)">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 150'%3E%3Crect width='200' height='150' fill='%23374151'/%3E%3Ctext x='50%25' y='50%25' text-anchor='middle' fill='white' font-size='14'%3E😂 It works on my machine%3C/text%3E%3C/svg%3E" alt="Meme 1">
                    <span>"It works on my machine"</span>
                </div>
                <div class="meme-card" onclick="showMemeModal(2)">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 150'%3E%3Crect width='200' height='150' fill='%23374151'/%3E%3Ctext x='50%25' y='50%25' text-anchor='middle' fill='white' font-size='14'%3E🔥 Debugging: 99%% done%3C/text%3E%3C/svg%3E" alt="Meme 2">
                    <span>99% done, 100% to go</span>
                </div>
                <div class="meme-card" onclick="showMemeModal(3)">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 150'%3E%3Crect width='200' height='150' fill='%23374151'/%3E%3Ctext x='50%25' y='50%25' text-anchor='middle' fill='white' font-size='14'%3E💀 CSS is working%3C/text%3E%3C/svg%3E" alt="Meme 3">
                    <span>CSS is working... somehow</span>
                </div>
                <div class="meme-card" onclick="showMemeModal(4)">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 150'%3E%3Crect width='200' height='150' fill='%23374151'/%3E%3Ctext x='50%25' y='50%25' text-anchor='middle' fill='white' font-size='14'%3E🤖 AI: I'm 99%% sure%3C/text%3E%3C/svg%3E" alt="Meme 4">
                    <span>I'm 99% sure... maybe</span>
                </div>
            </div>
        </div>
        
        <!-- Easter Egg 2: Click Counter -->
        <div class="easter-egg-section">
            <h3>🖱️ The Button That Does Nothing (Or Does It?)</h3>
            <div class="mystery-button-container">
                <button class="mystery-button" id="mysteryBtn" onclick="incrementClickCounter()">
                    <span class="btn-icon">❓</span>
                    Don't Press Me
                </button>
                <div class="click-counter">
                    Times pressed: <span id="clickCount">0</span>
                    <div id="clickMessage" class="click-message"></div>
                </div>
            </div>
        </div>
        
        <!-- Easter Egg 3: Secret Stats -->
        <div class="easter-egg-section">
            <h3>📊 Hidden Statistics</h3>
            <div class="secret-stats-grid">
                <div class="secret-stat">
                    <span class="secret-stat-value" id="secretStat1">🐛</span>
                    <span class="secret-stat-label">Bugs squashed today</span>
                </div>
                <div class="secret-stat">
                    <span class="secret-stat-value" id="secretStat2">☕</span>
                    <span class="secret-stat-label">Coffees consumed</span>
                </div>
                <div class="secret-stat">
                    <span class="secret-stat-value" id="secretStat3">🌙</span>
                    <span class="secret-stat-label">Late nights</span>
                </div>
                <div class="secret-stat">
                    <span class="secret-stat-value" id="secretStat4">💻</span>
                    <span class="secret-stat-label">Lines of code</span>
                </div>
            </div>
        </div>
        
        <!-- Easter Egg 4: Pixel Art Game -->
        <div class="easter-egg-section">
            <h3>🎮 Secret Mini-Game: Snake</h3>
            <div class="game-container">
                <canvas id="snakeGame" width="400" height="400" style="background: #1a1a2e; border-radius: 12px;"></canvas>
                <div class="game-controls">
                    <button class="game-btn" onclick="startSnakeGame()">Start Game</button>
                    <div class="game-score">Score: <span id="snakeScore">0</span></div>
                </div>
                <p class="game-hint">Use arrow keys ← ↑ ↓ →</p>
            </div>
        </div>
        
        <!-- Easter Egg 5: Wall of Shame/Fame -->
        <div class="easter-egg-section">
            <h3>🏆 Wall of Fame</h3>
            <div class="wall-of-fame" id="wallOfFame">
                <?php
                // Get top 5 most active admins
                $fameList = [
                    ['name' => 'Admin Master', 'achievement' => 'First to unlock secret zone', 'date' => '2024-01-15'],
                    ['name' => 'Debug Wizard', 'achievement' => 'Found 10 easter eggs', 'date' => '2024-02-20'],
                    ['name' => 'Code Ninja', 'achievement' => 'Clicked button 1000 times', 'date' => '2024-03-10'],
                    ['name' => 'Pixel King', 'achievement' => 'High score in Snake: 42', 'date' => '2024-03-25'],
                    ['name' => 'Secret Hunter', 'achievement' => 'Discovered all secrets', 'date' => '2024-04-01'],
                ];
                ?>
                <?php foreach($fameList as $index => $entry): ?>
                <div class="fame-card" style="animation-delay: <?php echo $index * 0.1; ?>s">
                    <div class="fame-rank">#<?php echo $index + 1; ?></div>
                    <div class="fame-name"><?php echo $entry['name']; ?></div>
                    <div class="fame-achievement">🏆 <?php echo $entry['achievement']; ?></div>
                    <div class="fame-date">📅 <?php echo $entry['date']; ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Easter Egg 6: Console Message -->
        <div class="easter-egg-section">
            <h3>💬 Secret Console Message</h3>
            <div class="console-message" id="consoleMessage">
                <span class="console-prompt">></span>
                <span class="console-text" id="consoleText">Type "help" to see commands...</span>
            </div>
            <div class="console-input">
                <span class="console-prompt">$</span>
                <input type="text" id="consoleInput" placeholder="Enter command..." onkeypress="handleConsoleCommand(event)">
            </div>
            <div class="console-commands" id="commandOutput"></div>
        </div>
        
    </div>
</div>

<style>
.secret-zone {
    background: linear-gradient(135deg, #0f0f1a 0%, #1a1a2e 100%);
    border-radius: 20px;
    padding: 2rem;
    min-height: 600px;
    border: 2px solid #facc15;
    box-shadow: 0 0 30px rgba(250,204,21,0.2);
}

.secret-header {
    text-align: center;
    margin-bottom: 2rem;
}

.secret-header h2 {
    color: #facc15;
    font-size: 2rem;
    letter-spacing: 4px;
    text-shadow: 0 0 10px rgba(250,204,21,0.5);
}

.secret-subtitle {
    color: #94a3b8;
    margin-top: 0.5rem;
}

.access-code-status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(0,0,0,0.5);
    padding: 0.5rem 1rem;
    border-radius: 50px;
    margin-top: 1rem;
    font-size: 0.8rem;
}

.lock-icon {
    font-size: 1rem;
}

#accessMessage {
    color: #ef4444;
}

.easter-egg-section {
    background: rgba(255,255,255,0.05);
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid rgba(250,204,21,0.2);
}

.easter-egg-section h3 {
    color: #facc15;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

/* Meme Gallery */
.meme-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.meme-card {
    background: #1e1e2e;
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
}

.meme-card:hover {
    transform: translateY(-5px);
    background: #2a2a3e;
    box-shadow: 0 5px 20px rgba(250,204,21,0.2);
}

.meme-card img {
    width: 100%;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 0.5rem;
}

.meme-card span {
    font-size: 0.8rem;
    color: #cbd5e0;
}

/* Mystery Button */
.mystery-button-container {
    text-align: center;
}

.mystery-button {
    background: linear-gradient(135deg, #facc15, #eab308);
    border: none;
    padding: 1rem 2rem;
    border-radius: 50px;
    font-size: 1.2rem;
    font-weight: bold;
    color: #1a1a2e;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.mystery-button:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 20px rgba(250,204,21,0.4);
}

.mystery-button:active {
    transform: scale(0.95);
}

.click-counter {
    margin-top: 1rem;
    font-size: 0.9rem;
    color: #94a3b8;
}

#clickCount {
    font-size: 1.5rem;
    font-weight: bold;
    color: #facc15;
}

.click-message {
    margin-top: 0.5rem;
    font-size: 0.8rem;
    animation: fadeOut 2s forwards;
}

@keyframes fadeOut {
    0% { opacity: 1; transform: translateY(0); }
    100% { opacity: 0; transform: translateY(-10px); }
}

/* Secret Stats */
.secret-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.secret-stat {
    text-align: center;
    padding: 1rem;
    background: rgba(0,0,0,0.3);
    border-radius: 12px;
    transition: all 0.3s;
}

.secret-stat:hover {
    transform: translateY(-3px);
    background: rgba(250,204,21,0.1);
}

.secret-stat-value {
    display: block;
    font-size: 2rem;
    font-weight: bold;
    color: #facc15;
    margin-bottom: 0.5rem;
}

.secret-stat-label {
    font-size: 0.7rem;
    color: #94a3b8;
}

/* Snake Game */
.game-container {
    text-align: center;
}

#snakeGame {
    margin: 0 auto;
    display: block;
    box-shadow: 0 0 20px rgba(0,0,0,0.3);
}

.game-controls {
    margin-top: 1rem;
    display: flex;
    justify-content: center;
    gap: 1rem;
    align-items: center;
}

.game-btn {
    background: #facc15;
    border: none;
    padding: 0.5rem 1.5rem;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
}

.game-btn:hover {
    transform: scale(1.05);
}

.game-score {
    font-size: 1rem;
    color: #facc15;
}

.game-hint {
    margin-top: 0.5rem;
    font-size: 0.7rem;
    color: #64748b;
}

/* Wall of Fame */
.wall-of-fame {
    display: grid;
    gap: 0.75rem;
}

.fame-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem 1rem;
    background: rgba(0,0,0,0.3);
    border-radius: 12px;
    transition: all 0.3s;
    animation: slideInFromLeft 0.5s ease forwards;
    opacity: 0;
    transform: translateX(-20px);
}

@keyframes slideInFromLeft {
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.fame-card:hover {
    background: rgba(250,204,21,0.1);
    transform: translateX(5px);
}

.fame-rank {
    width: 40px;
    height: 40px;
    background: #facc15;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: #1a1a2e;
}

.fame-name {
    flex: 1;
    font-weight: bold;
    color: #e2e8f0;
}

.fame-achievement {
    font-size: 0.8rem;
    color: #facc15;
}

.fame-date {
    font-size: 0.7rem;
    color: #64748b;
}

/* Console Message */
.console-message {
    background: #0a0a0f;
    padding: 1rem;
    border-radius: 8px;
    font-family: monospace;
    margin-bottom: 1rem;
}

.console-prompt {
    color: #facc15;
    margin-right: 0.5rem;
}

.console-text {
    color: #10b981;
}

.console-input {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #0a0a0f;
    padding: 0.75rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.console-input input {
    flex: 1;
    background: transparent;
    border: none;
    color: #e2e8f0;
    font-family: monospace;
    outline: none;
}

.console-commands {
    font-family: monospace;
    font-size: 0.8rem;
    max-height: 200px;
    overflow-y: auto;
}

.command-line {
    padding: 0.25rem 0;
    color: #94a3b8;
}

.command-success {
    color: #10b981;
}

.command-error {
    color: #ef4444;
}
</style>

<script>
// ========== KONAMI CODE DETECTION ==========
let konamiIndex = 0;
const konamiCode = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'b', 'a'];

document.addEventListener('keydown', function(e) {
    const key = e.key;
    const expectedKey = konamiCode[konamiIndex];
    
    if (key === expectedKey) {
        konamiIndex++;
        if (konamiIndex === konamiCode.length) {
            unlockSecretZone();
            konamiIndex = 0;
        }
    } else {
        konamiIndex = 0;
    }
});

function unlockSecretZone() {
    const secretContent = document.getElementById('secretContent');
    const accessMessage = document.getElementById('accessMessage');
    const lockIcon = document.querySelector('.lock-icon');
    
    if (secretContent.style.display === 'none') {
        secretContent.style.display = 'block';
        accessMessage.innerHTML = '✅ ACCESS GRANTED! Welcome to the secret zone.';
        accessMessage.style.color = '#10b981';
        lockIcon.innerHTML = '🔓';
        
        // Play sound effect (optional)
        playUnlockSound();
        
        // Save to localStorage
        localStorage.setItem('secretZoneUnlocked', 'true');
        localStorage.setItem('secretZoneUnlockDate', new Date().toISOString());
        
        // Show confetti
        showConfetti();
    }
}

function playUnlockSound() {
    // Simple beep using Web Audio API
    try {
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioCtx.createOscillator();
        const gainNode = audioCtx.createGain();
        oscillator.connect(gainNode);
        gainNode.connect(audioCtx.destination);
        oscillator.frequency.value = 880;
        gainNode.gain.value = 0.1;
        oscillator.start();
        gainNode.gain.exponentialRampToValueAtTime(0.00001, audioCtx.currentTime + 0.5);
        oscillator.stop(audioCtx.currentTime + 0.5);
    } catch(e) {}
}

function showConfetti() {
    const colors = ['#facc15', '#10b981', '#ef4444', '#3b82f6', '#ec4899'];
    for (let i = 0; i < 100; i++) {
        const confetti = document.createElement('div');
        confetti.style.position = 'fixed';
        confetti.style.left = Math.random() * 100 + '%';
        confetti.style.top = '-10px';
        confetti.style.width = '10px';
        confetti.style.height = '10px';
        confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
        confetti.style.borderRadius = '50%';
        confetti.style.pointerEvents = 'none';
        confetti.style.zIndex = '9999';
        confetti.style.animation = `fall ${Math.random() * 2 + 1}s linear forwards`;
        document.body.appendChild(confetti);
        
        setTimeout(() => confetti.remove(), 3000);
    }
}

// Add fall animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fall {
        to {
            transform: translateY(100vh) rotate(360deg);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// ========== MYSTERY BUTTON CLICK COUNTER ==========
let clickCount = parseInt(localStorage.getItem('mysteryClickCount')) || 0;
document.getElementById('clickCount').textContent = clickCount;

function incrementClickCounter() {
    clickCount++;
    document.getElementById('clickCount').textContent = clickCount;
    localStorage.setItem('mysteryClickCount', clickCount);
    
    const messageDiv = document.getElementById('clickMessage');
    
    if (clickCount === 10) {
        messageDiv.innerHTML = '🎉 10 clicks! You\'re persistent!';
    } else if (clickCount === 50) {
        messageDiv.innerHTML = '🔥 50 clicks! Are you a robot?';
    } else if (clickCount === 100) {
        messageDiv.innerHTML = '🏆 100 clicks! Achievement unlocked: "Click Master"';
        showConfetti();
    } else if (clickCount === 500) {
        messageDiv.innerHTML = '💀 500 clicks... Dedication level: INSANE!';
    } else if (clickCount === 1000) {
        messageDiv.innerHTML = '👑 LEGEND! You pressed the button 1000 times!';
        showConfetti();
    } else {
        messageDiv.innerHTML = `+1 click! (${clickCount} total)`;
    }
    
    setTimeout(() => {
        messageDiv.innerHTML = '';
    }, 2000);
}

// ========== SECRET STATS (Random values that change) ==========
function updateSecretStats() {
    const bugsSquashed = Math.floor(Math.random() * 50);
    const coffees = Math.floor(Math.random() * 20) + 5;
    const lateNights = Math.floor(Math.random() * 30) + 1;
    const linesOfCode = Math.floor(Math.random() * 5000) + 1000;
    
    document.getElementById('secretStat1').innerHTML = `🐛 ${bugsSquashed}`;
    document.getElementById('secretStat2').innerHTML = `☕ ${coffees}`;
    document.getElementById('secretStat3').innerHTML = `🌙 ${lateNights}`;
    document.getElementById('secretStat4').innerHTML = `💻 ${linesOfCode}`;
}

setInterval(updateSecretStats, 5000);
updateSecretStats();

// ========== SNAKE GAME ==========
let snakeGameRunning = false;
let snake, food, direction, gameLoop;

function startSnakeGame() {
    if (snakeGameRunning) return;
    
    const canvas = document.getElementById('snakeGame');
    const ctx = canvas.getContext('2d');
    const gridSize = 20;
    const tileCount = canvas.width / gridSize;
    
    snake = [{x: 10, y: 10}];
    direction = {x: 0, y: 0};
    food = {x: Math.floor(Math.random() * tileCount), y: Math.floor(Math.random() * tileCount)};
    let score = 0;
    snakeGameRunning = true;
    
    function gameLoop() {
        if (!snakeGameRunning) return;
        
        // Move snake
        const head = {x: snake[0].x + direction.x, y: snake[0].y + direction.y};
        
        // Check wall collision
        if (head.x < 0 || head.x >= tileCount || head.y < 0 || head.y >= tileCount) {
            gameOver();
            return;
        }
        
        // Check self collision
        if (snake.some(segment => segment.x === head.x && segment.y === head.y)) {
            gameOver();
            return;
        }
        
        snake.unshift(head);
        
        // Check food collision
        if (head.x === food.x && head.y === food.y) {
            score++;
            document.getElementById('snakeScore').textContent = score;
            food = {x: Math.floor(Math.random() * tileCount), y: Math.floor(Math.random() * tileCount)};
        } else {
            snake.pop();
        }
        
        // Draw
        ctx.fillStyle = '#1a1a2e';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        // Draw snake
        ctx.fillStyle = '#10b981';
        snake.forEach(segment => {
            ctx.fillRect(segment.x * gridSize, segment.y * gridSize, gridSize - 2, gridSize - 2);
        });
        
        // Draw food
        ctx.fillStyle = '#ef4444';
        ctx.fillRect(food.x * gridSize, food.y * gridSize, gridSize - 2, gridSize - 2);
        
        setTimeout(() => requestAnimationFrame(gameLoop), 100);
    }
    
    function gameOver() {
        snakeGameRunning = false;
        alert(`Game Over! Score: ${score}`);
    }
    
    // Keyboard controls
    document.removeEventListener('keydown', snakeKeyHandler);
    document.addEventListener('keydown', snakeKeyHandler);
    
    function snakeKeyHandler(e) {
        switch(e.key) {
            case 'ArrowUp': if (direction.y === 0) direction = {x: 0, y: -1}; break;
            case 'ArrowDown': if (direction.y === 0) direction = {x: 0, y: 1}; break;
            case 'ArrowLeft': if (direction.x === 0) direction = {x: -1, y: 0}; break;
            case 'ArrowRight': if (direction.x === 0) direction = {x: 1, y: 0}; break;
        }
    }
    
    gameLoop();
}

// ========== CONSOLE COMMANDS ==========
function handleConsoleCommand(event) {
    if (event.key === 'Enter') {
        const input = document.getElementById('consoleInput');
        const command = input.value.toLowerCase().trim();
        const outputDiv = document.getElementById('commandOutput');
        
        const commandLine = document.createElement('div');
        commandLine.className = 'command-line';
        commandLine.innerHTML = `<span class="console-prompt">$</span> ${escapeHtml(command)}`;
        outputDiv.appendChild(commandLine);
        
        let response = '';
        
        switch(command) {
            case 'help':
                response = 'Available commands: help, secrets, stats, rainbow, clear, whoami, date, joke, meme';
                break;
            case 'secrets':
                response = '🔐 There are 6 easter eggs hidden in this zone! Find them all!';
                break;
            case 'stats':
                response = `📊 Clicks: ${clickCount} | Unlocked: ${localStorage.getItem('secretZoneUnlocked') === 'true' ? 'Yes' : 'No'}`;
                break;
            case 'rainbow':
                document.body.style.animation = 'rainbow 1s infinite';
                setTimeout(() => document.body.style.animation = '', 5000);
                response = '🌈 RAINBOW MODE ACTIVATED! (5 seconds)';
                break;
            case 'clear':
                outputDiv.innerHTML = '';
                input.value = '';
                return;
            case 'whoami':
                response = `👤 You are the admin of NutriFlow AI. Welcome back!`;
                break;
            case 'date':
                response = `📅 ${new Date().toLocaleString()}`;
                break;
            case 'joke':
                const jokes = [
                    'Why do programmers prefer dark mode? Because light attracts bugs!',
                    'What is a programmer\'s favorite place? The Foo Bar!',
                    'Why did the developer go broke? Because he used up all his cache!',
                    'What do you call a programmer from Finland? Nerdic!'
                ];
                response = jokes[Math.floor(Math.random() * jokes.length)];
                break;
            case 'meme':
                response = '🎭 Check the Meme Gallery above for some developer humor!';
                break;
            default:
                response = `Command not found: "${command}". Type "help" for available commands.`;
        }
        
        const responseLine = document.createElement('div');
        responseLine.className = `command-${response.includes('not found') ? 'error' : 'success'}`;
        responseLine.innerHTML = `<span class="console-prompt">></span> ${response}`;
        outputDiv.appendChild(responseLine);
        
        input.value = '';
        outputDiv.scrollTop = outputDiv.scrollHeight;
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showMemeModal(id) {
    alert(`Meme #${id} - Developer humor at its finest! 😂`);
}

// Auto-check if previously unlocked
if (localStorage.getItem('secretZoneUnlocked') === 'true') {
    unlockSecretZone();
}
</script>
