<!-- Admin Leaderboard -->
<div class="leaderboard-container">
    <div class="leaderboard-header">
        <h2>🏆 Admin Hall of Fame</h2>
        <p>Celebrating our most active administrators</p>
    </div>
    
    <div class="leaderboard-tabs">
        <button class="tab-btn active" data-tab="monthly">📅 Monthly</button>
        <button class="tab-btn" data-tab="alltime">🏆 All-Time</button>
        <button class="tab-btn" data-tab="achievements">🎖️ Achievements</button>
    </div>
    
    <div id="monthlyTab" class="tab-content active">
        <div class="leaderboard-table">
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Admin</th>
                        <th>Actions</th>
                        <th>Hours Active</th>
                        <th>Responses</th>
                        <th>Score</th>
                        <th>Badge</th>
                    </tr>
                </thead>
                <tbody id="monthlyLeaderboard">
                    <!-- Dynamique -->
                </tbody>
            </table>
        </div>
    </div>
    
    <div id="alltimeTab" class="tab-content">
        <div class="leaderboard-table">
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Admin</th>
                        <th>Total Actions</th>
                        <th>Total Hours</th>
                        <th>Total Responses</th>
                        <th>All-Time Score</th>
                        <th>Legendary Badge</th>
                    </tr>
                </thead>
                <tbody id="alltimeLeaderboard">
                    <!-- Dynamique -->
                </tbody>
            </table>
        </div>
    </div>
    
    <div id="achievementsTab" class="tab-content">
        <div class="achievements-grid" id="achievementsGrid">
            <!-- Dynamique -->
        </div>
    </div>
    
    <div class="leaderboard-footer">
        <div class="personal-stats" id="personalStats">
            <h3>📊 Your Stats This Month</h3>
            <div class="stats-row">
                <div class="stat">🎯 Actions: <span id="userActions">0</span></div>
                <div class="stat">⏱️ Hours: <span id="userHours">0</span></div>
                <div class="stat">💬 Responses: <span id="userResponses">0</span></div>
                <div class="stat">📈 Rank: <span id="userRank">#0</span></div>
            </div>
        </div>
    </div>
</div>

<style>
.leaderboard-container {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.leaderboard-header {
    text-align: center;
    margin-bottom: 2rem;
}

.leaderboard-header h2 {
    font-size: 1.8rem;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.leaderboard-tabs {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.tab-btn {
    padding: 0.6rem 1.5rem;
    background: #f1f5f9;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
}

.tab-btn.active {
    background: linear-gradient(135deg, #16a34a, #14532d);
    color: white;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
    animation: fadeIn 0.3s ease;
}

.leaderboard-table {
    overflow-x: auto;
}

.leaderboard-table table {
    width: 100%;
    border-collapse: collapse;
}

.leaderboard-table th {
    padding: 1rem;
    text-align: left;
    background: #f8fafc;
    color: #475569;
    font-weight: 600;
    border-bottom: 2px solid #e2e8f0;
}

.leaderboard-table td {
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.leaderboard-table tr:hover {
    background: #f8fafc;
}

.rank-1 {
    background: linear-gradient(135deg, #fef3c7, #fffbeb);
    font-weight: bold;
    color: #d97706;
}

.rank-2 {
    background: #f1f5f9;
}

.rank-3 {
    background: #fef2f2;
}

.medal {
    font-size: 1.3rem;
}

.achievements-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.achievement-card {
    background: #f8fafc;
    border-radius: 16px;
    padding: 1.2rem;
    text-align: center;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.achievement-card.unlocked {
    border-color: #16a34a;
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
}

.achievement-card:hover {
    transform: translateY(-5px);
}

.achievement-icon {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.achievement-name {
    font-weight: bold;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.achievement-desc {
    font-size: 0.7rem;
    color: #64748b;
}

.achievement-locked {
    opacity: 0.5;
    filter: grayscale(0.3);
}

.personal-stats {
    margin-top: 2rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
    border-radius: 16px;
}

.personal-stats h3 {
    margin-bottom: 1rem;
    color: #166534;
}

.stats-row {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 1rem;
}

.stats-row .stat {
    text-align: center;
    font-size: 0.9rem;
    color: #475569;
}

.stats-row .stat span {
    display: block;
    font-size: 1.5rem;
    font-weight: bold;
    color: #16a34a;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 768px) {
    .stats-row {
        flex-direction: column;
        align-items: center;
    }
}
</style>

<script>
// Simulated admin data (à remplacer par données réelles)
const admins = [
    { name: 'MasterAdmin', avatar: '👑', actions: 847, hours: 42, responses: 156, score: 2850, badge: '🏆 Legend' },
    { name: 'JohnDoe', avatar: '👨‍💻', actions: 623, hours: 38, responses: 112, score: 2150, badge: '⭐ Elite' },
    { name: 'SarahAdmin', avatar: '👩‍💼', actions: 589, hours: 35, responses: 98, score: 1950, badge: '⭐ Elite' },
    { name: 'MikeOps', avatar: '👨‍🔧', actions: 432, hours: 29, responses: 76, score: 1480, badge: '🟢 Pro' },
    { name: 'EmmaCare', avatar: '👩‍⚕️', actions: 398, hours: 26, responses: 89, score: 1420, badge: '🟢 Pro' },
    { name: 'CurrentUser', avatar: '🌟', actions: 245, hours: 18, responses: 45, score: 890, badge: '🔰 Rookie' }
];

const achievements = [
    { name: 'Early Bird', desc: 'Log in before 8 AM 10 times', icon: '🌅', unlocked: true },
    { name: 'Night Owl', desc: 'Log in after midnight 10 times', icon: '🦉', unlocked: false },
    { name: 'Quick Responder', desc: 'Reply to a message in under 5 min', icon: '⚡', unlocked: true },
    { name: 'Mass Manager', desc: 'Manage 100 users', icon: '👥', unlocked: false },
    { name: 'Bug Hunter', desc: 'Report 5 bugs', icon: '🐛', unlocked: true },
    { name: 'Documentation Hero', desc: 'Write 10 documentation entries', icon: '📚', unlocked: false },
    { name: 'Support Star', desc: 'Answer 50 support tickets', icon: '⭐', unlocked: false },
    { name: 'Code Master', desc: 'Merge 20 PRs', icon: '💻', unlocked: true }
];

function renderLeaderboard(type) {
    const sorted = [...admins].sort((a, b) => b.score - a.score);
    const tbody = document.getElementById(`${type}Leaderboard`);
    
    if (!tbody) return;
    
    let html = '';
    sorted.forEach((admin, index) => {
        const rank = index + 1;
        let rankClass = '';
        let medal = '';
        
        if (rank === 1) {
            rankClass = 'rank-1';
            medal = '🥇';
        } else if (rank === 2) {
            rankClass = 'rank-2';
            medal = '🥈';
        } else if (rank === 3) {
            rankClass = 'rank-3';
            medal = '🥉';
        }
        
        if (type === 'monthly') {
            html += `
                <tr class="${rankClass}">
                    <td>${medal || `#${rank}`}</td>
                    <td>${admin.avatar} ${admin.name}</td>
                    <td>${admin.actions}</td>
                    <td>${admin.hours}h</td>
                    <td>${admin.responses}</td>
                    <td><strong>${admin.score}</strong></td>
                    <td>${admin.badge}</td>
                </tr>
            `;
        } else {
            html += `
                <tr class="${rankClass}">
                    <td>${medal || `#${rank}`}</td>
                    <td>${admin.avatar} ${admin.name}</td>
                    <td>${admin.actions * 2}</td>
                    <td>${admin.hours * 3}h</td>
                    <td>${admin.responses * 2}</td>
                    <td><strong>${admin.score * 2}</strong></td>
                    <td>${admin.badge}${rank === 1 ? ' 🔥' : ''}</td>
                </tr>
            `;
        }
    });
    
    tbody.innerHTML = html;
}

function renderAchievements() {
    const container = document.getElementById('achievementsGrid');
    if (!container) return;
    
    let html = '';
    achievements.forEach(ach => {
        html += `
            <div class="achievement-card ${ach.unlocked ? 'unlocked' : 'achievement-locked'}">
                <div class="achievement-icon">${ach.icon}</div>
                <div class="achievement-name">${ach.name}</div>
                <div class="achievement-desc">${ach.desc}</div>
                ${ach.unlocked ? '<span style="color:#16a34a">✅ Unlocked</span>' : '<span style="color:#94a3b8">🔒 Locked</span>'}
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function updatePersonalStats() {
    const currentUser = admins.find(a => a.name === 'CurrentUser');
    if (currentUser) {
        const rank = [...admins].sort((a, b) => b.score - a.score).findIndex(a => a.name === 'CurrentUser') + 1;
        document.getElementById('userActions').textContent = currentUser.actions;
        document.getElementById('userHours').textContent = currentUser.hours;
        document.getElementById('userResponses').textContent = currentUser.responses;
        document.getElementById('userRank').textContent = `#${rank}`;
    }
}

// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
        
        this.classList.add('active');
        const tabId = this.dataset.tab;
        document.getElementById(`${tabId}Tab`).classList.add('active');
        
        if (tabId === 'monthly') renderLeaderboard('monthly');
        else if (tabId === 'alltime') renderLeaderboard('alltime');
        else if (tabId === 'achievements') renderAchievements();
    });
});

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    renderLeaderboard('monthly');
    renderAchievements();
    updatePersonalStats();
});
</script>
