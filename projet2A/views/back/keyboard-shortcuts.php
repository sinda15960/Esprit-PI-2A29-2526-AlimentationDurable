<!-- Keyboard Shortcuts Manager -->
<div class="shortcuts-container">
    <div class="shortcuts-header">
        <h2>⌨️ Keyboard Shortcuts Manager</h2>
        <p>Customize your keyboard shortcuts for faster navigation</p>
    </div>
    
    <div class="shortcuts-grid">
        <div class="shortcuts-card">
            <h3>📊 Navigation Shortcuts</h3>
            <div class="shortcut-item" data-action="dashboard">
                <div class="shortcut-info">
                    <span class="shortcut-icon">📊</span>
                    <span class="shortcut-name">Go to Dashboard</span>
                </div>
                <div class="shortcut-keys">
                    <input type="text" class="shortcut-input" id="shortcut-dashboard" value="Ctrl+Shift+D" readonly>
                    <button class="edit-shortcut" onclick="editShortcut('dashboard')">✏️</button>
                    <button class="reset-shortcut" onclick="resetShortcut('dashboard')">↺</button>
                </div>
            </div>
            
            <div class="shortcut-item" data-action="users">
                <div class="shortcut-info">
                    <span class="shortcut-icon">👥</span>
                    <span class="shortcut-name">Users Management</span>
                </div>
                <div class="shortcut-keys">
                    <input type="text" class="shortcut-input" id="shortcut-users" value="Ctrl+Shift+U" readonly>
                    <button class="edit-shortcut" onclick="editShortcut('users')">✏️</button>
                    <button class="reset-shortcut" onclick="resetShortcut('users')">↺</button>
                </div>
            </div>
            
            <div class="shortcut-item" data-action="messages">
                <div class="shortcut-info">
                    <span class="shortcut-icon">📬</span>
                    <span class="shortcut-name">Contact Messages</span>
                </div>
                <div class="shortcut-keys">
                    <input type="text" class="shortcut-input" id="shortcut-messages" value="Ctrl+Shift+M" readonly>
                    <button class="edit-shortcut" onclick="editShortcut('messages')">✏️</button>
                    <button class="reset-shortcut" onclick="resetShortcut('messages')">↺</button>
                </div>
            </div>
            
            <div class="shortcut-item" data-action="globe">
                <div class="shortcut-info">
                    <span class="shortcut-icon">🌍</span>
                    <span class="shortcut-name">Live Globe</span>
                </div>
                <div class="shortcut-keys">
                    <input type="text" class="shortcut-input" id="shortcut-globe" value="Ctrl+Shift+G" readonly>
                    <button class="edit-shortcut" onclick="editShortcut('globe')">✏️</button>
                    <button class="reset-shortcut" onclick="resetShortcut('globe')">↺</button>
                </div>
            </div>
            
            <div class="shortcut-item" data-action="terminal">
                <div class="shortcut-info">
                    <span class="shortcut-icon">💻</span>
                    <span class="shortcut-name">Retro Terminal</span>
                </div>
                <div class="shortcut-keys">
                    <input type="text" class="shortcut-input" id="shortcut-terminal" value="Ctrl+Shift+T" readonly>
                    <button class="edit-shortcut" onclick="editShortcut('terminal')">✏️</button>
                    <button class="reset-shortcut" onclick="resetShortcut('terminal')">↺</button>
                </div>
            </div>
        </div>
        
        <div class="shortcuts-card">
            <h3>⚡ Action Shortcuts</h3>
            <div class="shortcut-item" data-action="search">
                <div class="shortcut-info">
                    <span class="shortcut-icon">🔍</span>
                    <span class="shortcut-name">Focus Search</span>
                </div>
                <div class="shortcut-keys">
                    <input type="text" class="shortcut-input" id="shortcut-search" value="/" readonly>
                    <button class="edit-shortcut" onclick="editShortcut('search')">✏️</button>
                    <button class="reset-shortcut" onclick="resetShortcut('search')">↺</button>
                </div>
            </div>
            
            <div class="shortcut-item" data-action="refresh">
                <div class="shortcut-info">
                    <span class="shortcut-icon">🔄</span>
                    <span class="shortcut-name">Refresh Data</span>
                </div>
                <div class="shortcut-keys">
                    <input type="text" class="shortcut-input" id="shortcut-refresh" value="Ctrl+R" readonly>
                    <button class="edit-shortcut" onclick="editShortcut('refresh')">✏️</button>
                    <button class="reset-shortcut" onclick="resetShortcut('refresh')">↺</button>
                </div>
            </div>
            
            <div class="shortcut-item" data-action="help">
                <div class="shortcut-info">
                    <span class="shortcut-icon">❓</span>
                    <span class="shortcut-name">Show Help</span>
                </div>
                <div class="shortcut-keys">
                    <input type="text" class="shortcut-input" id="shortcut-help" value="?" readonly>
                    <button class="edit-shortcut" onclick="editShortcut('help')">✏️</button>
                    <button class="reset-shortcut" onclick="resetShortcut('help')">↺</button>
                </div>
            </div>
            
            <div class="shortcut-item" data-action="notifications">
                <div class="shortcut-info">
                    <span class="shortcut-icon">🔔</span>
                    <span class="shortcut-name">Open Notifications</span>
                </div>
                <div class="shortcut-keys">
                    <input type="text" class="shortcut-input" id="shortcut-notifications" value="Ctrl+N" readonly>
                    <button class="edit-shortcut" onclick="editShortcut('notifications')">✏️</button>
                    <button class="reset-shortcut" onclick="resetShortcut('notifications')">↺</button>
                </div>
            </div>
        </div>
        
        <div class="shortcuts-card">
            <h3>🎨 Theme Shortcuts</h3>
            <div class="shortcut-item" data-action="darkmode">
                <div class="shortcut-info">
                    <span class="shortcut-icon">🌙</span>
                    <span class="shortcut-name">Toggle Dark Mode</span>
                </div>
                <div class="shortcut-keys">
                    <input type="text" class="shortcut-input" id="shortcut-darkmode" value="Ctrl+Shift+H" readonly>
                    <button class="edit-shortcut" onclick="editShortcut('darkmode')">✏️</button>
                    <button class="reset-shortcut" onclick="resetShortcut('darkmode')">↺</button>
                </div>
            </div>
            
            <div class="shortcut-item" data-action="incognito">
                <div class="shortcut-info">
                    <span class="shortcut-icon">🕵️</span>
                    <span class="shortcut-name">Incognito Mode</span>
                </div>
                <div class="shortcut-keys">
                    <input type="text" class="shortcut-input" id="shortcut-incognito" value="Ctrl+I" readonly>
                    <button class="edit-shortcut" onclick="editShortcut('incognito')">✏️</button>
                    <button class="reset-shortcut" onclick="resetShortcut('incognito')">↺</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="shortcuts-footer">
        <button class="save-all-btn" onclick="saveAllShortcuts()">💾 Save All Changes</button>
        <button class="reset-all-btn" onclick="resetAllShortcuts()">↺ Reset to Defaults</button>
        <div class="shortcuts-status" id="shortcutsStatus"></div>
    </div>
    
    <div class="shortcuts-guide">
        <h4>📖 How to set a shortcut:</h4>
        <ol>
            <li>Click the ✏️ button next to a shortcut</li>
            <li>Press your desired key combination</li>
            <li>Press "Save" or "Cancel"</li>
            <li>Supported: Ctrl, Alt, Shift + any letter/number</li>
        </ol>
    </div>
</div>

<!-- Help Modal -->
<div id="shortcutsHelpModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>⌨️ Available Shortcuts</h2>
            <span class="modal-close" onclick="closeHelpModal()">&times;</span>
        </div>
        <div class="modal-body" id="shortcutsHelpContent">
            <!-- Dynamic content -->
        </div>
    </div>
</div>

<style>
.shortcuts-container {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.shortcuts-header {
    text-align: center;
    margin-bottom: 2rem;
}

.shortcuts-header h2 {
    font-size: 1.8rem;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.shortcuts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.shortcuts-card {
    background: #f8fafc;
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
    transition: all 0.3s;
}

.shortcuts-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.shortcuts-card h3 {
    font-size: 1.1rem;
    color: #1e293b;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e2e8f0;
}

.shortcut-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.shortcut-item:last-child {
    border-bottom: none;
}

.shortcut-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.shortcut-icon {
    font-size: 1.2rem;
}

.shortcut-name {
    font-size: 0.9rem;
    color: #334155;
}

.shortcut-keys {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.shortcut-input {
    background: white;
    border: 1px solid #cbd5e0;
    border-radius: 8px;
    padding: 0.3rem 0.6rem;
    font-size: 0.8rem;
    font-family: monospace;
    font-weight: bold;
    width: 110px;
    text-align: center;
    cursor: pointer;
}

.shortcut-input.editing {
    background: #fef3c7;
    border-color: #f59e0b;
    outline: none;
}

.edit-shortcut, .reset-shortcut {
    background: none;
    border: none;
    font-size: 1rem;
    cursor: pointer;
    padding: 0.2rem 0.4rem;
    border-radius: 6px;
    transition: all 0.3s;
}

.edit-shortcut:hover {
    background: #e2e8f0;
    transform: scale(1.1);
}

.reset-shortcut:hover {
    background: #fee2e2;
    transform: scale(1.1);
}

.shortcuts-footer {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
}

.save-all-btn, .reset-all-btn {
    padding: 0.6rem 1.5rem;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.save-all-btn {
    background: linear-gradient(135deg, #16a34a, #14532d);
    color: white;
    border: none;
}

.save-all-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(22,163,74,0.3);
}

.reset-all-btn {
    background: #e2e8f0;
    color: #475569;
    border: none;
}

.reset-all-btn:hover {
    background: #cbd5e0;
    transform: translateY(-2px);
}

.shortcuts-status {
    display: flex;
    align-items: center;
    font-size: 0.8rem;
    color: #16a34a;
}

.shortcuts-guide {
    margin-top: 2rem;
    padding: 1rem;
    background: #f1f5f9;
    border-radius: 12px;
}

.shortcuts-guide h4 {
    margin-bottom: 0.5rem;
    color: #1e293b;
}

.shortcuts-guide ol {
    margin-left: 1.5rem;
    color: #475569;
    font-size: 0.85rem;
}

.shortcuts-guide li {
    margin-bottom: 0.25rem;
}
</style>

<script>
let currentEditing = null;

// Load shortcuts from localStorage
function loadShortcuts() {
    const defaults = {
        dashboard: 'Ctrl+Shift+D',
        users: 'Ctrl+Shift+U',
        messages: 'Ctrl+Shift+M',
        globe: 'Ctrl+Shift+G',
        terminal: 'Ctrl+Shift+T',
        search: '/',
        refresh: 'Ctrl+R',
        help: '?',
        notifications: 'Ctrl+N',
        darkmode: 'Ctrl+Shift+H',
        incognito: 'Ctrl+I'
    };
    
    const saved = JSON.parse(localStorage.getItem('adminShortcuts') || '{}');
    const shortcuts = { ...defaults, ...saved };
    
    for (const [key, value] of Object.entries(shortcuts)) {
        const input = document.getElementById(`shortcut-${key}`);
        if (input) input.value = value;
    }
    
    return shortcuts;
}

// Save all shortcuts
function saveAllShortcuts() {
    const shortcuts = {};
    const inputs = document.querySelectorAll('.shortcut-input');
    inputs.forEach(input => {
        const id = input.id.replace('shortcut-', '');
        shortcuts[id] = input.value;
    });
    
    localStorage.setItem('adminShortcuts', JSON.stringify(shortcuts));
    applyShortcuts();
    
    const status = document.getElementById('shortcutsStatus');
    status.innerHTML = '✅ All shortcuts saved!';
    setTimeout(() => status.innerHTML = '', 2000);
}

// Reset to defaults
function resetAllShortcuts() {
    const defaults = {
        dashboard: 'Ctrl+Shift+D',
        users: 'Ctrl+Shift+U',
        messages: 'Ctrl+Shift+M',
        globe: 'Ctrl+Shift+G',
        terminal: 'Ctrl+Shift+T',
        search: '/',
        refresh: 'Ctrl+R',
        help: '?',
        notifications: 'Ctrl+N',
        darkmode: 'Ctrl+Shift+H',
        incognito: 'Ctrl+I'
    };
    
    for (const [key, value] of Object.entries(defaults)) {
        const input = document.getElementById(`shortcut-${key}`);
        if (input) input.value = value;
    }
    
    localStorage.setItem('adminShortcuts', JSON.stringify(defaults));
    applyShortcuts();
    
    const status = document.getElementById('shortcutsStatus');
    status.innerHTML = '↺ Reset to defaults!';
    setTimeout(() => status.innerHTML = '', 2000);
}

// Apply shortcuts globally
function applyShortcuts() {
    const shortcuts = JSON.parse(localStorage.getItem('adminShortcuts') || '{}');
    
    document.removeEventListener('keydown', globalShortcutHandler);
    document.addEventListener('keydown', globalShortcutHandler);
    
    function globalShortcutHandler(e) {
        // Build key combination string
        let combo = [];
        if (e.ctrlKey) combo.push('Ctrl');
        if (e.altKey) combo.push('Alt');
        if (e.shiftKey) combo.push('Shift');
        combo.push(e.key.toUpperCase().replace(/^[A-Z]$/, match => match));
        
        const pressed = combo.join('+');
        
        for (const [action, shortcut] of Object.entries(shortcuts)) {
            if (pressed === shortcut) {
                e.preventDefault();
                executeAction(action);
                break;
            }
        }
        
        // Single key shortcuts
        if (shortcuts.search === e.key && !e.ctrlKey && !e.altKey && !e.shiftKey) {
            e.preventDefault();
            executeAction('search');
        }
        if (shortcuts.help === e.key && !e.ctrlKey && !e.altKey && !e.shiftKey) {
            e.preventDefault();
            executeAction('help');
        }
    }
}

function executeAction(action) {
    switch(action) {
        case 'dashboard':
            window.location.href = 'index.php?action=admin_dashboard';
            break;
        case 'users':
            window.location.href = 'index.php?action=admin_users';
            break;
        case 'messages':
            document.getElementById('widget-messages')?.scrollIntoView({ behavior: 'smooth' });
            break;
        case 'globe':
            window.location.href = 'index.php?action=admin_globe';
            break;
        case 'terminal':
            window.location.href = 'index.php?action=admin_terminal';
            break;
        case 'search':
            document.getElementById('searchInput')?.focus();
            break;
        case 'refresh':
            location.reload();
            break;
        case 'help':
            showShortcutsHelp();
            break;
        case 'notifications':
            document.getElementById('widget-notifications')?.scrollIntoView({ behavior: 'smooth' });
            break;
        case 'darkmode':
            document.body.classList.toggle('dark-mode');
            break;
        case 'incognito':
            document.getElementById('incognitoToggle')?.click();
            break;
    }
}

function editShortcut(action) {
    const input = document.getElementById(`shortcut-${action}`);
    if (!input) return;
    
    if (currentEditing) {
        const prevInput = document.getElementById(`shortcut-${currentEditing}`);
        if (prevInput) {
            prevInput.readOnly = true;
            prevInput.classList.remove('editing');
        }
    }
    
    currentEditing = action;
    input.readOnly = false;
    input.classList.add('editing');
    input.value = '';
    input.placeholder = 'Press keys...';
    input.focus();
    
    function keyHandler(e) {
        e.preventDefault();
        let combo = [];
        if (e.ctrlKey) combo.push('Ctrl');
        if (e.altKey) combo.push('Alt');
        if (e.shiftKey) combo.push('Shift');
        
        let key = e.key.toUpperCase();
        if (key === 'CONTROL' || key === 'ALT' || key === 'SHIFT') return;
        
        combo.push(key);
        const result = combo.join('+');
        
        input.value = result;
        input.readOnly = true;
        input.classList.remove('editing');
        input.placeholder = '';
        
        document.removeEventListener('keydown', keyHandler);
        currentEditing = null;
        
        showToast(`Shortcut for ${action} set to ${result}`);
    }
    
    document.addEventListener('keydown', keyHandler, { once: true });
}

function resetShortcut(action) {
    const defaults = {
        dashboard: 'Ctrl+Shift+D',
        users: 'Ctrl+Shift+U',
        messages: 'Ctrl+Shift+M',
        globe: 'Ctrl+Shift+G',
        terminal: 'Ctrl+Shift+T',
        search: '/',
        refresh: 'Ctrl+R',
        help: '?',
        notifications: 'Ctrl+N',
        darkmode: 'Ctrl+Shift+H',
        incognito: 'Ctrl+I'
    };
    
    const input = document.getElementById(`shortcut-${action}`);
    if (input) input.value = defaults[action];
    
    showToast(`Shortcut for ${action} reset to ${defaults[action]}`);
}

function showShortcutsHelp() {
    const shortcuts = JSON.parse(localStorage.getItem('adminShortcuts') || '{}');
    const modal = document.getElementById('shortcutsHelpModal');
    const content = document.getElementById('shortcutsHelpContent');
    
    let html = '<div class="help-grid">';
    for (const [action, shortcut] of Object.entries(shortcuts)) {
        const actionName = action.charAt(0).toUpperCase() + action.slice(1);
        html += `
            <div class="help-item">
                <span class="help-action">${actionName}</span>
                <span class="help-shortcut"><kbd>${shortcut}</kbd></span>
            </div>
        `;
    }
    html += '</div>';
    content.innerHTML = html;
    modal.style.display = 'block';
}

function closeHelpModal() {
    document.getElementById('shortcutsHelpModal').style.display = 'none';
}

function showToast(message) {
    const status = document.getElementById('shortcutsStatus');
    status.innerHTML = `⌨️ ${message}`;
    setTimeout(() => status.innerHTML = '', 2000);
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    loadShortcuts();
    applyShortcuts();
});
</script>
