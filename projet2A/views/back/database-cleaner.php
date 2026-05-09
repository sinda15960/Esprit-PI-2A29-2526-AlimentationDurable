<!-- Database Cleaner Tool -->
<div class="cleaner-container">
    <div class="cleaner-header">
        <h2>🧹 Database Cleaner & Optimizer</h2>
        <p>Keep your database clean and performant</p>
    </div>
    
    <div class="cleaner-stats" id="cleanerStats">
        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-info">
                <h3>Total Size</h3>
                <p id="dbSize">-- MB</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <h3>Total Users</h3>
                <p id="totalUsers"><?php echo count($users); ?></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">⏰</div>
            <div class="stat-info">
                <h3>Inactive > 1 year</h3>
                <p id="inactiveUsers">--</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📝</div>
            <div class="stat-info">
                <h3>Old Logs</h3>
                <p id="oldLogs">--</p>
            </div>
        </div>
    </div>
    
    <div class="cleaner-actions">
        <div class="action-card">
            <div class="action-icon">🗑️</div>
            <h3>Delete Inactive Users</h3>
            <p>Remove users who haven't logged in for over 1 year</p>
            <div class="action-preview" id="inactivePreview">
                <span class="preview-count">--</span> users to delete
            </div>
            <button class="action-btn warning" onclick="confirmClean('inactive')">🧹 Clean Inactive Users</button>
        </div>
        
        <div class="action-card">
            <div class="action-icon">📋</div>
            <h3>Clear Old Logs</h3>
            <p>Remove activity logs older than 6 months</p>
            <div class="action-preview" id="logsPreview">
                <span class="preview-count">--</span> logs to clear
            </div>
            <button class="action-btn" onclick="confirmClean('logs')">🧹 Clear Old Logs</button>
        </div>
        
        <div class="action-card">
            <div class="action-icon">📬</div>
            <h3>Delete Old Messages</h3>
            <p>Remove contact messages older than 3 months</p>
            <div class="action-preview" id="messagesPreview">
                <span class="preview-count">--</span> messages to delete
            </div>
            <button class="action-btn" onclick="confirmClean('messages')">🧹 Delete Old Messages</button>
        </div>
        
        <div class="action-card">
            <div class="action-icon">🔧</div>
            <h3>Optimize Tables</h3>
            <p>Reorganize database tables for better performance</p>
            <div class="action-preview">
                <span class="preview-count">💪</span> Recommended weekly
            </div>
            <button class="action-btn success" onclick="optimizeTables()">⚡ Optimize Now</button>
        </div>
    </div>
    
    <div class="simulation-mode">
        <label class="simulation-toggle">
            <input type="checkbox" id="simulationMode" checked>
            <span class="toggle-slider"></span>
            <span class="toggle-label">🧪 Simulation Mode (Preview only, no actual deletion)</span>
        </label>
    </div>
    
    <div class="cleaner-history" id="cleanerHistory">
        <h3>📜 Cleanup History</h3>
        <div class="history-list">
            <div class="history-placeholder">No cleanup operations yet</div>
        </div>
    </div>
</div>

<style>
.cleaner-container {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.cleaner-header {
    text-align: center;
    margin-bottom: 2rem;
}

.cleaner-header h2 {
    font-size: 1.8rem;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.cleaner-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.cleaner-stats .stat-card {
    background: #f8fafc;
    padding: 1rem;
    border-radius: 12px;
    text-align: center;
}

.cleaner-stats .stat-icon {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.cleaner-stats .stat-info h3 {
    font-size: 0.8rem;
    color: #64748b;
    margin-bottom: 0.25rem;
}

.cleaner-stats .stat-info p {
    font-size: 1.5rem;
    font-weight: bold;
    color: #1e293b;
}

.cleaner-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.action-card {
    background: #f8fafc;
    border-radius: 16px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s;
    border: 1px solid #e2e8f0;
}

.action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.action-icon {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.action-card h3 {
    font-size: 1.1rem;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.action-card p {
    font-size: 0.8rem;
    color: #64748b;
    margin-bottom: 1rem;
}

.action-preview {
    background: #e2e8f0;
    padding: 0.5rem;
    border-radius: 8px;
    font-size: 0.8rem;
    margin-bottom: 1rem;
}

.preview-count {
    font-weight: bold;
    color: #16a34a;
    font-size: 1.1rem;
}

.action-btn {
    width: 100%;
    padding: 0.6rem;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    background: #3b82f6;
    color: white;
}

.action-btn.warning {
    background: #ef4444;
}

.action-btn.success {
    background: #16a34a;
}

.action-btn:hover {
    transform: translateY(-2px);
    filter: brightness(1.05);
}

.simulation-mode {
    background: #fef3c7;
    padding: 1rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.simulation-toggle {
    display: flex;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
}

.simulation-toggle input {
    width: 40px;
    height: 20px;
    appearance: none;
    background: #cbd5e0;
    border-radius: 20px;
    position: relative;
    cursor: pointer;
    transition: all 0.3s;
}

.simulation-toggle input:checked {
    background: #16a34a;
}

.simulation-toggle input::before {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    background: white;
    border-radius: 50%;
    top: 2px;
    left: 2px;
    transition: all 0.3s;
}

.simulation-toggle input:checked::before {
    left: 22px;
}

.toggle-label {
    font-size: 0.9rem;
    color: #92400e;
}

.cleaner-history {
    background: #f8fafc;
    border-radius: 16px;
    padding: 1rem;
}

.cleaner-history h3 {
    margin-bottom: 1rem;
    color: #1e293b;
}

.history-list {
    max-height: 150px;
    overflow-y: auto;
}

.history-item {
    padding: 0.5rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.8rem;
}

.history-item .date {
    color: #64748b;
}

.history-item .action {
    color: #16a34a;
    font-weight: 500;
}
</style>

<script>
// Simulated data (à remplacer par des appels API réels)
let cleanupHistory = JSON.parse(localStorage.getItem('cleanupHistory') || '[]');

function loadCleanerStats() {
    // Simulation stats
    document.getElementById('dbSize').textContent = '24.5 MB';
    document.getElementById('inactiveUsers').textContent = Math.floor(Math.random() * 20) + 3;
    document.getElementById('oldLogs').textContent = Math.floor(Math.random() * 500) + 100;
    
    document.querySelector('#inactivePreview .preview-count').textContent = document.getElementById('inactiveUsers').textContent;
    document.querySelector('#logsPreview .preview-count').textContent = document.getElementById('oldLogs').textContent;
    document.querySelector('#messagesPreview .preview-count').textContent = Math.floor(Math.random() * 50) + 20;
}

function confirmClean(type) {
    const simulationMode = document.getElementById('simulationMode').checked;
    let message = '';
    let count = 0;
    
    switch(type) {
        case 'inactive':
            count = document.getElementById('inactiveUsers').textContent;
            message = `Are you sure you want to delete ${count} inactive users? ${simulationMode ? '(SIMULATION MODE - No actual deletion)' : ''}`;
            break;
        case 'logs':
            count = document.getElementById('oldLogs').textContent;
            message = `Are you sure you want to delete ${count} old logs? ${simulationMode ? '(SIMULATION MODE - No actual deletion)' : ''}`;
            break;
        case 'messages':
            count = document.querySelector('#messagesPreview .preview-count').textContent;
            message = `Are you sure you want to delete ${count} old messages? ${simulationMode ? '(SIMULATION MODE - No actual deletion)' : ''}`;
            break;
    }
    
    if (confirm(message)) {
        performCleanup(type, simulationMode);
    }
}

function performCleanup(type, simulationMode) {
    let actionName = '';
    let count = 0;
    
    switch(type) {
        case 'inactive':
            actionName = 'Deleted inactive users';
            count = document.getElementById('inactiveUsers').textContent;
            if (!simulationMode) {
                document.getElementById('inactiveUsers').textContent = '0';
                document.querySelector('#inactivePreview .preview-count').textContent = '0';
            }
            break;
        case 'logs':
            actionName = 'Cleared old logs';
            count = document.getElementById('oldLogs').textContent;
            if (!simulationMode) {
                document.getElementById('oldLogs').textContent = '0';
                document.querySelector('#logsPreview .preview-count').textContent = '0';
            }
            break;
        case 'messages':
            actionName = 'Deleted old messages';
            count = document.querySelector('#messagesPreview .preview-count').textContent;
            if (!simulationMode) {
                document.querySelector('#messagesPreview .preview-count').textContent = '0';
            }
            break;
    }
    
    // Add to history
    const historyItem = {
        date: new Date().toLocaleString(),
        action: actionName,
        count: count,
        simulation: simulationMode
    };
    
    cleanupHistory.unshift(historyItem);
    if (cleanupHistory.length > 10) cleanupHistory.pop();
    localStorage.setItem('cleanupHistory', JSON.stringify(cleanupHistory));
    
    renderHistory();
    
    alert(`${simulationMode ? '[SIMULATION] ' : ''}${actionName}: ${count} items affected`);
    
    if (!simulationMode) {
        showToast('✅ Cleanup completed successfully');
    } else {
        showToast('🔬 Simulation mode: No actual changes were made');
    }
}

function optimizeTables() {
    const simulationMode = document.getElementById('simulationMode').checked;
    
    if (confirm(`Optimize database tables? ${simulationMode ? '(SIMULATION MODE)' : ''}`)) {
        if (!simulationMode) {
            showToast('🔄 Optimizing tables...');
            setTimeout(() => {
                showToast('✅ Tables optimized successfully!');
                
                cleanupHistory.unshift({
                    date: new Date().toLocaleString(),
                    action: 'Optimized tables',
                    count: '-',
                    simulation: false
                });
                if (cleanupHistory.length > 10) cleanupHistory.pop();
                localStorage.setItem('cleanupHistory', JSON.stringify(cleanupHistory));
                renderHistory();
            }, 2000);
        } else {
            showToast('🔬 [SIMULATION] Tables would be optimized');
            
            cleanupHistory.unshift({
                date: new Date().toLocaleString(),
                action: 'Optimized tables (SIMULATION)',
                count: '-',
                simulation: true
            });
            if (cleanupHistory.length > 10) cleanupHistory.pop();
            localStorage.setItem('cleanupHistory', JSON.stringify(cleanupHistory));
            renderHistory();
        }
    }
}

function renderHistory() {
    const historyList = document.querySelector('.history-list');
    if (!historyList) return;
    
    if (cleanupHistory.length === 0) {
        historyList.innerHTML = '<div class="history-placeholder">No cleanup operations yet</div>';
        return;
    }
    
    historyList.innerHTML = cleanupHistory.map(item => `
        <div class="history-item">
            <span class="date">${item.date}</span>
            <span class="action">${item.action} ${item.count > 0 ? `(${item.count})` : ''}</span>
            ${item.simulation ? '<span style="color:#f59e0b">🔬 SIM</span>' : '<span style="color:#16a34a">✅ DONE</span>'}
        </div>
    `).join('');
}

function showToast(msg) {
    const toast = document.createElement('div');
    toast.textContent = msg;
    toast.style.cssText = 'position:fixed;bottom:20px;right:20px;background:#16a34a;color:white;padding:10px20px;border-radius:10px;z-index:9999';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    loadCleanerStats();
    renderHistory();
});
</script>
