<!-- Incognito Mode - Preview Front Office as User -->
<div class="incognito-container">
    <div class="incognito-header">
        <div class="incognito-status" id="incognitoStatus">
            <span class="status-icon">👁️</span>
            <span class="status-text">Normal Mode</span>
        </div>
        <button class="incognito-toggle" id="incognitoToggle" onclick="toggleIncognitoMode()">
            🕵️ Enter Incognito Mode
        </button>
        <div class="incognito-info">
            <span class="info-icon">ℹ️</span>
            <span class="info-text">Preview your site as a regular user without logging out</span>
        </div>
    </div>
    
    <div id="incognitoFrameContainer" class="incognito-frame-container" style="display: none;">
        <div class="incognito-controls">
            <div class="incognito-badge">
                <span class="badge">🕵️‍♂️ INCOGNITO MODE ACTIVE</span>
                <span class="badge-sub">Viewing as: Regular User</span>
            </div>
            <div class="incognito-actions">
                <a href="index.php?action=home" target="incognitoFrame" class="action-btn">🏠 Home</a>
                <a href="index.php?action=register" target="incognitoFrame" class="action-btn">📝 Register</a>
                <a href="index.php?action=login" target="incognitoFrame" class="action-btn">🔑 Login</a>
                <button class="action-btn close" onclick="toggleIncognitoMode()">✖ Close</button>
            </div>
            <div class="incognito-tip">
                💡 Tip: You're seeing exactly what users see. Admin actions are disabled.
            </div>
        </div>
        <iframe name="incognitoFrame" id="incognitoFrame" class="incognito-frame" src="index.php?action=home"></iframe>
    </div>
    
    <div id="incognitoPreview" class="incognito-preview" style="display: block;">
        <div class="preview-card" onclick="toggleIncognitoMode()">
            <div class="preview-icon">🕵️</div>
            <h3>Preview as User</h3>
            <p>See exactly what your users see without logging out</p>
            <div class="preview-features">
                <span>✅ Test registration flow</span>
                <span>✅ Check login page</span>
                <span>✅ View homepage as user</span>
                <span>✅ No admin sidebar visible</span>
            </div>
            <button class="preview-btn">Start Incognito Mode →</button>
        </div>
    </div>
</div>

<style>
.incognito-container {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.incognito-header {
    background: linear-gradient(135deg, #1e293b, #0f172a);
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    color: white;
}

.incognito-status {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255,255,255,0.1);
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.9rem;
}

.status-icon {
    font-size: 1.2rem;
}

.incognito-toggle {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    border: none;
    padding: 0.6rem 1.5rem;
    border-radius: 50px;
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.incognito-toggle:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(139,92,246,0.4);
}

.incognito-toggle.active {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.incognito-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255,255,255,0.05);
    padding: 0.3rem 1rem;
    border-radius: 50px;
    font-size: 0.8rem;
}

/* Frame Container */
.incognito-frame-container {
    height: calc(100vh - 250px);
    min-height: 600px;
    display: flex;
    flex-direction: column;
}

.incognito-controls {
    background: #f1f5f9;
    padding: 0.75rem 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    border-bottom: 2px solid #8b5cf6;
}

.incognito-badge {
    display: flex;
    flex-direction: column;
}

.badge {
    background: #8b5cf6;
    color: white;
    padding: 0.2rem 0.8rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: bold;
    letter-spacing: 1px;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.badge-sub {
    font-size: 0.6rem;
    color: #64748b;
    margin-top: 0.2rem;
}

.incognito-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.action-btn {
    background: white;
    border: 1px solid #e2e8f0;
    padding: 0.3rem 1rem;
    border-radius: 20px;
    text-decoration: none;
    color: #334155;
    font-size: 0.8rem;
    transition: all 0.3s;
    cursor: pointer;
}

.action-btn:hover {
    background: #8b5cf6;
    color: white;
    border-color: #8b5cf6;
}

.action-btn.close {
    background: #ef4444;
    color: white;
    border-color: #ef4444;
}

.incognito-tip {
    font-size: 0.7rem;
    color: #64748b;
    background: #e2e8f0;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
}

.incognito-frame {
    width: 100%;
    flex: 1;
    border: none;
    background: white;
}

/* Preview Card */
.incognito-preview {
    padding: 3rem;
    text-align: center;
    background: linear-gradient(135deg, #f8fafc, #ecfdf5);
}

.preview-card {
    max-width: 500px;
    margin: 0 auto;
    background: white;
    border-radius: 30px;
    padding: 2.5rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    transition: all 0.3s;
}

.preview-card:hover {
    transform: translateY(-10px);
}

.preview-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.preview-card h3 {
    font-size: 1.5rem;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.preview-card p {
    color: #64748b;
    margin-bottom: 1.5rem;
}

.preview-features {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    text-align: left;
    background: #f8fafc;
    padding: 1rem;
    border-radius: 16px;
}

.preview-features span {
    font-size: 0.85rem;
    color: #334155;
}

.preview-btn {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    border: none;
    padding: 0.8rem 1.5rem;
    border-radius: 50px;
    color: white;
    font-weight: 600;
    cursor: pointer;
    width: 100%;
    transition: all 0.3s;
}

.preview-btn:hover {
    transform: translateY(-2px);
}
</style>

<script>
function toggleIncognitoMode() {
    const container = document.getElementById('incognitoFrameContainer');
    const preview = document.getElementById('incognitoPreview');
    const toggleBtn = document.getElementById('incognitoToggle');
    const status = document.getElementById('incognitoStatus');
    
    if (container.style.display === 'none') {
        container.style.display = 'flex';
        preview.style.display = 'none';
        toggleBtn.innerHTML = '🚪 Exit Incognito Mode';
        toggleBtn.classList.add('active');
        status.innerHTML = '<span class="status-icon">🕵️</span><span class="status-text">Incognito Mode</span>';
        status.style.background = 'rgba(139,92,246,0.2)';
        
        // Sauvegarder en session
        sessionStorage.setItem('incognitoMode', 'active');
    } else {
        container.style.display = 'none';
        preview.style.display = 'block';
        toggleBtn.innerHTML = '🕵️ Enter Incognito Mode';
        toggleBtn.classList.remove('active');
        status.innerHTML = '<span class="status-icon">👁️</span><span class="status-text">Normal Mode</span>';
        status.style.background = 'rgba(255,255,255,0.1)';
        
        sessionStorage.removeItem('incognitoMode');
    }
}
</script>
