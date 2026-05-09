<!-- Retro Terminal Mode -->
<div class="retro-terminal-container">
    <div class="terminal-header">
        <div class="terminal-controls">
            <span class="terminal-control close"></span>
            <span class="terminal-control minimize"></span>
            <span class="terminal-control maximize" onclick="toggleFullscreenTerminal()"></span>
        </div>
        <div class="terminal-title">NUTRIFLOW_AI_ADMIN@terminal:~</div>
        <div class="terminal-status">● ONLINE</div>
    </div>
    
    <div class="terminal-body" id="terminalBody">
        <div class="terminal-line">
            <span class="terminal-prompt">$</span>
            <span class="terminal-command">nutriflow --version</span>
        </div>
        <div class="terminal-output">
            NutriFlow AI Administration Terminal v2.0.0<br>
            Built with 💚 for healthy eating management<br>
            Type <span class="terminal-cmd-highlight">'help'</span> to see available commands<br>
            Type <span class="terminal-cmd-highlight">'theme modern'</span> to exit retro mode<br>
            <br>
            ┌─────────────────────────────────────────┐<br>
            │  🥗 WELCOME TO THE MATRIX, ADMIN! 🥗   │<br>
            └─────────────────────────────────────────┘<br>
        </div>
        
        <div id="terminalHistory"></div>
        
        <div class="terminal-input-line">
            <span class="terminal-prompt">$</span>
            <input type="text" id="terminalInput" class="terminal-input" autofocus onkeypress="handleTerminalCommand(event)">
            <span class="terminal-cursor">█</span>
        </div>
    </div>
    
    <div class="terminal-stats">
        <div class="terminal-stat">
            <span class="stat-label">USERS</span>
            <span class="stat-value" id="termUserCount"><?php echo count($users); ?></span>
        </div>
        <div class="terminal-stat">
            <span class="stat-label">UPTIME</span>
            <span class="stat-value" id="termUptime">00:00:00</span>
        </div>
        <div class="terminal-stat">
            <span class="stat-label">MEMORY</span>
            <span class="stat-value" id="termMemory">0%</span>
        </div>
        <div class="terminal-stat">
            <span class="stat-label">NET</span>
            <span class="stat-value" id="termNet">📶</span>
        </div>
    </div>
    
    <div class="terminal-ascii" id="terminalAscii">
        <pre>
    ╔═══════════════════════════════════════╗
    ║    N U T R I F L O W   A I   A D M I N    ║
    ╚═══════════════════════════════════════╝
        </pre>
    </div>
</div>

<style>
.retro-terminal-container {
    background: #0c0c0c;
    border-radius: 12px;
    overflow: hidden;
    font-family: 'Courier New', 'Fira Code', monospace;
    box-shadow: 0 20px 40px rgba(0,0,0,0.5);
    border: 1px solid #22c55e;
}

.terminal-header {
    background: #1a1a1a;
    padding: 0.75rem 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #22c55e;
}

.terminal-controls {
    display: flex;
    gap: 0.5rem;
}

.terminal-control {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
}

.terminal-control.close { background: #ff5f56; }
.terminal-control.minimize { background: #ffbd2e; }
.terminal-control.maximize { background: #27c93f; cursor: pointer; }

.terminal-title {
    color: #22c55e;
    font-size: 0.8rem;
    letter-spacing: 1px;
}

.terminal-status {
    color: #22c55e;
    font-size: 0.7rem;
    animation: blink 1s infinite;
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.terminal-body {
    background: #0c0c0c;
    padding: 1.5rem;
    height: 500px;
    overflow-y: auto;
    color: #22c55e;
    font-size: 0.9rem;
    line-height: 1.5;
}

.terminal-line {
    margin-bottom: 0.5rem;
}

.terminal-prompt {
    color: #22c55e;
    font-weight: bold;
    margin-right: 0.5rem;
}

.terminal-command {
    color: #e2e8f0;
}

.terminal-output {
    color: #94a3b8;
    margin-bottom: 1rem;
    white-space: pre-wrap;
}

.terminal-cmd-highlight {
    color: #facc15;
    background: rgba(34,197,94,0.1);
    padding: 0.1rem 0.3rem;
    border-radius: 4px;
}

.terminal-input-line {
    display: flex;
    align-items: center;
    margin-top: 0.5rem;
}

.terminal-input {
    background: transparent;
    border: none;
    color: #22c55e;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    flex: 1;
    outline: none;
}

.terminal-cursor {
    animation: cursorBlink 1s infinite;
    margin-left: 2px;
}

@keyframes cursorBlink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0; }
}

.terminal-stats {
    display: flex;
    justify-content: space-around;
    padding: 0.75rem;
    background: #1a1a1a;
    border-top: 1px solid #22c55e;
    font-size: 0.7rem;
}

.terminal-stat {
    text-align: center;
}

.terminal-stat .stat-label {
    color: #64748b;
    letter-spacing: 1px;
}

.terminal-stat .stat-value {
    color: #22c55e;
    font-weight: bold;
    margin-left: 0.5rem;
}

.terminal-ascii {
    background: #0a0a0a;
    padding: 0.5rem;
    text-align: center;
    border-top: 1px solid #22c55e;
}

.terminal-ascii pre {
    color: #22c55e;
    font-size: 0.6rem;
    margin: 0;
    font-family: monospace;
}

.history-line {
    margin-bottom: 0.25rem;
}

.history-output {
    color: #10b981;
    margin-left: 1rem;
    margin-bottom: 0.5rem;
    white-space: pre-wrap;
}

.history-error {
    color: #ef4444;
    margin-left: 1rem;
    margin-bottom: 0.5rem;
}
</style>

<script>
let terminalHistory = [];
let startTime = Date.now();

function handleTerminalCommand(event) {
    if (event.key === 'Enter') {
        const input = document.getElementById('terminalInput');
        const command = input.value.trim();
        
        if (command === '') return;
        
        // Add to history display
        const historyDiv = document.getElementById('terminalHistory');
        const commandLine = document.createElement('div');
        commandLine.className = 'history-line';
        commandLine.innerHTML = `<span class="terminal-prompt">$</span> <span class="terminal-command">${escapeHtml(command)}</span>`;
        historyDiv.appendChild(commandLine);
        
        // Process command
        const output = processTerminalCommand(command);
        
        const outputLine = document.createElement('div');
        outputLine.className = output.type === 'error' ? 'history-error' : 'history-output';
        outputLine.innerHTML = output.message;
        historyDiv.appendChild(outputLine);
        
        // Scroll to bottom
        const terminalBody = document.getElementById('terminalBody');
        terminalBody.scrollTop = terminalBody.scrollHeight;
        
        // Clear input
        input.value = '';
        
        // Save to localStorage
        terminalHistory.push({ command, output: output.message, timestamp: new Date() });
        localStorage.setItem('terminalHistory', JSON.stringify(terminalHistory.slice(-50)));
    }
}

function processTerminalCommand(command) {
    const cmd = command.toLowerCase().trim();
    const args = cmd.split(' ');
    const mainCmd = args[0];
    
    switch(mainCmd) {
        case 'help':
            return {
                type: 'success',
                message: `
Available commands:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  📊 SYSTEM
    • stats      - Show system statistics
    • users      - List all users
    • uptime     - Show system uptime
    • date       - Show current date/time
    • whoami     - Show current user
    
  👥 USER MANAGEMENT
    • list users     - List all registered users
    • user [id]      - Show user details
    • new users      - Show recent registrations
    
  🍽️ CONTENT
    • recipes       - Show recipe statistics
    • messages      - Show unread messages
    
  🎮 FUN
    • matrix       - Enter the matrix 🕶️
    • fortune      - Get a random fortune
    • ascii        - Show ASCII art
    • joke         - Tell a joke
    • rickroll     - 🎵 Never gonna give you up...
    
  🎨 THEME
    • theme modern   - Exit retro mode
    • theme dark     - Switch to dark theme
    
  🧹 UTILITIES
    • clear       - Clear terminal
    • exit        - Exit retro mode
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Type 'theme modern' to return to normal dashboard.
            `
            };
            
        case 'stats':
            const userCount = <?php echo count($users); ?>;
            const uptime = formatUptime();
            return {
                type: 'success',
                message: `
╔════════════════════════════════════╗
║         SYSTEM STATISTICS          ║
╠════════════════════════════════════╣
║  👥 Total Users     : ${userCount.toString().padStart(8)}     ║
║  🟢 Active Users    : ${getActiveUsersCount().toString().padStart(8)}     ║
║  🔴 Disabled Users  : ${getDisabledUsersCount().toString().padStart(8)}     ║
║  👑 Admins          : ${getAdminCount().toString().padStart(8)}     ║
║  📅 Uptime          : ${uptime.padStart(8)}     ║
║  💾 Memory Usage    : ${Math.floor(Math.random() * 30 + 20)}%       ║
╚════════════════════════════════════╝
                `
            };
            
        case 'users':
        case 'list':
            if (args[1] === 'users') {
                return {
                    type: 'success',
                    message: getUsersList()
                };
            }
            return {
                type: 'error',
                message: `Command not found: ${command}. Type 'help' for available commands.`
            };
            
        case 'uptime':
            return {
                type: 'success',
                message: `System uptime: ${formatUptime()}`
            };
            
        case 'date':
            return {
                type: 'success',
                message: new Date().toLocaleString()
            };
            
        case 'whoami':
            return {
                type: 'success',
                message: `👤 Username: ${localStorage.getItem('adminUsername') || 'Administrator'}\n🎭 Role: admin\n🆔 ID: 0x7F3A`
            };
            
        case 'recipes':
            return {
                type: 'success',
                message: `📖 Total recipes: 128\n⭐ Average rating: 4.8/5\n🔥 Most popular: "Healthy Buddha Bowl" (342 likes)`
            };
            
        case 'messages':
            return {
                type: 'success',
                message: `📬 Unread messages: ${getUnreadMessagesCount()}\n✉️ Total messages: ${getTotalMessagesCount()}`
            };
            
        case 'new':
            if (args[1] === 'users') {
                return {
                    type: 'success',
                    message: getNewUsersList()
                };
            }
            return {
                type: 'error',
                message: `Command not found: ${command}. Type 'help' for available commands.`
            };
            
        case 'matrix':
            startMatrixEffect();
            return {
                type: 'success',
                message: '🌐 Entering the matrix... Follow the green code!'
            };
            
        case 'fortune':
            const fortunes = [
                '🍀 A new user will register today!',
                '💪 Your code is cleaner than ever!',
                '🌟 A bug-free day awaits you!',
                '🎯 Your dashboard looks amazing!',
                '🚀 NutriFlow AI will change the world!',
                '🍕 Pizza for lunch? Yes!',
                '🧘 Take a break, you deserve it!'
            ];
            return {
                type: 'success',
                message: `🔮 ${fortunes[Math.floor(Math.random() * fortunes.length)]}`
            };
            
        case 'ascii':
            return {
                type: 'success',
                message: `
    ╔══════════════════════════╗
    ║   🥗 NUTRIFLOW AI 🥗    ║
    ║   ═══════════════════   ║
    ║   ╭━━━━━━━━━━━━━━━╮     ║
    ║   ┃  █▀▀ █▀█ █▀▀  ┃     ║
    ║   ┃  █▄▄ █▀▄ ██▄  ┃     ║
    ║   ╰━━━━━━━━━━━━━━━╯     ║
    ║   Healthy Eating Made    ║
    ║       Smart & Easy       ║
    ╚══════════════════════════╝
                `
            };
            
        case 'joke':
            const jokes = [
                'Why did the developer go broke? Because he used up all his cache!',
                'What do you call a programmer from Finland? Nerdic!',
                'Why do programmers prefer dark mode? Because light attracts bugs!',
                'How many programmers does it take to change a light bulb? None, that\'s a hardware problem!'
            ];
            return {
                type: 'success',
                message: `😂 ${jokes[Math.floor(Math.random() * jokes.length)]}`
            };
            
        case 'rickroll':
            return {
                type: 'success',
                message: `🎵 Never gonna give you up, never gonna let you down... 🎵\n(You've been Rick Rolled!)`
            };
            
        case 'theme':
            if (args[1] === 'modern') {
                exitRetroMode();
                return { type: 'success', message: '🔄 Switching to modern mode...' };
            } else if (args[1] === 'dark') {
                applyDarkTheme();
                return { type: 'success', message: '🎨 Dark theme applied!' };
            }
            return { type: 'error', message: 'Available themes: modern, dark' };
            
        case 'clear':
            document.getElementById('terminalHistory').innerHTML = '';
            return { type: 'success', message: '' };
            
        case 'exit':
            exitRetroMode();
            return { type: 'success', message: '👋 Goodbye! Exiting terminal mode...' };
            
        default:
            return {
                type: 'error',
                message: `Command not found: "${command}". Type 'help' for available commands.`
            };
    }
}

function getActiveUsersCount() {
    const activeBadges = document.querySelectorAll('.status-badge.active');
    return activeBadges.length || <?php 
        $active = 0;
        foreach($users as $u) if(isset($u['is_active']) && $u['is_active'] == 1) $active++;
        echo $active;
    ?>;
}

function getDisabledUsersCount() {
    const inactiveBadges = document.querySelectorAll('.status-badge.inactive');
    return inactiveBadges.length || <?php 
        $disabled = 0;
        foreach($users as $u) if(isset($u['is_active']) && $u['is_active'] == 0) $disabled++;
        echo $disabled;
    ?>;
}

function getAdminCount() {
    const adminBadges = document.querySelectorAll('.role-badge.admin');
    return adminBadges.length || <?php 
        $admin = 0;
        foreach($users as $u) if($u['role'] == 'admin') $admin++;
        echo $admin;
    ?>;
}

function getUnreadMessagesCount() {
    const unreadMessages = document.querySelectorAll('.message-card.unread');
    return unreadMessages.length || 0;
}

function getTotalMessagesCount() {
    const allMessages = document.querySelectorAll('.message-card');
    return allMessages.length || 0;
}

function getUsersList() {
    const users = document.querySelectorAll('#usersTableBody tr');
    let list = '┌────┬────────────────────────┬──────────────────────────────┐\n';
    list += '│ #  │ Username               │ Email                        │\n';
    list += '├────┼────────────────────────┼──────────────────────────────┤\n';
    
    users.forEach((user, index) => {
        if (index < 10) {
            const username = user.cells[0]?.textContent?.slice(0, 22) || 'Unknown';
            const email = user.cells[2]?.textContent?.slice(0, 28) || 'Unknown';
            list += `│ ${(index+1).toString().padStart(2)} │ ${username.padEnd(22)} │ ${email.padEnd(28)} │\n`;
        }
    });
    
    list += '└────┴────────────────────────┴──────────────────────────────┘\n';
    list += `Total: ${users.length} users. Type 'user [id]' for details.`;
    return list;
}

function getNewUsersList() {
    return "📊 New users this week: <?php 
        $weekAgo = date('Y-m-d H:i:s', strtotime('-7 days'));
        $count = 0;
        foreach($users as $u) if(strtotime($u['created_at']) > strtotime($weekAgo)) $count++;
        echo $count;
    ?>\nUse 'stats' for more details.";
}

function formatUptime() {
    const elapsed = Math.floor((Date.now() - startTime) / 1000);
    const hours = Math.floor(elapsed / 3600);
    const minutes = Math.floor((elapsed % 3600) / 60);
    const seconds = elapsed % 60;
    return `${hours.toString().padStart(2,'0')}:${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;
}

function startMatrixEffect() {
    const terminalBody = document.getElementById('terminalBody');
    const originalBg = terminalBody.style.background;
    let matrixInterval;
    
    terminalBody.style.background = '#000';
    
    function createMatrixChar() {
        const chars = '01アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲン';
        return chars[Math.floor(Math.random() * chars.length)];
    }
    
    matrixInterval = setInterval(() => {
        const matrixLine = document.createElement('div');
        matrixLine.style.color = '#22c55e';
        matrixLine.style.fontFamily = 'monospace';
        matrixLine.style.fontSize = '12px';
        matrixLine.style.opacity = Math.random() * 0.5 + 0.3;
        
        let line = '';
        for (let i = 0; i < 50; i++) {
            line += createMatrixChar();
        }
        matrixLine.textContent = line;
        terminalBody.appendChild(matrixLine);
        
        // Auto-remove after 3 seconds
        setTimeout(() => matrixLine.remove(), 3000);
        
        terminalBody.scrollTop = terminalBody.scrollHeight;
    }, 100);
    
    setTimeout(() => {
        clearInterval(matrixInterval);
    }, 10000);
}

function exitRetroMode() {
    // Save preference
    localStorage.setItem('retroMode', 'false');
    // Reload to normal dashboard
    window.location.href = 'index.php?action=admin_dashboard';
}

function applyDarkTheme() {
    document.body.style.background = '#1a1a2e';
    document.querySelector('.admin-container').style.background = '#1a1a2e';
}

function toggleFullscreenTerminal() {
    const container = document.querySelector('.retro-terminal-container');
    if (!document.fullscreenElement) {
        container.requestFullscreen();
    } else {
        document.exitFullscreen();
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Start uptime counter
setInterval(() => {
    const uptimeEl = document.getElementById('termUptime');
    if (uptimeEl) uptimeEl.textContent = formatUptime();
    
    const memoryEl = document.getElementById('termMemory');
    if (memoryEl) memoryEl.textContent = Math.floor(Math.random() * 30 + 20) + '%';
}, 1000);

// Focus input on click
document.getElementById('terminalBody').addEventListener('click', () => {
    document.getElementById('terminalInput').focus();
});
</script>
