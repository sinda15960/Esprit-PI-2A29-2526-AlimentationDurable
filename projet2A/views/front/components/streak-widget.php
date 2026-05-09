<!-- Streak Widget - Affiche la série de connexions et le niveau -->
<div class="streak-widget" id="streakWidget">
    <div class="streak-container">
        <div class="streak-fire">
            <div class="fire-animation" id="fireAnimation">🔥</div>
            <div class="streak-count" id="streakCount">0</div>
            <div class="streak-label">day streak</div>
        </div>
        
        <div class="level-container">
            <div class="level-badge" id="levelBadge">
                <span class="level-icon">🏆</span>
                <span class="level-number" id="levelNumber">1</span>
            </div>
            <div class="level-progress">
                <div class="progress-bar-level">
                    <div class="progress-fill-level" id="levelProgress" style="width: 0%"></div>
                </div>
                <div class="level-next" id="nextLevelInfo">Next level: 100 XP</div>
            </div>
        </div>
        
        <div class="streak-calendar" id="streakCalendar">
            <!-- Calendrier généré en JS -->
        </div>
    </div>
</div>

<div class="streak-rewards" id="streakRewards" style="display: none;">
    <div class="rewards-modal">
        <h3>🎉 Reward Unlocked!</h3>
        <p id="rewardMessage">You've reached a 7-day streak!</p>
        <div class="reward-icon">🏅</div>
        <button onclick="closeRewardModal()">Awesome!</button>
    </div>
</div>

<style>
.streak-widget {
    background: linear-gradient(135deg, #fff7ed, #fffbeb);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

body.dark-mode .streak-widget {
    background: linear-gradient(135deg, #1e293b, #0f172a);
}

.streak-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.streak-fire {
    text-align: center;
    position: relative;
}

.fire-animation {
    font-size: 3rem;
    animation: flameFlicker 1s infinite;
}

@keyframes flameFlicker {
    0%, 100% { transform: scale(1); text-shadow: 0 0 5px #f97316; }
    50% { transform: scale(1.1); text-shadow: 0 0 20px #ea580c; }
}

.streak-count {
    font-size: 2.5rem;
    font-weight: 800;
    color: #ea580c;
    line-height: 1;
}

.streak-label {
    font-size: 0.7rem;
    color: #9a3412;
}

.level-container {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.level-badge {
    background: linear-gradient(135deg, #16a34a, #14532d);
    border-radius: 50px;
    padding: 0.5rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: white;
    font-weight: bold;
}

.level-icon {
    font-size: 1.2rem;
}

.level-number {
    font-size: 1.2rem;
}

.level-progress {
    flex: 1;
}

.progress-bar-level {
    height: 8px;
    background: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
}

.progress-fill-level {
    height: 100%;
    background: linear-gradient(90deg, #16a34a, #8b5cf6);
    border-radius: 10px;
    transition: width 0.5s ease;
}

.level-next {
    font-size: 0.7rem;
    color: #64748b;
    margin-top: 0.25rem;
}

.streak-calendar {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
    display: flex;
    gap: 0.25rem;
    justify-content: center;
    flex-wrap: wrap;
}

.calendar-day {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 0.7rem;
    background: #f1f5f9;
    transition: all 0.3s;
}

.calendar-day.active {
    background: #16a34a;
    color: white;
}

.calendar-day.today {
    border: 2px solid #16a34a;
    font-weight: bold;
}

.rewards-modal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    border-radius: 20px;
    padding: 2rem;
    text-align: center;
    z-index: 2000;
    animation: bounceIn 0.5s ease;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}

@keyframes bounceIn {
    0% { transform: translate(-50%, -50%) scale(0); }
    50% { transform: translate(-50%, -50%) scale(1.1); }
    100% { transform: translate(-50%, -50%) scale(1); }
}

@media (max-width: 768px) {
    .streak-container {
        flex-direction: column;
        text-align: center;
    }
    
    .level-container {
        width: 100%;
    }
}
</style>

<script>
// Streak & Level System
class StreakManager {
    constructor() {
        this.streak = parseInt(localStorage.getItem('userStreak')) || 0;
        this.xp = parseInt(localStorage.getItem('userXP')) || 0;
        this.level = this.calculateLevel(this.xp);
        this.lastLoginDate = localStorage.getItem('lastLoginDate');
        this.loginHistory = JSON.parse(localStorage.getItem('loginHistory')) || [];
        this.init();
    }

    init() {
        this.checkDailyLogin();
        this.updateUI();
        this.renderCalendar();
        this.checkRewards();
    }

    checkDailyLogin() {
        const today = new Date().toDateString();
        
        if (this.lastLoginDate !== today) {
            const yesterday = new Date();
            yesterday.setDate(yesterday.getDate() - 1);
            const yesterdayStr = yesterday.toDateString();
            
            if (this.lastLoginDate === yesterdayStr) {
                this.streak++;
                this.addXP(10);
                this.addToHistory(true);
            } else if (this.lastLoginDate !== today) {
                if (this.lastLoginDate && this.lastLoginDate !== yesterdayStr) {
                    // Streak broken but don't reset completely, just don't increase
                    if (this.streak > 0 && this.lastLoginDate !== yesterdayStr) {
                        this.showStreakBrokenMessage();
                    }
                }
                this.addToHistory(true);
            }
            
            this.lastLoginDate = today;
            localStorage.setItem('lastLoginDate', today);
            localStorage.setItem('userStreak', this.streak);
            this.saveHistory();
        }
    }

    addXP(amount) {
        this.xp += amount;
        const newLevel = this.calculateLevel(this.xp);
        
        if (newLevel > this.level) {
            this.levelUp(newLevel);
        }
        
        this.level = newLevel;
        localStorage.setItem('userXP', this.xp);
    }

    calculateLevel(xp) {
        return Math.floor(xp / 100) + 1;
    }

    getXPForNextLevel() {
        return (this.level * 100) - this.xp;
    }

    levelUp(newLevel) {
        const message = `🎉 LEVEL UP! You reached level ${newLevel}! 🎉`;
        this.showNotification(message);
        
        // Special rewards at certain levels
        if (newLevel === 5) {
            this.showReward('🌟 Novice Badge Unlocked!');
        } else if (newLevel === 10) {
            this.showReward('⭐ Explorer Badge Unlocked!');
        } else if (newLevel === 25) {
            this.showReward('🏆 Master Badge Unlocked!');
        } else if (newLevel === 50) {
            this.showReward('👑 Legend Badge Unlocked!');
        }
    }

    addToHistory(loggedIn) {
        if (loggedIn) {
            const today = new Date().toLocaleDateString();
            if (!this.loginHistory.includes(today)) {
                this.loginHistory.push(today);
            }
        }
    }

    saveHistory() {
        if (this.loginHistory.length > 30) {
            this.loginHistory = this.loginHistory.slice(-30);
        }
        localStorage.setItem('loginHistory', JSON.stringify(this.loginHistory));
    }

    updateUI() {
        const streakElement = document.getElementById('streakCount');
        const levelElement = document.getElementById('levelNumber');
        const progressElement = document.getElementById('levelProgress');
        const nextLevelInfo = document.getElementById('nextLevelInfo');
        
        if (streakElement) streakElement.textContent = this.streak;
        if (levelElement) levelElement.textContent = this.level;
        
        const xpInLevel = this.xp % 100;
        const progressPercent = (xpInLevel / 100) * 100;
        if (progressElement) progressElement.style.width = `${progressPercent}%`;
        
        const xpNeeded = this.getXPForNextLevel();
        if (nextLevelInfo) nextLevelInfo.textContent = `${xpNeeded} XP to next level`;
        
        // Animate fire based on streak
        const fireElement = document.getElementById('fireAnimation');
        if (fireElement) {
            if (this.streak >= 30) {
                fireElement.style.animation = 'flameFlicker 0.5s infinite';
                fireElement.style.fontSize = '4rem';
            } else if (this.streak >= 7) {
                fireElement.style.animation = 'flameFlicker 0.8s infinite';
            }
        }
    }

    renderCalendar() {
        const container = document.getElementById('streakCalendar');
        if (!container) return;
        
        const today = new Date();
        const days = [];
        
        for (let i = 6; i >= 0; i--) {
            const date = new Date(today);
            date.setDate(today.getDate() - i);
            const dateStr = date.toLocaleDateString();
            const isActive = this.loginHistory.includes(dateStr);
            const isToday = i === 6;
            
            days.push(`
                <div class="calendar-day ${isActive ? 'active' : ''} ${isToday ? 'today' : ''}">
                    ${date.getDate()}
                </div>
            `);
        }
        
        container.innerHTML = days.join('');
    }

    checkRewards() {
        const rewards = {
            7: { message: '🔥 7-Day Streak! You earned the "Consistent" badge!', icon: '🏅' },
            14: { message: '⭐ 14-Day Streak! You\'re on fire!', icon: '⭐' },
            30: { message: '🏆 30-Day Streak! Legendary status!', icon: '🏆' },
            100: { message: '👑 100-Day Streak! You are a true champion!', icon: '👑' }
        };
        
        const reward = rewards[this.streak];
        if (reward && !localStorage.getItem(`reward_${this.streak}`)) {
            this.showReward(reward.message, reward.icon);
            localStorage.setItem(`reward_${this.streak}`, 'true');
            this.addXP(50);
        }
    }

    showReward(message, icon = '🎉') {
        const modal = document.getElementById('streakRewards');
        const rewardMessage = document.getElementById('rewardMessage');
        if (modal && rewardMessage) {
            rewardMessage.innerHTML = `${icon} ${message}`;
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.style.display = 'none';
            }, 3000);
        }
    }

    showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'level-up-notification';
        notification.innerHTML = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #16a34a;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            z-index: 9999;
            animation: slideIn 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        `;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    showStreakBrokenMessage() {
        const notification = document.createElement('div');
        notification.className = 'streak-broken';
        notification.innerHTML = '😢 Your streak was broken! Start a new one today!';
        notification.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #ef4444;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            z-index: 9999;
            animation: slideIn 0.3s ease;
        `;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 4000);
    }
}

function closeRewardModal() {
    document.getElementById('streakRewards').style.display = 'none';
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    window.streakManager = new StreakManager();
});
</script>
